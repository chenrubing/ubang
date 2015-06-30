<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta name="Keywords" content="frozen，frozenui，frozenjs，forzen ui，forzen js" />
<meta name="author" content="fayching" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>无标题文档</title>
<link type="image/x-icon" href="http://frozenui.github.io/static/favicon.ico" rel="icon">
<link rel="stylesheet" type="text/css" href="/Application/App/Static/css/app.css">
<script src="/Application/App/Static/js/jquery-2.0.3.min.js"></script>
<script src="/Application/App/Static/js/ajax.js"></script>
<script type="text/javascript">
$(function(){
	$(".ditus").hide();
	$("input[name='time']").hide();
	pingmu=document.body.offsetWidth;
	var wi=pingmu-($(".yue li").width()*$(".yue .yi").length)-6;
	var fen=(wi/($(".yue .yi").length*2)).toFixed(2);
	$("#shaohua").hide();
	$(".yue li").hide().filter(".yi").css("margin","0px "+fen+"px").show();	
	$(".yue li:first").click(function(e) {
		var a=$(".yue li").not(".yi").length;
		li=pingmu-($(".yue li").width()*a)-15;
		se=(li/(a*2)).toFixed(2);
        $(".yue .yi").hide().last().nextAll(this).css("margin","0px "+se+"px").show(400);
    });
    $(".yue .yi:last").click(function(e){
    	if($("#shaohua").css("display")=="none")
    	{
    		$("#shaohua").show(300);
    	}
    	else{
    		$("#shaohua").hide(300);
    	}
    });
	$(".yue li:not(.yi)").click(function(e) {
		$("input[name='tip']").val($(this).text());
		$(".yue li:not(.yi)").hide();
		$(".yue .yi").css("margin","0px "+fen+"px").show(300).first().text($(this).text());
    });	
    $(".li-yuyue").click(function(e){
    	if($("input[name='time']").css("display")=="none")
    	{
    		$("input[name='time']").show(300);
    	}
    	else{
    		$("input[name='time']").hide(300);
    	}
    });

	$(".form-tij").click(function(e) {
		var jquer=$("form").serialize();
		$.ajax({
	        cache: false,
	        type: 'POST',
	        url: '<?php echo U('yuyue');?>',
	        data :jquer,
	        dataType:'json',
	        success:function(json){
	        	alert(json['info']);
	        },
	        error:function(){
	            alert("网点数据加载失败");
	        }        
  		});
    });
	$(".shi").click(function(e) {
	    $(".quan").hide();
		$(".ditus").show(400);
	});
	$(".divs-zuo div:not(.zumu)").click(function(e) {
		var cshi = $(this).text();
    	$(".shi").text(cshi).add().attr("alt",$(this).attr("alt"));
    	$("input[name='town']").val(cshi);
		$(".ditus").hide();
		$(".quan").show();
	});

})
</script>
</style>
</head>
<body>

<header><a class="head_back" onClick="history.go(-1);">返回</a> 预约家政服务 <a class="head_search" href="<?php echo U('Ucenter/member/login');?>"><i class="f4">　</i></a></header>
<div class="quan">
<form method="post" class="demo">
	<div class="dizhi">
		<div class="shi"><a href="#" alt="" ><?php echo ($ipinfo["city"]); ?></a></div>
	    <div class="tianxie">
	    	<input name="address" placeholder="地址在哪" type="text">
	        <input name="mobile" placeholder="联系方式" type="text">
	        
	    </div>
	</div>
	<div  class="fuwu">
	<input name="time" placeholder="什么时候服务(时间 年-月-日)" type="text">
	<input name="tip" type="hidden" value="0" />
	<input type="hidden" name="town"  value="<?php echo ($ipinfo["city"]); ?>">
	<input type="hidden" name="cid" value="<?php echo ($_GET[id]); ?>" >
	<textarea  name="content"  type="text" placeholder="具体需求描述，如房子平米数，具体需要哪些服务" style="resize: none;"></textarea><input name="price" placeholder="期望出价" type="text">
	<input name="img" placeholder="图片说明" type="text">
	</div>	
	<div id="shaohua" class="fuwu"><input name="shaohua" placeholder=" 捎话" type="text"></div>
<div class="fei">
	<ul class="yue">
    	<li class="yi">小费</li>
    	<li class="yi li-yuyue">预约</li>
        <li class="yi">捎话</li>
        <li>0</li>
        <li>50</li>
        <li>100</li>
        <li>200</li>
    </ul>
</div>
<button  type="button" class="form-tij"  >确认发送</button>
</form>
</div>

<div class="ditus">

	<div class="divs-zuo">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["list"] != null): ?><div id="<?php echo ($vo["da"]); ?>" class="zumu"><?php echo ($vo["da"]); ?></div><?php endif; ?>
			<?php if(is_array($vo["list"])): $i = 0; $__LIST__ = $vo["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$al): $mod = ($i % 2 );++$i;?><div alt="<?php echo ($al["id"]); ?>"><?php echo ($al["name"]); ?></div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<div class="divs-you">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div><a href="#<?php echo ($vo["da"]); ?>"><?php echo ($vo["da"]); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</div>
</body>
</html>