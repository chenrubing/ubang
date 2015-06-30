<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html lang="">
<head>
<meta charset="UTF-8">
<title>FrozenUI - 专注于移动web的UI框架</title>
<meta name="Description" content="专注于移动web的UI框架，基于手机QQ规范" />
<meta name="Keywords" content="frozen，frozenui，frozenjs，forzen ui，forzen js" />
<meta name="author" content="fayching" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link type="image/x-icon" href="http://frozenui.github.io/static/favicon.ico" rel="icon">
<!--统计代码 -->
<!-- <link rel="stylesheet" type="text/css" href="http://i.gtimg.cn/vipstyle/frozenui/1.2.1/css/global.css?_bid=306"/> -->
<link rel="stylesheet" type="text/css" href="/Application/App/Static/frozenui/1.2.1/css/global.css">
<link rel="stylesheet" type="text/css" href="/Application/App/Static/css/app.css">
<!-- <script src="http://i.gtimg.cn/vipstyle/frozenjs/lib/zepto.min.js?_bid=304"></script>
        <script src="http://i.gtimg.cn/vipstyle/frozenjs/1.0.1/frozen.js?_bid=304"></script> -->
<script src="/Application/App/Static/frozenjs/lib/zepto.min.js"></script>
<script src="/Application/App/Static/frozenjs/1.0.1/frozen.js"></script>
</head>
<body>
<header> 家居服务 <a class="head_search" href="<?php echo U('Ucenter/member/login');?>">登陆</a></header>
<div class="content">
  <div class="ui-slider" >
    <ul class="ui-slider-content" >
      <li  ><img src="/Application/App/Static/img/s1.jpg"></li>
      <li><img src="/Application/App/Static/img/s1.jpg"></li>
      <li><img src="/Application/App/Static/img/s1.jpg"></li>
    </ul>
  </div>
</div>

<div class="ico_list">
  <ul>
    <li><a href="<?php echo U('getmap',array('id'=>1));?>"><img src="/Application/App/Static/img/ico/icon.png"><span>家政服务</span></a></li>
    <li><a href="<?php echo U('getmap',array('id'=>2));?>"><img src="/Application/App/Static/img/ico/icon.png"><span>家装服务</span></a></li>
    <li><a href="<?php echo U('getmap',array('id'=>3));?>"><img src="/Application/App/Static/img/ico/icon.png"><span>吃喝玩乐</span></a></li>
    <li><a href="#"><img src="/Application/App/Static/img/ico/icon.png"><span>狂装网</span></a></li>
    <!--<li><a href="<?php echo U('findbusiness');?>"><img src="/Application/App/Static/img/ico/icon.png"><span>找服务</span></a></li>-->
   
   
    </ul>
    <ul>
   
    <!--<li><a href="<?php echo U('findbusiness');?>"><img src="/Application/App/Static/img/ico/icon.png"><span>找服务</span></a></li>-->
    <li><a href="<?php echo U('Category');?>"><img src="/Application/App/Static/img/ico/post.png"><span>发需求</span></a></li>
    <li><a href="<?php echo U('my_plan');?>"><img src="/Application/App/Static/img/ico/icon.png"><span>服务记录</span></a></li>
    <li class="nobr"><a href="<?php echo U('Message');?>"><img src="/Application/App/Static/img/ico/icon.png"><span>消息中心</span></a></li>
    <li class="nobr"><a href="<?php echo U('evaluatelist');?>"><img src="/Application/App/Static/img/ico/icon.png"><span>评价</span></a></li>
    </ul>
    
</div>


<footer>
<li><a href="#"><span class="f1"  href="#"></span><i>首页</i></a></li>
<li><a href="#"><span class="f2"  href="#"></span><i>发需求</i></a></li>
<li><a href="#"><span class="f3"  href="#"></span><i>找服务</i></a></li>
<li><a href="#"><span class="f4"  href="#"></span><i>我的</i></a></li>
</ul>

</footer>
<script>
window.addEventListener('load', function(){
    var slider = new fz.Scroll('.ui-slider', {
        role: 'slider',
        indicator: true,
        autoplay: false,
        interval: 13000
    });

    slider.on('beforeScrollStart', function(fromIndex, toIndex) {
        console.log(fromIndex,toIndex)
    });

    slider.on('scrollEnd', function() {
        console.log('end')
    });
})
</script>
</body>