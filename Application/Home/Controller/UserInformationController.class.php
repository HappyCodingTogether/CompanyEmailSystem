<?php
/**
 * Created by PhpStorm.
 * User: CY
 * Date: 15-7-3
 * Time: 下午7:12
 */

namespace Home\Controller;


use Think\Controller;

class UserInformationController extends Controller{
    public function  showInformation(){
        $userInformation = M('user');
        $role= M('role');
        $userId=$_SESSION['userId'];
        $information = $userInformation->where('id='.$userId)->select();
        $user_role = $role->where('id='.$information[0]['role_id'])->select();
        $this->assign('account',$information[0]['account']);
        $this->assign('name',$information[0]['name']);
        $this->assign('authority',$user_role[0]['name']);
        $this->assign('phone',$information[0]['phone']);
        $this->assign('email',$information[0]['email']);
        if($_SESSION['roleId']==1){
            $this->display('userInformation/systemUserInformation');
        }else{
            $this->display('userInformation/userInformation');
        }

    }
    public function modifyInformation(){
        $data['account'] = $_POST['account'];
        $data['name'] = $_POST['name'];
        $data['phone'] = $_POST['phone'];
        $data['email'] = $_POST['email'];
        $data['id'] = $_SESSION['userId'];
        $password = $_POST['password'];
        $userId = $_SESSION['userId'];
        $userModel = M('user');
        if($password != '')
        {
            $data['password'] = md5($_POST['password']);
            $userModel->where('id='.$userId)->save($data);
        }
        else
        {
            $userModel->where('id='.$userId)->save($data);
        }
        if($_SESSION['roleId']==1){
            $this->redirect('User/manager');
        }else{
            $this->redirect('UserInformation/showInformation');
        }


    }
} 