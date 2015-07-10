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
            $userId=$_SESSION['userId'];
            if($roleId==2){
                $mailcon->receiveOutMail();
            }

            $this->getEmailList();
        }
        public function getEmailList(){
            $userId=$_SESSION['userId'];
            $roleId=$_SESSION['roleId'];
            $roleModel = M('ReceiveMails');
            $type=$_POST['type'];
            if(!$_POST['type']){
                $type='all';
            }
            if($_POST['page']!=null){
                $page=$_POST['page'];
            }else{
                $page=1;
            }
            $list=array();
            if($roleId==2){
                if($type=="all"){
                    $list=$roleModel->page($page,10)->order('id desc')->select();
                }else if($type=="isdb"){
                    $list=$roleModel->page($page,10)->where("status=1")->order('id desc')->select();
                }else if($type=="notdb"){
                    $list=$roleModel->page($page,10)->where("status=0")->order('id desc')->select();
                }else if($type=="isdeal"){
                    $list=$roleModel->page($page,10)->where('deal_status=1')->order('id desc')->select();
                }else if($type=="notdeal"){
                    $map=null;
                    $map['deal_status']=0;
                    $map['status']=1;
                    $list=$roleModel->page($page,10)->where($map)->order('id desc')->select();
                }
                else{
                    $list=$roleModel->page($page,10)->order('id desc')->select();
                }

            }
            else if($roleId==3){

                $mailUserModel=M("MailUser");
                $mailIdList=$mailUserModel->where('user_id='.$userId)->field('mail_id')->select();
                $mailList=array();
                foreach($mailIdList as $item){
                    $mailList[]=$item['mail_id'];
                }
                $map['id']=array("in",$mailList);
                if($type=="all"){
                    $list=$roleModel->page($page,10)->where($map)->order('id desc')->select();
                }
                else if($type=="isdeal"){
                    $map['deal_status']=1;
                    $list=$roleModel->page($page,10)->where($map)->order('id desc')->select();
                }else if($type=="notdeal"){
                    $map['deal_status']=0;
                    $list=$roleModel->page($page,10)->where($map)->order('id desc')->select();
                }else if($type=="issend"){
                    $sendModel=M('SendEmails');
                    $map['status']=1;
                    $map['user_id']=$userId;
                    $list=$sendModel->where($map)->select();
                }else if($type=="verifying"){
                    $sendModel=M('SendEmails');
                    $map['status']=2;
                    $map['user_id']=$userId;
                    $list=$sendModel->where($map)->select();
                }
                else if($type=="fail"){
                    $sendModel=M('SendEmails');
                    $map['status']=3;
                    $map['user_id']=$userId;
                    $list=$sendModel->where($map)->select();
                }


            }else if($roleId==4){

                $mailUserModel=M("MailUser");
                $mailIdList=$mailUserModel->where('user_id='.$userId)->field('mail_id')->select();
                $mailList=array();
                foreach($mailIdList as $item){
                    $mailList[]=$item['mail_id'];
                }
                $sendModel=M('SendEmails');
                if($type=="all"){
                    $map['id']=array("in",$mailList);
                    $list=$sendModel->page($page,10)->where($map)->order('id desc')->select();
                }
                else if($type=="notverify"){
                    $map['status']=2;
                    $map['id']=array("in",$mailList);
                    $list=$sendModel->page($page,10)->where($map)->order('id desc')->select();
                }
                else if($type=="isverify"){
                    $map['status']=array("in",array(1,3));
                    $map['id']=array("in",$mailList);
                    $list=$sendModel->page($page,10)->where($map)->order('id desc')->select();
                }

            }
            //在列表页显示标签
            $labelMode = M('label');
            //$lll=$labelMode->where('id=1')->select();
            for($a = 0;$a < count($list);$a++)
            {   if($list[$a]['label_id']!=null){
                $label = $labelMode->where('id='.$list[$a]['label_id'])->select();
                $list[$a]['label_id'] = $label[0]['name'];
                }else{
                $list[$a]['label_id'] = "";
                }

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
            $list=$roleModel->where("id=".$id)->find();
            $addFile=explode(";",trim($list['add_file']));
            $count=count($addFile);
            $list['addFiles']=null;
            if($count>=1&&$addFile[0]!=''){
                for($i=0;$i<$count-1;$i++){
                    //var_dump($addFile[$i]);
                    $fileModel=M('File');
                    $file=$fileModel->where('id='.$addFile[$i])->find();
                    $fileInfo[$i]=array("name"=>$file['name'],"path"=>__ROOT__."/".C('DOWNLOAD_UPLOAD.rootPath').$file['savename']);
                }
                $list['addFiles']=$fileInfo;
            }

            $result =json_encode($list);
            echo $result;
        }


    

    }
