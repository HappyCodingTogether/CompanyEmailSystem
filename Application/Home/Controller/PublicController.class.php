<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 2015/7/5
 * Time: 10:11
 */
namespace Home\Controller;


use Think\Controller;

class PublicController extends Controller
{
    public  function  base(){
        $this->display();
    }
    public function echoInfoLoad(){
        echo (json_encode($this->infoLoad()));
    }
    public function infoLoad(){
        $userId=$_SESSION['userId'];
        $role_id=$_SESSION['roleId'];
        $newMail=0;
        $deadlineMail=0;
        $verifyMail=0;
        $distributeMail=0;
        $receiveMailModel=M('ReceiveMails');
        $mailUserModel=M("MailUser");
        $sendMailModel=M("SendEmails");

        if($role_id==3){
            $map=null;
            $map['user_id']=$userId;
            $map['type']=1;
            $map['status']=0;
            $mailUser=$mailUserModel->where($map)->field('mail_id')->select();
            $mailId=array();
            foreach ($mailUser as $item) {
                $mailId[]=$item['mail_id'];
            }
            $map=null;
            $map['id']=array('in',$mailId);
            $map['deal_status']=0;
            $receiveMail=$receiveMailModel->where($map)->select();

            if($receiveMail!=null&&$receiveMail!=''){
                $newMail=count($receiveMail);
            }

            $timeNow=time();
            $timeAfter=$timeNow+86400;
            $timeStr=date('Y-m-d H:i:s',$timeAfter);
            foreach ($receiveMail as $item) {
                $deadline=null;
                $deadline=$item['deadline'];
                if($deadline!=null&&$deadline!=''){
                    if($timeAfter<$deadline){
                        $deadlineMail++;
                    }
                }
            }
        }
        if($role_id==2){
            $map=null;
            $map['status']=0;
            $receiveMail=$receiveMailModel->where($map)->field('id')->select();
            if($receiveMail!=null&&$receiveMail!=''){
                $distributeMail=count($receiveMail);
            }
        }
        if($role_id==4){
            $map=null;
            $map['user_id']=$userId;
            $map['type']=3;
            $map['status']=1;
            $mailUser=$mailUserModel->where($map)->field('mail_id')->select();
            $sendMailId=array();
            foreach($mailUser as $item){
                $sendMailId[]=$item['mail_id'];
            }

            if($sendMailId!=null&&$sendMailId!=''){
                $map=null;
                $map['id']=array('in',$sendMailId);
                $sendMail=$sendMailModel->where($map)->field('pre_mail_id')->select();
                $preMailId=array();
                foreach($sendMail as $item){
                    $preMailId[]=$item['pre_mail_id'];
                }
                if($preMailId!=null&&$preMailId!=''){
                    $map=null;
                    $map['id']=array('in',$preMailId);
                    $map['deal_status']=1;
                    $receiveMail=$receiveMailModel->where($map)->field('id')->select();
                    if($receiveMail!=null&&$receiveMail!=''){
                        $verifyMail=count($receiveMail);
                    }
                }
            }
        }

        $data=array();
        $data['newMail']=$newMail;
        $data['deadlineMail']=$deadlineMail;
        $data['distributeMail']=$distributeMail;
        $data['verifyMail']=$verifyMail;
        return $data;


    }
}
