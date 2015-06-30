<?php
// +----------------------------------------------------------------------
// | i友街 [ 新生代贵州网购社区 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.iyo9.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: i友街 <iyo9@iyo9.com> <http://www.iyo9.com>
// +----------------------------------------------------------------------
// 
namespace Addons\TencentMap;
use Common\Controller\Addon;

/**
 * 中国省市区三级联动插件
 * @author i友街
 */

    class TencentMapAddon extends Addon{

        public $info = array(
            'name'=>'TencentMap',
            'title'=>'腾讯地图插件',
            'description'=>'用于用户地图标注',
            'status'=>1,
            'author'=>'郑薏玮',
            'version'=>'1.0'
        );
		
		 public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

		
		//与地图互动的钩子
        public function TencentMap($param){
            $this->assign('param', $param);
            $this->display('map');
        }
		
	
	

        //获取插件所需的钩子是否存在
        public function getisHook($str, $addons, $msg=''){
            $hook_mod = M('Hooks');
            $where['name'] = $str;
            $gethook = $hook_mod->where($where)->find();
            if(!$gethook || empty($gethook) || !is_array($gethook)){
                $data['name'] = $str;
                $data['description'] = $msg;
                $data['type'] = 1;
                $data['update_time'] = NOW_TIME;
                $data['addons'] = $addons;
                if( false !== $hook_mod->create($data) ){
                    $hook_mod->add();
                }
            }
        }

    
    }