<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */
namespace App\HttpController\Api;

use App\HttpController\BaseController;
use EasySwoole\Component\Pool\PoolManager;


class Login extends BaseController
{

    public function index(){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?';
        $conf =  \EasySwoole\EasySwoole\Config::getInstance()->getConf('WEIXIN');
        $data['appid'] = $conf['appid'];
        $data['secret'] = $conf['secret'];
        $data['js_code'] =  $this->request()->getRequestParam('js_code');
        $data['grant_type'] = 'authorization_code';
        $query = http_build_query($data);
        $url.=$query;
        $res = file_get_contents($url);
        $user_info = json_decode($res,true);
        $open_id = $user_info['open_id'];

    }


}