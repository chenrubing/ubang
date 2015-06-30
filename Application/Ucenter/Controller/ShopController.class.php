<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午1:54
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Controller; 
use Think\Controller;

class ShopController extends BaseController
{
   

    public function index()
    {
		
		
	   clean_query_user_cache(is_login(), array('nickname', 'sex', 'signature', 'weixin', 'qq', 'shopname', 'mobile', 'avatar128', 'score4'));
       $memberinfo = query_user(array('nickname', 'signature', 'weixin', 'qq', 'mobile', 'avatar128', 'sex', 'shopname', 'score4'), is_login());
	
	   $num =sizeof(explode("\r\n",shopCofing('postup')));//获得代理商级别
	   //我邀请来的会员数
	   $myuser_num = M('Member')->where(array('form_uid'=>is_login()))->select();
	    foreach($myuser_num as $k => $v){
		   $myuserorder_num+= M('ShopOrder')->where(array('uid'=>$v['uid']))->count();
		   $myuserorder_price+= M('ShopOrder')->where(array('uid'=>$v['uid']))->sum('realprice');
	   }
	   
	   //我的所有代理会员
	   $myuser_all = M('MemberLink')->where(array('link_uid'=>is_login()))->select(); 
	   
	   foreach($myuser_all as $k => $v){
		   $allorder_num+= M('ShopOrder')->where(array('uid'=>$v['uid']))->count();
		   $allorder_price+= M('ShopOrder')->where(array('uid'=>$v['uid']))->sum('realprice');
	   }
	   
		$this->assign('num', $num);
		$this->assign('myuser_num', sizeof($myuser_num));
		$this->assign('myuser_all', sizeof($myuser_all));
		$this->assign('allorder_num', $allorder_num);
		$this->assign('allorder_price', $allorder_price);
		$this->assign('myuserorder_num', $myuserorder_num);
		$this->assign('myuserorder_price', $myuserorder_price);
		$this->assign('memberinfo', $memberinfo);
		
		
        $this->display();
    }
	
	
	 public function config($id = null,  $shopname = '', $qq = '', $weixin = '', $signature = '')
    {

        if (IS_POST) {
            $user['shopname'] = $shopname;
            $user['qq'] = $qq;
			$user['weixin'] = $weixin;
            $user['signature'] = $signature;
			if(M('Member')->where(array('uid'=>is_login()))->save($user)!==false){
				$this->success('设置成功，快来开启微商之旅吧！');
			}else{
				$this->error('设置失败');	
			}
		
        } else {
            //显示页面
            $this->assign('user', M('Member')->where(array('uid'=>is_login()))->find());
            $this->display();
        }

    }
	
	public function user()
    {
		$myuser = M('MemberLink')->where(array('link_uid'=>is_login()))->select(); 

		$this->assign('list', $myuser);
        $this->display();
    }
	
	
	public function order()
    {
		
	   $uids = M('MemberLink')->where(array('link_uid'=>is_login()))->field('uid')->select(); 
		foreach($uids as $k =>$v){
			$arr[] = $v['uid'];
		}
	   $allorder = M('ShopOrder')->where(array('uid' => array('in', implode(',',$arr))))->select();
	   $this->assign('userorder', $allorder);
       $this->display();
    }
	
	
	public function cash()
    {
		
        $info = M('Member')->where(array('uid'=>is_login()))->find();
		
		 if (IS_POST) {
			 $data = I('post.');
			 $data['uid']= is_login();
		     $data['create_time']= time();
			 
			if (!$data[money]) {
           	 	$this->error('请填写提现金额');
     	    }
			 
			if($info[score4]<$data[money]){
				  $this->error('佣金不足'.$data[money].'元');
			}
			 
		   if (!$data[accounts]) {
           	 $this->error('请填写卡号账户信息');
     	   }
		   
		   
		   if (!$data[name]) {
           	 $this->error('请填写开户名或支付宝/财付通的用户名');
     	   }
		   
		   if ($data[cashtype]=='银行卡') {
			 if (!$data[bank]) {
           		 $this->error('请填写开户行');
     	 	 }  
			 if (!is_numeric($data[accounts])) {
           		 $this->error('银行卡号只能为数字');
     	 	 }   
     	   } 
		   
		  	 if(M('ShopCash')->add($data)){
			  	 M('Member')->where(array('uid'=>is_login()))->setDec('score4',$data[money]);
			 	 $this->success('提现成功已经扣除'.$data[money].'元佣金提现到您的金融账户，如果24小时内仍没有到账，请联系我们的客服',U('Ucenter/shop/index'));
		  	 }else{
			   $this->error('提现失败');
		  	 }
		 }
		
        $log = D('ShopCash')->where(array('uid'=>is_login(),'status'=>array('egt',0)))->select();
        $this->assign('info', $info);
		$this->assign('log', $log);
        $this->setTitle('佣金提现');
        $this->display($type);
    }
	

	
	
}