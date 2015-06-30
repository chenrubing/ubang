<?php

namespace Ucenter\Controller; 
use Think\Controller;

class PostController extends Controller
{
   
    public function index()
    {
		
		$site_id = I('get.site_id','','intval');
		$site_id || $site_id = 1;
		//推广交接页面，如果没登陆，则说明是未注册用户，则记住get过来的site_id
		if(!is_login()){
			session('form_uid',$site_id); 
		}
		$this->display();
    }
	
}