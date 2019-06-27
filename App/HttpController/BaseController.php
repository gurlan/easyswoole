<?php

namespace App\HttpController;

use EasySwoole\EasySwoole\Config;


use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use duncan3dc\Laravel\BladeInstance;
use EasySwoole\MysqliPool\Mysql;

/**
 * 视图控制器
 * Class ViewController
 * @author  : evalor <master@evalor.cn>
 * @package App
 */
 class BaseController extends SessionController
{
    protected $view;
    protected $db;

    /**
     * 初始化模板引擎
     * ViewController constructor.
     * @param string   $actionName
     * @param Request  $request
     * @param Response $response
     */
    function __construct()
    {

        $tempPath   = Config::getInstance()->getConf('TEMP_DIR');    # 临时文件目录
        $this->view = new BladeInstance(EASYSWOOLE_ROOT . '/Views', "{$tempPath}/templates_c");
        $this->db = Mysql::getInstance()->pool('mysql')->getObj();
        parent::__construct();

    }

    /**
     * 输出模板到页面
     * @param string $view
     * @param array  $params
     * @author : evalor <master@evalor.cn>
     */
    function render(string $view, array $params = [])
    {
        $content = $this->view->render($view, $params);
        $this->response()->write($content);
    }
}