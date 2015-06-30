
    //ajax get请求
    $(document).on('click','.ajax-get', function() {
        var target,e;
        var that = this;
        if ($(this).hasClass('confirm')) {
			
			    var confirm_info = $(that).attr('confirm-info');
				var dia=$.dialog({ title:'温馨提示', content:confirm_info, button:["确认","取消"]}); 
        }
        if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
			
			dia.on("dialog:action",function(e){
			if(e.index==1){
				return false;
			}
		    var el=$.loading({  content:'努力加载中...',  }) 
			
           	$.ajax({type:'get',url:target,success:function(data){
                if (data.status == 1) {
                    if (data.url) {
						el2=$.tips({ content:data.info,  stayTime:2000,  type:"success"  }); 
						    setTimeout(function () {
                          		  location.href = data.url;
                   			 }, 1000);
                    } else {
                        el2=$.tips({ content:data.info,  stayTime:2000,  type:"success"  }); 
                    }
                   
                } else {
                    el2=$.tips({ content:data.info,  stayTime:2000,  type:"warn"  }); 
                   
                }
				 el.loading("hide");
			}});
			});
 
        }
        return false;
    });

    //ajax post submit请求
   	$(document).on('click','.ajax-post', function() {
        var target, query, form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;		
			if(!target_form){
				target_form = $(this).closest('form').attr('class');				
			}
			form = $('.' + target_form);
		
        if (($(this).attr('type') == 'submit') ||  (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
			
            if ($(this).attr('hide-data') === 'true') {//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            } else if (form.get(0) == undefined) {
				el3=$.tips({ content:'没有可操作数据',  stayTime:2000,  type:"warn"  }); 
                return false;
            } else if (form.get(0).nodeName == 'FORM') {
                if ($(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
					var dia=$.dialog({ title:'温馨提示', content:confirm_info, button:["确认","取消"]});
				}
                if ($(this).attr('url') !== undefined) {
                    target = $(this).attr('url');
                } else {
                    target = form.get(0).action;
                }
                query = form.serialize();
            } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
                form.each(function (k, v) {
                    if (v.type == 'checkbox' && v.checked == true) {
                        nead_confirm = true;
                    }
                })
                if (nead_confirm && $(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
					var dia=$.dialog({ title:'温4馨提示', content:confirm_info, button:["确认","取消"]});					
                }
                query = form.serialize();
            } else {
                if ($(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
					var dia=$.dialog({ title:'2温馨提示', content:confirm_info, button:["确认","取消"]});		
				}
                query = form.find('input,select,textarea').serialize();
            }
			
			dia.on("dialog:action",function(e){
			if(e.index==1){
				return false;
			}
		    var el=$.loading({  content:'努力加载中...',  }) 
			setTimeout(function(){ 
			$.ajax({type:'post',url:target,data:query,success:function(data){
                if (data.status == 1) {
                    if (data.url) {
                        el2=$.tips({ content:data.info,  stayTime:2000,  type:"success"  });
                    } else {
                        el2=$.tips({ content:data.info,  stayTime:2000,  type:"success"  }); 
                    } 
                } else {
                     el3=$.tips({ content:data.info,  stayTime:2000,  type:"warn"  }); 
                }
				 el.loading("hide");
			}});
			 }, 500);
		  });
        }
        return false;
    });
	
	