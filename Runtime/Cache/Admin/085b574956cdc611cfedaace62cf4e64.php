<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|微店营销系统管理后台</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">


    <!--zui-->
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/zui/css/zui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/css/admin.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/css/ext.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/static/date_time/css.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/static/ztree/zTreeStyle.css" media="all">
   
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/css/style.css" media="all">

    <script type="text/javascript" src="/Public/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Application/Admin/Static/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/Public/static/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/Public/static/date_time/jquery.date_time.js"></script>
    <!--<![endif]-->
    
    <script type="text/javascript">
        var ThinkPHP = window.Think = {
            "ROOT": "", //当前网站地址
            "APP": "/index.php?s=", //当前项目地址
            "PUBLIC": "/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>"
        }
    </script>
</head>
<body>
<style>

</style>
<div class="panel-header">
    <nav class="navbar navbar-inverse admin-bar" role="navigation">
        <div class="navbar-header">
            <a href="<?php echo U('Index/index');?>" class="navbar-brand">
                <img class="logo" src="/Application/Admin/Static/images/logo.png"></a>
            <!--<a class="navbar-brand" href="<?php echo U('Index/index');?>">OpenCenter 管理后台</a>-->
        </div>
        <div class="collapse navbar-collapse navbar-collapse-example">
            <ul id="nav_bar" class="nav navbar-nav">
                <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; if(($menu["hide"]) != "1"): ?><li data-id="<?php echo ($menu["id"]); ?>" class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>">
                            <?php if(($menu["icon"]) != ""): ?><i class="icon-<?php echo ($menu["icon"]); ?>"></i>&nbsp;<?php endif; ?>
                            <?php echo ($menu["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li><a target="_blank" href="<?php echo U('Home/Index/index');?>"><i class="icon-copy"></i> 打开前台</a></li>

                <li><a href="javascript:;"  onclick="clear_cache()"><i class="icon-trash"></i> 清空缓存</a></li>
                <!--<li><a target="_blank" href="<?php echo U('Home/Index/index');?>"><i class="icon-copy"></i> 打开前台</a></li>-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>
                        <?php echo session('user_auth.username');?> <b
                                class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                        <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
                    </ul>
                </li>
                <script>
                    function clear_cache() {
                        var msg = new $.Messager('清理缓存成功', {placement: 'bottom',type:'success'});
                        $.get('/cc.php');
						$.get('<?php echo U('Home/home/menus/deleteMenu');?>');
						$.get('<?php echo U('Home/home/menus/createMenu');?>');
						$.get('<?php echo U('Home/home/menus/getMenu');?>');
                        msg.show()
                    }
                </script>
            </ul>
        </div>
    </nav>

    <div class="admin-title">

    </div>

</div>



<div class="panel-main" style="float:left;">

    <div class="">


    <div class="clearfix " style="">
        <?php if(!empty($__MENU__["child"])): ?><div class="sub_menu_wrapper" style="background: rgb(245, 246, 247); bottom: -10px;top: 0;position: absolute;width: 180px;overflow: auto">
                <div>
                    <nav id="sub_menu" class="menu" data-toggle="menu">
                        <ul class="nav nav-primary">
                          
                                <!--     <?php if(!empty($_extra_menu)): ?>
                                         <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>-->
                                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                                    <?php if(!empty($sub_menu)): ?><li class="show">
                                            <a href="#">
                                                <?php if(!empty($key)): echo ($key); endif; ?>
                                            </a>
                                            <ul class="nav">
                                                <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                                        <a href="<?php echo (u($menu["url"])); ?>"><i class="icon-<?php echo ($menu["icon"]); ?>"></i> <?php echo ($menu["title"]); ?></a>
                                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </li><?php endif; ?>
                                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>

                     

                        </ul>
                    </nav>
                </div>
            </div><?php endif; ?>


        <?php if(!empty($__MENU__["child"])): $col=10; ?>
            <?php else: ?>
            <?php $col=12; endif; ?>
        <div id="main-content" class="" style="padding:10px;padding-left:0;padding-bottom:10px;position:absolute;right: 0;bottom: 0;top: 0;overflow: auto">
            <div id="top-alert" class="fixed alert alert-error" style="display: none;">
                <button class="close fixed" style="margin-top: 4px;">&times;</button>
                <div class="alert-content">这是内容</div>
            </div>
            <div id="main" style="overflow-y:auto;overflow-x:hidden;">
                
                    <!-- nav -->
                    <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                            <span>您的位置:</span>
                            <?php $i = '1'; ?>
                            <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                                    <?php else: ?>
                                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                                <?php $i = $i+1; endforeach; endif; ?>
                        </div><?php endif; ?>
                    <!-- nav -->
                

                <div class="admin-main-container">
                    
    <!--   <div class="with-padding-lg">
           <div class="col-xs-6">
               <div class="alert alert-info with-icon">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <div class="content"><strong>Hi!</strong> 主模块有新的更新。</div>
               </div>
           </div>
          <div class="col-xs-6">
              <div class="alert alert-info with-icon">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <div class="content"><strong>Hi!</strong> 微博模块有新的更新内容。</div>
              </div>
          </div>
       </div>-->

    <div class="with-padding-lg">
        <div class="count clearfix">
             <div class="col-xs-3 text-center">
                <a class="with-padding-lg bg-info" href="<?php echo U('user/index');?>"><i class="icon-user"></i>
    			   <?php echo ($count["today_user"]); ?>
			       <br/>
                    今日新增会员</a>
            </div>
            <div class="col-xs-3 text-center">
                <a href="<?php echo U('action/actionlog');?> " class="with-padding-lg bg-success">
                    <i class="icon-tasks"></i>
                    <?php echo ($count["today_order"]); ?>
                    <br/>
                    今日订单数量
                </a>
            </div>
       
            <div class="col-xs-3 text-center">
                <a class="with-padding-lg bg-warning" href="<?php echo U('Shop/ordersuccess');?>">
                    <i class="icon-group">
                    </i>
                 <?php echo ((isset($count["today_money"]) && ($count["today_money"] !== ""))?($count["today_money"]):'0'); ?>
                    <br/>
                    今日交易额
                </a>
            </div>
               <div class="col-xs-3 text-center">
                <a class="with-padding-lg bg-danger" href="<?php echo U('Shop/ordersuccess');?>">
                    <i class="icon-group">
                    </i>
                    <?php echo ($count["allmoney"]); ?>
                    <br/>
                    总成交交易额
                </a>
            </div>
        </div>
    </div>

    <div class="with-padding-lg" style="position: relative">
        <button class="btn  pull-right" data-toggle="modal" data-target="#settingCount"
                style="position: absolute;right: 15px;z-index: 999">
            <i class="icon-cog"></i>
            设置
        </button>
        <div id="myChart" height="400"></div>
    </div>

    <div class="clearfix">
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">

        </div>

    </div>
    <script>
        $('#main-content').css('left', 0);
        $(function () {
            $('#myChart').highcharts({
                chart: {
                    type: "spline",
                    style: {
                        fontFamily: '"Microsoft Yahei", "宋体"'
                    }
                },
                title: {
                    text: '最近<?php echo ($count["count_day"]); ?>天已付款订单增长指数',
                    x: -20 //center
                },
                xAxis: {
                    categories: eval('<?php echo ($count["last_day"]["days"]); ?>'),
                    title: {
                        text: '当天付款订单',
                        enabled: false
                    }
                },
                yAxis: {
                    title: ''
                },
                legend: {
                    layout: 'vertical',
                    verticalAlign: 'middle',
                    borderWidth: 0,
                    enabled: false
                },
                series: [{
                    name: '当天付款订单',
                    data: eval('<?php echo ($count["last_day"]["data"]); ?>'),
                    enable: true
                }], credits: {enabled: false}
            });
        });


    </script>


                </div>

            </div>
        </div>
    </div>
    </div>
</div>

<script>
    $(function () {
        var config = {
            '.chosen-select'           : {search_contains: true, drop_width: 400,no_results_text:'找不到匹配的选项'},
            '.report-select'           : {search_contains: true, width: '400px',no_results_text:'找不到匹配的选项'}
        };
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

    });
</script>


<script src="/Application/Admin/Static/zui/lib/chosen/chosen.js"></script>
<link href="/Application/Admin/Static/zui/lib/chosen/chosen.css" type="text/css" rel="stylesheet">




<!-- 内容区 -->

<!-- /内容区 -->
<script type="text/javascript">
    (function () {
        var ThinkPHP = window.Think = {
            "ROOT": "", //当前网站地址
            "APP": "/index.php?s=", //当前项目地址
            "PUBLIC": "/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>"
        }
    })();
</script>
<script type="text/javascript" src="/Public/static/think.js"></script>

<!--zui-->
<script type="text/javascript" src="/Application/Admin/Static/js/common.js"></script>
<script type="text/javascript" src="/Application/Admin/Static/js/upload.js"></script>
<script type="text/javascript" src="/Application/Admin/Static/js/com/com.toast.class.js"></script>
<script type="text/javascript" src="/Application/Admin/Static/zui/js/zui.js"></script>
<script type="text/javascript" src="/Application/Admin/Static/zui/lib/autotrigger/autotrigger.min.js"></script>
<!--zui end-->
<link rel="stylesheet" type="text/css" href="/Application/Admin/Static/js/kanban/kanban.css" media="all">
<script type="text/javascript" src="/Application/Admin/Static/js/kanban/kanban.js"></script>
<script type="text/javascript">
    +function () {
        var $window = $(window), $subnav = $("#subnav"), url;
        $window.resize(function () {
            $("#main").css("min-height", $window.height() - 130);
        }).resize();


        // 导航栏超出窗口高度后的模拟滚动条
        var sHeight = $(".sidebar").height();
        var subHeight = $(".subnav").height();
        var diff = subHeight - sHeight; //250
        var sub = $(".subnav");
        if (diff > 0) {
            $(window).mousewheel(function (event, delta) {
                if (delta > 0) {
                    if (parseInt(sub.css('marginTop')) > -10) {
                        sub.css('marginTop', '0px');
                    } else {
                        sub.css('marginTop', '+=' + 10);
                    }
                } else {
                    if (parseInt(sub.css('marginTop')) < '-' + (diff - 10)) {
                        sub.css('marginTop', '-' + (diff - 10));
                    } else {
                        sub.css('marginTop', '-=' + 10);
                    }
                }
            });
        }
    }();
    highlight_subnav("<?php echo U('Admin'.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$_GET);?>")
</script>
<!--优化系统DUMP显示方式@mingyangliu-->
<script type="text/javascript">
if ( $("pre").length > 0 ) { 
document.writeln("<div id=\"mydump\"  style=\"position: fixed;z-index: 999;top:0;height:400px;width: 500px;OVERFLOW:auto;\" ></div>");
$("pre").appendTo('#mydump'); 
document.writeln("<div style=\"z-index:9999;height:30px;float:right;text-align: right;overflow:hidden;position:fixed;bottom:0;right:150px;color:#000;line-height:30px;cursor:pointer;\">");
document.writeln("<a style=\"background-color: rgb(255, 255, 255);float: right;\"href=JavaScript:; class=\"STYLE1\" onclick=\"document.all.mydump.style.display=`none`;\">[隐藏DUMP数据]</a>");
document.writeln("<a style=\"background-color: rgb(255, 255, 255);float: right;\"href=JavaScript:; class=\"STYLE1\" onclick=\"document.all.mydump.style.display=`block`;\">[显示DUMP数据]</a>");
document.writeln("</div>");
}
</script>
<script type="text/javascript">
$("button.btn,a.btn").each(function(){ 
	replaceBtn(this,$(this).text());
});

function replaceBtn(dom,name){ 
	//为了删除按钮文字左右两端的空格，奇怪的问题，如果不过滤就匹配不到文字
	var btnNmae = $(dom).html().replace(/(&nbsp;)|\s|\u00a0/g, '');
	
	switch (btnNmae)
	{
	case '新增':
		$(dom).html('<i class="icon-plus"></i>'+btnNmae+'')
	break;
	case '添加':
		$(dom).html('<i class="icon-plus"></i>'+btnNmae+'')
	break;
		case '展开':
		$(dom).html('<i class="icon-resize-full"></i>'+btnNmae+'')
	break;
	case '收起':
		$(dom).html('<i class="icon-resize-small"></i>'+btnNmae+'')
	break;
		case '搜索':
		$(dom).html('<i class="icon-search"></i>'+btnNmae+'')
	break;
		case '启用':
		$(dom).html('<i class="icon-ok-circle"></i>'+btnNmae+'')
	break;
		case '排序':
		$(dom).html('<i class="icon-sort"></i>'+btnNmae+'')
	break;
		case '禁用':
		$(dom).html('<i class="icon-ban-circle"></i>'+btnNmae+'')
	break;
		case '确定':
		$(dom).html('<i class="icon-ok"></i>'+btnNmae+'')
	break;
		case '提交':
		$(dom).html('<i class="icon-ok"></i>'+btnNmae+'')
	break;
	case '修改':
		$(dom).html('<i class="icon-ok"></i>'+btnNmae+'')
	break;
		case '返回':
		$(dom).html('<i class="icon-arrow-left"></i>'+btnNmae+'')
	break;
		case '删除':
		$(dom).html('<i class="icon-remove"></i>'+btnNmae+'')
	break;
		case '关闭':
		$(dom).html('<i class="icon-remove"></i>'+btnNmae+'')
	break;
		case '返回':
		$(dom).html('<i class="icon-arrow-left"></i>'+btnNmae+'')
	break;
	case '迁移用户':
		$(dom).html('<i class="icon-exchange"></i>'+btnNmae+'')
	break;
	case '选择用户分组':
		$(dom).html('<i class="icon-hand-up"></i>'+btnNmae+'')
	break;
	case '清空':
		$(dom).html('<i class="icon-trash"></i>'+btnNmae+'')
	break;
	case '审核通过':
		$(dom).html('<i class="icon-eye-open"></i>'+btnNmae+'')
	break;
	case '审核不通过':
		$(dom).html('<i class="icon-eye-close"></i>'+btnNmae+'')
	break;
	case '充值':
		$(dom).html('<i class="icon-renminbi"></i>'+btnNmae+'')
	break;
	case '快速创建':
		$(dom).html('<i class="icon-bolt"></i>'+btnNmae+'')
	break;
	case '立即备份':
		$(dom).html('<i class="icon-tasks"></i>'+btnNmae+'')
	break;
	case '修复表':
		$(dom).html('<i class="icon-repeat"></i>'+btnNmae+'')
	break;
	case '优化表':
		$(dom).html('<i class="icon-align-justify"></i>'+btnNmae+'')
	break;
	case '还原':
		$(dom).html('<i class="icon-refresh"></i>'+btnNmae+'')
	break;
	case '彻底删除':
		$(dom).html('<i class="icon-trash"></i>'+btnNmae+'')
	break;
	case '新增补丁':
		$(dom).html('<i class="icon-plus"></i>'+btnNmae+'')
	break;
	case '第一':
		$(dom).html('<i class="icon-level-up"></i>'+btnNmae+'')
	break;
	case '最后':
		$(dom).html('<i class="icon-level-down"></i>'+btnNmae+'')
	break;
	case '上移':
		$(dom).html('<i class="icon-chevron-sign-up"></i>'+btnNmae+'')
	break;
	case '下移':
		$(dom).html('<i class="icon-chevron-sign-up"></i>'+btnNmae+'')
	break;
	case '保存':
		$(dom).html('<i class="icon-save"></i>'+btnNmae+'')
	break;
	case '关联新头衔':
		$(dom).html('<i class="icon-group"></i>'+btnNmae+'')
	break;
	case '导入':
		$(dom).html('<i class="icon-tint"></i>'+btnNmae+'')
	break;
	case '批量结款':
		$(dom).html('<i class="icon-yen"></i>'+btnNmae+'')
	break;
		case '发货':
		$(dom).html('<i class="icon-plane"></i>'+btnNmae+'')
	break;
	break;
		case '顶级分类':
		$(dom).html('<i class="icon-chevron-down"></i>'+btnNmae+'')
	break;
	break;
		case '请选择一个分类':
		$(dom).html('<i class="icon-chevron-down"></i>'+btnNmae+'')
	break;
	default:
		$(dom).html(''+btnNmae+'')			
	}
}
</script>

    <script type="text/javascript" src="/Application/Admin/Static/zui/lib/highchart/highcharts.js"></script>


    <div class="modal fade" id="settingCount">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only">关闭</span></button>
                    <h4 class="modal-title">设置统计图</h4>
                </div>
                <div class="modal-body">
                    <div class="with-padding">
                        <label>默认展示天数 </label><input type="text" class="form-control input-3" name="count_day" value="<?php echo ($count["count_day"]); ?>">

                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn " data-role="saveCountSetting">
                        <i class="icon-ok"></i> 保存
                    </button>
                    <button class="btn " data-dismiss="modal">
                        <i class="icon-remove"></i> 取消
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('[data-role=saveCountSetting]').click(function () {
            $.post("/index.php?s=/admin/index/index.html", {count_day: $('[name=count_day]').val()}, function (msg) {
                handleAjax(msg);
            });
        })
    </script>

</body>
</html>