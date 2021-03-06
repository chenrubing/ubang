<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;

/**
 * 后台分类管理控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class CategoryController extends AdminController
{

    public function _initialize(){
        parent::_initialize();
        $this->getMenu();
    }

    /**
     * 分类管理列表
     */
    public function index(){
		//如果开启异步加载，那么就不需要实例化
 		if(!C('CATEGORY_JSON')){
			$Category = D('Category');
   			$list  = $Category->order('`pid` asc, `sort` asc, id asc')->select(); 	
      	 	$alist = $Category->Tree($list);
 			$this->assign('alist', $alist);
		}
        $this->meta_title = '分类管理';
     	$this->display();
    }
	
	 
  	 /**
     * 异步json加载 郑薏玮 <715713881@qq.com>
     */
	public function GetTree(){
       	$Category = D('Category');
		if(array_key_exists( 'id',$_REQUEST)) {
			$pId=$_REQUEST['id'];
		}
		$pId || $pId=0;
   		$list  = $Category->order('`pid` asc, `sort` asc, id asc')->where("pid=$pId")->select(); 	
		foreach ($list as $key => $rs) {
	   		$child = $Category->where("pid=$rs[id]")->select();
			$isParent = ($child) ? ('true') : ('false'); 
			$json_tree.="{ id:'$rs[id]',name:'$rs[title]',pId:'$rs[pid]',status:'$rs[status]',ename:'$rs[status]',isParent:$isParent},";
		}
		echo "[$json_tree]";
    
    }
	
	public function Select(){
      
		$this->assign('tree', $tree);
        $this->display();
    }
	
    /**
     * 显示左边菜单，进行权限控制
     * @author huajie <banhuajie@163.com>
     */
    protected function getMenu()
    {
        //获取动态分类
        $cate_auth = AuthGroupModel::getAuthCategories(UID); //获取当前用户所有的内容权限节点
        // dump($cate_auth);exit;
        $cate_auth = $cate_auth == null ? array() : $cate_auth;
        $cate = M('Category')->where(array('status' => 1))->field('id,title,pid,allow_publish')->order('pid,sort')->select();

        //没有权限的分类则不显示
        if (!IS_ROOT) {
            foreach ($cate as $key => $value) {
                if (!in_array($value['id'], $cate_auth)) {
                    unset($cate[$key]);
                }
            }
        }

        $cate = list_to_tree($cate); //生成分类树

        //获取分类id
        $cate_id = I('param.cate_id');
        $this->cate_id = $cate_id;

        //是否展开分类
        $hide_cate = true;

        //生成每个分类的url
        foreach ($cate as $key => &$value) {
            $value['url'] = 'Article/index?cate_id=' . $value['id'];
            if ($cate_id == $value['id'] && $hide_cate) {
                $value['current'] = true;
            } else {
                $value['current'] = false;
            }
            if (!empty($value['_child'])) {
                $is_child = false;
                foreach ($value['_child'] as $ka => &$va) {
                    $va['url'] = 'Article/index?cate_id=' . $va['id'];
                    if (!empty($va['_child'])) {
                        foreach ($va['_child'] as $k => &$v) {
                            $v['url'] = 'Article/index?cate_id=' . $v['id'];
                            $v['pid'] = $va['id'];
                            $is_child = $v['id'] == $cate_id ? true : false;
                        }
                    }
                    //展开子分类的父分类
                    if ($va['id'] == $cate_id || $is_child) {
                        $is_child = false;
                        if ($hide_cate) {
                            $value['current'] = true;
                            $va['current'] = true;
                        } else {
                            $value['current'] = false;
                            $va['current'] = false;
                        }
                    } else {
                        $va['current'] = false;
                    }
                }
            }
        }
        $this->assign('nodes', $cate);

        $this->assign('cate_id', $this->cate_id);

        //获取面包屑信息
        $nav = get_parent_category($cate_id);
        $this->assign('rightNav', $nav);
        //获取回收站权限
        $show_recycle = $this->checkRule('Admin/article/recycle');
        $this->assign('show_recycle', IS_ROOT || $show_recycle);
        //获取草稿箱权限
        $this->assign('show_draftbox', C('OPEN_DRAFTBOX'));
    }

    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function tree($tree = null)
    {
        C('_SYS_GET_CATEGORY_TREE_') || $this->_empty();
        $this->assign('tree', $tree);
        $this->display('tree');
    }

    /* 编辑分类 */
    public function edit($id = null, $pid = 0)
    {
        $Category = D('Category');

        if (IS_POST) { //提交表单
		 if($pid==$id){
                $this->error("目标分类不能是本身");
            }
            if (false !== $Category->update()) {
				if($_POST[jump]){
					$this->success("编辑成功！",$_POST[jump]);
				}else{
					$this->success("编辑成功！", U('index'));
				}
				
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if ($pid) {
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if (!($cate && 1 == $cate['status'])) {
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

			
 			
            /* 获取分类信息 */
            $info = $id ? $Category->info($id) : '';
      		$list  = $Category->order('`pid` asc, `sort` asc, id asc')->select(); 	
       		$alist = $Category->Tree($list);
			$this->meta_title = '编辑分类';
            $this->assign('info', $info);
            $this->assign('category', $cate);
			$this->assign('alist', $alist);
            $this->display();
        }
    }

    /* 新增分类 */
    public function add($pid = 0)
    {
        $Category = D('Category');

        if (IS_POST) { //提交表单
            if (false !== $Category->update()) {
                $this->success('新增成功！', U('index'));
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if ($pid) {
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if (!($cate && 1 == $cate['status'])) {
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }
			
			/* 获取分类信息 */
      		$list  = $Category->order('`pid` asc, `sort` asc, id asc')->select(); 	
       		$alist = $Category->Tree($list);
			$this->meta_title = '新增分类';
			$this->assign('alist', $alist);
            $this->assign('category', $cate);
            $this->display('edit');
        }
    }

    /**
     * 删除一个分类
     * @author huajie <banhuajie@163.com>
     */
    public function remove()
    {
        $cate_id = I('id');
        if (empty($cate_id)) {
            $this->error('参数错误!');
        }

        //判断该分类下有没有子分类，有则不允许删除
        $child = M('Category')->where(array('pid' => $cate_id))->field('id')->select();
        if (!empty($child)) {
            $this->error('请先删除该分类下的子分类');
        }

        //判断该分类下有没有内容
        $document_list = M('Document')->where(array('category_id' => $cate_id))->field('id')->select();
        if (!empty($document_list)) {
            $this->error('请先删除该分类下的文章（包含回收站）');
        }

        //删除该分类信息
        $res = M('Category')->delete($cate_id);
        if ($res !== false) {
            //记录行为
            action_log('update_category', 'category', $cate_id, UID);
            $this->success('删除分类成功！');
        } else {
            $this->error('删除分类失败！');
        }
    }

 

    /**
     * 合并分类
     * @author huajie <banhuajie@163.com>
     */
    public function merge()
    {
        $to = I('post.to');
        $from = I('post.from');
        $Model = M('Category');

        //检查分类绑定的模型
        $from_models = explode(',', $Model->getFieldById($from, 'model'));
        $to_models = explode(',', $Model->getFieldById($to, 'model'));
        foreach ($from_models as $value) {
            if (!in_array($value, $to_models)) {
                $this->error('请给目标分类绑定' . get_document_model($value, 'title') . '模型');
            }
        }

        //检查分类选择的文档类型
        $from_types = explode(',', $Model->getFieldById($from, 'type'));
        $to_types = explode(',', $Model->getFieldById($to, 'type'));
        foreach ($from_types as $value) {
            if (!in_array($value, $to_types)) {
                $types = C('DOCUMENT_MODEL_TYPE');
                $this->error('请给目标分类绑定文档类型：' . $types[$value]);
            }
        }

        //合并文档
        $res = M('Document')->where(array('category_id' => $from))->setField('category_id', $to);

        if ($res) {
            //删除被合并的分类
            $Model->delete($from);
            $this->success('合并分类成功！', U('index'));
        } else {
            $this->error('合并分类失败！');
        }

    }
}
