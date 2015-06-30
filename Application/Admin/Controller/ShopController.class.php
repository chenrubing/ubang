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
class ShopController extends AdminController
{

    public function _initialize()
    {
        /*
        $this->shopModel = D('Shop/ShopGoods');
		$this->ShopBuy = D('Shop/ShopOrder');
        $this->shop_configModel = D('Shop/ShopConfig');
        $this->shop_categoryModel = D('Shop/ShopCategory');*/
		
		//获取微店配置
		$Config = M('ServiceConfig')->where(array('id'=>1))->find();
		$Config['scale_b'] = $Config['scale']*0.01;
		$Config['cashtype'] = explode("\r\n",$Config['cashtype']);
		$Config['postup'] = explode("\r\n",$Config['postup']);
		$Config['postup_num'] =sizeof($Config['postup']);//获得代理商有多少级（数组长度）
		
		$this->Config->$Config;
		$this->assign('Config', $Config);
		
        parent::_initialize();
    }
	
    /**微店配置
     * @param int $id
     * @param string $cname
     * @author 郑薏玮<715713881@qq.com>
     */
    public function config()
    {
        if (IS_POST) {
			$data =I('post.');
			
            if (!$data['pname']) {
                $this->error('佣金名称不能为空');
            }
			
			if (!$data['pname'] || !$data['postup'] || !$data['red'] || !$data['redtime'] || !$data['redtext']) {
                $this->error('微店配置任何配置不能留空');
            }
            $rs = M('ShopConfig')->where(array('id'=>1))->save($data);
            if ($rs!==false) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        } else {
            $info =  M('ServiceConfig')->where(array('id'=>1))->find();
            $builder = new AdminConfigBuilder();
            $builder->title('微店配置');
            $builder->meta_title = '微店配置';
            $builder->keyId()->keyText('pname', '佣金名称')->keyInteger('scale', '佣金为商品进的百分比','%')->keyTextArea('postup', '佣金比例配置','每级一行，如销售者为第一行，发放30%的佣金即填写30，建议不超过2级')->keyInteger('red', '受邀注册红包金额','元')->keyInteger('redtime', '红包过期时间','天')->keyTextArea('redtext', '红包推广词')->keySingleImage('wxad_img', '分享推广图片','制作充满诱惑的海报更能吸引用户注册')->keyTextArea('cashtype', '允许提现的方式','支付宝、财付通、银行卡等，每种方式一行');
            $builder->data($info);
            $builder->buttonSubmit(U('Shop/Config'), '保存');
            $builder->display();
        }
    }
	
	
	public function JsonTree(){
       	$Category = D('ShopCategory');
		if(array_key_exists( 'id',$_REQUEST)) {
			$pId=$_REQUEST['id'];
		}
		$pId || $pId=0;
		
		$map['pid'] = $pId;
		$map['status'] = 1;
		
   		$list  = $Category->where($map)->order('`sort` desc, id desc')->select(); 	
		foreach ($list as $key => $rs) {
	   		$child = $Category->where("pid=$rs[id]")->order('create_time desc')->select();
			$isParent = ($child) ? ('true') : ('false'); 
			$json_tree.="{ id:'$rs[id]',name:'$rs[title]',pId:'$rs[pid]',status:'$rs[status]',isParent:$isParent},";
		}
		echo "[$json_tree]";
    
    }
	
	public function Select(){
		$this->assign('tree', $tree);
        $this->display();
    }
	

    /**商品分类
     * @author 郑薏玮<715713881@qq.com>
     */
    public function category()
    {
        //显示页面
        $builder = new AdminTreeListBuilder();
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';

       //分类树，如果开启异步加载，那么就不需要实例化 郑薏玮 <715713881@qq.com>
 		if(!C('CATEGORY_JSON')){
			$Category = D('ShopCategory');
   			$list = $Category->order('`sort` desc, id desc')->where("status >= 0")->select(); 	
      	 	$tree = $Category->Tree($list);
 			$this->assign('tree', $tree);
		}

        $builder->title('商城分类管理')
            ->buttonNew(U('Shop/add'))->buttonOpen()->buttonClose()
            ->data($tree)
            ->display();
    }

	
    /**分类添加
     * @param int $id
     * @param int $pid
     * @author 郑薏玮<715713881@qq.com>
     */
    public function add($id = 0, $pid = 0)
    {
        if (IS_POST) {
            if ($id != 0) {
				
                $category = $this->shop_categoryModel->create();
                if ($this->shop_categoryModel->save($category)!==false) {
                    $this->success('编辑成功', U('Shop/category'));
                } else {
                    $this->error('编辑失败');
                }
            } else {
				$category = $this->shop_categoryModel->create();
				if($this->shop_categoryModel->add($category)){
            		$this->success('新增成功', U('Shop/category'));
				}else{
					$this->error('新增失败');
            	}
			}
        } else {
            $builder = new AdminConfigBuilder();
        
            $category = $this->shop_categoryModel->where("id = ".$id)->find();
			
            if(!$category[pid]){
             	$title = "顶级分类";
			}else{
				$title = $this->shop_categoryModel->where('id = '.$category[pid])->getField('title');
			}
			$category[status] || $category[status] = 1;
			
            $builder->title('新增分类')->keyId()->keyText('title', '分类名称')->keySelectJson('pid', '父分类', $title)
                ->keyStatus()->keyCreateTime()
                ->data($category)
                ->buttonSubmit(U('Shop/add'))->buttonBack()->display();
        }

    }


    /**
     * 设置商品分类状态：删除=-1，禁用=0，启用=1
     * @param $ids
     * @param $status
     * @author 郑薏玮<715713881@qq.com>
     */
    public function setStatus($ids, $status)
    { 
     	  M('ShopCategory')->where(array('id' => array('in', $ids)))->save(array('status' => $status));
          $this->success('设置成功', $_SERVER['HTTP_REFERER']);
    }

    /**
     * 设置商品状态：删除=-1，禁用=0，启用=1
     * @param $ids
     * @param $status
     * @author 郑薏玮<715713881@qq.com>
     */
    public function setGoodsStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('shop', $ids, $status);
    }
	



    /**设置是否为新品
     * @param int $id
     * @author 郑薏玮<715713881@qq.com>
     */
    public function setNew($id = 0)
    {
        if ($id == 0) {
            $this->error('请选择商品');
        }
        $is_new = intval(!$this->shopModel->where(array('id' => $id))->getField('is_new'));
        $rs = $this->shopModel->where(array('id' => $id))->setField(array('is_new' => $is_new));
        if ($rs) {
            $this->success('设置成功！');
        } else {
            $this->error('设置失败！');
        }
    }

 
	
    /**商品列表
     * @param int $page
     * @param int $r
     * @author 郑薏玮<715713881@qq.com>
     */
    public function goodsList($page = 1, $r = 20)
    {
		
        $map['status'] = array('egt', 0);
        $goodsList = $this->shopModel->where($map)->order('update_time desc')->page($page, $r)->select();
        $totalCount = $this->shopModel->where($map)->count();
        $builder = new AdminListBuilder();
        $builder->title('商品列表');
        $builder->meta_title = '商品列表';
		$category = $this->shop_categoryModel->where(array('status'=>1))->select();
        foreach ($category as $k => $v) {
            $c[$v[id]] = $v[title];
        }
        $builder->buttonNew(U('Shop/goodsEdit'))->setStatusUrl(U('goodsStatus'))->buttonSetStatus(U('goodsStatus'),'1','启用')->buttonSetStatus(U('goodsStatus'),'0','禁用')->buttonSetStatus(U('goodsStatus'),'-1','删除');
        $builder->keyId()->keyImage('img', '图片')->keyText('title', '商品名称')->keyMap('cid', '商品分类',$c)->keyText('price', '销售价格')->keyText('goods_num', '商品余量')->keyText('sell_num', '已售出量')->keyStatus('status', '出售状态')->keyDoActionEdit('Shop/goodsEdit?id=###');
        $builder->data($goodsList);
        $builder->pagination($totalCount, $r);
        $builder->display();
    }
	
	
	
	//设置出售状态
	public function goodsStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
		
        M('ShopGoods')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        } 
    }
	

    /**
     * 商品编辑
     * @author 郑薏玮<715713881@qq.com>
     */
	 
    public function goodsEdit($id='')
    {
        $isEdit = $id ? 1 : 0;
        if (IS_POST) {
			
			$data = I('post.');
			
            if (!$data[title]) {
                $this->error('请输入商品名称');
            }
			
			if (!$data[cid]) {
                $this->error('请不要在一级分类中发布商品');
            }
			
			if (!$data[price]) {
                $this->error('请正确输入商品价格');
            }
			
			if (!$data[goods_num]) {
                $this->error('请正确输入商品剩余量');
            }
			
			if (!$data[sell_num]) {
                $this->error('请正确输入商品已售量');
            }
			
			if (!$data[img]) {
                $this->error('请上传商品图片');
            }
      
			 
            if ($isEdit) {
				$data['update_time'] = time();
                $rs = $this->shopModel->save($data);
            } else {
                $map['status'] = array('egt', 0);
                $data['create_time'] = time();
                $rs = $this->shopModel->add($data);
            }
            if ($rs) {
                $this->success($isEdit ? '编辑成功' : '添加成功', U('Shop/goodsList'));
            } else {
                $this->error($isEdit ? '编辑失败' : '添加失败');
            }
        } else {
			$info =  $this->shopModel->where('id=' . $id)->find();
			$info['status'] = 1;
			$title = cid2name($info[cid]);
			
			$builder = new AdminConfigBuilder();
			$builder->title('编辑商品');
      	    $builder->meta_title = '编辑商品';
         
            $builder->keyId()->keyText('title','商品名称')->keyInteger('price','销售价','元')->keyInteger('pmoney','独立佣金','填写这里则优先根据这里填写的佣金按比例发放')->keySelectJson('cid', '父分类', $title)->keyMultiImage('img', '商品图片')->keyTextArea('introduct', '商品广告语')->keyEditor('content', '商品详情')->keyTextArea('color_spec', '颜色规格','每行一个规格')->keyTextArea('size_spec', '尺寸规格','每行一个规格')->keyInteger('realprice', '成本价','元')->keyInteger('goods_num', '库存','件')->keyInteger('sell_num', '出售数量','件')->keyInteger('weight', '重量','千克')->keyCheckBox('good_type', '定义商品属性','',array('index'=>'首页展示','new'=>'新品上市','hot'=>'热销商品','low'=>'优惠商品','will'=>'超值商品','over'=>'清仓甩卖'))
                ->keyStatus()
                ->data($info)
                ->buttonSubmit(U('Shop/goodsedit'))->buttonBack();
             $builder->display();
   
        }
    }
	


    /**待发货交易列表
     * @param int $page
     * @param int $r
     * @author 郑薏玮<715713881@qq.com>
     */
    public function unorder($page = 1, $r = 10)
    {
	
        $list = M('ShopOrder')->where(array('status'=>array('not in','3,-4')))->order('create_time desc')->page($page, $r)->select();
        $totalCount = M('ShopOrder')->where(array('status'=>array('not in','3,-4')))->count();
        foreach ($list as &$val) {
            $address = D('shop_address')->find($val['address_id']);
            $val['name'] = op_t($address['name']);
            $val['address'] = op_t($address['address']);
            $val['phone'] = op_t($address['phone']);
        }
        unset($val);
        //显示页面
        $builder = new AdminListBuilder();
        $builder->title('待处理订单');
        $builder->meta_title = '待处理订单';
        $builder->setStatusUrl(U('order_manage'))->buttonSetStatus(U('oredrStatus'),'-4','删除')->buttonSetStatus(U('oredrStatus'),'1','已付款')->buttonSetStatus(U('oredrStatus'),'2','已发货')->buttonSetStatus(U('oredrStatus'),'3','交易成功')
            ->keyId()->keyUid('uid','购买者')->keyText('price','应付款')->keyText('realprice','实付款')->keyText('goods_info', '商品信息')->keyText('name', '收货人姓名')->keyText('address', '收货地址')->keyText('phone', '手机号码')->keyCreateTime('create_time', '购买时间')->shopStatus('status','订单状态')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
	
	
	
    /**已完成交易列表
     * @param int $page
     * @param int $r
     * @author 郑薏玮<715713881@qq.com>
     */
    public function orderSuccess($page = 1, $r = 20)
    {
        //读取列表
		$hot_num = D('ShopConfig')->find();
        $list = M('ShopOrder')->where(array('status'=>array('eq','3')))->order('create_time desc')->page($page, $r)->select();
        $totalCount = M('ShopOrder')->where(array('status'=>array('eq','3')))->count();

        foreach ($list as &$val) {
            $val['goods_name'] =M('ShopGoods')->where('id=' . $val['goods_id'])->getField('goods_name');
            $address = M('shop_address')->find($val['address_id']);
            $val['name'] = $address['name'];
            $val['address'] = $address['address'];
            $val['zipcode'] = $address['zipcode'];
            $val['phone'] = $address['phone'];
			unset($val);
        }
        
        //显示页面
        $builder = new AdminListBuilder();
        $builder->title('完成的交易');
        $builder->meta_title = '完成的交易'; 
        $builder->setStatusUrl(U('order_manage'))->buttonSetStatus(U('oredrStatus'),'-4','删除')->buttonSetStatus(U('oredrStatus'),'0','未付款')->buttonSetStatus(U('oredrStatus'),'1','已付款')->buttonSetStatus(U('oredrStatus'),'2','已发货')
            ->keyId()->keyUid('uid','购买者')->keyText('price','应付款')->keyText('realprice','实付款')->keyText('goods_info', '商品信息')->keyText('name', '收货人姓名')->keyText('address', '收货地址')->keyText('phone', '手机号码')->keyCreateTime('create_time', '购买时间')->shopStatus('status','订单状态')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
	
	
	
	
	
	 /**佣金发放
     * @param int $page
     * @param int $r
     * @author 郑薏玮<715713881@qq.com>
     */
    public function reward($nickname='',$page = 1, $r = 20)
    {
		
		 if(is_numeric($nickname)) {
            $map['uid|id'] = array(intval($nickname), array('like', '%' . $nickname . '%'), '_multi' => true);
         }else if($nickname){
			 $map['uid'] = M('Member')->where(array('nickname'=>$nickname))->getField('uid');
		 }
		 $map['status'] = array('eq','3');

        	//读取列表
        	$list = M('ShopOrder')->where($map)->order('create_time desc')->page($page, $r)->select();
        	$totalCount = M('ShopOrder')->where(array('status'=>array('eq','3')))->count();
		
			foreach($list as $k =>$v){
				$list[$k]['user_tree'] = get_Usertree($v['uid'],$this->Config['postup_num']);
				$list[$k]['reward'] = $v['realprice']*$this->Config['scale_b'];
				$arr[$k] = explode('|',$v[goods_info]);
				$list[$k]['goods_info'] = $arr[$k];
				foreach($arr[$k] as $kt =>$vt){
					$list[$k]['goods_info'][$kt]=explode(",",$vt);
				}
			}
        	$this->assign('list', $list);
        	$this->display();
		
    }
	
	
	
	  public function post_reward($ids='',$is_reward='')
      {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
		

			foreach($id as $k => $orderId){
				if(M('ShopOrder')->where(array('id'=>$orderId))->getField('is_reward') == $is_reward){
					 $this->error('该订单已经发放/或撤销过佣金了，请不要重复操作');
				}
				$uid = get_OrderInfo($orderId,'uid');//订单购买者
				$order_reward = get_OrderInfo($orderId,'realprice')*$this->Config['scale_b'];//计算出订单佣金
				$tree = get_Usertree($uid,$this->Config['postup_num']);//得出会员树
				//开始发放
				foreach($tree as $key => $value){
					$reward = $order_reward*$value[scale]/100;
					if($is_reward>0){
						//如果是撤销填写负数金额即可
						setReward($value['uid'],$reward,$orderId);
						D('Common/Message')->sendMessageWithoutCheckSelf($value['uid'], '您有一笔“'.$reward.'”元的佣金已经到账，发放佣金的订单号：'.$orderId, '佣金到账通知',U('Ucenter/index/cash'),1);
					}else{
						setReward($value['uid'],-$reward,$orderId);
						D('Common/Message')->sendMessageWithoutCheckSelf($value['uid'], '您有一笔“'.$reward.'”元的佣金被扣除，原因：管理员手动扣除', '佣金扣除通知',U('Ucenter/index/cash'),1);
					}
				}
			}
		
		 M('ShopOrder')->where(array('id' => array('in', $id)))->setField('is_reward', $is_reward);
		
        if ($is_reward == 0) {
            $this->success('撤销佣金完成');
        } else {
            $this->success('发放完成');
        } 
    }
        	
		
	
	
	//伪删除订单操作
	public function oredrStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
		if($status == 3){
			M('ShopOrder')->where(array('id' => array('in', $id)))->setField('over_time', time());
		}
        M('ShopOrder')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -4) {
            $this->success('删除成功');
        } else{
			$this->success('设置成功');	
		}
    }
	
	
	//订单状态管理器
	public function order_manage($ids='')
    {
		$info = M('ShopOrder')->where(array('id'=>$ids))->find();
		
		if (IS_POST) {
			
			$data = I('post.');
			if($data['status'] == 3){
				$data[over_time] = time();
			}
			
			if (M('ShopOrder')->save($data)) {
				if($data[message]){
			  		D('Common/Message')->sendMessageWithoutCheckSelf($info[uid], $data[message], '订单状态变更通知',U('Ucenter/order/index'),1);
				}
         	   $this->success('设置成功！');
    	    } else {
        	    $this->error('你没有变更交易状态，或未知错误');
      	    }
		}
		
		$this->assign('info', $info);
		$this->assign('ids', $ids);
		$this->display();
		  
    }
	

	
    /**佣金记录
     * @author 郑薏玮<715713881@qq.com>
     */
    public function moneyLog($page = 1, $r = 20)
    {
        //读取列表
        $model = M('ShopRecord');
		$map['status'] = array('eq',1);
        $list = $model->where($map)->page($page, $r)->order('create_time desc')->select();
        $totalCount = $model->count();
        //显示页面
        $builder = new AdminListBuilder();

        $builder->title('佣金发放记录');
        $builder->meta_title = '佣金发放记录';

        $builder->buttonSetStatus(U('moneyLogStatus'),'-1','删除')->keyUid('uid','获得佣金者')->keyText('money', '金额')->keyCreateTime('create_time','发放时间')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
	
	
	
	//删除佣金记录
	public function moneyLogStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
		
        M('ShopRecord')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        } 
    }
	
	
	 /**佣金记录
     * @author 郑薏玮<715713881@qq.com>
     */
    public function cashLog($page = 1, $r = 20)
    {
        //读取列表
        $model = M('ShopCash');
        $list = $model->where(array('status'=>array('egt',0)))->page($page, $r)->order('create_time desc')->select(); 
        $totalCount = $model->count();
        //显示页面
        $builder = new AdminListBuilder();

        $builder->title('佣金发放记录');
        $builder->meta_title = '佣金发放记录';

        $builder->buttonSetStatus(U('CashStatus'),'1','已结款')->buttonSetStatus(U('CashStatus'),'0','未结算')->buttonSetStatus(U('CashStatus'),'-1','删除')->keyId()->keyUid('uid','提现者')->keyText('money', '提现金额')->keyHtml('cashtype', '提现方式')->keyText('accounts', '账户')->keyText('name', '账户名')->keyText('bank', '开户行')->key('status','状态', 'status',array(0=>'未结款',1=>'<span class="text-success">已结款</span>'))->keyCreateTime()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
	
	
	
		//删除佣金记录
	public function CashStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
		
        M('ShopCash')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        } 
    }
	
	
	
	public function setCashStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
		
		if(is_array($ids) ){
			//多选
        	if ($status == 1) { 
          	  foreach ($ids as $id) {
             	   $log = D('ShopCash')->where('id=' . $id)->find();
               	   $message = "您申请的一笔{$log[money]}元的佣金提现状态变更";
              	    D('Message')->sendMessageWithoutCheckSelf($log['uid'], $message, '提现通知', U('Ucenter/shop/cash'), is_login(), 1); 
           	    }
       	  	}
		}else{
			//单选
			$log = D('ShopCash')->where('id=' . $ids)->find();
            $message = "您申请的一笔{$log[money]}元的佣金状态变更";
            D('Message')->sendMessageWithoutCheckSelf($log['uid'], $message, '提现通知', U('Ucenter/shop/cash'), is_login(), 1);
			
		}
        $builder->doSetStatus('ShopCash', $ids, $status);
    }

	

}

