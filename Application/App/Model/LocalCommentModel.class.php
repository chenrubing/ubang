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
class LocalCommentModel extends Model
{

    protected $_validate = array(
        array('uid','require','字段不能为空',0),
        array('cid','require','字段不能为空',0),
        array('plan_id','require','字段不能为空',0),
        array('star','require','字段不能为空',0),
        array('content','require','字段不能为空',0),
        array('content','checklength','内容的长度8-100之间',0,'callback', 3, array(8, 100)), 

    );


    
    

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