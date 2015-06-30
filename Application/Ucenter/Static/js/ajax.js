
    //ajax get请求
$(document).on('click','.ajax-get', function() {
        var target;
        var that = this;
        if ($(this).hasClass('confirm')) {
            if (!confirm('确认要执行该操作吗?')) {
                return false;
            }
        }
        if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            $.get(target).success(function (data) {
                if (data.status == 1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~', 'success');
                    } else {
                        updateAlert(data.info, 'success');
                    }
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else if ($(that).hasClass('no-refresh')) {
                            $('#top-alert').find('button').click();
                        } else {
                            location.reload();
                        }
                    }, 3000);
                } else {
                    updateAlert(data.info);
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else {
                            $('#top-alert').find('button').click();
                        }
                    }, 15000);
                }
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
        
        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
          
            if ($(this).attr('hide-data') === 'true') {//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            } else if (form.get(0) == undefined) {
                updateAlert('没有可操作数据。','danger');
                return false;
            } else if (form.get(0).nodeName == 'FORM') {
                if ($(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
                    confirm_info=confirm_info?confirm_info:"确认要执行该操作吗?";
                    if (!confirm(confirm_info)) {
                        return false;
                    }
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
                    confirm_info=confirm_info?confirm_info:"确认要执行该操作吗?";
                    if (!confirm(confirm_info)) {
                        return false;
                    }
                }
                query = form.serialize();
            } else {
                if ($(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
                    confirm_info=confirm_info?confirm_info:"确认要执行该操作吗?";
                    if (!confirm(confirm_info)) {
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            //$(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true).prepend('<i class="am-icon-spinner am-icon-spin"></i> ');

            $.post(target, query).success(function (data) {
                if (data.status == 1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~', 'success');
                    } else {
                        updateAlert(data.info, 'success');
                    }
                    setTimeout(function () {
                        if (data.url) {
                           // location.href = data.url;
                        } else if ($(that).hasClass('no-refresh')) {
                           // $('#top-alert').find('button').click();
                           // $(that).removeClass('disabled').prop('disabled', false).find('i').remove();
                        
                        } else {
                            
                           // location.reload();
                        }
                    }, 1500);
                } else {
                    updateAlert(data.info);
                    setTimeout(function () {
                        if (data.url) {

                            //location.href = data.url;
                        } else {
                           // $('#top-alert').find('button').click();
                           // $(that).removeClass('disabled').prop('disabled', false).find('i').remove();
                        }
                    }, 1500);
                }
            });
        }
        return false;
    });
    window.updateAlert = function (text, c) {

        if(typeof c !='undefined')
        {
            var msg = $.messager.show(text, {placement: 'bottom',type:c});
        }else {
            var msg = $.messager.show(text, {placement: 'bottom'})
        }
        msg.show();
    };
