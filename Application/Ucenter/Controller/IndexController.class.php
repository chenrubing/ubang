<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午1:54
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Controller;


use Think\Controller;

class IndexController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
       
    }
	
	//我的红包
	public function red($page=1)
    {
		if(I('get.status',0,'intval')){
			$map[status] = I('get.status',0,'intval');
		}
		
	   	$map['uid']= is_login();
        $list =  M('ShopRed')->where($map)->page($page, $r)->order('status desc')->select();
        $this->assign('list', $list);
        $this->setTitle('我的红包');
        $this->display();
    }
	
	
	
	
    /**消息页面
     * @param int    $page
     * @param string $tab 当前tab
     */
    public function messages($page = 1, $type = '')
    {
        $map = $this->getMapByTab($type, $map);

        $map['to_uid'] = is_login();

        $messages = D('Message')->where($map)->order('create_time desc')->page($page, 10)->select();
        $totalCount = D('Message')->where($map)->order('create_time desc')->count(); //用于分页

        foreach ($messages as &$v) {
            if ($v['from_uid'] != 0) {
                $v['from_user'] = query_user(array('username', 'space_url', 'avatar64', 'space_link'), $v['from_uid']);
            }
        }

        $this->assign('totalCount', $totalCount);
        $this->assign('messages', $messages);
        //设置Tab
         $this->setTitle('消息中心');
        $this->display();
    }
	

    /**
     * @param $tab
     * @param $map
     * @return mixed
     */
    private function getMapByTab($type, $map)
    {
        switch ($type) {
            case 'system':
                $map['type'] = 0;
                break;
            case 'user':
                $map['type'] = 1;
                break;
            case 'app':
                $map['type'] = 2;
                break;
            case 'all':
                break;
            default:
                $map['is_read'] = 0;
                break;
        }
        return $map;
    }
	
	
	public function setMessage($id='',$status='')
    {
		if($status < 1){
			if(M('Message')->where(array('id' => $id,'to_uid'=>is_login()))->delete()){
				$this->success("删除成功");	
			}else{
				$this->success("删除失败");	
			}
		}else{
			
			if(M('Message')->where(array('id'=>$id,'to_uid'=>is_login()))->setField('is_read',1)!==false)
			{
				$this->success("设置成功");
			}else{
				$this->error("设置失败");
			}
		}
    }
	
	
	//我的收藏
	 public function collection($page=1)
    {
        $mymark = M('ShopBookmark')->where(array('uid'=>is_login()))->select();
	    $totalCount = M('ShopBookmark')->where(array('uid'=>is_login()))->count();
		
		foreach($mymark as $k => $v){
			$list[]  = M('ShopGoods')->where(array('id'=>$v['shop_id']))->find();
		}
        $this->assign('totalCount', $totalCount);
        $this->assign('list', $list);
        $this->setTitle('我的收藏');
        $this->display();
    }

	public function delshopbook($id='0')
    {
		
		$map[uid] = is_login();
		$map[shop_id] = I('get.id','0','intval');
		
        if(M('ShopBookmark')->where($map)->delete()){
			$this->success('取消成功');
		}else{
			$this->error('删除失败');  
		}
    }
	
	
	
	//我的订单
	public function order($type='')
    {
	
		$map['uid'] = is_login();
		
		if(I('get.status')!='all'){
			$map['status'] = I('get.status',0,'intval');
		}
		$list = M('ShopOrder')->where($map)->order('create_time desc')->select();
		foreach($list as $k =>$v){
			$arr[$k] = explode('|',$v[goods_info]);
			$list[$k]['goods_info'] = $arr[$k];
			foreach($arr[$k] as $kt =>$vt){
				$list[$k]['goods_info'][$kt]=explode(",",$vt);
			}
		}
		
		$this->setTitle('我的订单');
		$this->assign('list', $list);
        $this->display();
    }
	
	//我的订单
	public function doorder() {
		
		$id = I('get.id',0,'intval');
		$status = I('get.status',0,'intval');
		
		$map['uid'] = is_login();
		$map['id'] = $id;
		
		if($status=='-4'){
			if(M('ShopOrder')->where($map)->delete()){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');	
			}
		}else{
			if(M('ShopOrder')->where($map)->setField('status',$status)){
				$this->success('订单状态变更成功');
			}else{
				$this->error('订单状态变更失败');	
			}
		}
    }
	
	
	
	

}