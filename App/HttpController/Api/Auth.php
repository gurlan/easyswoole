<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */
namespace App\HttpController\Api;

use App\HttpController\BaseController;
use App\Model\User;
use App\Service\WechatCrypt;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\FastCache\Cache;


class Auth extends BaseController
{

    /**
     * 登录
     * @return bool
     */
    public function login(){
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
        $openid = $user_info['openid'];
        Cache::getInstance()->set('sessionKey',$user_info['session_key']);
        $user = $this->userModel->getDetailByKey('openid',$openid);
        if (!$user){
           return $this->writeJson(100404);
        }
        return    $this->writeJson(200,$user,'登录成功');

    }

    /**
     * 注册
     * @return bool
     */
    public function register(){
        $content = $this->request()->getBody()->__toString();
        $raw_array = json_decode($content, true);
        $conf =  \EasySwoole\EasySwoole\Config::getInstance()->getConf('WEIXIN');
        $appid = $conf['appid'];
        $sessionKey =  Cache::getInstance()->get('sessionKey');
        $weixin = new WechatCrypt($appid, $sessionKey);
        $encryptedData = $raw_array['encryptedData'];
        $iv = $raw_array['iv'];

        try{
            $errCode = $weixin->decryptData($encryptedData, $iv, $data );
            if ($errCode == 0) {
                $user_info = json_decode($data,true);
                $row['nickname'] =  $user_info['nickName'];
                $row['head_image'] =  $user_info['avatarUrl'];
                $row['sex'] =  $user_info['gender'];
                $row['openid'] =  $user_info['openId'];
                if (!$this->userModel->getDetailByKey('openid',$row['openid'])){
                    $this->db->insert($this->userModel->tableName,$row);
                    return    $this->writeJson(200,['openid'=>$row['openid']],'注册成功');
                }
                return     $this->writeJson(200,['openid'=>$row['openid']],'注册成功');
            }else{
                return     $this->writeJson(100401,'','session失效');
            }
        }catch (\Exception $exception){
         var_dump($exception->getMessage());
            return  $this->writeJson(100500,'','注册失败');
        }

    }


}