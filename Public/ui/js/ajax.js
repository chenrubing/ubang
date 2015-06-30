
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
                        updateAlert(data.info + '   请稍等~~', 'success');
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
            $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true).prepend('<i class="am-icon-spinner am-icon-spin"></i> ');

            $.post(target, query).success(function (data) {
                if (data.status == 1) {
                    if (data.url) {
                        updateAlert(data.info + ' 请稍等~~', 'success');
                    } else {
                        updateAlert(data.info, 'success');
                    }
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else if ($(that).hasClass('no-refresh')) {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false).find('i').remove();
						
                        } else {
							
                             $(that).removeClass('disabled').prop('disabled', false).find('i').remove();
                        }
                    }, 1500);
                } else {
                    updateAlert(data.info);
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false).find('i').remove();
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

/* ========================================================================
 * Bootstrap: transition.js v3.2.0
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      WebkitTransition : 'webkitTransitionEnd',
      MozTransition    : 'transitionend',
      OTransition      : 'oTransitionEnd otransitionend',
      transition       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }

    return false // explicit for ie8 (  ._.)
  }

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false
    var $el = this
    $(this).one('bsTransitionEnd', function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()

    if (!$.support.transition) return

    $.event.special.bsTransitionEnd = {
      bindType: $.support.transition.end,
      delegateType: $.support.transition.end,
      handle: function (e) {
        if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
      }
    }
  })

}(jQuery);

/* ========================================================================
 * ZUI: string.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function()
{
    'use strict';

    String.prototype.format = function(args)
    {
        var result = this;
        if (arguments.length > 0)
        {
            var reg;
            if (arguments.length == 1 && typeof(args) == "object")
            {
                for (var key in args)
                {
                    if (args[key] !== undefined)
                    {
                        reg = new RegExp("({" + key + "})", "g");
                        result = result.replace(reg, args[key]);
                    }
                }
            }
            else
            {
                for (var i = 0; i < arguments.length; i++)
                {
                    if (arguments[i] !== undefined)
                    {
                        reg = new RegExp("({[" + i + "]})", "g");
                        result = result.replace(reg, arguments[i]);
                    }
                }
            }
        }
        return result;
    };


    /**
     * Judge the string is a integer number
     *
     * @access public
     * @return bool
     */
    String.prototype.isNum = function(s)
    {
        if (s !== null)
        {
            var r, re;
            re = /\d*/i;
            r = s.match(re);
            return (r == s) ? true : false;
        }
        return false;
    };
})();


/* ========================================================================
 * Bootstrap: zmodal.js v3.2.0
 * http://getbootstrap.com/javascript/#zmodals
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ========================================================================
 * Updates in ZUI：
 * 1. changed event namespace to *.zui.zmodal
 * 2. added position option to ajust poisition of zmodal
 * 3. added event 'escaping.zui.zmodal' with an param 'esc' to judge the esc
 *    key down
 * ======================================================================== */

+ function($){
    'use strict';

    // zmodal CLASS DEFINITION
    // ======================

    var zmodal = function(element, options)
    {
        this.options = options
        this.$body = $(document.body)
        this.$element = $(element)
        this.$backdrop =
            this.isShown = null
        this.scrollbarWidth = 0

        if (this.options.remote)
        {
            this.$element
                .find('.zmodal-content')
                .load(this.options.remote, $.proxy(function()
                {
                    this.$element.trigger('loaded.zui.zmodal')
                }, this))
        }
    }

    zmodal.VERSION = '3.2.0'

    zmodal.TRANSITION_DURATION = 300
    zmodal.BACKDROP_TRANSITION_DURATION = 150

    zmodal.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true,
        position: 'fit' // 'center' or '40px' or '10%'
    }

    zmodal.prototype.toggle = function(_relatedTarget, position)
    {
        return this.isShown ? this.hide() : this.show(_relatedTarget, position)
    }

    zmodal.prototype.ajustPosition = function(position)
    {
        if (typeof position === 'undefined') position = this.options.position;
        if (typeof position === 'undefined') return;
        var $dialog = this.$element.find('.zmodal-dialog');
        var half = Math.max(0, ($(window).height() - $dialog.outerHeight()) / 2);
        var pos = position == 'fit' ? (half * 2 / 3) : (position == 'center' ? half : position);
        $dialog.css('margin-top', pos);
    }

    zmodal.prototype.show = function(_relatedTarget, position)
    {
        var that = this
        var e = $.Event('show.zui.zmodal',
        {
            relatedTarget: _relatedTarget
        })

        this.$element.trigger(e)

        if (this.isShown || e.isDefaultPrevented()) return

        this.isShown = true

        this.checkScrollbar()
        this.$body.addClass('zmodal-open')

        this.setScrollbar()
        this.escape()

        this.$element.on('click.dismiss.zui.zmodal', '[data-dismiss="zmodal"]', $.proxy(this.hide, this))

        this.backdrop(function()
        {
            var transition = $.support.transition && that.$element.hasClass('fade')

            if (!that.$element.parent().length)
            {
                that.$element.appendTo(that.$body) // don't move zmodals dom position
            }

            that.$element
                .show()
                .scrollTop(0)

            if (transition)
            {
                that.$element[0].offsetWidth // force reflow
            }

            that.$element
                .addClass('in')
                .attr('aria-hidden', false)

            that.ajustPosition(position);

            that.enforceFocus()

            var e = $.Event('shown.zui.zmodal',
            {
                relatedTarget: _relatedTarget
            })

            transition ?
                that.$element.find('.zmodal-dialog') // wait for zmodal to slide in
            .one('bsTransitionEnd', function()
            {
                that.$element.trigger('focus').trigger(e)
            })
                .emulateTransitionEnd(zmodal.TRANSITION_DURATION) :
                that.$element.trigger('focus').trigger(e)
        })
    }

    zmodal.prototype.hide = function(e)
    {
        if (e) e.preventDefault()

        e = $.Event('hide.zui.zmodal')

        this.$element.trigger(e)

        if (!this.isShown || e.isDefaultPrevented()) return

        this.isShown = false

        this.$body.removeClass('zmodal-open')

        this.resetScrollbar()
        this.escape()

        $(document).off('focusin.zui.zmodal')

        this.$element
            .removeClass('in')
            .attr('aria-hidden', true)
            .off('click.dismiss.zui.zmodal')

        $.support.transition && this.$element.hasClass('fade') ?
            this.$element
            .one('bsTransitionEnd', $.proxy(this.hidezmodal, this))
            .emulateTransitionEnd(zmodal.TRANSITION_DURATION) :
            this.hidezmodal()
    }

    zmodal.prototype.enforceFocus = function()
    {
        $(document)
            .off('focusin.zui.zmodal') // guard against infinite focus loop
        .on('focusin.zui.zmodal', $.proxy(function(e)
        {
            if (this.$element[0] !== e.target && !this.$element.has(e.target).length)
            {
                this.$element.trigger('focus')
            }
        }, this))
    }

    zmodal.prototype.escape = function()
    {
        if (this.isShown && this.options.keyboard)
        {
            $(document).on('keydown.dismiss.zui.zmodal', $.proxy(function(e)
            {

                if (e.which == 27)
                {
                    var et = $.Event('escaping.bs.zmodal')
                    var result = this.$element.triggerHandler(et, 'esc')
                    if (result != undefined && (!result)) return
                    this.hide()
                }
            }, this))
        }
        else if (!this.isShown)
        {
            $(document).off('keydown.dismiss.zui.zmodal')
        }
    }

    zmodal.prototype.hidezmodal = function()
    {
        var that = this
        this.$element.hide()
        this.backdrop(function()
        {
            that.$element.trigger('hidden.zui.zmodal')
        })
    }

    zmodal.prototype.removeBackdrop = function()
    {
        this.$backdrop && this.$backdrop.remove()
        this.$backdrop = null
    }

    zmodal.prototype.backdrop = function(callback)
    {
        var that = this
        var animate = this.$element.hasClass('fade') ? 'fade' : ''

        if (this.isShown && this.options.backdrop)
        {
            var doAnimate = $.support.transition && animate

            this.$backdrop = $('<div class="zmodal-backdrop ' + animate + '" />')
                .appendTo(this.$body)

            this.$element.on('mousedown.dismiss.zui.zmodal', $.proxy(function(e)
            {
                if (e.target !== e.currentTarget) return
                this.options.backdrop == 'static' ? this.$element[0].focus.call(this.$element[0]) : this.hide.call(this)
            }, this))

            if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

            this.$backdrop.addClass('in')

            if (!callback) return

            doAnimate ?
                this.$backdrop
                .one('bsTransitionEnd', callback)
                .emulateTransitionEnd(zmodal.BACKDROP_TRANSITION_DURATION) :
                callback()

        }
        else if (!this.isShown && this.$backdrop)
        {
            this.$backdrop.removeClass('in')

            var callbackRemove = function()
            {
                that.removeBackdrop()
                callback && callback()
            }
            $.support.transition && this.$element.hasClass('fade') ?
                this.$backdrop
                .one('bsTransitionEnd', callbackRemove)
                .emulateTransitionEnd(zmodal.BACKDROP_TRANSITION_DURATION) :
                callbackRemove()

        }
        else if (callback)
        {
            callback()
        }
    }

    zmodal.prototype.checkScrollbar = function()
    {
        if (document.body.clientWidth >= window.innerWidth) return
        this.scrollbarWidth = this.scrollbarWidth || this.measureScrollbar()
    }

    zmodal.prototype.setScrollbar = function()
    {
        var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
        if (this.scrollbarWidth) this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
    }

    zmodal.prototype.resetScrollbar = function()
    {
        this.$body.css('padding-right', '')
    }

    zmodal.prototype.measureScrollbar = function()
    { // thx walsh
        var scrollDiv = document.createElement('div')
        scrollDiv.className = 'zmodal-scrollbar-measure'
        this.$body.append(scrollDiv)
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
        this.$body[0].removeChild(scrollDiv)
        return scrollbarWidth
    }


    // zmodal PLUGIN DEFINITION
    // =======================

    function Plugin(option, _relatedTarget, position)
    {
        return this.each(function()
        {
            var $this = $(this)
            var data = $this.data('zui.zmodal')
            var options = $.extend(
            {}, zmodal.DEFAULTS, $this.data(), typeof option == 'object' && option)

            if (!data) $this.data('zui.zmodal', (data = new zmodal(this, options)))
            if (typeof option == 'string') data[option](_relatedTarget, position)
            else if (options.show) data.show(_relatedTarget, position)
        })
    }

    var old = $.fn.zmodal

    $.fn.zmodal = Plugin
    $.fn.zmodal.Constructor = zmodal


    // zmodal NO CONFLICT
    // =================

    $.fn.zmodal.noConflict = function()
    {
        $.fn.zmodal = old
        return this
    }


    // zmodal DATA-API
    // ==============

    $(document).on('click.zui.zmodal.data-api', '[data-toggle="zmodal"]', function(e)
    {
        var $this = $(this)
        var href = $this.attr('href')
        var $target = null

        try
        {
            // strip for ie7
            $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
        }
        catch (ex)
        {
            return
        }
        if (!$target.length) return;
        var option = $target.data('zui.zmodal') ? 'toggle' : $.extend(
        {
            remote: !/#/.test(href) && href
        }, $target.data(), $this.data())

        if ($this.is('a')) e.preventDefault()

        $target.one('show.zui.zmodal', function(showEvent)
        {
            // only register focus restorer if zmodal will actually get shown
            if (showEvent.isDefaultPrevented()) return
            $target.one('hidden.zui.zmodal', function()
            {
                $this.is(':visible') && $this.trigger('focus')
            })
        })
        Plugin.call($target, option, this, $this.data('position'))
    })

}(jQuery);

/* ========================================================================
 * ZUI: zmodal.trigger.js v1.2.0
 * http://zui.sexy/docs/javascript.html#zmodals
 * Licensed under MIT
 * ======================================================================== */


(function($)
{
    'use strict';

    if (!$.fn.zmodal) throw new Error('zmodal trigger requires zmodal.js');

    // ONCE zmodal CLASS DEFINITION
    // ======================
    var zmodalTrigger = function(options)
    {
        options = $.extend(
        {}, zmodalTrigger.DEFAULTS, $.zmodalTriggerDefaults, options);
        this.isShown = false;
        this.options = options;
      

        // todo: handle when: options.show = true
    };

    zmodalTrigger.DEFAULTS = {
        type: 'custom',
        width: null, // number, css definition
        size: null, // 'md', 'sm', 'lg', 'fullscreen'
        height: 'auto',
        icon: null,
        name: 'triggerzmodal',
        fade: true,
        position: 'fit',
        showHeader: true,
        delay: 0,
        backdrop: true,
        keyboard: true
    };

    zmodalTrigger.prototype.init = function(options)
    {
        var that = this;
        if (options.url)
        {
            if (!options.type || (options.type != 'ajax' && options.type != 'iframe'))
            {
                options.type = 'ajax';
            }
        }
        if (options.remote)
        {
            options.type = 'ajax';
            if (typeof options.remote === 'string') options.url = options.remote;
        }
        else if (options.iframe)
        {
            options.type = 'iframe';
            if (typeof options.iframe === 'string') options.url = options.iframe;
        }
        else if (options.custom)
        {
            options.type = 'custom';
            if (typeof options.custom === 'string')
            {
                var $doms;
                try
                {
                    $doms = $(options.custom);
                }
                catch (e)
                {}

                if ($doms && $doms.length)
                {
                    options.custom = $doms;
                }
                else if ($.isFunction(window[options.custom]))
                {
                    options.custom = window[options.custom];
                }
            }
        }

        var $zmodal = $('#' + options.name);
        if ($zmodal.length)
        {
            if (!this.isShown) $zmodal.off('.zui.zmodal');
            $zmodal.remove();
        }
        $zmodal = $('<div id="' + options.name + '" class="zmodal zmodal-trigger"><div class="am-icon-spinner am-icon-spin loader"></div><div class="zmodal-dialog"><div class="zmodal-content"><div class="zmodal-header"><button class="close" data-dismiss="zmodal">×</button><h4 class="zmodal-title"><i class="zmodal-icon"></i> <span class="zmodal-title-name"></span></h4></div><div class="zmodal-body"></div></div></div></div>').appendTo('body');

        var bindEvent = function(optonName, eventName)
        {
            var handleFunc = options[optonName];
            if ($.isFunction(handleFunc)) $zmodal.on(eventName + '.zui.zmodal', handleFunc);
        };
        bindEvent('onShow', 'show');
        bindEvent('shown', 'shown');
        bindEvent('onHide', 'hide');
        bindEvent('hidden', 'hidden');
        bindEvent('loaded', 'loaded');

        $zmodal.on('shown.zui.zmodal', function()
        {
            that.isShown = true;
        }).on('hidden.zui.zmodal', function()
        {
            that.isShown = false;
        });

        this.$zmodal = $zmodal;
        this.$dialog = $zmodal.find('.zmodal-dialog');
    };

    zmodalTrigger.prototype.show = function(option)
    {
        var options = $.extend(
        {}, this.options, option);
        this.init(options);
        var that = this,
            $zmodal = this.$zmodal,
            $dialog = this.$dialog,
            custom = options.custom;
        var $body = $dialog.find('.zmodal-body').css('padding', ''),
            $header = $dialog.find('.zmodal-header'),
            $content = $dialog.find('.zmodal-content');

        $zmodal.toggleClass('fade', options.fade)
            .addClass(options.cssClass)
            .toggleClass('zmodal-md', options.size === 'md')
            .toggleClass('zmodal-sm', options.size === 'sm')
            .toggleClass('zmodal-lg', options.size === 'lg')
            .toggleClass('zmodal-fullscreen', options.size === 'fullscreen')
            .toggleClass('zmodal-loading', !this.isShown);
        $header.toggle(options.showHeader);
        $header.find('.zmodal-icon').attr('class', 'zmodal-icon icon-' + options.icon);
        $header.find('.zmodal-title-name').html(options.title || '');
        if (options.size && options.size === 'fullscreen')
        {
            options.width = '';
            options.height = '';
        }

        var readyToShow = function(delay)
        {
            if (typeof delay === 'undefined') delay = 300;
            // $zmodal.removeClass('fade');
            setTimeout(function()
            {
                $dialog = $zmodal.find('.zmodal-dialog');
                if (options.width && options.width != 'auto')
                {
                    $dialog.css('width', options.width);
                }
                if (options.height && options.height != 'auto') $dialog.css('height', options.height);
                that.ajustPosition(options.position);
                // if(options.fade) $zmodal.addClass('fade');
                $zmodal.removeClass('zmodal-loading');

                if (options.type != 'iframe')
                {
                    $dialog.off('resize.zui.zmodaltrigger').on('resize.zui.zmodaltrigger', function()
                    {
                        that.ajustPosition();
                    });
                }
            }, delay);
        };

        if (options.type === 'custom' && custom)
        {
            if ($.isFunction(custom))
            {
                var customContent = custom(
                {
                    zmodal: $zmodal,
                    options: options,
                    zmodalTrigger: that,
                    ready: readyToShow
                });
                if (typeof customContent === 'string')
                {
                    $body.html(customContent);
                    readyToShow();
                }
            }
            else if (custom instanceof $)
            {
                $body.html($('<div>').append(custom.clone()).html());
                readyToShow();
            }
            else
            {
                $body.html(custom);
                readyToShow();
            }
        }
        else if (options.url)
        {
            $zmodal.attr('ref', options.url);
            if (options.type === 'iframe')
            {
                $zmodal.addClass('zmodal-iframe');
                this.firstLoad = true;
                var iframeName = 'iframe-' + options.name;
                $header.detach();
                $body.detach();
                $content.empty().append($header).append($body);
                $body.css('padding', 0)
                    .html('<iframe id="' + iframeName + '" name="' + iframeName + '" src="' + options.url + '" frameborder="no" allowtransparency="true" scrolling="auto" style="width: 100%; height: 100%; left: 0px;"></iframe>');

                if (options.waittime > 0)
                {
                    that.waitTimeout = setTimeout(readyToShow, options.waittime);
                }

                var frame = document.getElementById(iframeName);
                frame.onload = frame.onreadystatechange = function()
                {
                    $zmodal.attr('ref', frame.contentWindow.location.href);
                    if (that.firstLoad) $zmodal.addClass('zmodal-loading');
                    if (this.readyState && this.readyState != 'complete') return;
                    that.firstLoad = false;

                    if (options.waittime > 0)
                    {
                        clearTimeout(that.waitTimeout);
                    }

                    try
                    {
                        var frame$ = window.frames[iframeName].$;
                        if (frame$ && options.height === 'auto' && options.size != 'fullscreen')
                        {
                            // todo: update iframe url to ref attribute
                            var $framebody = frame$('body').addClass('body-zmodal');
                            var ajustFrameSize = function()
                            {
                                $zmodal.removeClass('fade');
                                var height = $framebody.outerHeight();
                                $body.css('height', height);
                                if (options.fade) $zmodal.addClass('fade');
                                readyToShow();
                            };

                            $zmodal.callEvent('loaded.zui.zmodal',
                            {
                                zmodalType: 'iframe'
                            });

                            setTimeout(ajustFrameSize, 100);

                            $framebody.off('resize.zui.zmodaltrigger').on('resize.zui.zmodaltrigger', ajustFrameSize);
                        }

                        frame$.extend(
                        {
                            closezmodal: that.close
                        });
                    }
                    catch (e)
                    {
                        readyToShow();
                    }
                };
            }
            else
            {
                $.get(options.url, function(data)
                {
                    var $data = $(data);
                    if ($data.hasClass('zmodal-dialog'))
                    {
                        $dialog.replaceWith($data);
                    }
                    else if ($data.hasClass('zmodal-content'))
                    {
                        $dialog.find('.zmodal-content').replaceWith($data);
                    }
                    else
                    {
                        $body.wrapInner($data);
                    }
                    $zmodal.callEvent('loaded.zui.zmodal',
                    {
                        zmodalType: 'ajax'
                    });
                    readyToShow();
                });
            }
        }

        $zmodal.zmodal(
        {
            show: 'show',
            backdrop: options.backdrop,
            keyboard: options.keyboard
        });
    };

    zmodalTrigger.prototype.close = function(callback, redirect)
    {
        this.$zmodal.on('hidden.zui.zmodal', function()
        {
            if ($.isFunction(callback)) callback();

            if (typeof redirect === 'string')
            {
                if (redirect === 'this') window.location.reload();
                else window.location = redirect;
            }
        }).zmodal('hide');
    };

    zmodalTrigger.prototype.toggle = function(options)
    {
        if (this.isShown) this.close();
        else this.show(options);
    };

    zmodalTrigger.prototype.ajustPosition = function(position)
    {
        this.$zmodal.zmodal('ajustPosition', position || this.options.position);
    };

    window.zmodalTrigger = zmodalTrigger;
    window.zmodalTrigger = new zmodalTrigger();

    $.fn.zmodalTrigger = function(option, settings)
    {
        return $(this).each(function()
        {
            var $this = $(this);
            var data = $this.data('zui.zmodaltrigger'),
                options = $.extend(
                {
                    title: $this.attr('title') || $this.text(),
                    url: $this.attr('href'),
                    type: $this.hasClass('iframe') ? 'iframe' : ''
                }, $this.data(), $.isPlainObject(option) && option);
            if (!data) $this.data('zui.zmodaltrigger', (data = new zmodalTrigger(options)));
            if (typeof option == 'string') data[option](settings);
            else if (options.show) data.show(settings);

            $this.on((options.trigger || 'click') + '.toggle.zui.zmodaltrigger', function(e)
            {
                data.toggle(options);
                if ($this.is('a')) e.preventDefault();
            });
        });
    };

    var old = $.fn.zmodal;
    $.fn.zmodal = function(option, settings)
    {
        return $(this).each(function()
        {
            var $this = $(this);
            if ($this.hasClass('zmodal')) old.call($this, option, settings);
            else $this.zmodalTrigger(option, settings);
        });
    };

    function getzmodal(zmodal)
    {
        var zmodalType = typeof(zmodal);
        if (zmodalType === 'undefined')
        {
            zmodal = $('.zmodal.zmodal-once');
        }
        else if (zmodalType === 'string')
        {
            zmodal = $('#' + zmodal).replace('##', '#');
        }
        if (zmodal && (zmodal instanceof $)) return zmodal;
        return null;
    }

    window.closezmodal = function(callback, redirect, zmodal)
    {
        zmodal = getzmodal(zmodal);
        if (zmodal && zmodal.length)
        {
            zmodal.each(function()
            {
                $(this).data('zui.zmodaltrigger').close(callback, redirect);
            });
        }
    };

    window.ajustzmodalPosition = function(position, zmodal)
    {
        zmodal = getzmodal(zmodal);
        if (zmodal && zmodal.length)
        {
            zmodal.zmodal('ajustPosition', position);
        }
    };

    $.extend(
    {
        closezmodal: window.closezmodal,
        ajustzmodalPosition: window.ajustzmodalPosition
    });

    $(document).on('click.zui.zmodaltrigger.data-api', '[data-toggle="zmodal"]', function(e)
    {
        var $this = $(this);
        var href = $this.attr('href');
        var $target = null;
        try
        {
            $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
        }
        catch (ex)
        {}
        if (!$target || !$target.length)
        {
            if (!$this.data('zui.zmodaltrigger'))
            {
                $this.zmodalTrigger(
                {
                    show: true
                });
            }
            else
            {
                $this.trigger('.toggle.zui.zmodaltrigger');
            }
        }
        if ($this.is('a'))
        {
            e.preventDefault();
        }
    });
}(window.jQuery));


/* ========================================================================
 * image.ready.js
 * http://www.planeart.cn/?p=1121
 * ========================================================================
 * @version 2011.05.27
 * @author  TangBin
 * ======================================================================== */


(function()
{
    'use strict';

    /**
     * Image ready
     * @param {String}  image url
     * @param {Function}  callback on image ready
     * @param {Function}  callback on image load
     * @param {Function}  callback on error
     * @example imgReady('image.png', function () {
        alert('size ready: width=' + this.width + '; height=' + this.height);
      });
     */
    window.imgReady = (function()
    {
        var list = [],
            intervalId = null,

            // 用来执行队列
            tick = function()
            {
                var i = 0;
                for (; i < list.length; i++)
                {
                    list[i].end ? list.splice(i--, 1) : list[i]();
                }
                !list.length && stop();
            },

            // 停止所有定时器队列
            stop = function()
            {
                clearInterval(intervalId);
                intervalId = null;
            };

        return function(url, ready, load, error)
        {
            var onready, width, height, newWidth, newHeight,
                img = new Image();

            img.src = url;

            // 如果图片被缓存，则直接返回缓存数据
            if (img.complete)
            {
                ready.call(img);
                load && load.call(img);
                return;
            }

            width = img.width;
            height = img.height;

            // 加载错误后的事件
            img.onerror = function()
            {
                error && error.call(img);
                onready.end = true;
                img = img.onload = img.onerror = null;
            };

            // 图片尺寸就绪
            onready = function()
            {
                newWidth = img.width;
                newHeight = img.height;
                if (newWidth !== width || newHeight !== height ||
                    // 如果图片已经在其他地方加载可使用面积检测
                    newWidth * newHeight > 1024
                )
                {
                    ready.call(img);
                    onready.end = true;
                }
            };
            onready();

            // 完全加载完毕的事件
            img.onload = function()
            {
                // onload在定时器时间差范围内可能比onready快
                // 这里进行检查并保证onready优先执行
                !onready.end && onready();

                load && load.call(img);

                // IE gif动画会循环执行onload，置空onload即可
                img = img.onload = img.onerror = null;
            };

            // 加入队列中定期执行
            if (!onready.end)
            {
                list.push(onready);
                // 无论何时只允许出现一个定时器，减少浏览器性能损耗
                if (intervalId === null) intervalId = setInterval(tick, 40);
            }
        };
    })();
}());

/* ========================================================================
 * ZUI: lightbox.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, window, Math)
{
    'use strict';

    if (!$.fn.zmodalTrigger) throw new Error('zmodal & zmodalTrigger requires for lightbox');
    if (!window.imgReady) throw new Error('imgReady requires for lightbox');

    var Lightbox = function(element, options)
    {
        this.$ = $(element);
        this.options = this.getOptions(options);

        this.init();
    };

    Lightbox.DEFAULTS = {
        zmodalTeamplate: '<div class="am-icon-spinner am-icon-spin loader"></div><div class="zmodal-dialog"><button class="close" data-dismiss="zmodal" aria-hidden="true"><i class="icon-remove"></i></button><button class="controller prev"><i class="icon icon-chevron-left"></i></button><button class="controller next"><i class="icon icon-chevron-right"></i></button><img class="lightbox-img" src="{image}" alt="" data-dismiss="zmodal" /><div class="caption"><div class="content">{caption}<div></div></div>'
    }; // default options

    Lightbox.prototype.getOptions = function(options)
    {
        var IMAGE = 'image';
        options = $.extend(
        {}, Lightbox.DEFAULTS, this.$.data(), options);
        if (!options[IMAGE])
        {
            options[IMAGE] = this.$.attr('src') || this.$.attr('href') || this.$.find('img').attr('src');
            this.$.data(IMAGE, options[IMAGE]);
        }
        return options;
    };

    Lightbox.prototype.init = function()
    {
        this.bindEvents();
    };

    Lightbox.prototype.initGroups = function()
    {
        var groups = this.$.data('groups');
        if (!groups)
        {
            groups = $('[data-toggle="lightbox"][data-group="' + this.options.group + '"], [data-lightbox-group="' + this.options.group + '"]');
            this.$.data('groups', groups);
            groups.each(function(index)
            {
                $(this).attr('data-group-index', index);
            });
        }
        this.groups = groups;
        this.groupIndex = parseInt(this.$.data('group-index'));
    };

    Lightbox.prototype.bindEvents = function()
    {
        var $e = this.$,
            that = this;
        var options = this.options;
        if (!options.image) return false;
        $e.zmodalTrigger(
        {
            type: 'custom',
            name: 'lightboxzmodal',
            position: 'center',
            custom: function(e)
            {
                that.initGroups();
                var zmodal = e.zmodal,
                    groups = that.groups,
                    groupIndex = that.groupIndex;

                zmodal.addClass('zmodal-lightbox')
                    .html(options.zmodalTeamplate.format(options))
                    .toggleClass('lightbox-with-caption', typeof options.caption == 'string')
                    .removeClass('lightbox-full')
                    .data('group-index', groupIndex);
                var dialog = zmodal.find('.zmodal-dialog'),
                    winWidth = $(window).width();
                window.imgReady(options.image, function()
                {
                    dialog.css(
                    {
                        width: Math.min(winWidth, this.width)
                    });
                    if (winWidth < (this.width + 30)) zmodal.addClass('lightbox-full');
                    e.ready();
                });

                zmodal.find('.prev').toggleClass('show', groups.filter('[data-group-index="' + (groupIndex - 1) + '"]').length > 0);
                zmodal.find('.next').toggleClass('show', groups.filter('[data-group-index="' + (groupIndex + 1) + '"]').length > 0);

                zmodal.find('.controller').click(function()
                {
                    var $this = $(this);
                    var id = zmodal.data('group-index') + ($this.hasClass('prev') ? -1 : 1);
                    var $e = groups.filter('[data-group-index="' + id + '"]');
                    if ($e.length)
                    {
                        var image = $e.data('image'),
                            caption = $e.data('caption');
                        zmodal.addClass('zmodal-loading')
                            .data('group-index', id)
                            .toggleClass('lightbox-with-caption', typeof caption == 'string')
                            .removeClass('lightbox-full');
                        zmodal.find('.lightbox-img').attr('src', image);
                        winWidth = $(window).width();
                        window.imgReady(image, function()
                        {
                            dialog.css(
                            {
                                width: Math.min(winWidth, this.width)
                            });
                            if (winWidth < (this.width + 30)) zmodal.addClass('lightbox-full');
                            e.ready();
                        });
                    }
                    zmodal.find('.prev').toggleClass('show', groups.filter('[data-group-index="' + (id - 1) + '"]').length > 0);
                    zmodal.find('.next').toggleClass('show', groups.filter('[data-group-index="' + (id + 1) + '"]').length > 0);
                    return false;
                });
            }
        });
    };

    $.fn.lightbox = function(option)
    {
        var defaultGroup = 'group' + (new Date()).getTime();
        return this.each(function()
        {
            var $this = $(this);

            var options = typeof option == 'object' && option;
            if (typeof options == 'object' && options.group)
            {
                $this.attr('data-lightbox-group', options.group);
            }
            else if ($this.data('group'))
            {
                $this.attr('data-lightbox-group', $this.data('group'));
            }
            else
            {
                $this.attr('data-lightbox-group', defaultGroup);
            }
            $this.data('group', $this.data('lightbox-group'));

            var data = $this.data('zui.lightbox');
            if (!data) $this.data('zui.lightbox', (data = new Lightbox(this, options)));

            if (typeof option == 'string') data[option]();
        });
    };

    $.fn.lightbox.Constructor = Lightbox;

    $(function()
    {
        $('[data-toggle="lightbox"]').lightbox();
    });
}(jQuery, window, Math));

/* ========================================================================
 * ZUI: messager.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, window)
{
    'use strict';

    var id = 0;
    var template = '<div class="messager messager-{type} {placement}" id="messager{id}" style="display:none"><div class="messager-content"></div><div class="messager-actions"><button type="button" class="close action">&times;</button></div></div>';
    var defaultOptions =
    {
        type: 'default',
        placement: 'top',
        time: 4000,
        parent: 'body',
        // clear: false,
        icon: null,
        close: true,
        fade: true,
        scale: true
    };
    var lastMessager;

    var Messager = function(message, options)
    {
        var that = this;
        that.id = id++;
        options = that.options = $.extend({}, defaultOptions, options);
        that.message = (options.icon ? '<i class="icon-' + options.icon + ' icon"></i> ' : '') + message;

        that.$ = $(template.format(options)).toggleClass('fade', options.fade).toggleClass('scale', options.scale).attr('id', 'messager-' + that.id);
        if(!options.close)
        {
            that.$.find('.close').remove();
        }
        else
        {
            that.$.on('click', '.close', function()
            {
                that.hide();
            });
        }

        that.$.find('.messager-content').html(that.message);


        that.$.data('zui.messager', that);
    };

    Messager.prototype.show = function(message)
    {
        var that = this, options = this.options;

        if(lastMessager)
        {
            if(lastMessager.id == that.id)
            {
                that.$.removeClass('in');
            }
            else if(lastMessager.isShow)
            {
                lastMessager.hide();
            }
        }

        if(that.hiding)
        {
            clearTimeout(that.hiding);
            that.hiding = null;
        }

        if(message)
        {
            that.message = (options.icon ? '<i class="icon-' + options.icon + ' icon"></i> ' : '') + message;
            that.$.find('.messager-content').html(that.message);
        }

        that.$.appendTo(options.parent).show();

        if (options.placement === 'top' || options.placement === 'bottom' || options.placement === 'center')
        {
            that.$.css('left', ($(window).width() - that.$.width() - 50) / 2);
        }

        if (options.placement === 'left' || options.placement === 'right' || options.placement === 'center')
        {
            that.$.css('top', ($(window).height() - that.$.height() - 50) / 2);
        }

        that.$.addClass('in');

        if(options.time)
        {
            that.hiding = setTimeout(function(){that.hide();}, options.time);
        }

        that.isShow = true;
        lastMessager = that;
    };

    Messager.prototype.hide = function()
    {
        var that = this;
        if(that.$.hasClass('in'))
        {
            that.$.removeClass('in');
            setTimeout(function(){that.$.remove();}, 200);
        }

        that.isShow = false;
    };

    $.Messager = Messager;
    var noConflictMessager = window.Messager;
    window.Messager = $.Messager;
    window.Messager.noConflict = function()
    {
        window.Messager = noConflictMessager;
    };

    $.showMessage = function(message, options)
    {
        if(typeof options === 'string')
        {
            options = {type: options};
        }
        var msg = new Messager(message, options);
        msg.show();
        return msg;
    };

    var getOptions = function(options)
    {
        return (typeof options === 'string') ? {placement: options} : options;
    };

    $.messager =
    {
        show: $.showMessage,
        primary: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'primary'}, getOptions(options)));
        },
        success: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'success', icon: 'ok-sign'}, getOptions(options)));
        },
        info: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'info', icon: 'info-sign'}, getOptions(options)));
        },
        warning: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'warning', icon: 'warning-sign'}, getOptions(options)));
        },
        danger: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'danger', icon: 'exclamation-sign'}, getOptions(options)));
        },
        important: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'important'}, getOptions(options)));
        },
        special: function(message, options)
        {
            return $.showMessage(message, $.extend({type: 'special'}, getOptions(options)));
        }
    };

    var noConflict = window.messager;
    window.messager = $.messager;
    window.messager.noConflict = function()
    {
        window.messager = noConflict;
    };
}(jQuery, window));

