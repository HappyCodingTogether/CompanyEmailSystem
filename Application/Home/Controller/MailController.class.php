<?php
namespace Home\Controller;
//set_time_limit(0);
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
            //var_dump($mailAccount);
            session_write_close();
            import("@.ORG.Util.receive");
            $mail_list = array();
            $mail = new \receiveMail();
            $new = 0;
            $connect = $mail->connect($mailAccount['pop3server'], '110', $mailAccount['emailusername'], $mailAccount['emailpassword'], 'INBOX', 'pop3/novalidate-cert');
            if (!$connect) {
                $connect = $mail->connect($mailAccount['pop3server'], '995', $mailAccount['emailusername'], $mailAccount['emailpassword'], 'INBOX', 'pop3/ssl/novalidate-cert');
            }
            //var_dump($connect);
            $mail_count = $mail->mail_total_count();

            if ($connect) {

                for ($i = 1; $i <= $mail_count; $i++) {
                    $mail_id = $mail_count - $i + 1;
                    $item = $mail->mail_list($mail_id);
                    $map = array();
                    if (empty($item[$mail_id])) {
                        $temp_mail_header = $mail->mail_header($mail_id);
                        $map['mid'] = $temp_mail_header['mid'];
                    } else {
                        $map['mid'] = $item[$mail_id];
                    }
                    $count = M('Receive_mails')->where($map)->count();

                    if ($count == 0) {
                        $model = M("Receive_mails");
                        $mailHeader = $mail->mail_header($mail_id);
                        $data = array();
                        $data['mid'] = $mailHeader['mid'];
                        $data['title'] = $mailHeader['name'];
                        $data['content'] = $mailHeader['content'];
                        $data['from'] = $mailHeader['from'];
                        $data['to'] = $mailHeader['to'];
                        $data['createtime']=$mailHeader['create_time'];
                        //var_dump($data['createtime']<strtotime(date('y-m-d h:i:s')) - 86400 * 30);
                        if($mailAccount['receiveday']!=0){

                            if($data['createtime']<strtotime(date('y-m-d h:i:s')) - 86400*$mailAccount['receiveday']){
                                $mail -> close_mail();
                                return ;
                            }
                        }

                        $model->add($data);
                    }else{

                        $mail->close_mail();
                    }
                }
                $mail->close_mail();

            }


        }

    }

    //--------------------------------------------------------------------
    //  发送邮件给外部
    //--------------------------------------------------------------------
    public function sendMail() {
        $mailAccountModel = M('Mail_account');
        $mailAccount = $mailAccountModel->find();

        $title =I('title');
        $body = I('emailContent');

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

            $add_file = $_REQUEST['add_file'];
            if (!empty($add_file)) {
                $files = array_filter(explode(';', $add_file));
                foreach ($files as $file) {
                    $file_id = think_decrypt($file);
                    $vo=M("File")->find($file_id);
                    $mail -> AddAttachment("..".__ROOT__ ."/".C('DOWNLOAD_UPLOAD.rootPath') . $vo['savepath'].$vo['savename'],$vo['name']);
                }
            }

            $sendMailModel = M('Send_emails');
            $data=array();
            $data['title']=$title;
            $data['content']=$body;
            $data['from']=$mailAccount['emailname'] . '|' . $mailAccount['emailaddress'];
            $data['user_id']=getUserId();
            if($_REQUEST['mail_id']!=NULL&&$_REQUEST['mail_id']!=''){
                $data['pre_mail_id']=$_REQUEST['mail_id'];
            }
            $data['to']=$to;
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