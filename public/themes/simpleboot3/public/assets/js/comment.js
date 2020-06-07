$(document).ready(function() {
    $(".comment-reply-collapse").on("click",function () {
           var item_id=$(this).data('item-id');
           //切换隐藏，显示
           if($(this).data('reply-count')>0){
               $item=$("#item-"+item_id);
               if($item.find('.comment-reply').is(':visible')){
                   $item.find('.comment-reply').slideUp('normal');
                   $(this).html('回复('+$(this).data('reply-count')+')');
               }
               else {
                   //显示
                   $item.find('.comment-reply').slideDown('normal');
                   $(this).html('收起回复');
               }
               return;
           }
    })
    //表情功能
    function Expression(){
        this.expressionBox = '.expression-box';
        this.expressionBtn = '.expression-btn';
        this.expressionItem = '.expression-list li';
        this.expressionClose = '.expression-close';
        this.parentDom = null;
        this.expressionCon = `<div class="expression-box"><span class="expression-close"></span><ul class="expression-list"><li class="expression-type1" title="微笑"></li><li class="expression-type2" title="大笑"></li><li class="expression-type3" title="调皮"></li><li class="expression-type4" title="惊讶"></li><li class="expression-type5" title="耍酷"></li><li class="expression-type6" title="发火"></li><li class="expression-type7" title="害羞"></li><li class="expression-type8" title="汗水"></li><li class="expression-type9" title="大哭"></li><li class="expression-type10" title="得意"></li><li class="expression-type11" title="鄙视"></li><li class="expression-type12" title="困"></li><li class="expression-type13" title="夸奖"></li><li class="expression-type14" title="晕倒"></li><li class="expression-type15" title="疑问"></li><li class="expression-type16" title="媒婆"></li><li class="expression-type17" title="狂吐"></li><li class="expression-type18" title="青蛙"></li><li class="expression-type19" title="发愁"></li><li class="expression-type20" title="轻吻"></li><li class="expression-type21" title="不屑"></li><li class="expression-type22" title="爱心"></li><li class="expression-type23" title="心碎"></li><li class="expression-type24" title="玫瑰"></li><li class="expression-type25" title="礼物"></li><li class="expression-type26" title="哭"></li><li class="expression-type27" title="奸笑"></li><li class="expression-type28" title="可爱"></li><li class="expression-type29" title="滑稽"></li><li class="expression-type30" title="呲牙"></li><li class="expression-type31" title="暴汗"></li><li class="expression-type32" title="楚楚可怜"></li><li class="expression-type33" title="睡觉"></li><li class="expression-type34" title="哭哭"></li><li class="expression-type35" title="生气"></li><li class="expression-type36" title="惊呆"></li><li class="expression-type37" title="喷水"></li><li class="expression-type38" title="彩虹"></li><li class="expression-type39" title="夜空"></li><li class="expression-type40" title="太阳"></li><li class="expression-type41" title="钞票"></li><li class="expression-type42" title="灯泡"></li><li class="expression-type43" title="咖啡"></li><li class="expression-type44" title="蛋糕"></li><li class="expression-type45" title="音乐"></li><li class="expression-type46" title="爱"></li><li class="expression-type47" title="胜利"></li><li class="expression-type48" title="赞"></li><li class="expression-type49" title="DISS"></li><li class="expression-type50" title="OK"></li></ul></div>`;

        this.init();
        this.addEvent();
    }

    Expression.prototype = {
        init: function(){
            //构建插入字符功能代码
            (function ($) {
                $.fn.extend({
                    insertAtCaret: function (myValue) {
                        var $t = $(this)[0];
                        if (document.selection) {
                            this.focus();
                            sel = document.selection.createRange();
                            sel.text = myValue;
                            this.focus();
                        } else
                            if ($t.selectionStart || $t.selectionStart == '0') {
                                var startPos = $t.selectionStart;
                                var endPos = $t.selectionEnd;
                                var scrollTop = $t.scrollTop;
                                $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                                this.focus();
                                $t.selectionStart = startPos + myValue.length;
                                $t.selectionEnd = startPos + myValue.length;
                                $t.scrollTop = scrollTop;
                            } else {
                                this.value += myValue;
                                this.focus();
                            }
                    }
                })
            })(jQuery);
            //插入表情盒子dom
            $('body').append(this.expressionCon);
        },
        addEvent: function(){
            var _self = this;

            $(document).on('click',_self.expressionItem,function(){
                //点击表情 插入内容
                var textarea = _self.parentDom.find('textarea');
                textarea.insertAtCaret('['+$(this).attr('title')+']');
                $(_self.expressionBox).hide();
            }).on('click',_self.expressionClose,function(){
                $(_self.expressionBox).hide();
            }).on('click',_self.expressionBtn,function(){
                if($(_self.expressionBox).is(':hidden')){
                    $(_self.expressionBox).css({
                        'top': $(this).offset().top + 30,
                        'left': $(this).offset().left
                    })
                    _self.parentDom = $(this).parent().parent().parent();
                    $(_self.expressionBox).show();
                }else{
                    $(_self.expressionBox).hide();
                }
            }).on('click',function(e){
                var target = e.target;
                if(!$(target).closest('.expression-wrap').length && !$(target).closest('.expression-box').length){
                    $(_self.expressionBox).hide();
                }
            })
        }
    }

    var expression = new Expression();
})