<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/26
 * Time: 11:35
 */
namespace  App\Model;

Class Base{

    protected  $db;
    public function __construct()
    {
        $this->db = \EasySwoole\MysqliPool\Mysql::getInstance()->pool('mysql')->getObj();
    }
}