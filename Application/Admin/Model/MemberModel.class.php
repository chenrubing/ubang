<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class MemberModel extends Model {

    protected $_validate = array(
        array('nickname', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
        array('nickname', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
    );

    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = array('status' => $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($uid);
        if(!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }

        //记录行为
        action_log('user_login', 'member', $uid, $uid);

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'username'        => $user['nickname'],
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }
	
	
	
	 /**
     * 设置角色用户默认基本信息
     * @param $role_id
     * @param $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function initUserRoleInfo($role_id,$uid){
        $roleModel=D('Role');
        $roleConfigModel=D('RoleConfig');
        $authGroupAccessModel=D('AuthGroupAccess');
        D('UserRole')->where(array('role_id'=>$role_id,'uid'=>$uid))->setField('init',1);
        //默认用户组设置
        $role=$roleModel->where(array('id'=>$role_id))->find();
        if($role['user_groups']!=''){
            $role=explode(',',$role['user_groups']);

            //查询已拥有用户组
            $have_user_group_ids=$authGroupAccessModel->where(array('uid'=>$uid))->select();
            $have_user_group_ids=array_column($have_user_group_ids,'group_id');
            //查询已拥有用户组 end

            $authGroupAccess['uid']=$uid;
            $authGroupAccess_list=array();
            foreach($role as $val){
                if($val!=''&&!in_array($val,$have_user_group_ids)){//去除已拥有用户组
                    $authGroupAccess['group_id']=$val;
                    $authGroupAccess_list[]=$authGroupAccess;
                }
            }
            unset($val);
            $authGroupAccessModel->addAll($authGroupAccess_list);
        }
        //默认用户组设置 end

        $map['role_id']=$role_id;
        $map['name']=array('in',array('score','rank'));
        $config=$roleConfigModel->where($map)->select();
        $config=array_combine(array_column($config,'name'),$config);



        //默认积分设置
        if(isset($config['score']['value'])){
            $value=json_decode($config['score']['value'],true);
            $data=$this->getUserScore($role_id,$uid,$value);
            $user=$this->where(array('uid'=>$uid))->find();
            foreach($data as $key=>$val){
                if($val>0){
                    if(isset($user[$key])){
                        $this->where(array('uid'=>$uid))->setInc($key,$val);
                    }else{
                        $this->where(array('uid'=>$uid))->setField($key,$val);
                    }
                }
            }
            unset($val);
        }
        //默认积分设置 end



        //默认头衔设置
        if(isset($config['rank']['value'])&&$config['rank']['value']!=''){
            $ranks=explode(',',$config['rank']['value']);
            if(count($ranks)){
                //查询已拥有头衔
                $rankUserModel=D('RankUser');
                $have_rank_ids=$rankUserModel->where(array('uid'=>$uid))->select();
                $have_rank_ids=array_column($have_rank_ids,'rank_id');
                //查询已拥有头衔 end

                $reason=json_decode($config['rank']['data'],true);
                $rank_user['uid']=$uid;
                $rank_user['create_time']=time();
                $rank_user['status']=1;
                $rank_user['is_show']=1;
                $rank_user['reason']=$reason['reason'];
                $rank_user_list=array();
                foreach($ranks as $val){
                    if($val!=''&&!in_array($val,$have_rank_ids)){//去除已拥有头衔
                        $rank_user['rank_id']=$val;
                        $rank_user_list[]=$rank_user;
                    }
                }
                unset($val);
                $rankUserModel->addAll($rank_user_list);
            }
        }
        //默认头衔设置 end
    }

    //默认显示哪一个角色的个人主页设置
    public function initDefaultShowRole($role_id,$uid)
    {
        $userRoleModel=D('UserRole');

        $roles=$userRoleModel->where(array('uid'=>$uid,'status'=>1,'role_id'=>array('neq',$role_id)))->select();
        if(!count($roles)){
            $data['show_role']=$role_id;
            //执行member表默认值设置
            $this->where(array('uid'=>$uid))->save($data);
        }
    }

}
