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
<style>
.ui-list-text>li{  padding: 12px 12px 12px 0;}
.content{background-color: #FFF;}
.div-hei{ height:35px; line-height:35px; border-bottom: 1px solid #CCC;}
.div-hei span{ margin-left:10px;}
.span-xins{ float: right; margin-right:10px;}
.div-pinlun li p{ display: block;  margin-left: 10px; font-size: 15px;}
.div-pinlun li p:nth-child(2){background-color: #F0F0F0;display: block; padding: 8px 9px; font-size: 14px; border-radius: 8px;  }
.div-pinlun li span{ margin-right:10px;}
.div-pinlun li{border-bottom: 1px solid #CCC;}
</style>
</head>
<body>
<header><a class="head_back" onClick="history.go(-1);">返回</a> 我的订单 </header>





<div class="content">
  <div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b" style="position:inherit">
        <li  class="current"> 来自客户  </li>
        <li>来自服务商 </li>
        
    </ul>
    
    <ul class="ui-tab-content" style="width: 300%; margin:0px">
    
        <li>
            <div class="div-hei"><span class="ismy">总评价(<em>1</em>次)</span><span class="span-xins">3星</span></div>
            <ul class="ui-list-text ui-border-b div-pinlun" id="list">

            </ul>
        </li>
        
        <li>
            <div class="div-hei"><span class="isjinxiao">总评价(<em>1</em>次)</span><span class="span-xins">3星</span></div>
            <ul class="ui-list-text ui-border-b div-pinlun" id="arr">
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

$.ajax({
      type: 'POST',
      url: '<?php echo U('evaluatelist');?>',
      data :"",
      dataType:'json',
      beforeSend:function(){
        //el=$.loading({  content:'努力加载中...', })
      },
      success:function(json){
          var list=""; 
          var arr="";
          if(json.data.list){
            $.each(json.data.list,function(index,vo){                
                list+='<li><p><span>'+vo['create_time']+'</span><span>'+vo['mobile']+'*********</span><span>'+vo['star']+'</span></p><p>'+vo['content']+'</p></li>';
            });  
          }
          if(json.data.arr){
            $.each(json.data.arr,function(index,vo){                
                arr+='<li><p><span>'+vo['create_time']+'</span><span>'+vo['mobile']+'*********</span><span>'+vo['star']+'</span></p><p>'+vo['content']+'</p></li>';
            });  
          }
          $("#list").html(list);
          $(".ismy em").text(json.data.count_list);
           $("#arr").html(arr);
          $(".isjinxiao em").text(json.data.arr_count);
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