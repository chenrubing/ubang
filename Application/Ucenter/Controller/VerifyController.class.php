<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM3:40
 */

namespace Ucenter\Controller;

use Think\Controller;

class VerifyController extends Controller
{


    /**
     * sendVerify 发送验证码
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function sendVerify()
    {
        $aAccount = $cUsername = I('post.account', '', 'op_t');   
        $den = I("post.den");
        $aType = I('post.type', '', 'op_t');
        $aType = $aType == 'mobile' ? 'mobile' : 'email';
        $aAction = I('post.action', 'config', 'op_t');
        //$this->ajaxReturn(array('status'=>0,'info'=>I("post.")));
        

        if (!check_reg_type($aType)) {
            $str = $aType == 'mobile' ? '手机' : '邮箱';
            //$this->error($str . '选项已关闭！');
            $this->ajaxReturn(array("info"=>$str.'选项已关闭！'));
        }


        if (empty($aAccount)) {
            //$this->error('帐号不能为空');
            $this->ajaxReturn(array("info"=>'帐号不能为空'));
        }
        check_username($cUsername, $cEmail, $cMobile);

        if ($aType == 'email' && empty($cEmail)) {
            //$this->error('请验证邮箱格式是否正确');
            $this->ajaxReturn(array("info"=>'请验证邮箱格式是否正确'));
        }
        if ($aType == 'mobile' && empty($cMobile)) {
            //$this->error('请验证手机格式是否正确');
            $this->ajaxReturn(array("info"=>'请验证手机格式是否正确'));
        }
        $checkIsExist = UCenterMember()->where(array($aType => $aAccount))->find();
        

        $verify = D('Verify')->addVerify($aAccount, $aType);
        if (!$verify) {
            //$this->error('发送失败！');
            $this->ajaxReturn(array("info"=>'发送失败！'));
        }


        $res = A(ucfirst($aAction))->doSendVerify($aAccount, $verify, $aType);
        
        if ($res === true) {
            //$this->success('发送成功，请查收');
            $this->ajaxReturn(array("info"=>'发送成功，请查收'));
        } else{
            //$this->error($res);
            $this->ajaxReturn(array("status"=>1,"info"=>$res));
        }


    }


}