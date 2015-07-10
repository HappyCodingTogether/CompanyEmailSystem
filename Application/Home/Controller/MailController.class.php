<?php
namespace Home\Controller;
use ORG\Util\IncomingMailAttachment;
use ORG\Util\Mailbox;
use Think\Controller;
class MailController extends Controller
{
    public function index()
    {
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>', 'utf-8');
    }
    /*
     * 从邮件服务器接收邮件
     */
    public function receiveOutMail()
    {
        $mailAccountModel = M('Mail_account');
        $mailAccount = $mailAccountModel->find();
        if($mailAccount['receiveday']==-1){
            return;
        }else{
            session_write_close();
            import("@.ORG.Util.Mailbox");
            import("@.ORG.Util.IncomingMail");
            import("@.ORG.Util.Mailbox");
            $mailbox =new Mailbox('{'.$mailAccount['pop3server'].':995/pop3/ssl}INBOX', $mailAccount['emailusername'],$mailAccount['emailpassword'], C('DOWNLOAD_UPLOAD.rootPath'));
            $mailsIds = $mailbox->searchMailBox('RECENT');
            if(!$mailsIds) {
                die('Mailbox is empty');
            }
            $mailCount = count($mailsIds);
            for($i=$mailCount;$i>0;$i--){
                $receiveMailModel=M('ReceiveMails');
                $mailinfo=$mailbox->getMailsInfo(array($i));
                if(!empty($mailinfo[0]->message_id)){
                    $mid=$mailinfo[0]->message_id;
                }else{
                    $mid=$i."_".time()."@Localhost";
                }
                if($mailinfo[0]->udate>time()-86400*$mailAccount['receiveday']){
                    //$map['mid']=$mid;
                    $map['createtime']=$mailinfo[0]->udate;

                    $count=$receiveMailModel->where($map)->count();
                    if($count==0){
                        $mail=$mailbox->getMail($i);
                        $data=array();
                        $data['mid']=$mid;
                        $data['title']=$mail->subject;
                        $data['from']=$mail->fromName.'|'.$mail->fromAddress;
                        $data['to']=$mailAccount['emailaddress'];
//                        foreach($mail->to as $item){
//                            $data['to']=$data['to'].$item.';';
//                        }

                        $data['createtime']=$mailinfo[0]->udate;
                        if($mail->textPlain!=null){
                            $data['content']=$mail->textPlain;
                        }
                        if($mail->textHtml!=null){
                            $data['content']=$mail->textHtml;
                        }
                        if($mail->attachments!=null){
                            $incomingMailAttachment=$mail->attachments;
                            $addFile="";
                            foreach($incomingMailAttachment as $item){
                                $fileModel=M('File');
                                $fileData=array();
                                $fileData['name']=$item->name;
                                $fileData['savename']=$item->saveName;
                                $fileData['create_time']=$data['createtime'];
                                //$fileModel->save($fileData);
                                $addFileID=$fileModel->add($fileData);
                                if(!$addFileID){
                                    $this->error("附件存储错误！");
                                }else{
                                    $addFile=$addFileID.";".$addFile;
                                }
                            }
                            $data['add_file']=$addFile;
                        }else{
                            $data['add_file']="";
                        }
                        //var_dump($data);
                        if(!$receiveMailModel->add($data)){
                            $this->error("邮件存储错误！");
                        }


                    }else{
                        $mailbox->__destruct();
                        return;
                    }

                }else{
                    $mailbox->__destruct();
                    return;
                }


            }
            $mailbox->__destruct();
            return;


        }


    }
    //--------------------------------------------------------------------
    //   接收邮件附件
    //--------------------------------------------------------------------
    private function _receive_file($str, &$data) {

        $ar = array_filter(explode("?", $str));
        $files = array();
        if (!empty($ar)) {
            foreach ($ar as $key => $value) {
                $ar2 = explode("|", $value);
                $cid = $ar2[0];
                $inline = $ar2[1];
                $file_name = $ar2[2];
                $tmp_name = $ar2[3];

                $files[$key]['name'] = $file_name;
                $files[$key]['tmp_name'] = $tmp_name;
                $files[$key]['size'] = filesize($tmp_name);
                $files[$key]['is_move'] = true;

                if (!empty($files)) {
                    $File = D('File');
                    $file_driver = C('DOWNLOAD_UPLOAD_DRIVER');
                    $info = $File -> upload($files, C('DOWNLOAD_UPLOAD'), C('DOWNLOAD_UPLOAD_DRIVER'), C("UPLOAD_{$file_driver}_CONFIG"));
                    if ($inline == "INLINE") {
                        $data['content'] = str_replace("cid:" . $cid, $info[0]['path'],$data['content']);
                    } else {
                        $add_file=$info[0]['id'].';';
                        //$add_file = $add_file . think_encrypt($info[0]['id']) . ';';
                    }
                }
            }
        }
        return $add_file;
    }

    //--------------------------------------------------------------------
    //  发送邮件给外部
    //--------------------------------------------------------------------
    public function sendMail() {
        $role_id=$_SESSION['roleId'];
        $dealMailId=$_REQUEST['mail_id'];
        if($role_id==3&&$dealMailId!=null){
            $receiveModel=M("ReceiveMails");
            $data=array();
            $data['deal_status']=1;
            $receiveModel->where('id='.$dealMailId)->save($data);
            $data=null;
        }
        $mailAccountModel = M('Mail_account');
        $mailAccount = $mailAccountModel->find();

        $title =I('title');
        $body = htmlspecialchars_decode(I('emailContent'));
        $to = I('to');
        $cc = "";//I('cc');
        $bcc = "";//I('bcc');
        //$this -> _set_recent($to . $cc . $bcc);

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
            //嵌入式图片处理
            if (preg_match('/\/Data\/files\/\d{6}\/.{14}(jpg|gif|png)/', $body, $images)) {
                $i = 1;
                foreach ($images as $image) {
                    if (strlen($image) > 20) {
                        $cid = 'img' . ($i++);
                        $name = $mail -> AddEmbeddedImage(substr($image, 1), $cid);
                        $body = str_replace($image, "cid:$cid", $body);
                    }
                }
            }

            $mail -> MsgHTML($body);
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
                        $mail->AddAttachment(DOC_ROOT."/Uploads/Download/".$saveName,$imgName);
                        $addfiles=$addfiles.$fileid.';';
                    }

                }
            }

            $sendMailModel = M('Send_emails');
            $data=array();
            $data['title']=$title;
            $data['content']=$body;
            $data['from']=$mailAccount['emailname'] . '|' . $mailAccount['emailaddress'];
            $data['user_id']=$_SESSION['userId'];
            $data['add_file']=$addfiles;
            if($_REQUEST['mail_id']!=NULL&&$_REQUEST['mail_id']!=''){
                $data['pre_mail_id']=$_REQUEST['mail_id'];
            }
            $data['to']=$to;
            $data['createtime']=time();
            // $data['type'];
            //后期要将存数据库，发邮件的过程存高速缓存，定时任务执行
            $id = $sendMailModel -> add($data);

            if ($mail -> Send()) {
                $newdata['status']=1;
                $sendMailModel->where("id=".$id)->save($newdata);
                cookie('current_node', 105);
                $this -> assign('jumpUrl', U('Index/table'));
                $this -> success("发送成功");
            } else {
                $newdata['status']=0;
                $sendMailModel->where("id=".$id)->save($newdata);
                $this -> error($mail -> ErrorInfo);

            };
        } catch (phpmailerException $e) {
            $this->error(" 发送失败");
            //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            $this->error("发送失败");
            //Boring error messages from anything else!
        }
    }
    public function deal(){
        $email_id=$_REQUEST['email_id'];
        //var_dump($email_id);
        $receiveMailModel=M("ReceiveMails");
        $data=array();
        $data['deal_status']=1;
        $receiveMailModel->where('id='.$email_id)->save($data);
        echo json_encode("OK");
    }

    private function get_mail_list_by_dept_id($id) {
        $dept = tree_to_list(list_to_tree( M("Dept") -> where('is_del=0') -> select(), $id));
        $dept = rotate($dept);
        $dept = implode(",", $dept['id']) . ",$id";
        $model = M("User");
        $where['dept_id'] = array('in', $dept);
        $where['is_del'] = array('eq', 0);
        $where['email'] = array('neq', '');
        $data = $model -> where($where) -> select();
        return $data;
    }




}