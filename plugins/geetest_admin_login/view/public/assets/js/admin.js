$(document).ready(function(){
	document.getElementById('input_username').focus();
    //全局ajax处理
    $.ajaxSetup({
        complete: function (jqXHR) {
        },
        data: {},
        error: function (jqXHR, textStatus, errorThrown) {
            //请求失败处理
        }
    });

    if ($.browser && $.browser.msie) {
        //ie 都不缓存
        $.ajaxSetup({
            cache: false
        });
    }

    //不支持placeholder浏览器下对placeholder进行处理
    if (document.createElement('input').placeholder !== '') {
        $('[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur().parents('form').submit(function () {
            $(this).find('[placeholder]').each(function () {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
        });
    }
    //极验插件初始化
    $.ajax({
        url: ajaxUrl,
        type: "get",
        dataType: "json",
        cache:false,
        success: function (data) {
            console.log(data);   
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                new_captcha: data.new_captcha,
                product: "embed", 
                width: '100%',
                offline: !data.success 
            }, function (captchaObj) {
                captchaObj.appendTo("#captcha");
                formInit(captchaObj);
            });
        }
    });
    
});

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location  = win.location;
    location.href = location.pathname + location.search;
}
//对表单初始化
function formInit(captchaObj){
    var ajaxForm_list = $('form.js-ajax-form');
    
    if (ajaxForm_list.length) {
        Wind.css('artDialog');
        Wind.use('ajaxForm', 'artDialog', 'noty3', 'validate', function () {
            var $btn;
            $('button.js-ajax-submit').on('click', function (e) {
                var btn = $(this), form = btn.parents('form.js-ajax-form');
                $btn    = btn;
                validate_result = captchaObj.getValidate();
                if (!validate_result) {
                    new Noty({
                        text: not_verify,
                        type: 'error',
                        layout: 'topCenter',
                        modal: true,
                        animation: {
                            open: 'animated bounceInDown', // Animate.css class names
                            close: 'animated bounceOutUp', // Animate.css class names
                        },
                        timeout: 1
                    }).show();
                    $(window).focus();
                    return false;
                }
                
                if (btn.data("loading")) {
                    return;
                }

                //ie处理placeholder提交问题
                if ($.browser && $.browser.msie) {
                    form.find('[placeholder]').each(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });
                }
            });

            ajaxForm_list.each(function () {
                $(this).validate({
                    //是否在获取焦点时验证
                    //onfocusout : false,
                    //是否在敲击键盘时验证
                    //onkeyup : false,
                    //当鼠标点击时验证
                    //onclick : false,
                    //给未通过验证的元素加效果,闪烁等
                    highlight: function (element, errorClass, validClass) {
                        if (element.type === "radio") {
                            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                        } else {
                            var $element = $(element);
                            $element.addClass(errorClass).removeClass(validClass);
                            $element.parent().addClass("has-error");//bootstrap3表单
                            $element.parents('.control-group').addClass("error");//bootstrap2表单

                        }
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        if (element.type === "radio") {
                            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                        } else {
                            var $element = $(element);
                            $element.removeClass(errorClass).addClass(validClass);
                            $element.parent().removeClass("has-error");//bootstrap3表单
                            $element.parents('.control-group').removeClass("error");//bootstrap2表单
                        }
                    },
                    showErrors: function (errorMap, errorArr) {
                        var i, elements, error;
                        for (i = 0; this.errorList[i]; i++) {
                            error = this.errorList[i];
                            if (this.settings.highlight) {
                                this.settings.highlight.call(this, error.element, this.settings.errorClass, this.settings.validClass);
                            }
                            //this.showLabel( error.element, error.message );
                        }
                        if (this.errorList.length) {
                            //this.toShow = this.toShow.add( this.containers );
                        }
                        if (this.settings.success) {
                            for (i = 0; this.successList[i]; i++) {
                                //this.showLabel( this.successList[ i ] );
                            }
                        }
                        if (this.settings.unhighlight) {
                            for (i = 0, elements = this.validElements(); elements[i]; i++) {
                                this.settings.unhighlight.call(this, elements[i], this.settings.errorClass, this.settings.validClass);
                            }
                        }
                        this.toHide = this.toHide.not(this.toShow);
                        this.hideErrors();
                        this.addWrapper(this.toShow).show();
                    },
                    submitHandler: function (form) {
                        var $form = $(form);
                        $form.ajaxSubmit({
                            url: $btn.data('action') ? $btn.data('action') : $form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                            dataType: 'json',
                            beforeSubmit: function (arr, $form, options) {

                                $btn.data("loading", true);
                                var text = $btn.text();

                                //按钮文案、状态修改
                                $btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                            },
                            success: function (data, statusText, xhr, $form) {

                                function _refresh() {
                                    if (data.url) {
                                        //返回带跳转地址
                                        window.location.href = data.url;
                                    } else {
                                        if (data.code == 1) {
                                            //刷新当前页
                                            reloadPage(window);
                                        }
                                    }
                                }

                                var text = $btn.text();

                                //按钮文案、状态修改
                                $btn.removeClass('disabled').prop('disabled', false).text(text.replace('中...', '')).parent().find('span').remove();
                                if (data.code == 1) {
                                    if ($btn.data('success')) {
                                        var successCallback = $btn.data('success');
                                        window[successCallback](data, statusText, xhr, $form);
                                        return;
                                    }
                                    new Noty({
                                        text: data.msg,
                                        type: 'success',
                                        layout: 'topCenter',
                                        modal: true,
                                        animation: {
                                            open: 'animated bounceInDown', // Animate.css class names
                                            close: 'animated bounceOutUp', // Animate.css class names
                                        },
                                        timeout: 1,
                                        callbacks: {
                                            afterClose: function () {
                                                if ($btn.data('refresh') == undefined || $btn.data('refresh')) {
                                                    _refresh();
                                                }
                                            }
                                        }
                                    }).show();
                                    $(window).focus();
                                } else if (data.code == 0) {
                                    var $verify_img = $form.find(".verify_img");
                                    if ($verify_img.length) {
                                        $verify_img.attr("src", $verify_img.attr("src") + "&refresh=" + Math.random());
                                    }

                                    var $verify_input = $form.find("[name='verify']");
                                    $verify_input.val("");

                                    //$('<span class="tips_error">' + data.msg + '</span>').appendTo($btn.parent()).fadeIn('fast');
                                    $btn.removeProp('disabled').removeClass('disabled');

                                    new Noty({
                                        text: data.msg,
                                        type: 'error',
                                        layout: 'topCenter',
                                        modal: true,
                                        animation: {
                                            open: 'animated bounceInDown', // Animate.css class names
                                            close: 'animated bounceOutUp', // Animate.css class names
                                        },
                                        timeout: 1,
                                        callbacks: {
                                            afterClose: function () {
                                            	if (data.msg == verify_wrong){
                                            		captchaObj.reset();
                                            	}
                                                _refresh();
                                            }
                                        }
                                    }).show();
                                    $(window).focus();
                                }


                            },
                            error: function (xhr, e, statusText) {
                                art.dialog({
                                    id: 'warning',
                                    icon: 'warning',
                                    content: statusText,
                                    cancelVal: '关闭',
                                    cancel: function () {
                                        reloadPage(window);
                                    },
                                    ok: function () {
                                        reloadPage(window);
                                    }
                                });

                            },
                            complete: function () {
                                $btn.data("loading", false);
                            }
                        });
                    }
                });
            });

        });
    }
}







