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
                    
  <div class="main-title">
    <h2><?php echo ($title); ?>
      <?php if($suggest): ?>（<?php echo (htmlspecialchars($suggest)); ?>）<?php endif; ?>
    </h2>
  </div>
    <?php if($group): ?><div class="with-padding">

    <div class="tab-wrap" style="margin-bottom: 5px">
      <ul class="nav nav-secondary group_nav">
        <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vGroup): $mod = ($i % 2 );++$i;?><li class="<?php if($i == 1): ?>active<?php endif; ?>"><a href="javascript:"><?php echo ($key); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </div>
    </div><?php endif; ?>
    <div class="with-padding">
    <?php if(!empty($errMsg)): ?><div class="alert alert-danger" style="margin-bottom:10px"><?php echo ($errMsg); ?></div><?php endif; ?>
    <form action="<?php echo ($savePostUrl); ?>" method="post" class="form-horizontal">
      <?php if($group){ ?>    
      <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vGroup): $mod = ($i % 2 );++$i;?><div class="group_list" style="<?php if($i != 1): ?>display: none;<?php endif; ?>">
          <table width="100%" border="0" class="table table-hover table-bordered ">
            <?php if(is_array($keyList)): $i = 0; $__LIST__ = $keyList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$field): $mod = ($i % 2 );++$i;?><tr>
                <?php if(in_array($field['name'],$vGroup)){ ?>
                <td width="20%"><?php echo (htmlspecialchars($field["title"])); ?></td>

<?php if($field['name'] == 'action'): ?><p style="color: #f00;">开发人员注意：你使用了一个名称为action的字段，由于这个字段名称会与form[action]冲突，导致无法提交表单，请换用另外一个名字。</p><?php endif; ?>

<td width="80%"><?php switch($field["type"]): case "text": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>" class="text input-4 form-control"/><?php break;?>

    <?php case "password": ?><input type="password" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>" class="text input-4 form-control"/><?php break;?>

    <?php case "label": echo ($field["value"]); break;?>

    <?php case "hidden": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" /><?php break;?>

    <?php case "readonly": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-2 form-control" placeholder="无需填写" readonly/><?php break;?>

    <?php case "integer": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-1 form-control"/><?php break;?>

    <?php case "uid": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-2 form-control" /><?php break;?>

    <?php case "select": ?><select name="<?php echo ($field["name"]); ?>" class="form-control" style="width:auto;">

        <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = $field['value']==$key ? 'selected' : ''; ?>

          <option value="<?php echo ($key); ?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

      </select><?php break;?>

    <?php case "radio": if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = $field['value']==$key ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>

        <label for="<?php echo ($inputId); ?>">

          <input id="<?php echo ($inputId); ?>" name="<?php echo ($field["name"]); ?>" value="<?php echo ($key); ?>" type="radio"  <?php echo ($checked); ?>/>

          <?php echo (htmlspecialchars($option)); ?></label><?php endforeach; endif; else: echo "" ;endif; break;?>

    <?php case "selectjson": ?><span  data-title="选择栏目" class="btn" href="javascript:void(0);"  data-remote="<?php echo U('Select');?>" data-toggle="modal" id="CategoryName" ><i class="icon-chevron-down"></i> <?php echo ((isset($field['subtitle']) && ($field['subtitle'] !== ""))?($field['subtitle']):'请选择一个分类'); ?></span>

      <input type="hidden" id="CategoryPid"  name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/><?php break;?>

    <?php case "singleImage": ?><div class="controls">

        <div id="upload_single_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;">选择图片</div>

        <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>_input" value="<?php echo ($field['value']); ?>"/>

        <div class="upload-img-box">

          <div class="upload-pre-item">

            <?php if(!empty($field["value"])): ?><div class="each kanban-item card"><img src="<?php echo (get_cover($field["value"],'path')); ?>" id="<?php echo ($field["name"]); ?>_view" data-toggle="lightbox"> <span class="caption"><a href="javascript:void(0);" onclick="Cutimg('<?php echo ($field["name"]); ?>')">剪裁</a><a href="javascript:void(0);"  onclick="$(this).closest('.each').remove();">删除</a></span> </div><?php endif; ?>

          </div>

        </div>

      </div>

      <script type="application/javascript">

            $(function () {

                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({

                    // 选完文件后，是否自动上传。

                    auto: true,

                    // swf文件路径

                    swf: 'Uploader.swf',

                    // 文件接收服务端。

                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",

                    // 选择文件的按钮。可选。

                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.

                    pick: '#upload_single_image_<?php echo ($field["name"]); ?>',

                    // 只允许选择图片文件。

                    accept: {

                        title: 'Images',

                        extensions: 'gif,jpg,jpeg,bmp,png',

                        mimeTypes: 'image/*'

                    }

                });

                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {

                    uploader_<?php echo ($field["name"]); ?>.upload();

                });

                /*上传成功*/

                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {

                    if (data.status) {

						

						 var str='';

						 str+='<div class="each kanban-item card">';

						 str+='<img src="'+ data.path+'" id="<?php echo ($field["name"]); ?>_view" data-toggle="lightbox">';

						 str+='<span class="caption">';

					 	 str+='<a href="javascript:void(0);" onclick="Cutimg(\'<?php echo ($field["name"]); ?>\')">剪裁</a>';

					 	 str+='<a href="javascript:void(0);"  onclick="$(this).closest(\'.each\').remove();">删除</a>';

					 	 str+='</span>';

						 str+='<strong class="card-heading">';

						 str+=' <input type="hidden" name="<?php echo ($field["name"]); ?>" value="'+ data.id+'"  id="<?php echo ($field["name"]); ?>_input"/>';

						 str+='</strong></div>';

								  

                        $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').html(str);

                        uploader_<?php echo ($field["name"]); ?>.reset();

                    } else {

                        updateAlert(data.info);

                        setTimeout(function () {

                            $('#top-alert').find('button').click();

                            $(that).removeClass('disabled').prop('disabled', false);

                        }, 1500);

                    }

                });

            })

        </script><?php break;?>

    <?php case "multiImage": ?><div class="controls multiImage">

        <div id="upload_multi_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;">选择图片</div>

        <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field['value']); ?>"/>

        <div class="upload-img-box">

          <div class="upload-pre-item">

            <?php if(!empty($field["value"])): $aIds = explode(',',$field['value']); ?>

              <?php if(is_array($aIds)): $i = 0; $__LIST__ = $aIds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$aId): $mod = ($i % 2 );++$i;?><div class="each kanban-item card"> <img src="<?php echo (get_cover($aId,'path')); ?>"  data-toggle="lightbox" id="<?php echo ($field["name"]); ?>_view">

                  <input type="hidden"   value="<?php echo ($aId); ?>" id="<?php echo ($field["name"]); ?>_input"/>

                  <span class="caption"> <a href="javascript:void(0);" onclick="Cutimg('<?php echo ($field["name"]); ?>')">剪裁</a><a href="javascript:void(0);"  onclick="admin_image.removeImage($(this),'<?php echo ($aId); ?>')">删除</a></span> </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>

          </div>

        </div>

      </div>

      <script>

            $(function () {

                var id = "#upload_multi_image_<?php echo ($field["name"]); ?>";

                var limit = parseInt('<?php echo ($field["opt"]); ?>');

                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({

                    // 选完文件后，是否自动上传。

                      // swf文件路径

                    swf: 'Uploader.swf',

                    // 文件接收服务端。

                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",

                    // 选择文件的按钮。可选。

                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.

                    //pick: '#upload_multi_image_<?php echo ($field["name"]); ?>',

                    pick: {'id': id, 'multi': true},

                    fileNumLimit: limit,

                    // 只允许选择图片文件。

                    accept: {

                        title: 'Images',

                        extensions: 'gif,jpg,jpeg,bmp,png',

                        mimeTypes: 'image/*'

                    }

                });

                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {

                    uploader_<?php echo ($field["name"]); ?>.upload();

                });

                uploader_<?php echo ($field["name"]); ?>.on('uploadFinished', function (file) {

                    uploader_<?php echo ($field["name"]); ?>.reset();

                });

                /*上传成功*/

                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {

                          if (data.status) {

                            var ids = $("[name='<?php echo ($field["name"]); ?>']").val();

                            ids = ids.split(',');

                          if( ids.indexOf(data.id) == -1){

                                var rids = admin_image.upAttachVal('add',data.id, $("[name='<?php echo ($field["name"]); ?>']"));

                              if(rids.length>limit){

                                  updateAlert('超过图片限制');

                                  return;

                              }

							  

					  var str='';

					 		str+='<div class="each kanban-item card">';

					 		str+='<img src="'+ data.path+'"  data-toggle="lightbox"  id="<?php echo ($field["name"]); ?>_'+ data.id+'_view">';

					 		str+='<span class="caption">';

					 		str+='<a href="javascript:void(0);" onclick="Cutimg(\'<?php echo ($field["name"]); ?>_'+ data.id+'\')">剪裁</a>';

					 		str+='<a href="javascript:void(0);"  onclick="admin_image.removeImage($(this),'+data.id+')">删除</a>';

					 		str+='</span>';

					 		str+='<input type="hidden"  value="'+ data.id+'" id="<?php echo ($field["name"]); ?>_'+ data.id+'_input"/>';

					 		str+='</div>';

							  

                              $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').append(str);

                            }else{

                                updateAlert('该图片已存在');

                            }

                        } else {

                            updateAlert(data.info);

                            setTimeout(function () {

                                $('#top-alert').find('button').click();

                                $(that).removeClass('disabled').prop('disabled', false);

                            }, 1500);

                        }

                });

            })

        </script><?php break;?>

    <?php case "viewImage": ?><div class="controls">

        <div data-toggle="upimg" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>" title="请上传图片" maxnum="1" value="<?php echo ($field['value']); ?>" src="<?php echo (get_cover($field['value'],'path')); ?>"></div>

      </div><?php break;?>

    <?php case "viewFile": ?><div class="controls">

        <div data-toggle="upfield" name="<?php echo ($field["name"]); ?>" title="请上传附件" maxnum="1" value="<?php echo ($field['value']); ?>" id="<?php echo ($field["name"]); ?>" src="<?php echo (get_file($field['value'],'ext')); ?>"></div>

      </div><?php break;?>

    <?php case "checkbox": $importCheckBox = true; ?>

      <?php $field['value_array'] = explode(',', $field['value']); ?>

      <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = in_array($key,$field['value_array']) ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>

   		

        <label for="<?php echo ($inputId); ?>">

          <input type="checkbox" value="<?php echo ($key); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox"  data-field-name="<?php echo ($field["name"]); ?>" <?php echo ($checked); ?>/>

          <?php echo (htmlspecialchars($option)); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>

      <input type="hidden" name="<?php echo ($field["name"]); ?>" class="oneplus-checkbox-hidden" data-field-name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/><?php break;?>

    

  

    <?php case "jiedian": $importCheckBox = true; ?>

      <?php $arrs = explode(',', $field['value']); ?>

      <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><div style="width:100%; padding:5px; height:auto;">

        <?php $checked = in_array($option[id],$arrs) ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>

          <input type="checkbox" value="<?php echo ($option["id"]); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox"  name="<?php echo ($field["name"]); ?>[]" <?php echo ($checked); ?>/>

          <?php echo (htmlspecialchars($option["title"])); ?>

          <div style="width:100%; margin-left:30px;">

         <?php if($option['_child']) { foreach($option['_child'] as $vo) { $checked = in_array($vo[id],$arrs) ? 'checked' : ''; ?>

                      <input type="checkbox" value="<?php echo ($vo["id"]); ?>"  id="<?php echo ($inputId); ?>" class="oneplus-checkbox"  name="<?php echo ($field["name"]); ?>[]" <?php echo ($checked); ?>/>

                        <?php echo (htmlspecialchars($vo["title"])); ?>                      

                      <?php if($vo['_child']) { ?>

                      <div style="width:100%; margin-left:40px;">

                       <?php foreach($vo['_child'] as $li) { $checked = in_array($li[id],$arrs) ? 'checked' : ''; ?>                             

                             <input type="checkbox" value="<?php echo ($li["id"]); ?>" id="<?php echo ($inputId); ?>"  class="oneplus-checkbox"  name="<?php echo ($field["name"]); ?>[]" <?php echo ($checked); ?>/>

                             <?php echo (htmlspecialchars($li["title"])); ?>

                            

                      <?php } ?>

                      </div>

                      <?php } ?>

          <?php } } ?>

          </div>

      </div><?php endforeach; endif; else: echo "" ;endif; ?>
      <script type="text/javascript">      

            $(document).on("click",".oneplus-checkbox",function(){
              if($(this).is(':checked'))

              {              //alert("11");
                  $(this).closest("div").prev(".oneplus-checkbox").first().attr("checked", true).add().closest("div").prev(".oneplus-checkbox").first().attr("checked", true);
              }
              else
              {
                  //$(this).closest("div").prev(".oneplus-checkbox").first().attr("checked", false).add().closest("div").prev(".oneplus-checkbox").first().attr("checked", false);

              }

            })

      </script><?php break;?>
    <?php case "editor": echo W('Common/Ueditor/editor',array($field['name'],$field['name'],$field['value'],$field['style']['width'],$field['style']['height'],$field['config'])); break;?>

    <?php case "textarea": ?><textarea name="<?php echo ($field["name"]); ?>" class="text input-5 form-control"><?php echo (htmlspecialchars($field["value"])); ?></textarea><?php break;?>

    <?php case "year": ?><input type="text" name="<?php echo ($field["name"]); ?>"  id="<?php echo ($field["name"]); ?>_time" class="text input-2 form-control" value="<?php echo ($field[value]); ?>" placeholder="请选择时间"/>

      <script type="text/javascript">

	Calendar.setup({

			weekNumbers: true,

		    inputField : "<?php echo ($field["name"]); ?>_time",

		    trigger    : "<?php echo ($field["name"]); ?>_time",

		    dateFormat: "%Y-%m-%d",

		    showTime: true,

		    minuteStep: 1,

		    onSelect   : function() {this.hide();}

	});

</script><?php break;?>

    <?php case "time": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>

      <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>

      <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text input-2 time form-control" value="<?php echo (time_format($field["value"])); ?>" placeholder="请选择时间"/><?php break;?>

    

    <!--添加城市选择（需安装城市联动插件,css样式不好处理排版有点怪）-->

    

    <?php case "city": ?><!--修正在编辑信息时无法正常显示已经保存的地区信息--> 

      <?php echo hook('J_China_City',array('province'=>$field['value']['0'],'city'=>$field['value']['1'],'district'=>$field['value']['2'],'community'=>$field['value']['3'])); break;?>

      

    <?php case "map": ?><!--修正在编辑信息时无法正常显示已经保存的地区信息--> 
    <?php echo hook('TencentMap',array('name'=>$field['name'],'value'=>$field['value'])); break;?>   

    <!--弹出窗口选择并返回值（目前只支持返回ID）开始--> 

    <?php case "dataselect": ?><input type="text" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"  class="text input-3 form-control" />

      <input class="btn" style="margin-left:10px" type="button" value="选择" onclick="openwin('<?php echo ($field["opt"]); ?>','600','500')">

      <script type="text/javascript">
						//弹出窗口
						function openwin(url,width,height){
						    var l=window.screen.width ;
						    var w= window.screen.height;
						    var al=(l-width)/2;
						    var aw=(w-height)/2;
						    var OpenWindow=window.open(url,"弹出窗口","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,width="+width+",height="+height+",top="+aw+",left="+al+"");
						    OpenWindow.focus();
						if(OpenWindow!=null){ //弹出窗口关闭事件
						//if(window.attachEvent) OpenWindow.attachEvent("onbeforeunload",   quickOut);
						if(window.attachEvent) OpenWindow.attachEvent("onunload",   quickOut);
						}
						}
						//关闭触发方法
						function quickOut()
						{
						alert("窗口已关闭");

						}
				 </script><?php break;?>

    

    <!--弹出窗口选择并返回值（目前只支持返回ID）结束-->

    

    <?php case "kanban": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value='<?php echo json_encode($field["value"]);?>'/>

      <div class="kanbans" id="<?php echo ($field["name"]); ?>">

        <?php foreach($field['value'] as $key =>$kanban){ ?>

        <div class="kanban panel" data-id="<?php echo ($kanban['data-id']); ?>" data-title="<?php echo ($kanban['title']); ?>">

          <div class="panel-heading"> <strong><?php echo ($kanban['title']); ?></strong> </div>

          <div class="panel-body">

            <div class="kanban-list">

              <?php if(is_array($kanban["items"])): $i = 0; $__LIST__ = $kanban["items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="kanban-item item" data-id="<?php echo ($vo["data-id"]); ?>" data-title="<?php echo ($vo["title"]); ?>"> <?php echo ($vo["title"]); ?> </div><?php endforeach; endif; else: echo "" ;endif; ?>

            </div>

          </div>

        </div>

        <?php } ?>

      </div>

      <script>

            $(function () {

                var flag = "<?php echo ($field["name"]); ?>"

                $('#<?php echo ($field["name"]); ?>').kanbans({'drop': function () {

                    var kanban =new Array();

                    $('.kanbans .kanban').each(function (index, element) {

                        if ($(element).data('id')) {

                            kanban[index] =  new Object();

                            kanban[index]['data-id'] =  $(element).data('id');

                            kanban[index]['title'] =  $(element).data('title');

                            kanban[index]['items'] =  new Array();

                            var obj = $(element).find('.item');

                            for (var i = 0; i < obj.length; i++) {

                                kanban[index]['items'][i] = new Object();

                                kanban[index]['items'][i]['data-id'] = $(obj[i]).data('id');

                                kanban[index]['items'][i]['title'] = $(obj[i]).data('title');

                            };

                        }

                    })

                    var kanban_str = JSON.stringify(kanban);

                    $('[name="'+flag+'"]').val(kanban_str);

                }

                })

            })

        </script><?php break;?>

    <?php case "chosen": ?><select name="<?php echo ($field["name"]); ?>[]" style="width: 400px" class="chosen-select" multiple="multiple">

        <?php if( key($field['opt']) === 0){ ?>

        <?php if(is_array($field['opt'])): $i = 0; $__LIST__ = $field['opt'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>

          <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

        <?php }else{ foreach($field['opt'] as $optgroupkey =>$optgroup){ ?>

        <optgroup label="<?php echo ($optgroupkey); ?>">

        <?php if(is_array($optgroup)): $i = 0; $__LIST__ = $optgroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>

          <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

        </optgroup>

        <?php } } ?>

      </select><?php break;?>

    <?php case "multiInput": $field['name'] = is_array($field['name'])?$field['name']:explode('|',$field['name']); foreach($field['name'] as $key=>$val){ ?>

      <?php switch($field['config'][$key]['type']): case "text": ?><input type="text" name="<?php echo ($val); ?>" value="<?php echo (htmlspecialchars($field['value'][$key])); ?>"  class=" pull-left text input-3 form-control" style="<?php echo ($field['config'][$key]['style']); ?>" placeholder="<?php echo ($field['config'][$key]['placeholder']); ?>"/><?php break;?>

        <?php case "select": ?><select name="<?php echo ($val); ?>" class="pull-left form-control" style="<?php echo ($field['config'][$key]['style']); ?>" >

            <?php foreach($field['config'][$key]['opt'] as $key_opt =>$option){ ?>

            <?php $selected = $field['value'][$key]==$key_opt ? 'selected' : ''; ?>

            <option value="<?php echo ($key_opt); ?>"<?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option>

            <?php } ?>

          </select><?php break; endswitch;?>

      <?php } break;?>

    <?php default: ?>

    <span style="color: #f00;">错误：未知字段类型 <?php echo ($field["type"]); ?></span>

    <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"/><?php endswitch;?>

  <?php if($field['subtitle']): ?><span class="check-tips">（<?php echo (htmlspecialchars($field["subtitle"])); ?>）</span><?php endif; ?></td>
                <?php } ?>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
      <?php }else{ ?>
      <table width="100%" border="0" class="table table-hover table-bordered ">
        <?php if(is_array($keyList)): $i = 0; $__LIST__ = $keyList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$field): $mod = ($i % 2 );++$i;?><tr> <td width="20%"><?php echo (htmlspecialchars($field["title"])); ?></td>

<?php if($field['name'] == 'action'): ?><p style="color: #f00;">开发人员注意：你使用了一个名称为action的字段，由于这个字段名称会与form[action]冲突，导致无法提交表单，请换用另外一个名字。</p><?php endif; ?>

<td width="80%"><?php switch($field["type"]): case "text": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>" class="text input-4 form-control"/><?php break;?>

    <?php case "password": ?><input type="password" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>" class="text input-4 form-control"/><?php break;?>

    <?php case "label": echo ($field["value"]); break;?>

    <?php case "hidden": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" /><?php break;?>

    <?php case "readonly": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-2 form-control" placeholder="无需填写" readonly/><?php break;?>

    <?php case "integer": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-1 form-control"/><?php break;?>

    <?php case "uid": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-2 form-control" /><?php break;?>

    <?php case "select": ?><select name="<?php echo ($field["name"]); ?>" class="form-control" style="width:auto;">

        <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = $field['value']==$key ? 'selected' : ''; ?>

          <option value="<?php echo ($key); ?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

      </select><?php break;?>

    <?php case "radio": if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = $field['value']==$key ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>

        <label for="<?php echo ($inputId); ?>">

          <input id="<?php echo ($inputId); ?>" name="<?php echo ($field["name"]); ?>" value="<?php echo ($key); ?>" type="radio"  <?php echo ($checked); ?>/>

          <?php echo (htmlspecialchars($option)); ?></label><?php endforeach; endif; else: echo "" ;endif; break;?>

    <?php case "selectjson": ?><span  data-title="选择栏目" class="btn" href="javascript:void(0);"  data-remote="<?php echo U('Select');?>" data-toggle="modal" id="CategoryName" ><i class="icon-chevron-down"></i> <?php echo ((isset($field['subtitle']) && ($field['subtitle'] !== ""))?($field['subtitle']):'请选择一个分类'); ?></span>

      <input type="hidden" id="CategoryPid"  name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/><?php break;?>

    <?php case "singleImage": ?><div class="controls">

        <div id="upload_single_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;">选择图片</div>

        <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>_input" value="<?php echo ($field['value']); ?>"/>

        <div class="upload-img-box">

          <div class="upload-pre-item">

            <?php if(!empty($field["value"])): ?><div class="each kanban-item card"><img src="<?php echo (get_cover($field["value"],'path')); ?>" id="<?php echo ($field["name"]); ?>_view" data-toggle="lightbox"> <span class="caption"><a href="javascript:void(0);" onclick="Cutimg('<?php echo ($field["name"]); ?>')">剪裁</a><a href="javascript:void(0);"  onclick="$(this).closest('.each').remove();">删除</a></span> </div><?php endif; ?>

          </div>

        </div>

      </div>

      <script type="application/javascript">

            $(function () {

                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({

                    // 选完文件后，是否自动上传。

                    auto: true,

                    // swf文件路径

                    swf: 'Uploader.swf',

                    // 文件接收服务端。

                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",

                    // 选择文件的按钮。可选。

                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.

                    pick: '#upload_single_image_<?php echo ($field["name"]); ?>',

                    // 只允许选择图片文件。

                    accept: {

                        title: 'Images',

                        extensions: 'gif,jpg,jpeg,bmp,png',

                        mimeTypes: 'image/*'

                    }

                });

                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {

                    uploader_<?php echo ($field["name"]); ?>.upload();

                });

                /*上传成功*/

                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {

                    if (data.status) {

						

						 var str='';

						 str+='<div class="each kanban-item card">';

						 str+='<img src="'+ data.path+'" id="<?php echo ($field["name"]); ?>_view" data-toggle="lightbox">';

						 str+='<span class="caption">';

					 	 str+='<a href="javascript:void(0);" onclick="Cutimg(\'<?php echo ($field["name"]); ?>\')">剪裁</a>';

					 	 str+='<a href="javascript:void(0);"  onclick="$(this).closest(\'.each\').remove();">删除</a>';

					 	 str+='</span>';

						 str+='<strong class="card-heading">';

						 str+=' <input type="hidden" name="<?php echo ($field["name"]); ?>" value="'+ data.id+'"  id="<?php echo ($field["name"]); ?>_input"/>';

						 str+='</strong></div>';

								  

                        $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').html(str);

                        uploader_<?php echo ($field["name"]); ?>.reset();

                    } else {

                        updateAlert(data.info);

                        setTimeout(function () {

                            $('#top-alert').find('button').click();

                            $(that).removeClass('disabled').prop('disabled', false);

                        }, 1500);

                    }

                });

            })

        </script><?php break;?>

    <?php case "multiImage": ?><div class="controls multiImage">

        <div id="upload_multi_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;">选择图片</div>

        <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field['value']); ?>"/>

        <div class="upload-img-box">

          <div class="upload-pre-item">

            <?php if(!empty($field["value"])): $aIds = explode(',',$field['value']); ?>

              <?php if(is_array($aIds)): $i = 0; $__LIST__ = $aIds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$aId): $mod = ($i % 2 );++$i;?><div class="each kanban-item card"> <img src="<?php echo (get_cover($aId,'path')); ?>"  data-toggle="lightbox" id="<?php echo ($field["name"]); ?>_view">

                  <input type="hidden"   value="<?php echo ($aId); ?>" id="<?php echo ($field["name"]); ?>_input"/>

                  <span class="caption"> <a href="javascript:void(0);" onclick="Cutimg('<?php echo ($field["name"]); ?>')">剪裁</a><a href="javascript:void(0);"  onclick="admin_image.removeImage($(this),'<?php echo ($aId); ?>')">删除</a></span> </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>

          </div>

        </div>

      </div>

      <script>

            $(function () {

                var id = "#upload_multi_image_<?php echo ($field["name"]); ?>";

                var limit = parseInt('<?php echo ($field["opt"]); ?>');

                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({

                    // 选完文件后，是否自动上传。

                      // swf文件路径

                    swf: 'Uploader.swf',

                    // 文件接收服务端。

                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",

                    // 选择文件的按钮。可选。

                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.

                    //pick: '#upload_multi_image_<?php echo ($field["name"]); ?>',

                    pick: {'id': id, 'multi': true},

                    fileNumLimit: limit,

                    // 只允许选择图片文件。

                    accept: {

                        title: 'Images',

                        extensions: 'gif,jpg,jpeg,bmp,png',

                        mimeTypes: 'image/*'

                    }

                });

                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {

                    uploader_<?php echo ($field["name"]); ?>.upload();

                });

                uploader_<?php echo ($field["name"]); ?>.on('uploadFinished', function (file) {

                    uploader_<?php echo ($field["name"]); ?>.reset();

                });

                /*上传成功*/

                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {

                          if (data.status) {

                            var ids = $("[name='<?php echo ($field["name"]); ?>']").val();

                            ids = ids.split(',');

                          if( ids.indexOf(data.id) == -1){

                                var rids = admin_image.upAttachVal('add',data.id, $("[name='<?php echo ($field["name"]); ?>']"));

                              if(rids.length>limit){

                                  updateAlert('超过图片限制');

                                  return;

                              }

							  

					  var str='';

					 		str+='<div class="each kanban-item card">';

					 		str+='<img src="'+ data.path+'"  data-toggle="lightbox"  id="<?php echo ($field["name"]); ?>_'+ data.id+'_view">';

					 		str+='<span class="caption">';

					 		str+='<a href="javascript:void(0);" onclick="Cutimg(\'<?php echo ($field["name"]); ?>_'+ data.id+'\')">剪裁</a>';

					 		str+='<a href="javascript:void(0);"  onclick="admin_image.removeImage($(this),'+data.id+')">删除</a>';

					 		str+='</span>';

					 		str+='<input type="hidden"  value="'+ data.id+'" id="<?php echo ($field["name"]); ?>_'+ data.id+'_input"/>';

					 		str+='</div>';

							  

                              $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').append(str);

                            }else{

                                updateAlert('该图片已存在');

                            }

                        } else {

                            updateAlert(data.info);

                            setTimeout(function () {

                                $('#top-alert').find('button').click();

                                $(that).removeClass('disabled').prop('disabled', false);

                            }, 1500);

                        }

                });

            })

        </script><?php break;?>

    <?php case "viewImage": ?><div class="controls">

        <div data-toggle="upimg" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>" title="请上传图片" maxnum="1" value="<?php echo ($field['value']); ?>" src="<?php echo (get_cover($field['value'],'path')); ?>"></div>

      </div><?php break;?>

    <?php case "viewFile": ?><div class="controls">

        <div data-toggle="upfield" name="<?php echo ($field["name"]); ?>" title="请上传附件" maxnum="1" value="<?php echo ($field['value']); ?>" id="<?php echo ($field["name"]); ?>" src="<?php echo (get_file($field['value'],'ext')); ?>"></div>

      </div><?php break;?>

    <?php case "checkbox": $importCheckBox = true; ?>

      <?php $field['value_array'] = explode(',', $field['value']); ?>

      <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = in_array($key,$field['value_array']) ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>

   		

        <label for="<?php echo ($inputId); ?>">

          <input type="checkbox" value="<?php echo ($key); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox"  data-field-name="<?php echo ($field["name"]); ?>" <?php echo ($checked); ?>/>

          <?php echo (htmlspecialchars($option)); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>

      <input type="hidden" name="<?php echo ($field["name"]); ?>" class="oneplus-checkbox-hidden" data-field-name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/><?php break;?>

    

  

    <?php case "jiedian": $importCheckBox = true; ?>

      <?php $arrs = explode(',', $field['value']); ?>

      <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><div style="width:100%; padding:5px; height:auto;">

        <?php $checked = in_array($option[id],$arrs) ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>

          <input type="checkbox" value="<?php echo ($option["id"]); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox"  name="<?php echo ($field["name"]); ?>[]" <?php echo ($checked); ?>/>

          <?php echo (htmlspecialchars($option["title"])); ?>

          <div style="width:100%; margin-left:30px;">

         <?php if($option['_child']) { foreach($option['_child'] as $vo) { $checked = in_array($vo[id],$arrs) ? 'checked' : ''; ?>

                      <input type="checkbox" value="<?php echo ($vo["id"]); ?>"  id="<?php echo ($inputId); ?>" class="oneplus-checkbox"  name="<?php echo ($field["name"]); ?>[]" <?php echo ($checked); ?>/>

                        <?php echo (htmlspecialchars($vo["title"])); ?>                      

                      <?php if($vo['_child']) { ?>

                      <div style="width:100%; margin-left:40px;">

                       <?php foreach($vo['_child'] as $li) { $checked = in_array($li[id],$arrs) ? 'checked' : ''; ?>                             

                             <input type="checkbox" value="<?php echo ($li["id"]); ?>" id="<?php echo ($inputId); ?>"  class="oneplus-checkbox"  name="<?php echo ($field["name"]); ?>[]" <?php echo ($checked); ?>/>

                             <?php echo (htmlspecialchars($li["title"])); ?>

                            

                      <?php } ?>

                      </div>

                      <?php } ?>

          <?php } } ?>

          </div>

      </div><?php endforeach; endif; else: echo "" ;endif; ?>
      <script type="text/javascript">      

            $(document).on("click",".oneplus-checkbox",function(){
              if($(this).is(':checked'))

              {              //alert("11");
                  $(this).closest("div").prev(".oneplus-checkbox").first().attr("checked", true).add().closest("div").prev(".oneplus-checkbox").first().attr("checked", true);
              }
              else
              {
                  //$(this).closest("div").prev(".oneplus-checkbox").first().attr("checked", false).add().closest("div").prev(".oneplus-checkbox").first().attr("checked", false);

              }

            })

      </script><?php break;?>
    <?php case "editor": echo W('Common/Ueditor/editor',array($field['name'],$field['name'],$field['value'],$field['style']['width'],$field['style']['height'],$field['config'])); break;?>

    <?php case "textarea": ?><textarea name="<?php echo ($field["name"]); ?>" class="text input-5 form-control"><?php echo (htmlspecialchars($field["value"])); ?></textarea><?php break;?>

    <?php case "year": ?><input type="text" name="<?php echo ($field["name"]); ?>"  id="<?php echo ($field["name"]); ?>_time" class="text input-2 form-control" value="<?php echo ($field[value]); ?>" placeholder="请选择时间"/>

      <script type="text/javascript">

	Calendar.setup({

			weekNumbers: true,

		    inputField : "<?php echo ($field["name"]); ?>_time",

		    trigger    : "<?php echo ($field["name"]); ?>_time",

		    dateFormat: "%Y-%m-%d",

		    showTime: true,

		    minuteStep: 1,

		    onSelect   : function() {this.hide();}

	});

</script><?php break;?>

    <?php case "time": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>

      <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>

      <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text input-2 time form-control" value="<?php echo (time_format($field["value"])); ?>" placeholder="请选择时间"/><?php break;?>

    

    <!--添加城市选择（需安装城市联动插件,css样式不好处理排版有点怪）-->

    

    <?php case "city": ?><!--修正在编辑信息时无法正常显示已经保存的地区信息--> 

      <?php echo hook('J_China_City',array('province'=>$field['value']['0'],'city'=>$field['value']['1'],'district'=>$field['value']['2'],'community'=>$field['value']['3'])); break;?>

      

    <?php case "map": ?><!--修正在编辑信息时无法正常显示已经保存的地区信息--> 
    <?php echo hook('TencentMap',array('name'=>$field['name'],'value'=>$field['value'])); break;?>   

    <!--弹出窗口选择并返回值（目前只支持返回ID）开始--> 

    <?php case "dataselect": ?><input type="text" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"  class="text input-3 form-control" />

      <input class="btn" style="margin-left:10px" type="button" value="选择" onclick="openwin('<?php echo ($field["opt"]); ?>','600','500')">

      <script type="text/javascript">
						//弹出窗口
						function openwin(url,width,height){
						    var l=window.screen.width ;
						    var w= window.screen.height;
						    var al=(l-width)/2;
						    var aw=(w-height)/2;
						    var OpenWindow=window.open(url,"弹出窗口","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,width="+width+",height="+height+",top="+aw+",left="+al+"");
						    OpenWindow.focus();
						if(OpenWindow!=null){ //弹出窗口关闭事件
						//if(window.attachEvent) OpenWindow.attachEvent("onbeforeunload",   quickOut);
						if(window.attachEvent) OpenWindow.attachEvent("onunload",   quickOut);
						}
						}
						//关闭触发方法
						function quickOut()
						{
						alert("窗口已关闭");

						}
				 </script><?php break;?>

    

    <!--弹出窗口选择并返回值（目前只支持返回ID）结束-->

    

    <?php case "kanban": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value='<?php echo json_encode($field["value"]);?>'/>

      <div class="kanbans" id="<?php echo ($field["name"]); ?>">

        <?php foreach($field['value'] as $key =>$kanban){ ?>

        <div class="kanban panel" data-id="<?php echo ($kanban['data-id']); ?>" data-title="<?php echo ($kanban['title']); ?>">

          <div class="panel-heading"> <strong><?php echo ($kanban['title']); ?></strong> </div>

          <div class="panel-body">

            <div class="kanban-list">

              <?php if(is_array($kanban["items"])): $i = 0; $__LIST__ = $kanban["items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="kanban-item item" data-id="<?php echo ($vo["data-id"]); ?>" data-title="<?php echo ($vo["title"]); ?>"> <?php echo ($vo["title"]); ?> </div><?php endforeach; endif; else: echo "" ;endif; ?>

            </div>

          </div>

        </div>

        <?php } ?>

      </div>

      <script>

            $(function () {

                var flag = "<?php echo ($field["name"]); ?>"

                $('#<?php echo ($field["name"]); ?>').kanbans({'drop': function () {

                    var kanban =new Array();

                    $('.kanbans .kanban').each(function (index, element) {

                        if ($(element).data('id')) {

                            kanban[index] =  new Object();

                            kanban[index]['data-id'] =  $(element).data('id');

                            kanban[index]['title'] =  $(element).data('title');

                            kanban[index]['items'] =  new Array();

                            var obj = $(element).find('.item');

                            for (var i = 0; i < obj.length; i++) {

                                kanban[index]['items'][i] = new Object();

                                kanban[index]['items'][i]['data-id'] = $(obj[i]).data('id');

                                kanban[index]['items'][i]['title'] = $(obj[i]).data('title');

                            };

                        }

                    })

                    var kanban_str = JSON.stringify(kanban);

                    $('[name="'+flag+'"]').val(kanban_str);

                }

                })

            })

        </script><?php break;?>

    <?php case "chosen": ?><select name="<?php echo ($field["name"]); ?>[]" style="width: 400px" class="chosen-select" multiple="multiple">

        <?php if( key($field['opt']) === 0){ ?>

        <?php if(is_array($field['opt'])): $i = 0; $__LIST__ = $field['opt'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>

          <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

        <?php }else{ foreach($field['opt'] as $optgroupkey =>$optgroup){ ?>

        <optgroup label="<?php echo ($optgroupkey); ?>">

        <?php if(is_array($optgroup)): $i = 0; $__LIST__ = $optgroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>

          <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

        </optgroup>

        <?php } } ?>

      </select><?php break;?>

    <?php case "multiInput": $field['name'] = is_array($field['name'])?$field['name']:explode('|',$field['name']); foreach($field['name'] as $key=>$val){ ?>

      <?php switch($field['config'][$key]['type']): case "text": ?><input type="text" name="<?php echo ($val); ?>" value="<?php echo (htmlspecialchars($field['value'][$key])); ?>"  class=" pull-left text input-3 form-control" style="<?php echo ($field['config'][$key]['style']); ?>" placeholder="<?php echo ($field['config'][$key]['placeholder']); ?>"/><?php break;?>

        <?php case "select": ?><select name="<?php echo ($val); ?>" class="pull-left form-control" style="<?php echo ($field['config'][$key]['style']); ?>" >

            <?php foreach($field['config'][$key]['opt'] as $key_opt =>$option){ ?>

            <?php $selected = $field['value'][$key]==$key_opt ? 'selected' : ''; ?>

            <option value="<?php echo ($key_opt); ?>"<?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option>

            <?php } ?>

          </select><?php break; endswitch;?>

      <?php } break;?>

    <?php default: ?>

    <span style="color: #f00;">错误：未知字段类型 <?php echo ($field["type"]); ?></span>

    <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"/><?php endswitch;?>

  <?php if($field['subtitle']): ?><span class="check-tips">（<?php echo (htmlspecialchars($field["subtitle"])); ?>）</span><?php endif; ?></td> </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </table>
      <?php } ?>
      <div class="buttons">
        <?php if(is_array($buttonList)): $i = 0; $__LIST__ = $buttonList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$button): $mod = ($i % 2 );++$i;?><button <?php echo ($button["attr"]); ?>><?php echo ($button["title"]); ?></button>
          &nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
    </form>
  </div>  </div>

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

  <?php if($importDatetimePicker): ?><link href="/Application/Admin/Static/zui/lib/datetimepicker/datetimepicker.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Application/Admin/Static/zui/lib/datetimepicker/datetimepicker.min.js"></script> 
    <script>
            $('.time').datetimepicker({
                format: 'yyyy-mm-dd hh:ii',
                language: "zh-CN",
                minView: 2,
                autoclose: true
            });

            $('.time').change(function () {
                var fieldName = $(this).attr('data-field-name');
                var dateString = $(this).val();
                var date = new Date(dateString);
                var timestamp = date.getTime();
                $('[name=' + fieldName + ']').val(Math.floor(timestamp / 1000));
            });
        </script><?php endif; ?>
  <?php if($importCheckBox): ?><script>
            $(function () {
                function implode(x, list) {
                    var result = "";
                    for (var i = 0; i < list.length; i++) {
                        if (result == "") {
                            result += list[i];
                        } else {
                            result += ',' + list[i];
                        }
                    }
                    return result;
                }

                $('.oneplus-checkbox').change(function (e) {
                    var fieldName = $(this).attr('data-field-name');
                    var checked = $('.oneplus-checkbox[data-field-name=' + fieldName + ']:checked');
                    var result = [];
                    for (var i = 0; i < checked.length; i++) {
                        var checkbox = $(checked.get(i));
                        result.push(checkbox.attr('value'));
                    }
                    result = implode(',', result);
                    $('.oneplus-checkbox-hidden[data-field-name=' + fieldName + ']').val(result);
                });
            })
        </script><?php endif; ?>
  <script type="text/javascript">
        $(function () {
            $('.group_nav li a').click(function () {
                $('.group_list').hide();
                $('.group_list').eq($(".group_nav li a").index(this)).show();
                $('.group_nav li').removeClass('active');
                $(this).parent().addClass('active');
            })
        })
        Think.setValue("type", <?php echo ((isset($info["type"]) && ($info["type"] !== ""))?($info["type"]):0); ?>);
        Think.setValue("group", <?php echo ((isset($info["group"]) && ($info["group"] !== ""))?($info["group"]):0); ?>);
        //导航高亮
        highlight_subnav('<?php echo U('Config / index');?>');
    </script>
  <link type="text/css" rel="stylesheet" href="/Public/js/ext/magnific/magnific-popup.css"/>
  <script type="text/javascript" src="/Public/js/ext/magnific/jquery.magnific-popup.min.js"></script> 
  <script type="text/javascript" charset="utf-8" src="/Public/js/ext/webuploader/js/webuploader.js"></script>
  <link href="/Public/js/ext/webuploader/css/webuploader.css" type="text/css" rel="stylesheet">
  <script>
        $(document).ready(function () {
            $('.popup-gallery').each(function () { // the containers for all your galleries
                $(this).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    tLoading: '正在载入 #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1] // Will preload 0 - before current, and 1 after the current image

                    },
                    image: {
                        tError: '<a href="%url%">图片 #%curr%</a> 无法被载入.',
                        titleSrc: function (item) {
                            /*           return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';*/
                            return '';
                        },
                        verticalFit: true
                    }
                });
            });
        });
    </script> 



</body>
</html>