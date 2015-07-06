<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 2015/7/4
 * Time: 22:43
 */
namespace Home\Controller;
use Think\Controller;
class VerifyController extends \Think\Controller{
    public function verify()
    {
        $mail_id = $_REQUEST['mail_id'];
        $from=$_REQUEST['from'];
        $verify_user_id = $_REQUEST['verify_user'];
        $to = $_REQUEST['to'];
        $title = $_REQUEST['title'];
        $content = $_REQUEST['content'];
        $user_id = $_SESSION['userId'];

        $sendMailModel = M("SendEmails");

        $data = array();
        $data['title'] = $title;
        $data['from']=$from;
        $data['content'] = $content;
        $data['to'] = $to;
        $data['pre_mail_id'] = $mail_id;
        $data['type'] = 0;
        $data['user_id'] = $user_id;
        $data['status'] = 2;
        $data['createtime']=time();
        $rel = $sendMailModel->add($data);

        if ($rel == NULL || $rel == "") {
            $this->error('错误');
        } else {
            $mail_id = $rel;
            $MailUserModel = M("MailUser");
            $data = array();
            $data['mail_id'] = $mail_id;
            $data['user_id'] = $verify_user_id;
            $data['type'] = 2;
            $data['status'] = 1;

            $rel = $MailUserModel->add($data);
            if ($rel == NULL) {
                $this->error('错误');
            } else {
                $this->success("提交成功");
            }
        }
    }
}