<?php

namespace App\Controller;
use Think\Controller;
use User\Api\UserApi;
require_once APP_PATH . 'User/Conf/config.php';
/*数据返回方式 data【array】
 * status 状态
 * data 数据
 * msg 信息
 ************************/
class ApiController extends Controller {
	
	public function index(){
		
	
	
	}
	//登陆
	public function login()
    {
		if(IS_AJAX){
			
			$data = I('post.');
			
			$uid = UCenterMember()->login($data[username], $data[password], 1); //通过账号密码取到uid
			
			if($uid > 0){
				D('Member')->login($uid, false, 1); //无密码登陆
				
				$_data = array('status'=>'1','data'=>array('userid'=>$uid,'username'=>$data['username'],'msg'=>'登陆成功！')); 	
			}else{
				 switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; 
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; 
                }
                $_data=array(
					'status'=>'0',					
					'msg'=>'登陆失败！'
					);
			}
			
			$this->ajaxReturn($_data);
		} 
		$this->display();
	}
	
	public function get_MemberInfo(){
		
		if(IS_AJAX){
			$MemberData =M('Member')->where(array('uid'=>is_login()))->find(); 
			$MemberData[face] = get_cover($MemberData[face],'path');

			if($MemberData){
				$this->ajaxReturn(array('status'=>1,'data'=>$MemberData));
			}else{
				$this->ajaxReturn(array('status'=>0));
			}
		}
	}


	//发布需求
	public function desired()
	{
		if(IS_AJAX)
		{
			$data=I('post.');
			$data['uid']=is_login();
			$data['time']=strtotime($data['time']);
			$data['create_time']=time();
			$data['status']=1;
			if (!D("Service")->create($data)){
			   $this->error(D("Service")->getError());
			}
			else{
				$dr=D("Service")->add($data);
				if($dr){
					$this->ajaxReturn(array('status'=>1));
				}
				else{
					$this->ajaxReturn(array('status'=>0));
				}
			}
		}
		$map=array('status'=>array("EGT",0));
		$service_category=M("ServiceCategory");
		$list=$service_category->where("pid=0")->select();
		$this->assign("list",$list);
		$this->display();

	}

	//交付定金
	public function plan()
	{
		$arrfo =  M('ServiceConfig')->where(array('id'=>1))->find();
		//定金
		if(IS_AJAX)
		{
			$me=M("Member");
			$se=D("ServicePlan");
			$li=$me->where(array("score1"=>array("egt",$arrfo['switchs']),"uid"=>is_login()))->find();
			if($li){
				$des=I("post.");
				$des['create_time'] = time();
				$des['status']=1;
				$des['credit']=1;
				$des['uid'] = is_login();
				$cee['uid']=is_login();
				$cee['score1']=$li['score1']-$arrfo['switchs'];
				$me->startTrans();

				if (!$se->create($data)){
			   		$this->error($se->getError());
				}
				else{
					$dr=$se->add($des);
					$drs=$me->save($cee);
					if($dr && $drs){
						$this->ajaxReturn(array('status'=>1,"msg"=>"预约成功"));
						$me->commit();
					}
					else{
						$this->ajaxReturn(array('status'=>0,"info"=>"预约失败"));
						$me->rollback();
					}
				}
			}
			else{
				$this->ajaxReturn(array('status'=>0,"info"=>"您的余额不足，请充值"));
			}
		}
		$arrfo =  M('ServiceConfig')->where(array('id'=>1))->find();
		$dat['status'] = array('EGT','0');
        $dat['uid'] = array('GT','1');
        $arr=array();
        $listke=M('Member')->where($dat)->where(array("show_role"=>1))->select(); 
        $this->assign("arrfo",$arrfo);
        $this->assign("listke",$listke);
        $this->display();
	}	


	public function local()
	{
		if(IS_AJAX)
		{
			$data=I("post.");
			$data['create_time']=time();
			$data['status']=1;
			$data['uid']=is_login();
			//D("LocalComment")->startTrans();
			if (!D("LocalComment")->create($data)){
			   	$this->error(D("LocalComment")->getError());
			}
			else
			{
				$li=D("LocalComment")->add($data);
				$di=M("ServicePlan")->where(array("id"=>$data['plan_id']))->save(array("credit"=>3));
				if(!empty($li) && !empty($di))
				{
					$this->ajaxReturn(array('status'=>1,"msg"=>"评论成功"));
					//D("LocalComment")->commit();
				}
				else
				{
					$this->ajaxReturn(array('status'=>0,"info"=>"评论失败"));
					//D("LocalComment")->rollback();
				}
			}
		}

		$dat['status'] = array('EGT','0');
        $dat['uid'] = array('GT','1');
        $listke=M('Member')->where($dat)->where(array("show_role"=>1))->select();
        $this->assign("listke",$listke);
        $this->display();
	}

	public function fenlei()
	{
		if(IS_AJAX)
		{
			$map=array('status'=>array("EGT",0));
			$map['pid']=I('post.pid');
			$service_category=M("ServiceCategory");
			$list=$service_category->where($map)->select();
			if($list)
			{
				$this->ajaxReturn(array('status'=>1,'data'=>$list));
			}
			else
			{
				$this->ajaxReturn(array('status'=>0));
			}
		}
	}



	
	
	
	
}