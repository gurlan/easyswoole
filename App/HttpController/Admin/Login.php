<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/18
 * Time: 16:27
 */
namespace App\HttpController\Admin;

use App\HttpController\BaseController;

use EasySwoole\Validate\Validate;

class Login extends BaseController
{

    function index()
    {

         if ($this->request()->getMethod()=='GET'){
             return  $this->render('Login/index');
         } else{
             $data = $this->request()->getParsedBody();
             $valitor = new Validate();
             $valitor->addColumn('username', '名字')->required('名字不为空');
             $valitor->addColumn('password', '密码')->required('密码不为空');
             $boole = $valitor->validate($data);
             if (!$boole){
                 echo ($valitor->getError()->getErrorRuleMsg()); return;
             }
             $username = $data['username'];
             $password = $data['password'];
             $res = $this->db->where('username',$username)->where('password',md5($password))->getOne('admin');
            if (!$res){
                $this->response()->withHeader('Content-type','text/html;charset=utf-8');
                $this->response()->write('账号或密码错误');
            }
             $this->session()->start();
             $this->session()->set('admin_id',$res['id']);

            return $this->response()->redirect("/admin/index");
         }
    }

}