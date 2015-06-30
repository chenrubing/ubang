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
<link rel="stylesheet" type="text/css" href="/Application/App/Static/frozenui/1.2.1/css/global.css">
<link rel="stylesheet" type="text/css" href="/Application/App/Static/css/app.css">
<script src="/Application/App/Static/frozenjs/lib/zepto.min.js"></script>
<script src="/Application/App/Static/frozenjs/1.0.1/frozen.js"></script>
<script src="/Application/App/Static/js/ajax.js"></script>
<style type="text/css">
  .pingjia{height:24px; line-height:25px; padding:0px 14px; font-size: 13px;}
</style>
</head>
<body>
<header><a class="head_back" onClick="history.go(-1);">返回</a> 消息中心 </header>


<div class="content">
  <div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b" style="position:inherit">
       <li> 全部订单 <div class="ui-badge-muted" id="listcount"></div></li>
    </ul>

    
    <ul class="ui-tab-content" style="width: 300%; margin:0px" >

        <li>
           <ul class="ui-list ui-list-function ui-list-link ui-border-tb" id="list">
               
            </ul>
        </li>
    
        
       
    </ul>
</div>
<script>
var tab = new fz.Scroll('.ui-tab', {
    role: 'tab',
    interval: 3000
    //autoplay: true
});
tab.currentPage = 0;
$(tab.nav.children[0]).removeClass('current');
$(tab.nav.children[tab.currentPage]).addClass('current');
tab.scrollTo(-tab.itemWidth*tab.currentPage,0);
tab.on('beforeScrollStart', function(fromIndex, toIndex) {
    console.log(fromIndex, toIndex)
});

tab.on('scrollEnd', function() {
    console.log('end')
});  
</script>
<script type="text/javascript">
 
function all(){

var el;
    $.ajax({
      type: 'POST',
      url: '<?php echo U('Message');?>',
      data :"",
      dataType:'json',
      beforeSend:function(){
        el=$.loading({  content:'努力加载中...', })
      },
      success:function(json){
          var list="";
          if(json.data.list){
            $.each(json.data.list,function(index,vo){
                
                list+='<li class="ui-border-t div-neigao"><div class="ui-list-info"><h4 class="ui-nowrap"><a href="index.php?s=/app/demo/my_postservice/id/'+vo["id"]+'">'+vo['address']+'</a></h4><h4 class="ui-nowrap"><a href="index.php?s=/app/demo/my_postservice/id/'+vo["id"]+'">'+vo['mobile']+'</a></h4><h4 class="ui-nowrap">'+vo['create_time']+'</h4></div><div class="ui-badge-muted">'+vo['status']+'</div></li>';
            });  
          }
          $("#list").html(list);
          $("#listcount").text(json.data.list_count);
         
          
          el.loading("hide");
      },
      error:function(){
        //alert("获取失败");
      }
    });

}
all();
</script>
</div> 







<div class="empty"></div>
<footer>
  <li><a href="<?php echo U('App/demo/index');?>"><span class="f1"  href="#"></span><i>首页</i></a></li>
  <li><a href="#"><span class="f2"  href="#"></span><i>发需求</i></a></li>
  <li><a href="<?php echo U('App/demo/getmap');?>"><span class="f3"  href="#"></span><i>找服务</i></a></li>
  <li><a href="#"><span class="f4"  href="#"></span><i>我的</i></a></li>
  </ul>
</footer>
</body>