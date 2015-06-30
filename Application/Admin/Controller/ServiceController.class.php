<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminSortBuilder;
use Admin\Builder\AdminTreeListBuilder;
/**
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 */
class ServiceController extends AdminController {
    public function _initialize(){
        parent::_initialize();
    }

   /**服务配置
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
            $rs = M('ServiceConfig')->where(array('id'=>1))->save($data);
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
            $builder->keyId()->keyText('pname', '佣金名称')->keyInteger('scale', '佣金为商品进的百分比','%')->keyTextArea('postup', '佣金比例配置','每级一行，如销售者为第一行，发放30%的佣金即填写30，建议不超过2级')->keyInteger('red', '受邀注册红包金额','元')->keyInteger('redtime', '红包过期时间','天')->keyTextArea('redtext', '红包推广词')->keySingleImage('wxad_img', '分享推广图片','制作充满诱惑的海报更能吸引用户注册')->keyTextArea('cashtype', '允许提现的方式','支付宝、财付通、银行卡等，每种方式一行')->keyText("switchs","定金","预约金额")->keyText("cut","切换","用户切换时间（小时）");
            $builder->data($info);
            $builder->buttonSubmit(U('service/Config'), '保存');
            $builder->display();
        }
    }
   
    //服务需求列表
    public function index($page = 1, $r = 20){
          $map['status'] = array('EGT','0');
          $ServiceList = M("Service")->where($map)->page($page, $r)->select();
          $totalCount = M("Service")->where($map)->count();
          $builder = new AdminListBuilder();
          $builder->title("订单列表");
          $builder->setStatusUrl(U('serviceStatus'))->buttonNew(U('sevice_edit'))->buttonSetStatus(U('serviceStatus'),'1','启用')->buttonSetStatus(U('serviceStatus'),'0','禁用')->buttonSetStatus(U('serviceStatus'),'-1','删除');
          $builder->keyId()->keyUid('uid', "发布用户")->keyText('price', '期望出价')->keyCreateTime();
          $builder->keyStatus()->keyDoAction('Service/sevice_edit?id=###', '查看需求详情');
          $builder->data($ServiceList);
          $builder->pagination($totalCount, $r);
          $builder->display();
    }
  
  //设置需求
  public function serviceStatus($status)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);  
        M('Service')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        } 
    }
  
   /**
     * 用户需求编辑/创建
     * @author 郑薏玮<715713881@qq.com>
     */
    public function sevice_edit($id='')
    {
        $isEdit = $id ? 1 : 0;
        if (IS_POST) {      
		      $data = I('post.');		      
          if (!$data[title]) {
              $this->error('请输入需求名称');
          }	      
		      if (!$data[cid]) {
              $this->error('请选择需求种类');
          }
	        if (!$data[content]) {
              $this->error('去求详情不能为空');
          }    
		      if (!$data[img]) {
              $this->error('请上传需求图片');
          }          
          if ($isEdit) {
     			$data['update_time'] = time();
              $rs = M('Service')->save($data);
          } else {
              $map['status'] = array('egt', 0);
              $data['create_time'] = time();
              $rs = M('Service')->add($data);
          }
          if ($rs) {
              $this->success($isEdit ? '编辑成功' : '添加成功', U('service/index'));
          } else {
              $this->error($isEdit ? '编辑失败' : '添加失败');
          }
        } else {
          $dat['status'] = array('EGT','0');
          $arr=array("0"=>'请选择');
          $arrs=array("0"=>'请选择');
          //用户
          $listke=M('Member')->where($dat)->where(array("show_role"=>1,))->select(); 
          foreach ($listke as $key => $value) {
              $arr[$value['uid']]=$value['nickname'];
          }

          $info =  M('Service')->where(array('id'=>$id))->find();
          $info['status'] = 1;
          $title = cid2name($info[cid]);
          $builder = new AdminConfigBuilder();
          $builder->title('编辑/新增订单');
          $builder->meta_title = '编辑/新增需求';   
          $builder->keyId()->keySelect('uid',"需求发布用户",'',$arr)->keyMultiImage('img','需求图片')->keySelectJson('cid', '需求分类', $title)->keyEditor('content', '需求详情')->keyInteger('price', '期望出价','元')->keyTime('time', '期望解决时间')
              ->keyStatus()
              ->data($info)
              ->buttonSubmit(U(''))->buttonBack();
           $builder->display();
   
        }
    }
  //获取栏目组成json
  public function JsonTree(){
        $Category = D('ServiceCategory');
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
  
  //分类选择页面
  public function Select(){
    $this->assign('tree', $tree);
        $this->display();
    }
  
  
  /**需求分类
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
	      $Category = D('ServiceCategory');
	        $list = $Category->order('sort asc,id asc')->where(array("status"=>array("egt",0)))->select();
	       // echo "<pre>";   
	        //var_dump($list);exit;
	        $tree = $Category->Tree($list);
	        //dump($tree);
	    }
        $builder->title('商城分类管理')
            ->buttonNew(U('Service/category_add'))->buttonOpen()->buttonClose()
            ->data($tree)
            ->display();
    }


    
  
  
    
  //设置需求分类状态
  public function category_status($status)
  {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);
        M('ServiceCategory')->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        } 
    }
  
  
  
  /**分类添加
     * @param int $id
     * @param int $pid
     * @author 郑薏玮<715713881@qq.com>
     */
    public function category_add($id = 0, $pid = 0)
    {
        $isEdit = $id ? 1 : 0;
        if (IS_POST) {
          	$data = I('post.');      
         	if ($isEdit) {
          		$data['update_time'] = time();
                if($pid==0){
                	$data['relation']=",".$id.",";
                }
                else{
                	$fin=M('ServiceCategory')->where(array("id"=>$pid))->find();
                	$data['relation']=$fin['relation'].$id.",";
                }
                $rs = M('ServiceCategory')->save($data);
        	}else {
                $data['create_time'] = time();
                $rs = M('ServiceCategory')->add($data);
                $li=array();
                if($data['pid']==0){
                	$li=array("relation"=>",".$rs.",");
                }
                else{
                	$fin=M('ServiceCategory')->where(array("id"=>$data['pid']))->find();
                	$li['relation']=$fin['relation'].$rs.",";
                }
                M('ServiceCategory')->where(array("id"=>$rs))->save($li);
         	}
         	if ($rs){
                $this->success($isEdit ? '编辑成功' : '添加成功', U('Service/category',array("time"=>time())));
         	}else{
                $this->error($isEdit ? '编辑失败' : '添加失败');
          }        
        }
        else 
        {
          $builder = new AdminConfigBuilder();
          $info = M('ServiceCategory')->where(array('id'=>$id))->find();
          $info['c_title'] = M('ServiceCategory')->where(array('id'=>$pid))->getField('title');
          $info['status'] || $info['status'] = 1;
          if(!$info['pid']){
              $info['c_title'] = "顶级分类";
              $info['pid'] = $pid;
          }
          $builder->title('新增分类')->keyId()->keyText('title', '分类名称')
          ->keySelectJson('pid', '父分类',$info['c_title'])->keyMultiImage('imgs','小图标')->keyTextArea('pass', '允许提现的方式','支付宝、财付通、银行卡等，每种方式一行')->keyInteger('sort', '栏目排序')->keyInteger('handsel', '定金')
          ->keyStatus()->keyCreateTime()->data($info)
          ->buttonSubmit(U('Service/category_add'))
          ->buttonBack()->display();
        }
    }
  

    
    //服务案例
    public function caselist($page = 1, $r = 20){
          $map['status'] = array('EGT','0');
          $service_case=M("ServiceCase");
          $CompleteList = $service_case->where($map)->page($page, $r)->select();
          $totalCount = $service_case->where($map)->count();
          $builder = new AdminListBuilder();
          $builder->title("服务案例");
          $builder->buttonNew(U('case_edit'))
          ->buttonDelete(U('localStatus', array('status' => '-1','tables'=>'ServiceCase')))
          ->buttonEnable(U('localStatus',array('status' => '1','tables'=>'ServiceCase')))
          ->buttonDisable(U('localStatus',array('status' => '0','tables'=>'ServiceCase')));
          $builder->keyId()->keyUid('uid', "用户名")->keyUid('bid', '商家名')->keyImage('img', '案例图片')->keyCreateTime();
          $builder->keyStatus()->keyDoAction('Service/case_edit?id=###', '编辑');
          $builder->data($CompleteList);
          $builder->pagination($totalCount, $r);
          $builder->display();
    }

    //服务案例编辑
    public function case_edit()
    {
        $service_case=M("ServiceCase");
          if(IS_POST){  
              $data = I('post.');
              if(!$data['id']){
                 $res = $service_case->add($data);
              }else{
                 $res = $service_case->save($data);
              }
              if($res!== false){
                 $this->success($data['id'] ? '更新成功！' : '新增成功！',U('Service/caselist'));
              } else {
                 $this->error($data['id'] ? '更新失败！' : '新增失败！');  
              }
          }else{
              
            $map['id'] = I('get.id');
            $info = $service_case->where($map)->find();
           // dump($info);
            $info['status'] = 1;
            $builder = new AdminConfigBuilder();
            $builder->title("服务案例编辑");
            $dat['status'] = array('EGT','0');

            $arr=array("0"=>'请选择');
            $arrs=array("0"=>'请选择');
            //用户
            $listke=M('Member')->where($dat)->where(array("show_role"=>1,))->select(); 
            foreach ($listke as $key => $value) {
                $arr[$value['uid']]=$value['nickname'];
            }

            //商家
            $listshang=M('Member')->where($dat)->where(array("show_role"=>3))->select(); 
            foreach ($listshang as $key => $value) {
                $arrs[$value['uid']]=$value['nickname'];
            }

            $builder->keyId()->keySelect('uid',"用户",'',$arr)->keySelect('bid',"商家",'',$arrs)
            ->keyMultiImage('img', '案例图片')->keyCreateTime()->keyStatus();
            $builder->data($info);
            $builder->buttonSubmit('', '保存');
            $builder->buttonBack();
            $builder->display();
        }
    }
    



    //定金记录和商家进度  -2 取消该订单 -1 删除 0 未交定金 1已交定金 2 开始服务 3服务完成
    public function  planlist($page = 1, $r = 20)
    {
          $map['status'] = array('neq','-1');
          $CompleteList = M("ServicePlan")->where($map)->page($page, $r)->select();
          $totalCount =M("ServicePlan")->where($map)->count();


          $builder = new AdminListBuilder();
          $builder->title("订单管理");
          $builder->setStatusUrl(U('plan_manage'))->buttonNew(U('plan_edit'))
          ->buttonDelete(U('localStatus', array('status' => '-1','tables'=>'ServicePlan')));

          $builder->keyId()->keyUid('uid', "用户名")->keyUid('bid', '商家名')->keyText('price', '预约金');
          $builder->keyCreateTime()->planStatus('status','状态',array("-3"=>"商家放弃了该订单","-2"=>"用户放弃订单","0"=>"商家未做回馈","1"=>"商家已回馈","2"=>"双方修改交流中","3"=>"服务中","4"=>"订单完成","-1"=>"删除订单"));
          $builder->data($CompleteList);
          $builder->pagination($totalCount, $r);
          $builder->display();

    }
	
	
	//订单状态管理器
	public function plan_manage($ids='')
  {

		  $info = M("ServicePlan")->where(array('id'=>$ids))->find();
	  	if (IS_POST) {
  			$data = I('post.');
  			if (M('ServicePlan')->save($data)) {
           	$this->success('设置成功！');
      	    } else {
          	$this->error('你没有变更交易状态，或未知错误');
        }
		  }
		$this->assign('statusArr', array("-3"=>"商家放弃了该订单","-2"=>"用户放弃订单","0"=>"商家未做回馈","1"=>"商家已回馈","2"=>"双方修改交流中","3"=>"服务中","4"=>"订单完成","-1"=>"删除订单"));
		$this->assign('info', $info);
		$this->assign('ids', $ids);
		$this->display();
		  
  }
	
    
    //评论
    public function localcomment($page = 1, $r = 20){
          $map['status'] = array('EGT','0');
          $local_comment=M("LocalComment");
          $arr=array("1"=>"一星","2"=>"二星","3"=>"三星","4"=>"四星","5"=>"五星");
          //状态
          $sta=array("-2"=>"审核失败","-1"=>"删除","0"=>"已评价","1"=>"已审核");
          $CompleteList = $local_comment->where($map)->page($page, $r)->select();
          $totalCount = $local_comment->where($map)->count();
          foreach($CompleteList as $key=>$val){
            $CompleteList[$key]['star']=$arr[$val['star']];
            $CompleteList[$key]['status']=$sta[$val['status']];
          }
          $builder = new AdminListBuilder();
          $builder->title("评论");
          $builder->buttonNew(U('local_edit'))
          ->buttonDelete(U('localStatus', array('status' => '-1','tables'=>'LocalComment')))
          ->buttonEnable(U('localStatus',array('status' => '1','tables'=>'LocalComment')))
          ->buttonDisable(U('localStatus',array('status' => '0','tables'=>'LocalComment')));

          $builder->keyId()->keyUid('uid', "用户名")->keyUid('bid', '商家名')->keyImage('img', '案例图片')->keyText("star","星级")->keyCreateTime();
          $builder->keyText("status","状态")->keyDoAction('Service/local_edit?id=###', '编辑');
          $builder->data($CompleteList);
          $builder->pagination($totalCount, $r);
          $builder->display();
    }
    

    //评论编辑
    public function local_edit()
    {
          if(IS_POST){  
              $data = I('post.');
              if(!$data['id']){
                 $res = M("LocalComment")->add($data);
              }else{
                 $res = M("LocalComment")->save($data);
              }
              if($res!== false){
                 $this->success($data['id'] ? '更新成功！' : '新增成功！',U('Service/localcomment'));
              } else {
                 $this->error($data['id'] ? '更新失败！' : '新增失败！');  
              }
          }else{
            $arrds=array("1"=>"一星","2"=>"二星","3"=>"三星","4"=>"四星","5"=>"五星");
            $map['id'] = I('get.id');
            $info = M("LocalComment")->where($map)->find();
            
            $info['status'] = 1;
            $builder = new AdminConfigBuilder();
            $builder->title("评论编辑");
            $dat['status'] = array('EGT','0');

            //状态
            $sta=array("-2"=>"审核失败","-1"=>"删除","0"=>"已评价","1"=>"已审核");
            $arr=array("0"=>'请选择');
            $arrs=array("0"=>'请选择');
            //用户
            $listke=M('Member')->where($dat)->where(array("show_role"=>1))->select(); 
            foreach ($listke as $key => $value) {
                $arr[$value['uid']]=$value['nickname'];
            }
            //商家
            $listshang=M('Member')->where($dat)->where(array("show_role"=>3))->select(); 
            foreach ($listshang as $key => $value) {
                $arrs[$value['uid']]=$value['nickname'];
            }
            $builder->keyId()->keySelect('uid',"用户",'',$arr)->keySelect('bid',"商家",'',$arrs)->keySelect('star',"星级",'',$arrds)->keyEditor("content", "评论内容", "",'',array('width'=>'90%','height'=>'200px')) 
            ->keyMultiImage('img', '案例图片')->keyCreateTime()->keySelect('status',"状态",'',$sta);
            $builder->data($info);
            $builder->buttonSubmit('', '保存');
            $builder->buttonBack();
            $builder->display();
        }
    }
    
    //删除，启用，禁用
    public function localStatus($status,$tables)
    {
        $id = array_unique((array)I('ids', 0));
        if ($id[0] == 0) {
            $this->error('请选择要操作的数据!');
        }
        $id = is_array($id) ? $id : explode(',', $id);        
        M($tables)->where(array('id' => array('in', $id)))->setField('status', $status);
        if ($status == -1) {
            $this->success('删除成功');
        } else if ($status == 0) {
            $this->success('禁用成功');
        } else {
            $this->success('启用成功');
        } 
    }



    //轮播图 
    public function carousellist($page = 1, $r = 20)
    {

          $map['status'] = array('EGT','0');
          $CompleteList = M("Carousel")->where($map)->page($page, $r)->select();
          $totalCount = M("Carousel")->where($map)->count();
          $builder = new AdminListBuilder();
          $builder->title("轮播图列表");
          $builder->buttonNew(U('carousel_edit'))
          ->buttonDelete(U('localStatus', array('status' => '-1','tables'=>'Carousel')))
          ->buttonEnable(U('localStatus',array('status' => '1','tables'=>'Carousel')))
          ->buttonDisable(U('localStatus',array('status' => '0','tables'=>'Carousel')));

          $builder->keyId()->keyImage('img', '图片')->keyCreateTime()->keyStatus()->keyDoAction('Service/carousel_edit?id=###', '编辑');
          $builder->data($CompleteList);
          $builder->pagination($totalCount, $r);
          $builder->display();
    }

    //评论编辑
    public function carousel_edit()
    {
          if(IS_POST){  
              $data = I('post.');
              if(!$data['id']){
                 $res = M("Carousel")->add($data);
              }else{
                 $res = M("Carousel")->save($data);
              }
              if($res!== false){
                 $this->success($data['id'] ? '更新成功！' : '新增成功！',U('Service/carousellist'));
              } else {
                 $this->error($data['id'] ? '更新失败！' : '新增失败！');  
              }
          }else{
            $map['id'] = I('get.id');
            $info = M("Carousel")->where($map)->find();
            $info['status'] = 1;
            $builder = new AdminConfigBuilder();
            $builder->title("评论编辑");
            $dat['status'] = array('EGT','0');
            $builder->keyId()->keyMultiImage('img', '图片')->keyText("url", "链接")->keyStatus()->keyCreateTime();
            $builder->data($info);
            $builder->buttonSubmit('', '保存');
            $builder->buttonBack();
            $builder->display();
        }
    }


 

}
