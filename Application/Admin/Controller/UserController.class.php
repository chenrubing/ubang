<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminSortBuilder;
use Common\Model\MemberModel;
use Common\Model\TreeModel;
use User\Api\UserApi;
require_once APP_PATH . 'User/Conf/config.php';
/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UserController extends AdminController
{

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index()
    {
        $nickname = I('nickname');
        $map['status'] = array('egt', 0);
		$map['uid'] = array('neq', 1);
        if (is_numeric($nickname)) {
            $map['uid|nickname'] = array(intval($nickname), array('like', '%' . $nickname . '%'), '_multi' => true);
        } else {
            $map['nickname'] = array('like', '%' . (string)$nickname . '%');
        }
        $list = $this->lists('Member', $map);
		
		foreach($list as $k => $v){
			$list[$k][mobile] = M('UcenterMember')->where(array('id'=>$v[uid]))->getField('mobile');
		}
		
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }

    public function changeGroup()
    {

        if ($_POST['do'] == 1) {
            //清空group
            $aAll = I('post.all', 0, 'intval');
            $aUids = I('post.uid', array(), 'intval');
            $aGids = I('post.gid', array(), 'intval');

            if ($aAll) {//设置全部用户
                $prefix = C('DB_PREFIX');
                D('')->execute("TRUNCATE TABLE {$prefix}auth_group_access");
                $aUids = UCenterMember()->getField('id', true);

            } else {
                M('AuthGroupAccess')->where(array('uid' => array('in', implode(',', $aUids))))->delete();;
            }
            foreach ($aUids as $uid) {
                foreach ($aGids as $gid) {
                    M('AuthGroupAccess')->add(array('uid' => $uid, 'group_id' => $gid));
                }
            }


            $this->success('成功。');
        } else {
            $aId = I('post.id', array(), 'intval');

            foreach ($aId as $uid) {
                $user[] = query_user(array('space_link', 'uid'), $uid);
            }


            $groups = M('AuthGroup')->where(array('status'=>1))->select();
            $this->assign('groups', $groups);
            $this->assign('users', $user);
            $this->display();
        }

    }

    /**用户扩展资料信息页
     * @param null $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function expandinfo_select($page = 1, $r = 20)
    {
        $nickname = I('nickname');
        $map['status'] = array('egt', 0);
		$map['uid'] = array('neq', 1);
        if (is_numeric($nickname)) {
            $map['uid|nickname'] = array(intval($nickname), array('like', '%' . $nickname . '%'), '_multi' => true);
        } else {
            $map['nickname'] = array('like', '%' . (string)$nickname . '%');
        }
		
		
        $list = M('Member')->where($map)->order('last_login_time desc')->page($page, $r)->select();
        $totalCount = M('Member')->where($map)->count();
        int_to_string($list);
        //扩展信息查询
        $map_profile['status'] = 1;
        $field_group = D('field_group')->where($map_profile)->select();
        $field_group_ids = array_column($field_group, 'id');
        $map_profile['profile_group_id'] = array('in', $field_group_ids);
        $fields_list = D('field_setting')->where($map_profile)->getField('id,field_name,form_type');
        $fields_list = array_combine(array_column($fields_list, 'field_name'), $fields_list);
        $fields_list = array_slice($fields_list, 0, 8);//取出前8条，用户扩展资料默认显示8条
        foreach ($list as &$tkl) {
            $tkl['id'] = $tkl['uid'];
            $map_field['uid'] = $tkl['uid'];
            foreach ($fields_list as $key => $val) {
                $map_field['field_id'] = $val['id'];
                $field_data = D('field')->where($map_field)->getField('field_data');
                if ($field_data == null || $field_data == '') {
                    $tkl[$key] = '';
                } else {
                    $tkl[$key] = $field_data;
                }
            }
        }
        $builder = new AdminListBuilder();
        $builder->title("用户扩展资料列表");
        $builder->meta_title = '用户扩展资料列表';
        $builder->setSearchPostUrl(U('Admin/User/expandinfo_select'),'','')->search('搜索', 'nickname', 'text', '请输入用户昵称或者ID');
        $builder->keyId()->keyLink('nickname', "昵称", 'User/expandinfo_details?uid=###');
        foreach ($fields_list as $vt) {
            $builder->keyText($vt['field_name'], $vt['field_name']);
        }
        $builder->data($list);
        $builder->pagination($totalCount, $r);
        $builder->display();
    }

        

    /**用户扩展资料详情
     * @param string $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function adduser($uid = 0)
    {
		clean_query_user_cache(I('get.uid'), array('username', 'email', 'nickname', 'mobile'));
		$userinfo = query_user(array('username', 'email', 'nickname', 'mobile'), I('get.uid'));
		
        if(IS_POST){
			
        $data = I('post.');
        $data['category_ids']=",".implode(",", $data['category_ids']).",";
		if(!$data[uid]){	
        //创建会员
            $return = check_action_limit('reg', 'ucenter_member', 1, 1, true);
            if ($return && !$return['state']) {
                $this->error($return['info'], $return['url']);
            }
   
            $aUnType = 4; 
            check_username($data[username], $data[email], $data[mobile], $aUnType);
      
            /* 注册用户 */
            $uid = UCenterMember()->register($data[username], $data[nickname], $data[password], $data[email], $data[mobile], $aUnType);
            if (0 < $uid) { //注册成功
			
				$this->initRoleUser($data['show_role'], $uid); //初始化角色用户
				$data['uid'] = $uid;
				M('Member')->save($data); 
				
                $this->success('创建成功');
            } else { 
                 $this->error($this->showRegError($uid));
            }
		
		}else{
			//修改会员
            if (D('Member')->save($data)!==false) {
				
				//修改密码及手机 判断还有提交的数据与服务器数据不同时才更新
				if($data['username']!=$userinfo['username']){
					$saveUcenter['username'] = $data['username'];
				}
				if($data['mobile']!=$userinfo['mobile']){
					$saveUcenter['mobile'] = $data['mobile'];
				}
				if($data['email']!=$userinfo['email']){
					$saveUcenter['email'] = $data['email'];
				}
				if($data['password']){
					$saveUcenter['password'] = $data['password'];
				}
				
				if($saveUcenter){	
				 	$Api = new UserApi(); 
				 	$res = $Api->updateInfos($data['uid'], $saveUcenter);
				 	if ($res['status']<1) {
       		     		$this->error(UCenterMember()->getErrorMessage($res['info']));
       			 	}
				}
				 $this->success('修改成功！');
             }else{
				 $this->error('修改失败');
			 }
		 }
        }else{
			
			if(I('get.uid')){
				$map['uid'] = I('get.uid');
            	$map['status'] = array('egt', 0);
            	$user = M('Member')->where($map)->find();
				$member = array_merge($user, $userinfo);  
		   }
           $member['category_ids']=substr($member['category_ids'],0,-1);
           $member['category_ids']=substr($member['category_ids'],1);


            //dump($member['category_ids']);
			//获取角色
			$RoleList = M('Role')->where('status=1')->select();
			foreach($RoleList as $k => $v){
				$rlist[$v[id]]= $v[title];
			}
			//分类

			$CategoryList = M('ServiceCategory')->where('status=1')->select();
            $category=D("Tree")->toTree($CategoryList, $pk='id',$pid = 'pid',$child = '_child');
           
            $builder = new AdminConfigBuilder();
            $builder->title("用户管理");
			
            $builder->meta_title = '用户管理';
            $builder->keyId('uid')->keySelect('show_role', '角色','',$rlist)->keyText('username', '用户名','也可填写手机')
            ->keyText('nickname', '用户昵称')->keyText('mobile', '手机')->keyText('email', '邮箱')->keyPassWord('password', '密码')
            ->keyCheckBoxJieDian('category_ids', '经营项目', '多选', $category)
            ->keyRadio('sex', '性别','', array(0 => '保密',1 => '男',2 => '女'))->keyText('qq', 'QQ')->keyYear('birthday', '生日')->keyTextArea('signature', '签名')->keyCity('城市')->keyText('address','地址')->keyMap('map','地图标注');
			
			$field = D('Ucenter/Score')->getTypeList(array('status'=>1));
		
            foreach($field as $vf){
               $builder->keyInteger('score'.$vf['id'], $vf['title']);
            }
			
            $builder->data($member);
            $builder->buttonSubmit('', '保存');
            $builder->buttonBack();
            $builder->display();
        }
    }


        

	
     /**修改扩展字段
     * @param string $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
	public function expandinfo_details()
    {
	 
	  if(IS_POST){
		$uid = I('post.uid');
		$profile_group_id = I('post.profile_group_id');
		$field_list=$this->getRoleFieldIds($uid);
		
        if($field_list){
            $map_field['id']=array('in',$field_list);
        }else{
            $this->error('没有要保存的信息！');
        }
        $map_field['profile_group_id']=$profile_group_id;
        $map_field['status']=1;
        $field_setting_list = D('field_setting')->where($map_field)->order('sort asc')->select();

        if (!$field_setting_list) {
            $this->error('没有要修改的信息！');
        }

        $data = null;
        foreach ($field_setting_list as $key => $val) {
            $data[$key]['uid'] = $uid;
            $data[$key]['field_id'] = $val['id'];
            switch ($val['form_type']) {
                case 'input':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    if (!$val['value'] || $val['value'] == '') {
                        if ($val['required'] == 1) {
                            $this->error($val['field_name'] . '内容不能为空！');
                        }
                    } else {
                        $val['submit'] = $this->_checkInput($val);
                        if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                            $this->error($val['submit']['msg']);
                        }
                    }
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'radio':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'checkbox':
                    $val['value'] = $_POST['expand_' . $val['id']];
                    if (!is_array($val['value']) && $val['required'] == 1) {
                        $this->error('请至少选择一个：' . $val['field_name']);
                    }
                    $data[$key]['field_data'] = is_array($val['value']) ? implode('|', $val['value']) : '';
                    break;
                case 'select':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'time':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $val['value'] = strtotime($val['value']);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'textarea':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    if (!$val['value'] || $val['value'] == '') {
                        if ($val['required'] == 1) {
                            $this->error($val['field_name'] . '内容不能为空！');
                        }
                    } else {
                        $val['submit'] = $this->_checkInput($val);
                        if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                            $this->error($val['submit']['msg']);
                        }
                    }
                    $val['submit'] = $this->_checkInput($val);
                    if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                        $this->error($val['submit']['msg']);
                    }
                    $data[$key]['field_data'] = $val['value'];
                    break;
            }
        }
        $map['uid'] = $uid;
        $map['role_id']=M('Member')->where(array('uid'=>$uid))->getField('show_role');
        $is_success = false;
        foreach ($data as $dl) {
            $dl['role_id']=$map['role_id'];
            $map['field_id'] = $dl['field_id'];
            $res = D('field')->where($map)->find();
            if (!$res) {
                if ($dl['field_data'] != '' && $dl['field_data'] != null) {
                    $dl['createTime'] = $dl['changeTime'] = time();
					
                    if (!D('field')->add($dl)) {
                        $this->error('信息添加时出错！');
                    }
                    $is_success = true;
                }
            } else {
                $dl['changeTime'] = time();
                if (!D('field')->where('id=' . $res['id'])->save($dl)) {
                    $this->error('信息修改时出错！');
                }
                $is_success = true;
            }
            unset($map['field_id']);
        }
        clean_query_user_cache($uid, 'expand_info');
        if ($is_success) {
            $this->success('保存成功！');
        } else {
            $this->error('没有要保存的信息！');
        }
    }else{
		$uid = I('get.uid');
		A('Ucenter/config')->getExpandInfo($uid);	
	}
        $this->display();
    }
	
	
	/**
     * 获取角色字段
     * @param $role_id
     * @param $uid
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
	 private function getRoleFieldIds($uid=null){
        $role_id=get_role_id($uid);
        $fields_list=S('Role_Expend_Info_'.$role_id);
        if(!$fields_list){
            $map_role_config=getRoleConfigMap('expend_field',$role_id);
            $fields_list=D('RoleConfig')->where($map_role_config)->getField('value');
            if($fields_list){
                $fields_list=explode(',',$fields_list);
                S('Role_Expend_Info_'.$role_id,$fields_list,600);
            }
        }
        return $fields_list;
    }
	
	
	 /**
     * 初始化角色用户信息
     * @param $role_id
     * @param $uid
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function initRoleUser($role_id = 0, $uid)
    {
        $memberModel = D('Member');
        $role = D('Role')->where(array('id' => $role_id))->find();
        $user_role = array('uid' => $uid, 'role_id' => $role_id, 'step' => "finish");
        if ($role['audit']) { //该角色需要审核
            $user_role['status'] = 2; //未审核
        } else {
            $user_role['status'] = 1;
        }
        $result = D('UserRole')->add($user_role);
        if (!$role['audit']) { //该角色不需要审核
            $memberModel->initUserRoleInfo($role_id, $uid);
        }
        $memberModel->initDefaultShowRole($role_id, $uid);

        return $result;
    }
	
	
	
	



    /**扩展用户信息分组列表
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function profile($page = 1, $r = 20)
    {
        $map['status'] = array('egt', 0);
        $profileList = D('field_group')->where($map)->order("sort asc")->page($page, $r)->select();
        $totalCount = D('field_group')->where($map)->count();
        $builder = new AdminListBuilder();
        $builder->title("扩展信息分组列表");
        $builder->meta_title = '扩展信息分组';
        $builder->buttonNew(U('editProfile', array('id' => '0')))->buttonDelete(U('changeProfileStatus', array('status' => '-1')))->setStatusUrl(U('changeProfileStatus'))->buttonSort(U('sortProfile'));
        $builder->keyId()->keyText('profile_name', "分组名称")->keyText('sort', '排序')->keyTime("createTime", "创建时间")->keyBool('visiable', '是否公开');
        $builder->keyStatus()->keyDoAction('User/field?id=###', '管理字段')->keyDoAction('User/editProfile?id=###', '编辑');
        $builder->data($profileList);
        $builder->pagination($totalCount, $r);
        $builder->display();
    }
	

    /**扩展分组排序
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function sortProfile($ids = null)
    {
        if (IS_POST) {
            $builder = new AdminSortBuilder();
            $builder->doSort('Field_group', $ids);
        } else {
            $map['status'] = array('egt', 0);
            $list = D('field_group')->where($map)->order("sort asc")->select();
            foreach ($list as $key => $val) {
                $list[$key]['title'] = $val['profile_name'];
            }
            $builder = new AdminSortBuilder();
            $builder->meta_title = '分组排序';
            $builder->data($list);
            $builder->buttonSubmit(U('sortProfile'))->buttonBack();
            $builder->display();
        }
    }

    /**扩展字段列表
     * @param $id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function field($id, $page = 1, $r = 20)
    {
        $profile = D('field_group')->where('id=' . $id)->find();
        $map['status'] = array('egt', 0);
        $map['profile_group_id'] = $id;
        $field_list = D('field_setting')->where($map)->order("sort asc")->page($page, $r)->select();
        $totalCount = D('field_setting')->where($map)->count();
        $type_default = array(
            'input' => '单行文本框',
            'radio' => '单选按钮',
            'checkbox' => '多选框',
            'select' => '下拉选择框',
            'time' => '日期',
            'textarea' => '多行文本框'
        );
        $child_type = array(
            'string' => '字符串',
            'phone' => '手机号码',
            'email' => '邮箱',
            'number' => '数字'
        );
        foreach ($field_list as &$val) {
            $val['form_type'] = $type_default[$val['form_type']];
            $val['child_form_type'] = $child_type[$val['child_form_type']];
        }
        $builder = new AdminListBuilder();
        $builder->title('【' . $profile['profile_name'] . '】 字段管理');
        $builder->meta_title = $profile['profile_name'] . '字段管理';
        $builder->buttonNew(U('editFieldSetting', array('id' => '0', 'profile_group_id' => $id)))->buttonDelete(U('setFieldSettingStatus', array('status' => '-1')))->setStatusUrl(U('setFieldSettingStatus'))->buttonSort(U('sortField', array('id' => $id)))->button('返回', array('href' => U('profile')));
        $builder->keyId()->keyText('field_name', "字段名称")->keyBool('visiable', '是否公开')->keyBool('required', '是否必填')->keyText('sort', "排序")->keyText('form_type', '表单类型')->keyText('child_form_type', '二级表单类型')->keyText('form_default_value', '默认值')->keyText('validation', '表单验证方式')->keyText('input_tips', '用户输入提示');
        $builder->keyTime("createTime", "创建时间")->keyStatus()->keyDoAction('User/editFieldSetting?profile_group_id=' . $id . '&id=###', '编辑');
        $builder->data($field_list);
        $builder->pagination($totalCount, $r);
        $builder->display();
    }

    /**分组排序
     * @param $id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function sortField($id = '', $ids = null)
    {
        if (IS_POST) {
            $builder = new AdminSortBuilder();
            $builder->doSort('FieldSetting', $ids);
        } else {
            $profile = D('field_group')->where('id=' . $id)->find();
            $map['status'] = array('egt', 0);
            $map['profile_group_id'] = $id;
            $list = D('field_setting')->where($map)->order("sort asc")->select();
            foreach ($list as $key => $val) {
                $list[$key]['title'] = $val['field_name'];
            }
            $builder = new AdminSortBuilder();
            $builder->meta_title = $profile['profile_name'] . '字段排序';
            $builder->data($list);
            $builder->buttonSubmit(U('sortField'))->buttonBack();
            $builder->display();
        }
    }

    /**添加、编辑字段信息
     * @param $id
     * @param $profile_group_id
     * @param $field_name
     * @param $child_form_type
     * @param $visiable
     * @param $required
     * @param $form_type
     * @param $form_default_value
     * @param $validation
     * @param $input_tips
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function editFieldSetting($id = 0, $profile_group_id = 0, $field_name = '', $child_form_type = 0, $visiable = 0, $required = 0, $form_type = 0, $form_default_value = '', $validation = 0, $input_tips = '')
    {
        if (IS_POST) {
            $data['field_name'] = $field_name;
            if ($data['field_name'] == '') {
                $this->error('字段名称不能为空！');
            }
            $data['profile_group_id'] = $profile_group_id;
            $data['visiable'] = $visiable;
            $data['required'] = $required;
            $data['form_type'] = $form_type;
            //当表单类型为以下三种是默认值不能为空判断@MingYang
            $form_types = array('radio','checkbox','select');
            if(in_array($data['form_type'],$form_types)){
                if($data['form_default_value'] == ''){
                    $this->error($data['form_type'].'表单类型默认值不能为空');
                }
            }
            $data['input_tips'] = $input_tips;
            //增加当二级字段类型为join时也提交$child_form_type @MingYang
            if ($form_type == 'input' && $child_form_type == 'join') {
                $data['child_form_type'] = $child_form_type;
            }
            $data['form_default_value'] = $form_default_value;
            $data['validation'] = $validation;
            if ($id != '') {
                $res = D('field_setting')->where('id=' . $id)->save($data);
            } else {
                $map['field_name'] = $field_name;
                $map['status'] = array('egt', 0);
                $map['profile_group_id'] = $profile_group_id;
                if (D('field_setting')->where($map)->count() > 0) {
                    $this->error('该分组下已经有同名字段，请使用其他名称！');
                }
                $data['status'] = 1;
                $data['createTime'] = time();
                $data['sort'] = 0;
                $res = D('field_setting')->add($data);
            }
            if ($res) {
                $this->success($id == '' ? "添加字段成功" : "编辑字段成功", U('field', array('id' => $profile_group_id)));
            } else {
                $this->error($id == '' ? "添加字段失败" : "编辑字段失败");
            }
        } else {
            $builder = new AdminConfigBuilder();
            if ($id != 0) {
                $field_setting = D('field_setting')->where('id=' . $id)->find();
                $builder->title("修改字段信息");
                $builder->meta_title = '修改字段信息';
            } else {
                $builder->title("添加字段");
                $builder->meta_title = '新增字段';
                $field_setting['profile_group_id'] = $profile_group_id;
                $field_setting['visiable'] = 1;
                $field_setting['required'] = 1;
            }
            $type_default = array(
                'input' => '单行文本框',
                'radio' => '单选按钮',
                'checkbox' => '多选框',
                'select' => '下拉选择框',
                'time' => '日期',
                'textarea' => '多行文本框'
            );
            $child_type = array(
                'string' => '字符串',
                'phone' => '手机号码',
                'email' => '邮箱',
                //增加可选择关联字段类型 @MingYang
                'join' => '关联字段',
                'number' => '数字'
            );
            $builder->keyReadOnly("id", "标识")->keyReadOnly('profile_group_id', '分组id')->keyText('field_name', "字段名称")->keySelect('form_type', "表单类型", '', $type_default)->keySelect('child_form_type', "二级表单类型", '', $child_type)->keyTextArea('form_default_value',"多个值用'|'分割开,格式【字符串：男|女，数组：1:男|2:女，关联数据表：字段名|表名】开")
                ->keyText('validation', '表单验证规则', '例：min=5&max=10')->keyText('input_tips', '用户输入提示', '提示用户如何输入该字段信息')->keyBool('visiable', '是否公开')->keyBool('required', '是否必填');
            $builder->data($field_setting);
            $builder->buttonSubmit(U('editFieldSetting'), $id == 0 ? "添加" : "修改")->buttonBack();

            $builder->display();
        }

    }

    /**设置字段状态：删除=-1，禁用=0，启用=1
     * @param $ids
     * @param $status
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setFieldSettingStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('field_setting', $ids, $status);
    }

    /**设置分组状态：删除=-1，禁用=0，启用=1
     * @param $status
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function changeProfileStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
        D('field_group')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        }

    }

    /**添加、编辑分组信息
     * @param $id
     * * @param $profile_name
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function editProfile($id = 0, $profile_name = '', $visiable = 1)
    {
        if (IS_POST) {
            $data['profile_name'] = $profile_name;
            $data['visiable'] = $visiable;
            if ($data['profile_name'] == '') {
                $this->error('分组名称不能为空！');
            }
            if ($id != '') {
                $res = D('field_group')->where('id=' . $id)->save($data);
            } else {
                $map['profile_name'] = $profile_name;
                $map['status'] = array('egt', 0);
                if (D('field_group')->where($map)->count() > 0) {
                    $this->error('已经有同名分组，请使用其他分组名称！');
                }
                $data['status'] = 1;
                $data['createTime'] = time();
                $res = D('field_group')->add($data);
            }
            if ($res) {
                $this->success($id == '' ? "添加分组成功" : "编辑分组成功", U('profile'));
            } else {
                $this->error($id == '' ? "添加分组失败" : "编辑分组失败");
            }
        } else {
            $builder = new AdminConfigBuilder();
            if ($id != 0) {
                $profile = D('field_group')->where('id=' . $id)->find();
                $builder->title("修改分组信息");
                $builder->meta_title = '修改分组信息';
            } else {
                $builder->title("添加扩展信息分组");
                $builder->meta_title = '新增分组';
            }
            $builder->keyReadOnly("id", "标识")->keyText('profile_name', '分组名称')->keyBool('visiable', '是否公开');
            $builder->data($profile);
            $builder->buttonSubmit(U('editProfile'), $id == 0 ? "添加" : "修改")->buttonBack();
            $builder->display();
        }

    }

    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname()
    {
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display();
    }

    /**
     * 修改昵称提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitNickname()
    {
        //获取参数
        $nickname = I('post.nickname');
        $password = I('post.password');
        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User = new UserApi();
        $uid = $User->login(UID, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member = D('Member');
        $data = $Member->create(array('nickname' => $nickname));
        if (!$data) {
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid' => $uid))->save($data);

        if ($res) {
            $user = session('user_auth');
            $user['username'] = $data['nickname'];
            session('user_auth', $user);
            session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        } else {
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword()
    {
        $this->meta_title = '修改密码';
        $this->display();
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitPassword()
    {
        //获取参数
        $password = I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if ($data['password'] !== $repassword) {
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api = new UserApi();
        $res = $Api->updateInfo(UID, $password, $data);
        if ($res['status']) {
            $this->success('修改密码成功！');
        } else {
            $this->error(UCenterMember()->getErrorMessage($res['info']));
        }
    }

    /**
     * 用户行为列表
     * @author huajie <banhuajie@163.com>
     */
    public function action()
    {
        //获取列表数据
        $Action = M('Action')->where(array('status' => array('gt', -1)));
        $list = $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author huajie <banhuajie@163.com>
     */
    public function addAction()
    {
        $this->meta_title = '新增行为';
        $this->assign('data', null);
        $this->display('editaction');
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function editAction()
    {
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data', $data);
        $this->meta_title = '编辑行为';
        $this->display();
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction()
    {
        $res = D('Action')->update();
        if (!$res) {
            $this->error(D('Action')->getError());
        } else {
            $this->success($res['id'] ? '更新成功！' : '新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method = null)
    {
        $id = array_unique((array)I('id', 0));
        if (in_array(C('USER_ADMINISTRATOR'), $id)) {
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',', $id) : $id;
        if (empty($id)) {
            $this->error('请选择要操作的数据!');
        }
        $map['uid'] = array('in', $id);
		$uid['id'] = array('in', $id);
        switch (strtolower($method)) {
            case 'forbiduser':
                $this->forbid('Member', $map);
                break;
            case 'resumeuser':
                $this->resume('Member', $map);
                break;
            case 'deleteuser':
				if(C('TRUE_EDL_USER')){
					$this->true_delete_user('Member', $map, $uid);
				}else{
				  	$this->delete('Member', $map);
				}
                break;
            default:
                $this->error('参数非法');

        }
    }


    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    public function showRegError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = '用户名长度必须在4-16个字符以内！';
                break;
            case -2:
                $error = '用户名被禁止注册！';
                break;
            case -3:
                $error = '用户名被占用！';
                break;
            case -4:
                $error = '密码长度必须在6-30个字符之间！';
                break;
            case -5:
                $error = '邮箱格式不正确！';
                break;
            case -6:
                $error = '邮箱长度必须在4-32个字符之间！';
                break;
            case -7:
                $error = '邮箱被禁止注册！';
                break;
            case -8:
                $error = '邮箱被占用！';
                break;
            case -9:
                $error = '手机格式不正确！';
                break;
            case -10:
                $error = '手机被禁止注册！';
                break;
            case -11:
                $error = '手机号被占用！';
                break;
            case -20:
                $error = '用户名只能由数字、字母和"_"组成！';
                break;
            case -30:
                $error = '昵称被占用！';
                break;
            case -31:
                $error = '昵称被禁止注册！';
                break;
            case -32:
                $error = '昵称只能由数字、字母、汉字和"_"组成！';
                break;
            case -33:
                $error = '昵称不能少于两个字！';
                break;
            default:
                $error = '未知错误24';
        }
        return $error;
    }



    public function scoreList()
    {
        //读取数据
        $map = array('status' => array('GT', -1));
        $model = D('Ucenter/Score');
        $list = $model->getTypeList($map);

        //显示页面
        $builder = new AdminListBuilder();
        $builder
            ->title('积分类型')
            ->suggest('id<=4的不能删除')
            ->buttonNew(U('editScoreType'))
            ->setStatusUrl(U('setTypeStatus'))->buttonEnable()->buttonDisable()->button('删除',array('class' => 'btn ajax-post tox-confirm', 'data-confirm' => '您确实要删除积分分类吗？（删除后对应的积分将会清空，不可恢复，请谨慎删除！）', 'url' => U('delType'), 'target-form' => 'ids'))
            ->button('充值',array('href' => U('recharge')))

            ->keyId()->keyText('title', '名称')
           ->keyText('unit', '单位')->keyStatus()->keyDoActionEdit('editScoreType?id=###')
            ->data($list)
            ->display();
    }

    public function recharge(){
        $scoreTypes = D('Ucenter/Score')->getTypeList(array('status'=>1));
        if(IS_POST){
            $aUids = I('post.uid','','op_t');
            foreach($scoreTypes as $v){
                $aAction = I('post.action_score'.$v['id'],'','op_t');
                $aValue = I('post.value_score'.$v['id'],0,'intval');
                D('Ucenter/Score')->setUserScore($aUids,$aValue,$v['id'],$aAction);
            }
            $this->success('设置成功','refresh');
        }else{

            $this->assign('scoreTypes',$scoreTypes);
            $this->display();
        }
    }

    public function getNickname(){
        $uid = I('get.uid',0,'intval');
        if($uid){
            $user = query_user(null,$uid);
            $this->ajaxReturn($user);
        }
        else{
            $this->ajaxReturn(null);
        }

    }

    public function setTypeStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('ucenter_score_type', $ids, $status);

    }

    public function delType($ids){
        $model = D('Ucenter/Score');
        $res = $model->delType($ids);
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    public function editScoreType(){
            $aId = I('id',0,'intval');
            $model = D('Ucenter/Score');
            if (IS_POST) {
                $data['title'] = I('post.title','','op_t');
                $data['status'] = I('post.status',1,'intval');
                $data['unit'] = I('post.unit','','op_t');

                if ($aId != 0) {
                    $data['id'] = $aId;
                    $res = $model->editType($data);
                } else {
                    $res = $model->addType($data);
                }
                if ($res) {
                    $this->success(($aId == 0 ? '添加' : '编辑') . '成功');
                } else {
                    $this->error(($aId == 0 ? '添加' : '编辑') . '失败');
                }
            } else {
                $builder = new AdminConfigBuilder();
                if ($aId != 0) {
                    $type = $model->getType(array('id'=>$aId));
                } else {
                    $type = array('status' => 1, 'sort' => 0);
                }
                $builder->title(($aId == 0 ? '新增' : '编辑').'积分分类')->keyId()->keyText('title', '名称')
                    ->keyText('unit', '单位')
                    ->keySelect('status', '状态', null,  array(-1 => '删除', 0 => '禁用', 1 => '启用'))
                    ->data($type)
                    ->buttonSubmit(U('editScoreType'))->buttonBack()->display();
            }
    }




    /**input类型验证
     * @param $data
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    function _checkInput($data)
    {
        if ($data['form_type'] == "textarea") {
            $validation = $this->_getValidation($data['validation']);
            if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                if ($validation['max'] == 0) {
                    $validation['max'] = '';
                }
                $info['succ'] = 0;
                $info['msg'] = $data['field_name'] . "长度必须在" . $validation['min'] . "-" . $validation['max'] . "之间";
            }
        } else {
            switch ($data['child_form_type']) {
                case 'string':
                    $validation = $this->_getValidation($data['validation']);
                    if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                        if ($validation['max'] == 0) {
                            $validation['max'] = '';
                        }
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "长度必须在" . $validation['min'] . "-" . $validation['max'] . "之间";
                    }
                    break;
                case 'number':
                    if (preg_match("/^\d*$/", $data['value'])) {
                        $validation = $this->_getValidation($data['validation']);
                        if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                            if ($validation['max'] == 0) {
                                $validation['max'] = '';
                            }
                            $info['succ'] = 0;
                            $info['msg'] = $data['field_name'] . "长度必须在" . $validation['min'] . "-" . $validation['max'] . "之间，且为数字";
                        }
                    } else {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "必须是数字";
                    }
                    break;
                case 'email':
                    if (!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "格式不正确，必需为邮箱格式";
                    }
                    break;
                case 'phone':
                    if (!preg_match("/^\d{11}$/", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "格式不正确，必须为手机号码格式";
                    }
                    break;
            }
        }
        return $info;
    }
	
	
	    /**处理$validation
     * @param $validation
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    function _getValidation($validation)
    {
        $data['min'] = $data['max'] = 0;
        if ($validation != '') {
            $items = explode('&', $validation);
            foreach ($items as $val) {
                $item = explode('=', $val);
                if ($item[0] == 'min' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['min'] = $item[1];
                }
                if ($item[0] == 'max' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['max'] = $item[1];
                }
            }
        }
        return $data;
    }

}
