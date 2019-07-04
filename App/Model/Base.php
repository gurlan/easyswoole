<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/26
 * Time: 11:35
 */
namespace  App\Model;

use App\Utility\Pool\MysqlPool;

Class Base{

    protected  $db;
    public function __construct()
    {
        $this->db =  MysqlPool::defer();
    }

    public function getDetailByKey($key,$val){
       return $this->db->where($key,$val)->getOne($this->tableName);
    }


}