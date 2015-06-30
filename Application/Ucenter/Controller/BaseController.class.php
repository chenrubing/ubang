<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM3:40
 */

namespace Ucenter\Controller;
use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
		
        if (!is_login()) {
            $this->error('需要登录');
        }
		
        //红包过期处理
		$red[uid] = is_login();
		$red[overtime] =  array('ELT',time());
		$red[status] = 1;
		M('ShopRed')->where($red)->setField('status','-1');
        $this->mid = is_login();
	
		//获取微店配置
		$Config = M('ShopConfig')->where(array('id'=>1))->find();
		$Config['cashtype'] = explode("\r\n",$Config['cashtype']);
		$this->assign('Config', $Config);
    }


    protected function ensureApiSuccess($result)
    {
        if (!$result['success']) {
            $this->error($result['message'], $result['url']);
        }
    }

    protected function requireLogin()
    {
        if (!is_login()) {
            $this->error('必须登录才能操作');
        }
    }
}