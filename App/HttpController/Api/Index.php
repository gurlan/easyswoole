<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */

namespace App\HttpController\Api;

use App\HttpController\BaseController;
use EasySwoole\Component\Pool\PoolManager;


class Index extends BaseController
{
    protected $user = array();

    public function onRequest(?string $action): ?bool
    {
        $header = $this->request()->getHeaders();

        if (isset($header['openid']) and $this->userModel->getDetailByKey('openid', $header['openid'][0])) {
            $this->user = $this->userModel->getDetailByKey('openid', $header['openid'][0]);
        }
        return true;
    }

    public function index()
    {
        if (!$this->user)
            return $this->writeJson(100404);

        $table_name = 'video';
        $page = $this->request()->getRequestParam('page');
        $page = $page ? $page : 1;
        $page_size = $this->request()->getRequestParam('pageSize');
        $page_size = $page_size ? $page_size : 1;
        $list = $this->db->orderBy('id', 'desc')->get($table_name, [($page - 1) * $page_size, $page_size]);
        foreach ($list as $k => $v) {
            $comment = $this->commentModel->getCommentList($v['id']);
            $danmu = array();

            foreach ($comment as $key => $val) {
                $danmu[$key]['text'] = $val['cont'];
                $danmu[$key]['color'] = '#' . $this->randColor();
                $danmu[$key]['time'] = $val['videoTime'];
            }
            $list[$k]['comment'] = $danmu;
            $list[$k]['commnetnum'] = count($comment);
            $list[$k]['isLike'] = 0;
            if ($this->db->where('video_id',$v['id'])->where('user_id',$this->user['id'])->getOne('love')){
                $list[$k]['isLike'] = 1;
            }

        }
        return $this->writeJson(200, $list);
    }

    protected function randColor()
    {
        $colors = array();
        for ($i = 0; $i < 6; $i++) {
            $colors[] = dechex(rand(0, 15));
        }
        return implode('', $colors);
    }

    /**
     *点赞
     */
    public function addUserLike()
    {
        $id = $this->request()->getRequestParam('id');
        $this->db->where('id', $id)->setInc('video', 'likenum');

        $user_id = $this->user ? $this->user['id'] : $this->writeJson(100404, '', '成功');
        \EasySwoole\EasySwoole\Swoole\Task\TaskManager::async(function () use ($id, $user_id) {
            $data['video_id'] = $id;
            $data['user_id'] = $user_id;
            $conf = new \EasySwoole\Mysqli\Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL'));
            $db = new \EasySwoole\Mysqli\Mysqli($conf);
            $db->insert('love', $data);
            return true;
        }, function () {
            echo "异步任务执行完毕...\n";
        });

        $this->writeJson(200, 1, '成功');

    }

    /**
     *评论
     */
    public function addComment()
    {

        $content = $this->request()->getBody()->__toString();
        $raw_array = json_decode($content, true);
        $data['cont'] = $raw_array['cont'];
        $data['video_id'] = $raw_array['contentId'];
        $data['createTime'] = time();
        $data['user_id'] = 1;
        $data['nickName'] = '测试';
        $data['videoTime'] = intval($raw_array['videoTime']);
        $this->db->insert('comment', $data);
        $this->writeJson(200, '', '成功');

    }

    /**
     *评论列表
     */
    public function getCommentList()
    {
        $id = $this->request()->getRequestParam('contId');
        $page = $this->request()->getRequestParam('page');
        $page = $page ? $page : 1;
        $page_size = $this->request()->getRequestParam('pageSize');
        $page_size = $page_size ? $page_size : 1;

        $list = $this->db->orderBy('id', 'desc')->where('video_id', $id)->get('comment', [($page - 1) * $page_size, $page_size]);
        if ($list) {
            foreach ($list as &$v) {
                $v['createTime'] = date('Y-m-d H:i', $v['createTime']);
            }
        }
        $total = $this->db->where('video_id', $id)->count('comment');
        $this->writeJson(200, $list, $total);
    }


}