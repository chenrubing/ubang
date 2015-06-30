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

<script src="/Application/App/Static/js/ajax.js"></script>

</head>

<body>

<header><a class="head_back" onClick="history.go(-1);">返回</a> 商家信息 </header>

<div class="content">

  <div class="ui-slider" >

    <ul class="ui-slider-content" >
      <li  ><img src="/Application/App/Static/img/c1.jpg"></li>
      <li><img src="/Application/App/Static/img/c2.jpg"></li>
      <li><img src="/Application/App/Static/img/c3.jpg"></li>
    </ul>
  </div>
</div>
<div class="ui-form-item ui-border-b">
        <label for="#">
            公司状态：
        </label>
        <input name="title" type="text" placeholder="暂无内容" value="空闲" disabled>
    </div>


<div class="ui-form-item ui-border-b">
        <label for="#">
            公司名称：
        </label>
        <input name="title" type="text" placeholder="暂无内容" value="<?php echo ($info["nickname"]); ?>" disabled>
    </div>

<div class="ui-form-item ui-form-item-textarea ui-border-b" style=" min-height:45px; height:auto">
        <label for="#">
            公司概述：
        </label>
        <div style=" padding:12px 0px 10px 100px; line-height:22px;"><?php echo ($info["content"]); ?></div>
    </div>

<div class="ui-form-item div-he ui-border-b">
        <label for="#">
            擅长工作：
        </label>
        <div style=" padding:12px 0px 10px 100px; line-height:22px;"><?php echo ($info["category_ids"]); ?></div>
</div>

<div class="ui-form-item ui-border-b">
        <label for="#">
            公司资质：
        </label>
        <input  name="price"  type="text" placeholder="暂无内容" value="3年(已通过狂装网资质验证)" disabled>
    </div>
    

<div class="ui-form-item ui-border-b">
        <label for="#">
            公司地点：
        </label>
        <input  name="address"  type="text" placeholder="暂无内容" value="<?php echo ($info["address"]); ?>" disabled>
    </div>
  <input type="hidden" name="cid" value="<?php echo ($_GET[id]); ?>" >

  
  <div class="ui-btn-group-tiled ui-btn-wrap">
  <!--  <button type="button" class="ui-btn-lg ui-btn-primary ajax-get confirm" url="<?php echo U('to_plan',array('bid'=>$info[uid]));?>" confirm-info="确定要联系该公司吗？">
        联系该商家
    </button>-->
    <button  style="margin-right:0px;" class="ui-btn-lg" onClick="history.go(-1);">
        返回
    </button>

</div>







<div class="empty"></div>

<footer>

  <li><a href="<?php echo U('App/demo/index');?>"><span class="f1"  href="#"></span><i>首页</i></a></li>

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