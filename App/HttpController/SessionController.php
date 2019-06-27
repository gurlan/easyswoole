<?php

namespace App\HttpController;

use EasySwoole\Session\AbstractSessionController;
use EasySwoole\Session\FileSessionHandler;
/**
 * SESSION控制器
 * Class ViewController
 * @author  : evalor <master@evalor.cn>
 * @package App
 */
class SessionController extends AbstractSessionController
{

    protected function sessionHandler(): \SessionHandlerInterface
    {
        return new FileSessionHandler();
    }
    public function index(){

    }
}