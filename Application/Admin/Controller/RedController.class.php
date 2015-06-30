<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-18
 * Time: 上午10:07
 * @author 郑薏玮<715713881@qq.com>
 */
namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;

use Think\Model;

/**
 * Class ShopController
 * @package Admin\controller
 * @郑钟良
 */
class RedController extends AdminController
{



    function _initialize()
    {
        $this->RedModel = D('Shop/ShopRed');

        parent::_initialize();
    }
	
	 

	
    /**红包列表
     * @param int $page
     * @param int $r
     * @author 郑薏玮<715713881@qq.com>
     */
    public function index($page = 1, $r = 20)
    {
		
       
        $goodsList = $this->RedModel->order('create_time desc')->page($page, $r)->select();
        $totalCount = $this->RedModel->count();
        $builder = new AdminListBuilder();
        $builder->title('红包记录');
        $builder->meta_title = '红包记录';
		
		
        $builder->buttonSetStatus(U('redStatus'),'-1','已过期')->buttonSetStatus(U('redStatus'),'1','未使用')->buttonSetStatus(U('redStatus'),'2','已使用');
        $builder->keyId()->keyUid('uid', '红包所有者')->keyUid('formuid', '发放者')->keyText('money', '红包金额')->keyTime('overtime', '过期时间')->keyCreateTime()->key('status','状态', 'status',array(-1=>'已过期',1=>'未使用',2=>'已使用'));
        $builder->data($goodsList);
        $builder->pagination($totalCount, $r);
        $builder->display();
    }
	
		//删除佣金记录
	public function redStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
		
        M('ShopRed')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('设置成功');
        } else if ($status == 0) {
            $this->success('设置成功');
        } else {
            $this->success('设置成功');
        } 
    }

	

}

