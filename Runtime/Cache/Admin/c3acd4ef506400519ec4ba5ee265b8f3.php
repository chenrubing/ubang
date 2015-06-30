<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>登录后台</title>
        <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/css/login.css" media="all">
        <!--zui-->
        <link rel="stylesheet" type="text/css" href="/Application/Admin/Static/zui/css/zui.css" media="all">
        <style>
		img{ max-width:none; margin-left:-26px}
		</style>
        <!--zui end-->
    </head>
    <body >
        <div id="main-content">

            <!-- 主体 -->
            <div class="login-body">
                <div class="login-main pr">
                    <form action="<?php echo U('login');?>" method="post" class="login-form">
                        <h3><img style="width:294px; height:49px" src="/Application/Admin/Static/images/login_logo.png"></h3>
                        <div id="itemBox" class="item-box">
                            <div class="input-group user-name" >
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="用户名">
                            </div>
                            <div class="input-group password">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" name="password"  class="form-control" placeholder="密码">
                            </div>

                            <?php if(APP_DEBUG == false): ?><div class="input-group password">
                                    <span class="input-group-addon"><i class="icon-ok"></i></span>
                                    <input type="text" name="verify"  class="form-control" placeholder="验证码"  autocomplete="off">
                                    <span class="input-group-btn">
                                    <button  class="btn btn-default reloadverify" type="button"><i class="icon-refresh"></i></button>
                                    </span>
                                </div>


                                <div>
                                    <img class="verifyimg reloadverify" alt="点击切换" src="<?php echo U('Public/verify');?>">
                                </div><?php endif; ?>

                        </div>
                        <div class="login_btn_panel">
                            <button class="login-btn btn btn-info btn-block btn-lg" type="submit">
                                <span class="in"><i class="icon-loading"></i>登 录 中 ..</span>
                                <span class="on">登 录</span>
                            </button>
                            <div class="check-tips"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	<!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/js/jquery-2.0.3.min.js"></script>
    <!--<![endif]-->
    <script type="text/javascript" src="/Application/Admin/Static/zui/js/zui.js"></script>
    <script type="text/javascript">
    	/* 登陆表单获取焦点变色 */
    	$(".login-form").on("focus", "input", function(){
            $(this).closest('.item').addClass('focus');
        }).on("blur","input",function(){
            $(this).closest('.item').removeClass('focus');
        });

    	//表单提交
    	$(document)
	    	.ajaxStart(function(){
	    		$("button:submit").addClass("log-in").attr("disabled", true);
	    	})
	    	.ajaxStop(function(){
	    		$("button:submit").removeClass("log-in").attr("disabled", false);
	    	});

    	$("form").submit(function(){
    		var self = $(this);
    		$.post(self.attr("action"), self.serialize(), success, "json");
    		return false;

    		function success(data){
    			if(data.status){
    				window.location.href = data.url;
    			} else {
                    var msg = new $.Messager(data.info, {placement: 'bottom'});
                    msg.show();
    				//刷新验证码
                    $('[name=verify]').val('');
    				$(".reloadverify").click();
    			}
    		}
    	});

		$(function(){
			//初始化选中用户名输入框
			$("#itemBox").find("input[name=username]").focus();
			//刷新验证码
			var verifyimg = $(".verifyimg").attr("src");
            $(".reloadverify").click(function(){
                if( verifyimg.indexOf('?')>0){
                    $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
                }else{
                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
                }
            });

            //placeholder兼容性
                //如果支持
            function isPlaceholer(){
                var input = document.createElement('input');
                return "placeholder" in input;
            }
                //如果不支持
            if(!isPlaceholer()){
                $(".placeholder_copy").css({
                    display:'block'
                })
                $("#itemBox input").keydown(function(){
                    $(this).parents(".item").next(".placeholder_copy").css({
                        display:'none'
                    })
                })
                $("#itemBox input").blur(function(){
                    if($(this).val()==""){
                        $(this).parents(".item").next(".placeholder_copy").css({
                            display:'block'
                        })
                    }
                })
            }
		});
    </script>
</body>
</html>