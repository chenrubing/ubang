<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-7
 * Time: 下午1:57
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace App\Model;

use Think\Model;

/**
 * 角色模型
 * Class RoleModel
 * @package Admin\Model
 * @郑钟良
 */
class ServicePlanModel extends Model
{

    protected $_validate = array(
        array('uid','require','字段不能为空',0),
        array('cid','require','字段不能为空',0),
        array('total_money','require','字段不能为空',0),
        array('credit','require','字段不能为空',0),
        array('money','require','字段不能为空',0),
        array('time','require','字段不能为空',0),
        array('mobile','require','字段不能为空',0),
        array('address','require','字段不能为空',0),
        array('money','double','金额必须是数字',0), 
        array('total_money','double','金额必须是数字',0), 
        array('mobile','validateMobile','手机号码格式出错',0,'callback'),

    );


    public function validateMobile($mobile) {     
        $res1= preg_match('/^1(3[0-9]|5[0-35-9]|8[025-9])\\d{8}$/', $mobile);    
        $res2= preg_match('/^1(34[0-8]|(3[5-9]|5[017-9]|8[278])\\d)\\d{7}$/', $mobile);    
        $res3= preg_match('/^1(3[0-2]|5[256]|8[56])\\d{8}$/', $mobile);  
        $res4= preg_match('/^1((33|53|8[09])[0-9]|349)\\d{7}$/', $mobile);      
        $res5= preg_match('/^0(10|2[0-5789]|\\d{3})\\d{7,8}$/', $mobile);    
      
        if ( $mobile && ( $res1 ||$res2 ||$res3 ||$res4 ||$res5  ) )  
        {  
            return true;  
        }else{  
           return false;  
        }   
    } 
    

    function checklength($str, $min, $max) {
        preg_match_all("/./u", $str, $matches);
        $len = count($matches[0]);
        if ($len < $min || $len > $max) {
            return false;
        } else {
            return true;
        }
    }

    
} 