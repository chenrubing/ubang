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
<link rel="stylesheet" type="text/css" href="/Application/App/Static/frozenui/1.2.1/css/global.css">
<link rel="stylesheet" type="text/css" href="/Application/App/Static/css/app.css">
<script src="/Application/App/Static/js/jquery-2.0.3.min.js"></script>
<script src="/Application/App/Static/js/ajax.js"></script>
</head>
<body>
<header><a class="head_back" onClick="history.go(-1);">返回</a> 我发布的需求 </header>

<div class="ui-form-item ui-form-item-textarea ui-border-b">
        <label for="#">
            需求详情：
        </label>
        <textarea  name="content"  type="text" placeholder="具体情况描述，如房子平米数，具体需要哪些服务" style="resize: none;"><?php echo ($info["content"]); ?></textarea>
    </div>
<div class="ui-form-item ui-border-b">
        <label for="#">
            图片说明：
        </label>
         <input  name="img"  type="text" placeholder="上传图片" value="<?php echo ($info["img"]); ?>">
</div>
<div class="ui-form-item ui-border-b">
        <label for="#">
            期望出价：
        </label>
        <input  name="price"  type="text" placeholder="您的心理价位" value="<?php echo ($info["price"]); ?>">
    </div>

<div class="ui-form-item ui-border-b">
        <label for="#">
            期望时间：
        </label>
        <input  name="time"  type="text" placeholder="期望何时开始施工/设计" value="<?php echo ($info["create_time"]); ?>">
    </div>
    <div class="ui-form-item ui-border-b">
        <label for="#">
            联系手机：
        </label>
        <input  name="mobile"  type="text" placeholder="请留下可用联系方式" value="<?php echo ($info["mobile"]); ?>">
    </div>
<div class="ui-form-item ui-border-b">
        <label for="#">
            具体地点：
        </label>
        <input  name="address"  type="text" placeholder="以便工作人员上门"  value="<?php echo ($info["address"]); ?>">
    </div>
<div class="ui-form-item ui-border-b">
        <label for="#">
            订单状态：
        </label>
        <input  name="address"  type="text" placeholder="以便工作人员上门"  value="<?php echo ($info["sta"]); ?>">
    </div>
 
  <div class="ui-btn-group-tiled ui-btn-wrap"> 
   <!-- <?php if($info["status"] == 3 ): ?>--><!--<?php endif; ?>  -->
        <A href="<?php if($info["local_id"] == null): echo U(evaluate,array('id'=>$info[id])); else: ?>javascript:void(0)<?php endif; ?>" class="ui-btn-lg"> <?php if($info["local_id"] != null ): ?>以评价<?php else: ?>评价<?php endif; ?></A>
    
</div>


<div class="empty"></div>
<footer>
  <li><a href="<?php echo U('App/demo/index');?>"><span class="f1"  href="#"></span><i>首页</i></a></li>
  <li><a href="#"><span class="f2"  href="#"></span><i>发需求</i></a></li>
  <li><a href="#"><span class="f3"  href="#"></span><i>找服务</i></a></li>
  <li><a href="#"><span class="f4"  href="#"></span><i>我的</i></a></li>
  </ul>
</footer>
</body>