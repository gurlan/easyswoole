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

  public function index()
    {
        $table_name = 'video';
        $page = $this->request()->getRequestParam('page');
        $page =$page?$page:1;
        $page_size = $this->request()->getRequestParam('pageSize');
        $page_size=$page_size?$page_size:1;
        $list = $this->db->orderBy('id', 'desc')->get($table_name,[($page-1)*$page_size,$page_size]);
        $this->writeJson(200,$list);
    }

    /**
     *点赞
     */
    public function addUserLike(){
        $id = $this->request()->getRequestParam('id');
        $this->db->where('id',$id)->setInc('video','likenum');

        \EasySwoole\EasySwoole\Swoole\Task\TaskManager::async(function () use($id){
            $data['video_id'] = $id;
            $data['user_id'] = 1;

            $conf = new \EasySwoole\Mysqli\Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL'));
            $db = new \EasySwoole\Mysqli\Mysqli($conf);

            var_dump($data);
            $db->insert('love',$data);
            return true;
        }, function () {
            echo "异步任务执行完毕...\n";
        });

        $this->writeJson(200,'','成功');

    }


}