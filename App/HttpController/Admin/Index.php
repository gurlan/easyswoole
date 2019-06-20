<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */
namespace App\HttpController\Admin;


use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{

    function index()
    {

        return   $this->response()->write('111');

    }
}