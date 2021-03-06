<?php
/**
 * Created by PhpStorm.
 * User: wys
 * Date: 15-6-29
 * Time: 下午5:55
 */
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller{
    public function index(){
        $this->display('manager');
    }

    public function addUser(){
        $userModel = M('User');
        $map['account']=$_POST['account'];
        $user=$userModel->where($map)->find();
        if($user!=NULL){
            $this->error("用户名重复");
        }else{
            $data['name'] = $_POST['name'];
            $data['account'] = $_POST['account'];
            $data['password'] = md5($_POST['password']);
            $data['phone'] = $_POST['phone'];
            $data['email'] = $_POST['email'];
            $data['role_id'] = $_POST['authority'];
            $userModel->add($data);
            $this->success("添加成功");
        }
    }

    public function add(){
        $roleModel = M('role');
        $role=$roleModel->select();
        $this->assign('role',$role);
        $this->display();

    }
    public  function  manager(){
        $page=$_REQUEST['page'];
        $UserModel=M("User");
        $userList=$UserModel->page($page,10)->select();
        $roleModel=M('Role');
        $roleList=$roleModel->select();
        $role=array();
        foreach($roleList as $item){
            $role[$item['id']]=$item;
        }

        foreach($userList as $key=>$item){
            $userList[$key]["role_name"]=$role[$item['role_id']]['name'];
        }
        //var_dump($userList);
        $this->assign("userCount",count($userList));
        $this->assign("pageNow",$page);
        $this->assign("userList",$userList);

        $this->assign('roleList',$roleList);

        $this->display();
    }
    public  function editor(){
        $userId=$_REQUEST['id'];
        $userModel=M('User');
        $user=$userModel->where("id=".$userId)->find();
        $roleModel=M('Role');
        $roleList=$roleModel->select();
        $this->assign("role",$roleList);
        $this->assign("user",$user);
        $this->display();
    }
    public function set(){
        $mailAccountModel = M('Mail_account');
        $mailAccount = $mailAccountModel->find();
        $this->assign("mailAccount",$mailAccount);
        $this->display();
    }
    public function edit(){
        $userID=$_REQUEST['userID'];
        $name=$_REQUEST['name'];
        $email=$_REQUEST['email'];
        if($_REQUEST['password']!=""&&$_REQUEST['password']!=null){
            $password=md5($_REQUEST['password']);
            $data['password']=$password;
        }
        $phone=$_REQUEST['phone'];
        $role=$_REQUEST['authority'];
        $userModel=M('User');
        $data['name']=$name;
        $data['email']=$email;

        $data['phone']=$phone;
        $data['role_id']=$role;
        //var_dump($data);
        $userModel->where("id=".$userID)->save($data);

        $this->redirect('manager');
    }
    public function updateInfo(){
        $address=I('emailAddress');

        $emailName=I('emailName');
        $popserver=I('popserver');
        $stmpserver=I('stmpserver');
        $user=I('user');
        $pwd=I('pwd');
        $day=I('receiveday');
        $account=M("mail_account");
        // var_dump($popserver);
        $data['emailAddress']=$address;
        $data['emailName']=$emailName;
        $data['pop3Server']=$popserver;
        $data['stmpServer']=$stmpserver;
        $data['emailUserName']=$user;
        $data['emailPassword']=$pwd;
        $data['receiveday']=$day;
        //var_dump($data);
        $abc=$account->where('id=1')->save($data);
        if ($abc==1) {
            $this->success("修改成功");
        } else {
            $this->error('错误');
        }
    }
}