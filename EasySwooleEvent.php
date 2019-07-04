<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Whoops\Handler\CallbackHandler;
use EasySwoole\Whoops\Handler\PrettyPageHandler;
use EasySwoole\Whoops\Run;
use EasySwoole\EasySwoole\Config as GConfig;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\Mysqli\Config;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Socket\Dispatcher;
use App\WebSocket\WebSocketParser;
use EasySwoole\FastCache\Cache;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');

        /*whoops模板*/
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler);  // 输出一个漂亮的页面
        $whoops->pushHandler(new CallbackHandler(function ($exception, $inspector, $run, $handle) {
            // 可以推进多个Handle 支持回调做更多后续处理
        }));
        $whoops->register();

        //Pool注册
        PoolManager::getInstance()->register(MysqlPool::class);
    }

    public static function mainServerCreate(EventRegister $register)
    {

        // TODO: Implement mainServerCreate() method.
        Run::attachTemplateRender(ServerManager::getInstance()->getSwooleServer());

        /**
         * **************** websocket控制器 **********************
         */
        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();

        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);
        // 给server 注册相关事件 在 WebSocket 模式下  on message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage, function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($dispatch) {

            $dispatch->dispatch($server, $frame->data, $frame);
        });

        /**
         * **************** 缓存 **********************
         */
        Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)->attachToServer(ServerManager::getInstance()->getSwooleServer());
    }

    public static function onRequest(Request $request, Response $response): bool
    {

        // TODO: Implement onRequest() method.
        //拦截请求
        Run::attachRequest($request, $response);
        return true;

    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}