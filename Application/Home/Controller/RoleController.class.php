<?php
/**
 * Created by PhpStorm.
 * User: wys
 * Date: 15-7-8
 * Time: 上午9:54
 */
namespace Home\Controller;
use Think\Controller;
class RoleController extends Controller{
    public function index(){
        $roleModel=M("Role");
        $roleList=$roleModel->select();
        $this->assign('roleList',$roleList);

        $nodeModel=M("Node");
        $node1List=$nodeModel->where('level=1')->select();
        $this->assign('node1List',$node1List);
        $this->display();
    }

    public function edit(){
        $role_id=$_REQUEST['id'];
        $roleModel=M("Role");
        $role=$roleModel->where('id='.$role_id)->find();
        $accessModel=M("Access");
        $access=$accessModel->where('role_id='.$role_id)->select();
        $accessId=array();
        foreach($access as $item){
            $accessId[]=$item['node_id'];
        }
        $nodeModel=M("Node");
        $node1List=$nodeModel->where('level=1')->order('sort')->select();
        foreach($node1List as &$node){
            if(in_array($node['id'],$accessId)){
                $node['checked']=1;
            }else{
                $node['checked']=0;
            }
        }
        $node2List=$nodeModel->where('level=2')->order('sort')->select();
        foreach($node2List as &$node){
            if(in_array($node['id'],$accessId)){
                $node['checked']=1;
            }else{
                $node['checked']=0;
            }
        }
        $this->assign('role',$role);
        $this->assign('node1List',$node1List);
        $this->assign('node2List',$node2List);
        $this->display();
    }
    public function add(){
        $nodeModel=M("Node");
        $node1List=$nodeModel->where('level=1')->order('sort')->select();
        $node2List=$nodeModel->where('level=2')->order('sort')->select();
        $this->assign('node1List',$node1List);
        $this->assign('node2List',$node2List);
        $this->display();
    }

    public function update(){
        $nodes=$_REQUEST['nodes'];
        $role_name=$_REQUEST['role_name'];
        $role_id=$_REQUEST['role_id'];
        $roleModel=M("Role");
        $accessModel=M('Access');
        $map=null;
        $map['id']=array('neq',$role_id);
        $role=$roleModel->where($map)->select();
        $role_name_list=array();
        foreach($role as $item){
            $role_name_list[]=$item['name'];
        }
        if(!in_array($role_name,$role_name_list)){
            $data=null;
            $map=null;
            $map['id']=$role_id;
            $data['name']=$role_name;
            $roleModel->where($map)->save($data);
            $map=null;
            $map['role_id']=$role_id;
            $accessModel->where($map)->delete();

            foreach($nodes as $item){
                $data=null;
                $data['role_id']=$role_id;
                $data['node_id']=$item;
                $accessModel->where($map)->add($data);

            }
            $this -> assign('jumpUrl', U('Role/index'));
            $this -> success("修改成功");
        }else{
            $this->error('修改失败');
        }

    }
    public function insert(){
        $nodes=$_REQUEST['nodes'];
        $role_name=$_REQUEST['role_name'];
        $roleModel=M('Role');
        $accessModel=M('Access');
        $role=$roleModel->select();
        $role_name_list=array();
        foreach($role as $item){
            $role_name_list[]=$item['name'];
        }
        if(!in_array($role_name,$role_name_list)){
            $data=null;
            $data['name']=$role_name;
            $role_id=$roleModel->add($data);

            if($role_id!=0){
                foreach($nodes as $item){
                    $data=null;
                    $data['role_id']=$role_id;
                    $data['node_id']=$item;
                    $accessModel->add($data);
                }
                $this->assign('jumpUrl',U('Role/index'));
                $this->success('添加成功');
            }
        }else{
            $this->error('添加失败');
        }
    }
}