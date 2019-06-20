<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */
namespace App\HttpController\Admin;

use App\HttpController\ViewController;
use EasySwoole\Template\Render;


class Login extends ViewController
{

    function index()
    {
   // return   $this->response()->write('333');
     return  $this->render('Admin/index');     # 对应模板: Views/index.blade.php


    }

    function reload(){
        Render::getInstance()->restartWorker();
        $this->response()->write(1);
    }
}