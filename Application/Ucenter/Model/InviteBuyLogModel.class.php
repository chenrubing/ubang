<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-25
 * Time: 下午5:23
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Model;


use Think\Model;

class InviteBuyLogModel extends Model
{
    public function buy($type_id = 0, $num = 0)
    {
        $invite_type=D('Ucenter/InviteType')->where(array('id'=>$type_id))->find();
        $user=query_user('nickname');
        $data['content']="{$user} 在 ".time_format(time()).' 时购买了 '.$num.' 个 '.$invite_type['title'].' 的邀请名额';

        $data['uid']=is_login();
        $data['invite_type']=$type_id;
        $data['num']=$num;
        $data['create_time']=time();

        $result=$this->add($data);
        return $result;
    }

    public function getList($map=array(),&$totalCount,$page=1,$order='create_time desc',$r=20)
    {
        if(count($map)){
            $totalCount=$this->where($map)->count();
            if($totalCount){
                $list=$this->where($map)->order($order)->page($page,$r)->select();
            }
        }else{
            $totalCount=$this->count();
            if($totalCount){
                $list=$this->order($order)->page($page,$r)->select();
            }
        }
        $list=$this->_initSelectData($list);
        return $list;
    }

    private function _initSelectData($list=array())
    {
        $inviteTypeModel=D('Ucenter/InviteType');
        foreach($list as &$val){
            $inviteType=$inviteTypeModel->getSimpleData();
            $val['invite_type_title']=$inviteType['title'];
            $val['user']=query_user('nickname',$val['uid']);
            $val['user']='['.$val['uid'].']'.$val['user'];
        }
        unset($val);
        return $list;
    }
} 