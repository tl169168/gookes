/*-------------------
*Description:        By www.yiwuku.com
*Website:            https://app.zblogcn.com/?auth=115
*Author:             尔今 erx@qq.com
*update:             2017-05-15
-------------------*/

function timeFrame(beginTime, endTime) {
    var stime=Date.parse(beginTime.replace(/-/g,"/"));
	var etime=Date.parse(endTime.replace(/-/g,"/"));
	var now=new Date();
    if (now > stime && now < etime) {
        return true;
    } else {
        return false;
    }
}
var ntcheck = timeFrame(noticeStime,noticeEtime);
if(noRmenu==1){
	document.oncontextmenu = function(){
		nomsg();
		event.returnValue = false; 
	}
	document.oncontextmenu=function(e){
		nomsg();
		return false;
	}
}
if(noSelect==1){
	document.onselectstart=function(){
		nomsg();
		return false;
	}
}
document.onkeydown = function(e){  
	if ((e.keyCode == 116 && noF5 == 1)||(e.ctrlKey&&e.keyCode==82 && noF5 == 1)||(e.keyCode == 123 && noF12 == 1)){  
		nomsg();
		e.preventDefault();
	}
	if (e.ctrlKey && e.keyCode==67 && noCopy == 1){  
		nomsg();
		e.preventDefault();
	}
}
if(closeMsg!=""){
	window.onbeforeunload = function() { 
	　　return closeMsg; 
	}
}
if(noIframe==1){
	(function(window) {
		if (window.location !== window.top.location) {
			window.top.location = window.location;
		}
	})(this);
}
function nomsg(){
	if(tipShow==1){
		$("#xytipshow").animate({opacity: "1"},10).animate({top:"-=200px", opacity: "0"},1800).animate({top:"50%", opacity: "0"},10);
	}
}
!window.jQuery && alert("很遗憾，网页控制小神器插件无法启用！原因是当前主题jQuery加载异常，可能缺失或不规范导致，建议联系主题作者修复或换用其它主题。");
$(function(){
	if(noSelect==1){
		$("body").attr({style:"-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;user-select:none;"});
	}
	if(closeSite==1){
		$("body").html("<div style=\"position:fixed;top:0;left:0;width:100%;height:100%;text-align:center;background:#fff;padding-top:150px;z-index:99999;\">"+closeTips+"</div>");
	}
	if(noSaveimg==1){
		$("img,.disimg").bind("contextmenu", function(e){return false;});
		function jc_imgnomove(){
			return false;
		}
		function jc_imgnomoveAll(){
			for(i in document.images)document.images[i].ondragstart=jc_imgnomove;
		}
		$("img").bind("click",jc_imgnomoveAll());
	}
	if(noDisimg==1){
		$("img").each(function(){
			var pwh=$(this).width();
			var pht=$(this).height();
			var purl=$(this).attr("src");
			$(this).replaceWith('<div class="disimg" style="width:'+pwh+'px;height:'+pht+'px;background:url('+purl+') no-repeat;"></div>');
	    });
	}
	if(newOpen==1){
		$("*[class*='nav'],*[id*='nav'],*[class*='menu'],*[id*='nav']").each(function(){
			if(typeof($(this).attr("href")) != "undefined"){
				$(this).attr("target","_self");
			}
			$(this).find("a").attr("target","_self");
		});
	}
	if(noticeSet==1){
		function jc_setCookie(name, value) {
			var exp = new Date();
			exp.setTime(exp.getTime() + 1 * 24 * 60 * 60 * 1000);
			document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString()+";path=/";
		}
		function jc_getCookie(name) {
			var arr,
			reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
			if (arr = document.cookie.match(reg)) return (arr[2]);
			else return null
		}
		var jsctrl = jc_getCookie("jsctrl");
		$("body").prepend($(".notice-wrap").prop("outerHTML"));
		$(".notice-wrap:last").remove();
		var pbwidth=$(window).width();
		$(window).resize(function() {
			pbwidth=$(window).width();
		});
		var nboxheight=$(".notice-box").outerHeight();
		$(".notice-box").css({marginTop:-nboxheight/2});
		$(".notice-box .xyclose").click(function(){
			$(".notice-bg").fadeOut(600);
			$(".notice-mini").delay(500).fadeIn(600).animate({opacity:"0"},300).animate({opacity:"1"},300).animate({opacity:"0"},300).animate({opacity:"1"},300);
			$(".notice-box").animate({top:"160px",left:"0",width:"0",height:"0",opacity:"0"},600);
			jc_setCookie("jsctrl",1);
		});
		$(".notice-mini").click(function(){
			$(this).hide();
			$(".notice-bg").fadeIn(300);
			if(pbwidth <= 640){
				$(".notice-box").animate({top:"42%",left:"50%",width:"90%",height:nboxheight,marginLeft:"-45%",opacity:"1"},600);
			}else{
				$(".notice-box").animate({top:"42%",left:"50%",width:"50%",height:nboxheight,marginLeft:"-25%",opacity:"1"},600);
			}
			jc_setCookie("jsctrl",2);
		});
		if (ntcheck && (jsctrl == null || jsctrl == 2)) {
			$(".notice-mini").hide();
			$(".notice-bg").show();
			if(pbwidth <= 640){
				$(".notice-box").show().css({width:"90%",marginLeft:"-45%"});
			}else{
				$(".notice-box").show().css({width:"50%",marginLeft:"-25%"});
			}
		}else if(ntcheck && jsctrl == 1){
			$(".notice-mini").show();
			$(".notice-bg").hide();
			$(".notice-box").show().css({top:"160px",left:"0",width:"0",height:"0",opacity:"0"});
		}else{
			$(".notice-mini,.notice-bg,.notice-box").hide();
		}
	}
	if(tipShow==1){
		$("body").append('<span id="xytipshow" style="position:fixed;top:50%;left:50%;color:#F80;font-size:16px;margin-top:-10px;opacity:0;filter:alpha(opacity=0);z-index:99999">抱歉，功能暂被禁用！</span>');
	}
});

//若无十足把握，切勿修改以上代码，容易出错