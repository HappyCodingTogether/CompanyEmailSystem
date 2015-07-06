<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 15-6-29
 * Time: 下午3:36
 */
namespace Home\Controller;


use Think\Controller;
use Home\Controller\MailController;

class EmailListController extends Controller{

        public  function receiveNewMail(){
            $mailcon=new MailController();
            $roleId=$_SESSION['roleId'];
            if($roleId==2){
                $mailcon->receiveOutMail();
            }

            $this->getEmailList();
        }
        public function getEmailList(){
            $userId=$_SESSION['userId'];
            $roleId=$_SESSION['roleId'];
            $roleModel = M('ReceiveMails');
            if($_POST['page']!=null){
                $page=$_POST['page'];
            }else{
                $page=1;
            }
            $list=array();
            if($roleId==2){
                $list=$roleModel->page($page,10)->where("status=0")->order('id desc')->select();
            }
            else if($roleId==3){
                $mailUserModel=M("MailUser");
                $mailIdList=$mailUserModel->where('user_id='.$userId)->field('mail_id')->select();
                $mailList=array();
                foreach($mailIdList as $item){
                    $mailList[]=$item['mail_id'];
                }
                $map['id']=array("in",$mailList);

                $list=$roleModel->page($page,10)->where($map)->order('id desc')->select();

            }else if($roleId==4){
                $mailUserModel=M("MailUser");
                $mailIdList=$mailUserModel->where('user_id='.$userId)->field('mail_id')->select();
                $mailList=array();
                foreach($mailIdList as $item){
                    $mailList[]=$item['mail_id'];
                }
                $map['id']=array("in",$mailList);
                $sendModel=M('SendEmails');
                $list=$sendModel->page($page,10)->where($map)->order('id desc')->select();
            }

            $result =json_encode($list);
            //var_dump($result);
            echo $result;

        }
        public function getDetail(){
            $roleId=$_SESSION['roleId'];
            $roleModel = M('ReceiveMails');
            if($roleId==4){
                $roleModel = M('SendEmails');
            }
            $id=$_POST['id'];
            $list=array();
            // $list=$roleModel->page($page+1,10)->select();
            $list=$roleModel->where("id=".$id)->select();
            $result =json_encode($list);
            echo $result;
        }


    

    }
