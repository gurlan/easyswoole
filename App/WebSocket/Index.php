<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/7/3
 * Time: 19:45
 */

namespace App\WebSocket;

use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\FastCache\Cache;
use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * Class Index
 *
 * 此类是默认的 websocket 消息解析后访问的 控制器
 *
 * @package App\WebSocket
 */
class Index extends Controller
{
    public function index()
    {

        $fd = $this->caller()->getClient()->getFd(); //socket id
        $id = $this->caller()->getArgs()['id']; //视频id
        if (Cache::getInstance()->get('video_' . $id)) {
            $fd_array = Cache::getInstance()->get('video_' . $id);
            if (!in_array($fd, $fd_array)) {
                $fd_array[] = $fd;
            }
            Cache::getInstance()->set('video_' . $id, $fd_array);
        } else {
            $fd_array[] = $fd;
            Cache::getInstance()->set('video_' . $id, $fd_array);
        }

      $fd_array = Cache::getInstance()->get('video_' . $id);

      $content =  $this->caller()->getArgs()['content'];
        // 异步推送, 这里直接 use fd也是可以的
        TaskManager::async(function () use ($fd_array,$content) {
            $server = ServerManager::getInstance()->getSwooleServer();
            $i = 0;
            foreach ($fd_array as $v) {
               // sleep(1);
                $server->push($v, $content.'push in http at ' . date('H:i:s'));
                $i++;
            }
        });

      //  $this->response()->setMessage($this->caller()->getArgs()['content']);
    }

    function hello()
    {
        $this->response()->setMessage('call hello with arg:' . json_encode($this->caller()->getArgs()));
    }

    public function who()
    {
        $this->response()->setMessage('your fd is ' . $this->caller()->getClient()->getFd());
    }

    function delay()
    {
        $this->response()->setMessage('this is delay action');
        $client = $this->caller()->getClient();

        // 异步推送, 这里直接 use fd也是可以的
        TaskManager::async(function () use ($client) {
            $server = ServerManager::getInstance()->getSwooleServer();
            $i = 0;
            while ($i < 5) {
                sleep(1);
                $server->push($client->getFd(), 'push in http at ' . date('H:i:s'));
                $i++;
            }
        });
    }
}