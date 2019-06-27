<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */

namespace App\HttpController\Admin;

use App\HttpController\BaseController;
use App\Service\Oss;

class Video extends BaseController
{

    function index()
    {
        return $this->render('Video/index');
    }


    /**
     * 视频上传
     * @return bool|void
     */
    public function add()
    {
        if ($this->request()->getMethod() == 'GET') {
            return $this->render('Video/add');
        }
        $request = $this->request();
        $video = $request->getUploadedFile('video');//获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象

        try {
            $file = '/var/www/code/Temp/'.$video->getClientFilename();
            $video->moveTo($file,$video->getStream());
            $res = Oss::getInstance()->upload($video->getClientFilename(),$file);
            $data = $request->getRequestParam();
            $row['name'] = $data['name'];
            $row['url'] = $res['oss-request-url'];
            $this->db->insert('video',$row);
            unlink($file);
            return $this->response()->redirect("/admin/video");
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

    }
}