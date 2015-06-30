<?php
namespace App\Controller;
use Think\Controller;
use User\Api\UserApi;
require_once APP_PATH . 'User/Conf/config.php';
/*数据返回方式 data【array】
 * status 状态
 * data 数据
 * msg 信息
 ************************/
class DemoController extends Controller {

	protected function _initialize()
    {
        /*读取站点配置*/
    	//15分钟刷新一次
    	$arr=C("TMPL_PARSE_STRING");        	
    	$file=".".$arr['__UI__']."/dingshi.txt";
    	$times=file_get_contents($file);
    	if($times < time()-15*60)
    	{
    		//echo "a";
    		$this->dinshi();
    		file_put_contents($file,time());
    	}               
    }


    public function lunbo()
    {
    	if(IS_AJAX)
    	{
    		$list = M("Carousel")->where(array("status"=>array("EGT",0)))->select();
    		foreach ($list as $key => $va) {
    			$list[$key]['img'] ="http://kzw.ijiaque.com".get_cover($va['img'],"path");
    		}
    		$this->ajaxReturn(array("status"=>1,"data"=>$list));
    		
    	}    	    	
    	
    }

    public function zhifu()
	{
		$files=".".C("TMPL_PARSE_STRING.__PING__")."/init.php";
		require_once $files;
		//$input_data = json_decode(file_get_contents('php://input'), true);
		//if (empty($input_data['channel']) || empty($input_data['amount'])) {
		//    exit();
		//}
		/*
		$channel = strtolower($input_data['channel']);
		$amount = $input_data['amount'];
		$orderNo = substr(md5(time()), 0, 12);*/
		$channel = strtolower("alipay_wap");
		$amount = 1000;
		$orderNo = substr(md5(time()), 0, 12);
		\Pingpp\Pingpp::setApiKey('sk_test_q540q9GanD0O4eLWDSqDa5SC');
		try {
		    $red = \Pingpp\RedEnvelope::create(
		        array(
		            'subject'     => 'ajksdhkj',   //商品标题
		            'body'        => '1344asdasd',      //商品的描述信息，该参数最长为 128 个 Unicode 字符，yeepay_wap 对于该参数长度限制为 100 个 Unicode 字符。
		            'amount'      => $amount,   //交易金额
		            'order_no'    => $orderNo,  //商户系统自己生成的订单号
		            'currency'    => 'cny',     //三位 ISO 货币代码，人民币为 cny。
		            'extra'       => array(
		                'nick_name' => 'Nick Name',
		                'send_name' => 'Send Name'
		            ),
		            'recipient'   => '119.164.136.123',  //接收者 id， wx_pub 为用户的 open id。
		            'channel'     => $channel, //交易渠道
		            'app'         => array('id' => 'app_GeTunDi9WzzTyfDG'),   //Ping++ 分配给商户的应用 ID
		            'description' => 'Your Description'              //备注信息，最多 255 个字节。
		        )
		    );
		    echo $red;
		} catch (\Pingpp\Error\Base $e) {
		    header('Status: ' . $e->getHttpStatus());
		    echo($e->getHttpBody());
		}


		/*
		//引用 SDK 库文件
		require_once('/path/to/Pingpp.php');
		//获取客户端的参数，这里不能使用 $_POST 接收，所以我们提供了如下的参考方法接收
		$input_data = json_decode(file_get_contents("php://input"), true);
		//TODO 客户在这里自行处理接收过来的交易所需的数据
		//设置API KEY，如果是测试模式，这里填入 Test Key；如果是真实模式， 这里填入 Live Key。
		Pingpp::setApiKey("YOUR-KEY");
		//创建支付对象，发起交易
		$ch = Pingpp_Charge::create(    
		//array 里需要哪些参数请阅读 API Reference 文档
  		array(        
	  		"order_no"  => $orderNo,  //商户系统自己生成的订单号
	        "app"       => array("id" => "YOUR-APP-ID"),  
	        "amount"    => $amount,   
	        "channel"   => $channel,  
	        "currency"  => "cny",       
	        "client_ip" => $_SERVER["REMOTE_ADDR"],  //发起交易的客户端的 IP
	        "subject"   => "Your Subject",        
	        "body"      => "Your Body",        
	        "extra"     => null //仅客户端为 HTML5 时此参数不为空，具体请参考 API Reference 文档
		    )

		    'subject'     => 'ajksdhkj',   //商品标题
		            'body'        => 'Description',      //商品的描述信息，该参数最长为 128 个 Unicode 字符，yeepay_wap 对于该参数长度限制为 100 个 Unicode 字符。
		            'amount'      => $amount,   //交易金额
		            'order_no'    => $orderNo,  //商户系统自己生成的订单号
		            'currency'    => 'cny',     //三位 ISO 货币代码，人民币为 cny。
		            'extra'       => array(
		                'nick_name' => 'Nick Name',
		                'send_name' => 'Send Name'
		            ),
		            'recipient'   => '119.164.136.123',  //接收者 id， wx_pub 为用户的 open id。
		            'channel'     => $channel, //交易渠道
		            'app'         => array('id' => 'YOUR-APP-ID'),   //Ping++ 分配给商户的应用 ID
		            'description' => 'Your Description'              //备注信息，最多 255 个字节。
		);echo $ch;
	    */


	}

	public function index(){
		$this->display();
	}

	
	public function HeadUpload() {
        $config = C('PICTURE_UPLOAD');
        $upload = new \Think\Upload();
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    $upload->rootPath  =     './Uploads'; // 设置附件上传根目录
	    $upload->savePath  =     '/Picture/'; // 设置附件上传（子）目录
        
        // 上传文件 
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->ajaxReturn(array("status"=>0,"info"=>$upload->getError()));
        } else {// 上传成功
        	
            $path = $info['file']['savepath'].$info['file']['savename'];
            M('Member')->where(array('uid' => is_login()))->setField('img', $path);
            $this->ajaxReturn(array("status" => 1,"info"=>"上传成功"));
        }
    }

    public function memberfind()
    {
    	if(IS_AJAX)
    	{
    		$info = M("Member")->where(array("uid"=>is_login()))->find();
    		if($info)
    		{
    			$info['img'] = "http://kzw.ijiaque.com/Uploads".$info['img'];
    			$this->ajaxReturn(array("status"=>1,"info"=>$info));
    		}
    		else{
    			$this->ajaxReturn(array("status"=>0,"info"=>$info));
    		}
    		
    	}
    	
    }


	public function category($id=''){
		$list = M("ServiceCategory")->where(array('pid'=>$id,'status'=>1))->select();
		//检查下级是否有子分类,为跳转页面做准备
		foreach($list as $k => $v){
			if(M("ServiceCategory")->where(array('pid'=>$v['id'],'status'=>1))->find()){
				$list[$k]['is_son'] = 1;
			}else{
				$list[$k]['is_son'] = 0;	
			}
		}
		$this->assign("Category",$list);
		$this->display();


	}

	//判断是否有此类型的商家，判断该分类是否需要定金，是否有定金 
	public function cateif()
	{
		$id=I("post.id");
		$list=M("Member")->where(array("category_ids"=>array("like",'%,'.$id.',%'),"show_role"=>3,"status"=>1,"uid"=>array("gt",1)))->select();
		if(!$list)
		{
			$this->ajaxReturn(array('status'=>0,'info'=>'没有此类型商家存在!'));
		}
		else{
			$cate=M("ServiceCategory")->where(array('id'=>$id,'status'=>1))->field("handsel")->find();
			//print_r($cate);	
			if(empty($cate['handsel']))
			{
				$this->ajaxReturn(array('status'=>1));
			}
			else{
				//判断是否需要定金，账户余额是否充足
				//M("Member")->where(array("uid"=>is_login()))->field("score1")->find();
				$ya=M("Member")->where(array("uid"=>1))->field("score1")->find();
				if($ya['score1']>=$cate['handsel'])
				{
					$this->ajaxReturn(array('status'=>1));
				}
				else
				{
					$this->ajaxReturn(array('status'=>0,'info'=>'您的余额不足以支付定金,请充值!'));
				}
			}
		}
	}




	//查找最近的商家
	public function recently($category_ids=0,$zuida= array())
	{
		$id = is_login();
		$list = M("Member")->where(array("category_ids"=>array("like",'%,'.$category_ids.',%'),"show_role"=>3,"status"=>1,"uid"=>array("gt",1)))->field("uid,map")->select();
		$info = M("Member")->where(array("uid"=>$id))->find();
		$lr = explode(",", $info['map']);
		$arr=array();
        foreach ($list as $key => $va) {
        	$ar = explode(",",$va['map']);    
        	$list[$key]['juli'] = $this->GetDistance($lr[0],$lr[1], $ar[0],$ar[1], 1)/1000;
        }
        //按距离排序
        for($i=0;$i<count($list)-1;$i++)
        {
        	for($j=$i+1;$j<count($list);$j++){
	            if($list[$i]['juli']>=$list[$j]['juli']){ 

	                $temp=$list[$i];
	                $list[$i]=$list[$j];
	                $list[$j]=$temp;    
	            }
       		}  
        }
        return $list;
	}

	//计算坐标距离
	function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
	{
	   $EARTH_RADIUS = 6378.137;//地球半径 
	   $PI = 3.1415926;
       $radLat1 = $lat1 * $PI / 180.0;   //PI()圆周率
       $radLat2 = $lat2 * $PI / 180.0;
       $a = $radLat1 - $radLat2;
       $b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
       $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
       $s = $s * $EARTH_RADIUS;
       $s = round($s * 1000);
       if ($len_type --> 1)
       {
           $s /= 1000;
       }
	   return round($s, $decimal);
	}

	//将到时间的商家替换新商家，如果没有下续商家就删除订单
	public function dinshi()
	{
		$arr=array();
		$info = M('ServiceConfig')->where(array('id'=>1))->find();
		$plan = M("ServicePlan")->where(array("status"=>0))->select();
		foreach ($plan as $key => $val) {
			$plan[$key]['encrypt'] = unserialize($val['encrypt']);
			if($val['create_time']<time()-$info['cut']*3600)
			{   
				if($plan[$key]['encrypt'])
				{
					$lists=reset($plan[$key]['encrypt']);
					array_shift($plan[$key]['encrypt']);
					M("ServicePlan")->where(array("id"=>$val['id']))->save(array("encrypt"=>serialize($plan[$key]['encrypt']),"bid"=>$lists['uid'],"create_time"=>time()));
				}
				else
				{
					M("ServicePlan")->where(array("id"=>$val['id']))->save(array("status"=>-1,"create_time"=>time()));
				}
			}
		}
	}


		
  
	function GetIpLookup($ip = ''){
	    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
	    if(empty($res)){ return false; }  
	    $jsonMatches = array();  
	    preg_match('#\{.+?\}#', $res, $jsonMatches);  
	    if(!isset($jsonMatches[0])){ return false; }  
	    $json = json_decode($jsonMatches[0], true);  
	    if(isset($json['ret']) && $json['ret'] == 1){  
	        $json['ip'] = $ip;  
	        unset($json['ret']);  
	    }else{  
	        return false;  
	    }  
	    return $json;
	}  
  
  

	//预约
	public function yuyue(){

		$iipp = $_SERVER["REMOTE_ADDR"];
		$ipInfos = $this->GetIpLookup($iipp);
		$this->assign("ipinfo",$ipInfos);

		if(IS_AJAX)
		{
			$data=I('post.');
			$data['uid'] = is_login();
			$arr=$this->recently($data['cid']);			
			$data['bid'] = $arr[0]['uid'];
			unset($arr[0]);
			if($arr){
				$data['encrypt'] = serialize($arr);
			}
			$data['time']=strtotime($data['time']);
			$data['tip'] = $data['tip'];
			$data['create_time']=time();
			$data['status']=0;
			
			$res=M("ServicePlan")->add($data);
			if($res){
				$this->ajaxReturn(array('status'=>1,'info'=>'提交成功'));
			}
			else{
				$this->ajaxReturn(array('status'=>0,'info'=>'提交失败'));
			}
			
		}
		$arr=$this->shengshi();
		$this->assign("list",$arr);
		$this->display();
	}

	

	

	//找服务
	public function findbusiness(){
		$this->display();
	}


	public function shengshi()
	{
		$zimu=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","S","Y","Z");
		$region=M("Region");
		$list=$region->where("level=2")->select();
		$arr=array();
		foreach($zimu as $key=>$va)
		{
			$arr[$key]['da']=$va;
			foreach ($list as $k => $val) {
				if(strtoupper($val['spell'])==$va)
				{
					$arr[$key]['list'][]=$val;
				}
			}
		}
		return $arr;
	}





	// 给市加首字母
	public function jiandao()
	{
		$arr=$this->shengshi();
		$this->assign("list",$arr);
		$this->display();
	}
	
	//腾讯坐标转百度坐标
	function Convert_GCJ02_To_BD09($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
	}

	//百度坐标转腾讯
	public function  map_bd2tx($lat, $lon){  
          
        $x_pi=3.14159265358979324;  
        $x = $lon - 0.0065;
        $y = $lat - 0.006;  
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);  
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);  
        $tx_lon = $z * cos($theta);  
        $tx_lat = $z * sin($theta);
        return array("lat"=>$tx_lat,"lon"=>$tx_lon);
    }



	//找服务获取分类与会员
	public function GetJson($cid=''){
	 	if($cid!=0){
			$map1['uid'] = $cid;
			$map2['category_ids'] = array('like',array('%,'.$cid.',%'));
			$map2['show_role'] = 3;
			$map2['id']=array("gt",1);
		}
		$map2['show_role'] = 3;
		$map2['id']=array("gt",1);
		$top['category'] =  M('ServiceCategory')->where($map1)->select();
		$top['category'] || $top['category'] = 0;
		//$lis="117.130747,36.692105";
		$myLocation = explode(",", I("post.myLocation"));
		$list = $this->map_bd2tx($myLocation[0],$myLocation[1]);
 		//查询树
		$top['members'] = M('Member')->where($map2)->select();
		foreach ($top['members'] as $key => $val) {
			$lis = M('ServiceCategory')->where(array('id'=>array('in',$val['category_ids'])))->select();
			foreach ($lis as  $value) {
				$arr[] = $value['title'];
			}
			$top['members'][$key]['uid']=$val['uid'];
			$top['members'][$key]['category_ids']=implode(",", $arr);
			$p=explode(",",$top['members'][$key]['map']);
			$top['members'][$key]['julis'] = $this->GetDistance($list['lon'],$list['lat'], $p[0],$p[1], 1)/1000;
			$location1 = $this->Convert_GCJ02_To_BD09($p[1],$p[0]);   //转换腾讯坐标到百度坐标
			$top['members'][$key]['lon'] = $location1['lat'] ;
			$top['members'][$key]['lat'] = $location1['lng'];
			$p=array();
			$arr=array();
		}
		$top['members'] || $top['members'] = 0;
	    echo json_encode($top);exit;

	}



	public function getmaplist()
	{
		$id=I("post.uid");
		//$id=7;
		//获取分类
		$list = M("ServiceCategory")->where(array('pid'=>$id,'status'=>1))->select();
		$list||$list=0;
		$members=M("Member")->where(array("status"=>1,"category_ids"=>array("like",'%,'.$id.',%'),"uid"=>array("gt",1),"show_role"=>3))->select();
		//print_r(M("Member")->getlastsql());
		$members || $members=0;
		$top['category']=$list;
		$top['count']=count($list);
		$top['members']=$members;
		echo json_encode($top);exit;
	}

	//地图
	public function GetMap(){
		$id = I("post.id")?I("post.id"):I("get.id");	
		$list=M("ServiceCategory")->where(array("status"=>1,"pid"=>$id))->order('sort asc,id asc')->select();
		foreach ($list as $key => $va) {
    			$list[$key]['imgs'] ="http://kzw.ijiaque.com".get_cover($va['imgs'],"path");
    		}
		if(IS_AJAX){
			if($list){
				$this->ajaxReturn(array('status'=>1,'data'=>$list,'msg'=>'商家查找成功!'));
			}else{
				$this->ajaxReturn(array('status'=>0,'data'=>null,'msg'=>'没有此类型商家存在!'));
			}
		}
		$this->assign("list",$list);
		$this->display('getmap');		

	}

	

	//商家主页
	public function see_business($uid=''){
   		$userinfo = query_user(array('uid','nickname','address','reg_time','category_ids'), $uid);
   		$userinfo['category_ids']=substr($userinfo['category_ids'],0,-1);
        $userinfo['category_ids']=substr($userinfo['category_ids'],1);
        $list = M("ServiceCategory")->where(array("id"=>array("in",$userinfo['category_ids'])))->field("title")->select();
        foreach ($list as $key => $va) {
        	$arr[]=$va['title'];
        }
   		$userinfo['category_ids']=implode(",", $arr);
		$this->assign("info",$userinfo);
		$this->display();
	}

	
	

	//创建订单
	public function to_plan($bid=''){
		$arrfo =  M('ServiceConfig')->where(array('id'=>1))->find();
		clean_query_user_cache(is_login(), array('score'));
   		$userinfo = query_user(array('score'), is_login());
		if($userinfo<$arrfo['switchs']){
			$this->ajaxReturn(array('status'=>0,'info'=>'抱歉，您的押金不足'));
		}else{
			$this->ajaxReturn(array('status'=>1,'url'=>U('to_business',array('bid'=>$bid)),'info'=>'填写完成您的需求即可联系该商家'));
		}
	}


	//我的订单
	public function my_plan(){

		if(IS_AJAX)
		{
			//我发布的请求
			$mypost = M("Service")->where(array('uid'=>is_login(),"status"=>1,"mem_status"=>0))->order("create_time desc")->select();
			$text = array("-3"=>"商家取消订单","-2"=>"用户取消订单","0"=>"暂无回馈","1"=>"商家已回馈","2"=>"交流中","3"=>"服务中","4"=>"订单完成","-1"=>"删除订单");
			foreach($mypost as $k => $v){
				$mypost[$k]['status'] = $text[$v['status']];
			}

			//我给商家发布的请求
			$form_business = M()->table("ocenter_service_plan pl,ocenter_service se")
			->where("pl.service_id=se.id and se.mem_status=1 and se.status=1")
			->where(array('pl.uid'=>is_login()))->order("pl.create_time desc")
			->field("pl.*,se.title")->select();

			foreach($form_business as $k => $v){
				$form_business[$k]['status'] = $text[$v['status']];

			}

			//联系我的商家
			$list_se = M()->table("ocenter_service_plan pl,ocenter_service se")
			->where("pl.service_id=se.id and se.mem_status=0 and se.status=1")
			->where(array('pl.uid'=>is_login()))->order("pl.create_time desc")
			->field("pl.*,se.title")->select();

			foreach($list_se as $k => $v){
				$list_se[$k]['status'] = $text[$v['status']];
			}

			
			$arr['mypost']=$mypost;
			$arr['mypost_count']=count($mypost);
			$arr['business_count']=count($form_business);
			$arr['form_business']=$form_business;
			$arr['list_count']=count($list_se);
			$arr['list_se']=$list_se;
			$this->ajaxReturn(array('status'=>1,'info'=>'提交成功',"data"=>$arr));
		}
		$this->assign("mypost",$mypost);
		$this->assign("form_business",$form_business);
		$this->display();

	}


	//我的发布的需求查看页面
	public function my_c2b($id=''){
		$info = M("ServiceTobusiness")->where(array('uid'=>is_login(),"status"=>1,'id'=>$id))->find();
		$this->assign("info",$info);
		$this->display();
	}

	

	//我发布给商家的需求查看页面
	public function my_postservice($id=''){

		$text = array("-3"=>"商家取消订单","-2"=>"用户取消订单","0"=>"暂无回馈","1"=>"商家已回馈","2"=>"交流中","3"=>"服务中","4"=>"订单完成","-1"=>"删除订单");
		$list = M("ServicePlan")->where("status!=-1")->where(array('uid'=>is_login(),"id"=>$id))->order("create_time desc")->find();
			$list['create_time'] = date("Y-m-d H:i",$list['create_time']);
			$list['sta'] = $text[$list['status']];
		$this->assign("info",$list);
		$this->display();
	}


	//消息管理
	public function Message(){
		if(IS_AJAX)
		{
			$text = array("-3"=>"商家取消订单","-2"=>"用户取消订单","0"=>"暂无回馈","1"=>"商家已回馈","2"=>"交流中","3"=>"服务中","4"=>"订单完成","-1"=>"删除订单");
			//已完成订单

			$list = M("ServicePlan")->where("status!=-1")->where(array('uid'=>is_login()))->order("create_time desc")->select();
			foreach ($list as $key => $va) {
				$list[$key]['content'] = msubstr($va['content'],0,8);
				$list[$key]['create_time'] = date("Y-m-d H:i",$va['create_time']);
				$list[$key]['status'] = $text[$va['status']];
			}

			$data['list']=$list;
			$data['list_count']=count($list);
			$this->ajaxReturn(array('data'=>$data));
		}
		$this->display();
	}

	//评价
	public function evaluate($id="")
	{
		
		if(IS_AJAX)
		{
			$data = I("post.");
			$plan=M("ServicePlan")->where(array("id"=>$data['id']))->find();
			if(!$plan['local_id'])
			{
				$arr['star'] = $data['star'];
				$arr['content'] = $data['content'];
				$arr['img'] = $data['img'];
				$arr['uid'] = is_login();
				$arr['create_time'] = time();
				$arr['status'] = 0;
				$arr['bid'] = $plan['bid'];
				$li = M("LocalComment")->add($arr);
				if($li)
				{
					$list=M("ServicePlan")->where(array("id"=>$data['id']))->save(array("local_id"=>$li));
					if($list){
						$this->ajaxReturn(array('status'=>1,'info'=>'评论成功'));
					}
					else{
						$this->ajaxReturn(array('status'=>0,'info'=>'评论失败'));
					}
				}
				else{
					$this->ajaxReturn(array('status'=>0,'info'=>'评论失败'));
				}
			}
			else
			{
				$this->ajaxReturn(array('status'=>0,'info'=>'以评论订单，不能再评论'));
			}		
		}
		$this->display();
	}


	public function evaluatelist()
	{
		


		if(IS_AJAX)
		{
			//来自于客户的
			$list = M()->table("ocenter_local_comment co,ocenter_service_plan pl")->where("co.id=pl.local_id")->where(array("co.uid"=>is_login()))->field("co.*,pl.mobile")->select();
			foreach ($list as $key => $va) {
				$list[$key]['create_time'] = date('Y-m-d',$va['create_time']);
				$list[$key]['mobile'] = substr($va['mobile'] , 0 , 3);
			}
			//来自经销商的
			$arr = M()->table("ocenter_dealer_comment co,ocenter_service_plan pl")->where("co.id=pl.dealer_id")->where(array("co.bid"=>is_login()))->field("co.*,pl.mobile")->select();
			foreach ($arr as $key => $va) {
				$arr[$key]['create_time'] = date('Y-m-d',$va['create_time']);
				$arr[$key]['mobile'] = substr($va['mobile'] , 0 , 3);
			}


			$data['list'] = $list;
			$data['count_list'] = count($list);

			$data['arr'] = $arr;
			$data['arr_count'] = count($arr);
			$this->ajaxReturn(array("status"=>0,"data"=>$data)); 
		}

		$this->display();
		
	}




	//登陆
	public function login()		
    {

		if(IS_AJAX){
			$data = I('post.');
			$uid = UCenterMember()->login($data[username], $data[password], 1); //通过账号密码取到uid
			if($uid > 0){
				D('Member')->login($uid, false, 1); //无密码登陆
				$_data = array('status'=>'1','data'=>array('userid'=>$uid,'username'=>$data['username'],'msg'=>'登陆成功！')); 
			}else{
				 switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; 
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; 
                }
                $_data=array(
					'status'=>'0',					
					'msg'=>'登陆失败！'
					);
			}
			$this->ajaxReturn($_data);
		} 
	}

	

	public function get_MemberInfo(){
		if(IS_AJAX){
			$MemberData =M('Member')->where(array('uid'=>is_login()))->find(); 
			$MemberData[face] = get_cover($MemberData[face],'path');
			if($MemberData){
				$this->ajaxReturn(array('status'=>1,'data'=>$MemberData));
			}else{
				$this->ajaxReturn(array('status'=>0));
			}

		}

	}


}