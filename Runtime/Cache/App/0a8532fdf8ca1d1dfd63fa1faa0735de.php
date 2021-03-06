<?php if (!defined('THINK_PATH')) exit();?><DOCTYPE HTML>
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
<body><header><a class="head_back" onClick="history.go(-1);">返回</a> 评论 </header>
<form    method="post" class="demo">


<div class="ui-form-item ui-border-b">
        <label for="#">评论星级：
        </label>
        <input name="star" type="text" value="" placeholder="明确的标题让商家更容易发现">
    </div>

<div class="ui-form-item ui-form-item-textarea ui-border-b">
      <label for="#">
          评论内容：
      </label>
      <textarea  name="content"  type="text" placeholder="具体更具实际情况发表你的评论" style="resize: none;"></textarea>
  </div>

<div class="ui-form-item ui-border-b">
        <label for="#">
            图片展示：
        </label>
         <input  name="img"  type="text" placeholder="上传图片" value="">
         <input name="id" type="hidden" value="<?php echo I('get.id');?>" />
</div>
  <div class="ui-btn-group-tiled ui-btn-wrap">
    <button type="button" class="ui-btn-lg ui-btn-primary ajax-post confirm" url="<?php echo U('evaluate');?>" confirm-info="确定将该评论提交吗？">
        确定发送请求
    </button>
    <button class="ui-btn-lg" onClick="history.go(-1);">
        放弃
    </button>
</div>
</div>
</form>






<div class="empty"></div>

<footer>

  <li><a href="<?php echo U('App/demo/index');?>"><span class="f1"  href="#"></span><i>首页</i></a></li>

  <li><a href="#"><span class="f2"  href="#"></span><i>发需求</i></a></li>

  <li><a href="#"><span class="f3"  href="#"></span><i>找服务</i></a></li>

  <li><a href="#"><span class="f4"  href="#"></span><i>我的</i></a></li>

  </ul>

</footer>

</body>