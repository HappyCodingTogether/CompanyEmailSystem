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
        $from=$_SESSION['mailAddress'];
        $verify_user_id = $_REQUEST['verify_user'];
        $to = $_REQUEST['to'];
        $title = $_REQUEST['title'];
        $content = $_REQUEST['emailContent'];
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
        $files=$_FILES;
        $addfiles="";
        foreach($files as $item){
            if($item['size']>0&&$item['size']<1024*20000) {
                $dir = "Uploads/Download/";
                $imgName =$item['name'];
                $tmp=explode(".",$imgName);
                $count=count($tmp);
                $type=$tmp[$count-1];
                $rand = rand(0, 8000000);
                $saveName = $rand . time().'.'.$type;
                $path = $dir . $saveName;
                if (!is_dir($dir)) {
                    mkdir($dir);
                }
                $i = move_uploaded_file($item['tmp_name'], $path);
                $filedata['name']=$imgName;
                $filedata['savename']=$saveName;
                $filedata['create_time']=time();
                $fileModel=M("File");
                $fileid=$fileModel->add($filedata);
                if($fileid){
                    $addfiles=$addfiles.$fileid.';';
                }
            }
        }
        $data['add_file']=$addfiles;
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
    public function backmail(){
        $mail_id = $_REQUEST['mail_id'];
        $user_id = $_SESSION['userId'];
        $mailUser=M('mail_user');
        $mailUserId['user_id']=$user_id;
        $mailUserId['mail_id']=$mail_id;
        $abc=$mailUser->where($mailUserId)->delete();
        //var_dump($abc);*/
        $sendMail=M('send_emails');

        $data['status']='3';
        $data['content']=I('emailContent');
        //var_dump($data['content']);
        $abb=$sendMail->where('id='.$mail_id)->save($data);

        if (($abb == 1)&&($abc==1)) {
            $this->success("提交成功");
        } else {
            $this->error('错误');
        }
    }
    public function passmail(){
        $mailAccountModel = M('Mail_account');
        $mailAccount = $mailAccountModel->find();
        $id=I('id');
        import("@.ORG.Util.send");
        //从PHPMailer目录导入class.send.php类文件
        $mail = new \PHPMailer(true);
        // the true param means it will throw exceptions on errors, which we need to catch
        $mail -> IsSMTP();
        // telling the namespace Home\Controller;

        try {
            $mail -> Host = $mailAccount['stmpserver'];
            //"smtp.qq.com"; // SMTP server 部分邮箱不支持SMTP，QQ邮箱里要设置开启的
            $mail -> SMTPDebug = false;
            // 改为2可以开启调试
            $mail -> SMTPAuth = true;
            // enable SMTP authentication
            $mail -> Port = 25;
            // set the SMTP port for the GMAIL server
            $mail -> CharSet = "UTF-8";
            // 这里指定字符集！解决中文乱码问题
            $mail -> Encoding = "base64";

            $mail -> Username = $mailAccount['emailusername'];
            // SMTP account username
            $mail -> Password = $mailAccount['emailpassword'];
            // SMTP account password
            $mail -> SetFrom($mailAccount['emailaddress'], $mailAccount['emailname']);
            //发送者邮箱
            $mail -> AddReplyTo($mailAccount['emailaddress'], $mailAccount['emailname']);
            //回复到这个邮箱

            $sendMailModel = M('Send_emails');
            $mailinfo=$sendMailModel->where('id='.$id)->find();
            $to=$mailinfo['to'];
            $title=$mailinfo['title'];
            $body=$mailinfo['content'];
            $cc="";
            $bcc="";
            $addfiles=$mailinfo['add_file'];
            $arr_to = array_filter(explode(';', $to));
            foreach ($arr_to as $item) {
                if (strpos($item, "dept@group") !== false) {
                    $arr_tmp = array_filter(explode('|', $item));
                    $dept_id = str_replace("dept_", '', $arr_tmp[2]);
                    $mail_list = $this -> get_mail_list_by_dept_id($dept_id);
                    foreach ($mail_list as $val) {
                        $mail -> AddAddress($val["email"], $val["name"]);
                        // 收件人
                    }
                } else {
                    $arr_tmp = explode('|', $item);

                    if(count($arr_tmp)>1){

                        $mail -> AddAddress($arr_tmp[1], $arr_tmp[0]);
                    }else{

                        $mail -> AddAddress($arr_tmp[0],null);
                    }
                    // 收件人
                }
            }

            $arr_cc = array_filter(explode(';', $cc));
            foreach ($arr_cc as $item) {
                if (strpos($item, "dept@group") !== false) {
                    $arr_tmp = array_filter(explode('|', $item));
                    $dept_id = str_replace("dept_", '', $arr_tmp[2]);
                    $mail_list = $this -> get_mail_list_by_dept_id($dept_id);
                    foreach ($mail_list as $val) {
                        $mail -> AddCC($val["email"], $val["name"]);
                        // 收件人
                    }
                } else {
                    $tmp = explode('|', $item);
                    $mail -> AddCC($tmp[1], $tmp[0]);
                    // 收件人
                }
            }

            $arr_bcc = array_filter(explode(';', $bcc));
            foreach ($arr_bcc as $item) {
                if (strpos($item, "dept@group") !== false) {
                    $arr_tmp = array_filter(explode('|', $item));
                    $dept_id = str_replace("dept_", '', $arr_tmp[2]);
                    $mail_list = $this -> get_mail_list_by_dept_id($dept_id);
                    foreach ($mail_list as $val) {
                        $mail -> AddBCC($val["email"], $val["name"]);
                        // 收件人
                    }
                } else {
                    $tmp = explode('|', $item);
                    $mail -> AddBCC($tmp[1], $tmp[0]);
                    // 收件人
                }
            }

            $mail -> Subject = "=?UTF-8?B?" . base64_encode($title) . "?=";
            $mail -> MsgHTML($body);
            $addfiles=explode(";",$addfiles);
            foreach($addfiles as $item){
                    $fileModel=M('File');
                if($item!=null&&$item!=""){
                    $file=$fileModel->where('id='.$item)->find();
                    if($file){
                        $mail->AddAttachment(DOC_ROOT."/Uploads/Download/".$file['savename'],$file['name']);
                    }
                }

            }
            if ($mail -> Send()) {
                $newdata['status']=1;
                $sendMailModel->where("id=".$id)->save($newdata);
                cookie('current_node', 105);
                echo "true";
            } else {
                $newdata['status']=0;
                $sendMailModel->where("id=".$id)->save($newdata);
                echo "false";

            };
        } catch (phpmailerException $e) {
            echo "false";
            //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo "false";
            //Boring error messages from anything else!
        }
    }
}