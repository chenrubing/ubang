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
</head>
<body>
<header><a class="head_back" onClick="history.go(-1);">返回</a> 我的订单 </header>





<div class="content">
  <div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b" style="position:inherit">
        <li  class="current"> 我发给商家的  <div class="ui-badge" id="business">2</div></li>
        <li>联络我的商家 <div class="ui-badge-muted" id="list_Se_co">2</div></li>
        <li>我发布的 <div class="ui-badge-muted" id="myposts">2</div></li>
    </ul>
    
    <ul class="ui-tab-content" style="width: 300%; margin:0px">
    
        <li>
            <ul class="ui-list ui-list-text ui-border-b " id="form_business" >                
            </ul>
        </li>
        
        <li>
            <ul class="ui-list ui-list-text ui-border-b" id="list_Se">
                
            </ul>
        </li>
        
        <li>
            <ul class="ui-list ui-list-text ui-border-b" id="mypost">           
            </ul>
        </li>
    </ul>
</div></p>
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

</div> 


<div class="empty"></div>
<footer>
  <li><a href="<?php echo U('App/demo/index');?>"><span class="f1"  href="#"></span><i>首页</i></a></li>
  <li><a href="#"><span class="f2"  href="#"></span><i>发需求</i></a></li>
  <li><a href="<?php echo U('App/demo/getmap');?>"><span class="f3"  href="#"></span><i>找服务</i></a></li>
  <li><a href="#"><span class="f4"  href="#"></span><i>我的</i></a></li>
  </ul>
</footer>

<script type="text/javascript">
 
function all(){

var el;
    $.ajax({
      type: 'POST',
      url: '<?php echo U('my_plan');?>',
      data :"",
      dataType:'json',
      beforeSend:function(){
        el=$.loading({  content:'努力加载中...', })
      },
      success:function(json){
          var ht="";
          var mt="";
          var list="";
          if(json.data.form_business){
            $.each(json.data.form_business,function(index,vo){
                      ht+= '<li class="ui-border-t"><div class="ui-list-info"><h4 class="ui-nowrap"><a href="index.php?s=/app/demo/my_postservice/id/'+vo["service_id"]+'">'+vo["title"]+'</a></h4></div><div class="ui-badge-muted">'+vo['status']+'</div></li>';            
            });   
          }       
          if(json.data.mypost){
            $.each(json.data.mypost,function(index,vo){
               mt+=  '<li class="ui-border-t"><div class="ui-list-info"><h4 class="ui-nowrap"><a href="index.php?s=/app/demo/my_postservice/id/'+vo["id"]+'">'+vo['title']+'</a></h4></div><div class="ui-badge-muted">'+vo["status"]+'</div></li>';
            });
          }
          if(json.data.list_se)
          {
            $.each(json.data.list_se,function(index,vo){
               list+='<li class="ui-border-t"><div class="ui-list-info"><h4 class="ui-nowrap"><a href="index.php?s=/app/demo/my_postservice/id/'+vo["service_id"]+'">'+vo['title']+'</a></h4></div><div class="ui-badge">'+vo['status']+'</div></li>';
            });
          }
          $("#form_business").html(ht);
          $("#list_se").html(list);
          $("#mypost").html(mt); 
          $("#business").html(json.data.business_count);   
          $("#myposts").html(json.data.mypost_count);   
          $("#list_Se_co").html(json.data.list_count);       
          el.loading("hide");
      },
      error:function(){
        //alert("分类获取失败");
      }
    });

}
all();
</script>
</body>