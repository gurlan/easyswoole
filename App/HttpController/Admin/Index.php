<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */
namespace App\HttpController\Admin;

use App\HttpController\BaseController;


class Index extends BaseController
{

    function index()
    {
        return  $this->render('Index/index');

    }
}