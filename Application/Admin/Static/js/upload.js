

$("[data-toggle=upimg]").each(function(){
    var name = $(this).attr('name');
	var num = $(this).attr('maxnum');
	var value = $(this).attr('value');
	var title = $(this).attr('title');
	var src= $(this).attr('src');
	var id= $(this).attr('id');
	
	if(!src){
		src='Application/Admin/Static/images/picurl_up.png';
	}
	$(this).error(function(){
		src='Application/Admin/Static/images/picurl_err.png';
	});
	
	var str='';
	str += '<div class="UpimgBox" style="margin:0px"> <input type="hidden" name="'+name+'" value="'+value+'" id="'+id+'_input"> ';
	str += '<img id="'+id+'_view" data-remote="index.php?s=/Admin/File/images/num/'+num+'/name/'+id+'.html"  width="173" src="'+src+'"  data-toggle="modal" data-title="'+title+'"/>';
	str += '<span>';
	str += '<button class="cutimg" type="button" onclick=Cutimg("'+id+'")>剪裁图片</button>';
	str += '<button class="delimg" type="button" onclick=DelFile("'+id+'")>清除图片</button>';
	str += '</span>';
	str += '</div>';

	$(this).html(str);

  });
  
  
  
$("[data-toggle=upfield]").each(function(){
    var name = $(this).attr('name');
	var num = $(this).attr('maxnum');
	var value = $(this).attr('value');
	var title = $(this).attr('title');
	var src= $(this).attr('src');
	var id= $(this).attr('id');
	
	if(!src){
		src='Application/Admin/Static/images/picurl_up.png';
	}else{
		src='Public/images/file_ico/'+src+'.png';
	}
	$(this).error(function(){
		src='Application/Admin/Static/images/picurl_err.png';
	});
	
	var str='';
	str += '<div class="UpimgBox" style="margin:0px"> <input type="hidden" name="'+name+'" value="'+value+'" id="'+id+'_input"> ';
	str += '<img id="'+id+'_view" data-remote="index.php?s=/Admin/File/files/num/'+num+'/name/'+id+'.html"  width="173" src="'+src+'"  data-toggle="modal" data-title="'+title+'"/>';
	str += '<span>';
	str += '<button class="delimg" type="button" onclick=DelFile("'+id+'") style="width:100%">清除文件</button>';
	str += '</span>';
	str += '</div>';

	$(this).html(str);

  });
  
  
   
  
  //剪裁图片
function Cutimg(name, url) {
	var input_val = $('#'+name+'_input').val();
	var img_src = $('#'+name+'_view').attr('src');
	if(!input_val || input_val==0){
		updateAlert('您还没有上传图片', 'error');
		return false;
	}
	 (new ModalTrigger({title:'剪裁图像',width:'1000',height:'auto',iframe:'index.php?s=/Admin/File/cutimg/cover_id/'+input_val+'.html'})).show();
	$('#triggerModal').on('hide.zui.modal', function()
	{
  		$('#'+name+'_view').attr('src',img_src+'?'+Math.random()+'');//重载图片
	})
}
  
  //移除文件
function DelFile(name) {
	var input_val = $('#'+name+'_input').val();
	if(!input_val || input_val==0){
		updateAlert('已经清除了', 'error');
		return false;
	}
	$('#'+name+'_input').val('');
	$('#'+name+'_view').animate({top:'-80px',height:'115px',opacity:'0'},"1000" ,function() {
       $(this).attr('src','Application/Admin/Static/images/picurl_up.png').css({'top':'0px','opacity':'1'});
	   $('#'+name+'_view').css('height','auto');
	});
}



$(document).on('click','.ajax-cutimg', function() {
        var target, query, form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;
        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            form = $('.' + target_form);

            query = form.serialize();
			$(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
            $.post(target, query).success(function (data) {
                if (data.status == 1) {
					
					/*为iframe模态框关闭设置*/
					if(data.url=='CloseModal'){
						   updateAlert(data.info, 'success');
						   setTimeout(function () {  window.parent.$('#triggerModal').modal('toggle', 'false'); 	}, 1500); return false;
					}
					
                
                } else {
                    updateAlert(data.info);
              
                }
            });
        }
        return false;
    });
	
  
  
/* ========================================================================
 * Bootstrap: button.js v3.3.4
 * http://getbootstrap.com/javascript/#buttons
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+function($){var Button=function(element,options){this.$element=$(element);this.options=$.extend({},Button.DEFAULTS,options);this.isLoading=false};Button.VERSION="3.3.4";Button.DEFAULTS={loadingText:"loading..."};Button.prototype.setState=function(state){var d="disabled";var $el=this.$element;var val=$el.is("input")?"val":"html";var data=$el.data();state=state+"Text";if(data.resetText==null){$el.data("resetText",$el[val]())}setTimeout($.proxy(function(){$el[val](data[state]==null?this.options[state]:data[state]);if(state=="loadingText"){this.isLoading=true;$el.addClass(d).attr(d,d)}else{if(this.isLoading){this.isLoading=false;$el.removeClass(d).removeAttr(d)}}},this),0)};Button.prototype.toggle=function(){var changed=true;var $parent=this.$element.closest('[data-toggle="buttons"]');if($parent.length){var $input=this.$element.find("input");if($input.prop("type")=="radio"){if($input.prop("checked")&&this.$element.hasClass("active")){changed=false}else{$parent.find(".active").removeClass("active")}}if(changed){$input.prop("checked",!this.$element.hasClass("active")).trigger("change")}}else{this.$element.attr("aria-pressed",!this.$element.hasClass("active"))}if(changed){this.$element.toggleClass("active")}};function Plugin(option){return this.each(function(){var $this=$(this);var data=$this.data("bs.button");var options=typeof option=="object"&&option;if(!data){$this.data("bs.button",(data=new Button(this,options)))}if(option=="toggle"){data.toggle()}else{if(option){data.setState(option)}}})}var old=$.fn.button;$.fn.button=Plugin;$.fn.button.Constructor=Button;$.fn.button.noConflict=function(){$.fn.button=old;return this};$(document).on("click.bs.button.data-api",'[data-toggle^="button"]',function(e){var $btn=$(e.target);if(!$btn.hasClass("btn")){$btn=$btn.closest(".btn")}Plugin.call($btn,"toggle");e.preventDefault()}).on("focus.bs.button.data-api blur.bs.button.data-api",'[data-toggle^="button"]',function(e){$(e.target).closest(".btn").toggleClass("focus",/^focus(in)?$/.test(e.type))})}(jQuery);