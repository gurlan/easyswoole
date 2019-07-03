<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/7/3
 * Time: 11:13
 */

namespace App\Utility\Pool;


namespace App\Utility\Pool;

use EasySwoole\Component\Pool\PoolObjectInterface;
use EasySwoole\Mysqli\Mysqli;

class MysqlConnection extends Mysqli implements PoolObjectInterface
{
    function gc()
    {
        $this->resetDbStatus();
        $this->getMysqlClient()->close();
    }

    function objectRestore()
    {
        $this->resetDbStatus();
    }

    function beforeUse(): bool
    {
        return $this->getMysqlClient()->connected;
    }
}