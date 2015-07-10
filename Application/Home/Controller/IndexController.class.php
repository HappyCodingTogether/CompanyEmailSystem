<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(!isset($_SESSION['userId'])||!isset($_SESSION['roleId'])){
            $this->display('login');
        }else{
            if($_SESSION['roleId']==1){
                $this->redirect("User/manager");
            }else{
                $publicCon=new PublicController();
                $data=$publicCon->infoLoad();
                $this->assign("data",$data);
                $this->display();
            }

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
    public function statistics(){
        $mailModel = M('ReceiveMails');
        $distributionNumber = $mailModel->where('status=1')->count();//已分发邮件数量
        $notDistributionNumber = $mailModel->where('status=0')->count();//未分发邮件数量
        $dealNumber = $mailModel->where('deal_status=1')->count();//以处理邮件数量
        $notDealNumber = $mailModel->where('deal_status=0')->count();//未处理邮件数量
        $sendEmailModel = M('SendEmails');
        $notSend = $sendEmailModel->where('status=0')->count();//未发送或草稿数量
        $send = $sendEmailModel->where('status=1')->count();//发送成功数量
        $waitForCheck = $sendEmailModel->where('status=2')->count();//待审核数量
        $fail=$sendEmailModel->where('status=3')->count();//发送失败或审核未通过数量
        $this->assign("distributionNumber",$distributionNumber);
        $this->assign("notDistributionNumber",$notDistributionNumber);
        $this->assign("dealNumber",$dealNumber);
        $this->assign("notDealNumber",$notDealNumber);
        $this->assign("notSend",$notSend);
        $this->assign("send",$send);
        $this->assign("waitForCheck",$waitForCheck);
        $this->assign("fail",$fail);
        if($_SESSION['roleId']==1){
            $this->display("Index/systemStatistics");
        }else{
            $this->display();
        }

    }
    public function editor(){
        $this->display();
    }
    public function email(){
        $receiveMailModel=M('ReceiveMails');
        $firstMailID=$receiveMailModel->max('id');
        $lastMailID=$receiveMailModel->min('id');
        $this->assign("firstId",$firstMailID);
        $this->assign("lastId",$lastMailID);
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
                $mailAccountModel = M('Mail_account');
                $mailAccount = $mailAccountModel->find();
                $_SESSION['mailAddress']=$mailAccount['emailaddress'];
                $_SESSION['roleId']=$user['role_id'];
                $_SESSION['userId']=$user['id'];
                $_SESSION['userName']=$user['name'];
                $_SESSION['account']=$user['account'];
                $accessModel=M("Access");
                $map=null;
                $map['role_id']=$user['role_id'];
                $access=$accessModel->where($map)->select();
                $accessIds=array();
                foreach ($access as $item) {
                    $accessIds[]=$item['node_id'];
                }
                $_SESSION['accessIds']=$accessIds;

                switch($user['role_id']){
                    case 1:
                        $_SESSION['roleName']="系统管理员";
                        break;
                    case 2:
                        $_SESSION['roleName']="邮件分发人员";
                        break;
                    case 3:
                        $_SESSION['roleName']="邮件处理人员";
                        break;
                    case 4:
                        $_SESSION['roleName']="邮件审核人员";
                        break;
                    default:
                        $_SESSION['roleName']="";
                        break;
                }

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
        unset($_SESSION['userName']);
        unset($_SESSION['roleName']);
        unset($_SESSION['account']);
        unset($_SESSION['mailAddress']);
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
        $result = $displayModel->where("role_id=3")->select();//选择邮件处理人员
        $this->assign('user',$result);
        //获取标签列表
        $labelModel = M('label');
        $userId = $_SESSION['userId'];
        $labelResult = $labelModel->order('id desc')->where('user_id='.$userId.' or user_id=0')->select();
        $this->assign('label',$labelResult);
        $this->display();
    }
    public function changeLabel(){
        $labelNew['name']=$_POST['name'];
        $labelNew['user_id']=$_SESSION['userId'];
        $userId = $_SESSION['userId'];
        $labelModel = M('Label');
        $labelModel->add($labelNew);
        $labelResult = $labelModel->order('id desc')->where('user_id='.$userId.' or user_id=0')->select();
        echo json_encode($labelResult);
    }


}