<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 15-6-29
 * Time: 下午6:43
 */
namespace Home\Controller;
use Think\Controller;

class DistributionController extends Controller{

    public function distribute(){//分发功能函数
        $emailId = $_REQUEST['mailIds'];
        $userRead = $_REQUEST['userRead'];
        $userWrite = $_REQUEST['userWrite'];

        $emailIdArray = explode(",",$emailId);
        $distributionModel = M('MailUser');

        if($emailIdArray != NULL)
        {
            if($userRead != NULL)
            {
                foreach($userRead as $value){
                    foreach($emailIdArray as $mailValue){
                        $data = array();
                        $data['mail_id'] = $mailValue;
                        $data['user_id'] = $value;
                        $data['type'] = 0;
                        $data['status'] = 0;
                        $distributionModel->add($data);
                    }

                }
            }
            if($userWrite != NULL)
            {
                foreach($emailIdArray as $mailValue){
                    $data = array();
                    $data['mail_id'] = $mailValue;
                    $data['user_id'] = $userWrite;
                    $data['type'] = 1;
                    $data['status'] = 0;
                    $distributionModel->add($data);

                    $receiveModel = M(ReceiveMails);
                    $data = array();
                    $data['status'] = 1;

                   $receiveModel->where('id='.$mailValue)->save($data);
                }
            }
           $this->redirect('Index/table');
        }
    }
}