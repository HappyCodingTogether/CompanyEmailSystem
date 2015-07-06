<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(!isset($_SESSION['userId'])||!isset($_SESSION['roleId'])){
            $this->display('login');
        }else{
            $this->display();
        }
    }
    public function add(){
        $this->display();
    }
    public function addBook(){
        $this->display();
    }
    public function base(){
        $this->display();
    }
    public function edit(){
        $userModel=M('User');
        $user=$userModel->where('role_id=4')->select();
        $this->assign('userList',$user);
        $this->display();

    }
    public function editor(){
        $this->display();
    }
    public function email(){
        $this->display();
    }
    public function login(){
        if(!isset($_SESSION['userId'])||!isset($_SESSION['roleId'])){
            $this->display();
        }else{
            $this->display('index');
        }
    }
    public function checkLogin(){
        $map['account']=$_POST['account'];
        $userModel=M('User');
        $user=$userModel->where($map)->find();
        if($user==NULL||$user==''){
            $this->error('该用户不存在');
        }else{
            if($user['password']==md5($_POST['password'])){
                $_SESSION['roleId']=$user['role_id'];
                $_SESSION['userId']=$user['id'];
                $_SESSION['userName']=$user['name'];
                $_SESSION['account']=$user['account'];
                if($user['role_id']==1){
                    $this->redirect('User/manager');
                }
                $this->redirect("index");
            }else{
                $this->error(' 密码错误') ;
            }
        }
    }

    public function logout(){
        unset($_SESSION['userId']);
        unset($_SESSION['roleId']);
        unset($_SESSION['account']);
        $this->redirect('Index/login');
    }


    public function manager(){
        $this->display();
    }
    public function safe(){
        $this->display();
    }
    public function set(){
        $this->display();
    }
    public function table(){
        $displayModel = M('User');
        $result = $displayModel->select();//选择邮件处理人员
        $this->assign('user',$result);
        $this->display();
    }


}