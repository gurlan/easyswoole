<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/7/3
 * Time: 11:11
 */

namespace App\Utility\Pool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

class MysqlPool extends AbstractPool
{
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $conf = Config::getInstance()->getConf('MYSQL');
        $dbConf = new \EasySwoole\Mysqli\Config($conf);
        return new MysqlConnection($dbConf);
    }
}