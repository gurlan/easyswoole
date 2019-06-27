<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/27
 * Time: 16:26
 */

namespace App\Service;

use OSS\OssClient;
use OSS\Core\OssException;
use EasySwoole\EasySwoole\Config;
use  EasySwoole\Component\Singleton;

// 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
class Oss
{
    use Singleton;
    public $ossClient;
    public $bucket;
    private function __construct()
    {
        $oss = Config::getInstance()->getConf('OSS');
        $accessKeyId = $oss['key'];
        $accessKeySecret = $oss['secret'];
        $this->bucket =  $oss['bucket'];

// Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = "oss-cn-beijing.aliyuncs.com";

        try {
             $this->ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * 上传
     * @param string $object
     * @param string $content
     * @return null
     */
    public function upload($object='', $content=''){


         return   $this->ossClient->uploadFile($this->bucket,$object , $content);

    }

}
