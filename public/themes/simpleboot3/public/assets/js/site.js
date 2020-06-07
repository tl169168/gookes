function AutoScroll(obj) {
    if(!$(obj).children("ul").is(":animated")) {
        $(obj).children("ul").find("li:first").animate({
            opacity: 0
        }, 800, function() {
            $(this).parent().animate({
                top: "-65px"
            }, 500, function() {
                $(this).css({
                    top: "0"
                }).find("li:first").appendTo(this).css("opacity", 1);
            })
        })
    }
}
var animLen = $(".anim_emt").length;
var animElements = {
    init: function(elSelector) {
        var _this = this;
        void 0 == elSelector && (elSelector = "body");
        var wdAnim = function() {
            _this.showEmts(elSelector)
         if(!animLen) {
             $(window).off("scroll.anim")
         }
        };
        "ontouchstart" in document.documentElement ? $("html").addClass("no-scroll"): "";
        if (wdAnim(),$("html").is(".no-scroll")) {
            $(elSelector + " .anim_emt:not(:in-viewport)").removeClass("anim_emt");
        } else {
            "body" == elSelector ? $(window).off("scroll.anim").on("scroll.anim", wdAnim) : ''
        }
    },
    showEmts: function(elSelector) {
        void 0 != elSelector && "" != elSelector || (elSelector = "body");
        var txts = elSelector + " .split:in-viewport",
         els = elSelector + " .anim_emt:in-viewport";
        this.animEmts($(els).not(".in"))
    },
    animEmts: function(el) {
        el.each(function(ind) {
            var t = $(this);
            t.addClass("in");
            animLen -= 1;
            setTimeout(function() {
                animElements.animEmt(t)
            }, 100 * ind)
        })
    },
    animEmt: function(el) {
        el.addClass("start start-trans visb");
       /*el.css("pointer-events", "none"),
        el.one("transitionend", function() {
            el.removeClass("start-trans"),
            el.css("pointer-events", "")
        })*/
    }
};
$(document).ready(function() {

    $(document).on("mousewheel DOMMouseScroll", function (e) {
        var delta = (e.originalEvent.wheelDelta && (e.originalEvent.wheelDelta > 0 ? 1 : -1)) ||  // chrome & ie
            (e.originalEvent.detail && (e.originalEvent.detail > 0 ? -1 : 1));              // firefox
        if (delta > 0) {
            $(".gkheader .nav").removeClass("hidenav")
        } else if (delta < 0) {
            $(".gkheader .nav").addClass("hidenav")
        }
    })

    var homeSlideSwiper = new Swiper('.homeslide-box', {
        paginationClickable: true,
        loop: true,
        slidesPerView: 1,
        speed: 800,
        pagination: ".homeslide-dot",
        // nextButton: '.indexnews-wrap .slider__switch--next',
        // prevButton: '.indexnews-wrap .slider__switch--prev',
        autoplayDisableOnInteraction: false,
        roundLengths: true,
    });

    $(".partner-list").owlCarousel({
        items : 4,
        itemsDesktopSmall : [979, 4],
        itemsTablet : [768, 4],
        itemsTablet : [640, 3],
        itemsTabletSmall : false,
        itemsMobile : [479, 2],
        lazyLoad : true,
        autoPlay:true
      });
    $(".reward-list").owlCarousel({
        items : 7,
        itemsDesktop : [1199, 6],
        itemsDesktopSmall : [979, 5],
        itemsTablet : [768, 5],
        itemsTablet : [640, 4],
        itemsTabletSmall : false,
        itemsMobile : [479, 3],
        lazyLoad : true,
        autoPlay:true
    });

    $(".vision-list").owlCarousel({
        items : 4,
        itemsDesktop : [1199, 4],
        itemsDesktopSmall : [979, 3],
        itemsTablet : [768, 3],
        itemsTablet : [640, 2],
        itemsTabletSmall : false,
        itemsMobile : [479, 2],
        lazyLoad : true,
        autoPlay:true,
        // navigation : true
    });

    var videoLargeWrap = $(".video-large-wrap"),
        homevideo = document.getElementById("homevideo"),
        videoLarge = document.getElementById("video-large"),
        videoPlay = $("#playbtn"),
        videoClose = $("#video-close");
    videoPlay.on("click", function() {
        homevideo.pause(),
       $("body").css("overflow", 'hidden'),
        videoLargeWrap.show(),
        videoLarge.play()
    }),
    videoClose.on("click", function() {
        videoLarge.pause(),
        videoLargeWrap.hide(),
        $("body").css("overflow", 'initial'),
        homevideo.play()
    })

    $(".gkheader .menubtn").click(function(event) {
        if ($(".gkheader .nav").css('display') == 'none') {
            $(".gkheader .nav").slideDown();
            $(".gkcover").slideDown();
        } else {
            $(".gkheader .nav").slideUp();
            $(".gkcover").slideUp();
        }
        event.stopPropagation();
    });
    $(".gkcover").click(function() {
        $(".gkheader .nav").slideUp();
        $(".gkcover").slideUp();
    })
    var excuteOne = 0;
    $(window).off("load resize").on("load resize", function() {
        handleVideoRatio();

        var winW = $(window).width();
        var overscroll = window.innerWidth - document.body.clientWidth;
        $(".gkcover").hide();
        if(winW + overscroll >= 992) {
            $(".gkheader .nav").show();
        } else {
            $(".gkheader .nav").css("display", "none");
            handleMobileMenu()
        }

        if(!excuteOne) {
            animElements.init();
        }
        excuteOne = 1;
    })
    function handleMobileMenu() {
        var $subnav = $(".gkheader .subnav-list"),
            $nav = $(".gkheader .nav-list .item > a");
        $nav.off("click").on("click", function (e) {
            if ($(this).siblings(".subnav-list").length) {
                $nav.parent().removeClass("show")
                if ($(this).hasClass("cur")) {
                    $(this).removeClass("cur")
                    $(this).next("ul").slideUp()
                } else {
                    $nav.removeClass("cur")
                    $subnav.slideUp()
                    $(this).parent().addClass("show")
                    $(this).next("ul").stop(true).slideDown().end().parent().siblings().children("ul").hide()
                    $(this).addClass("cur")
                    e.preventDefault()
                }
            }
        });
    }

    function handleVideoRatio(){
      var width = $(window).width(),
        height = $(window).height();
      var ratio = height/width;
      $(".wevideo").css("padding-top", ratio * 100 + "%")
      // 背景视频为1280*720
      if(ratio > (720 / 1280)){
        $("#homevideo").css({
            width: "auto",
            height: "100%"
        })
      }else{
        $("#homevideo").css({
            width: "100%",
            height: "auto"
        })
      }
    }

    var platformSwiper = new Swiper('.platformSwiper', {
        nextButton: '.learning-btn-next',
        prevButton: '.learning-btn-prev',
        slidesPerView: 3,
        slidesPerColumn: 2,
        paginationClickable: true,
        spaceBetween: 8
    });

    $(".aimpoint-list .subitem a").on("click", function(e) {
        var hash = $(this).attr("href")
        if(hash && $(hash).length) {
            console.log(hash,1111)
            e.preventDefault();
            $("html,body").animate({
                scrollTop: $(hash).offset().top},
                800,
                "easeInOutExpo"
            )
        }
    })

    // getUrlParam()
    // function getUrlParam(name) {
    //     var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    //     var r   =   window.location.search.substr(1).match(reg);      
    //     if (r!=null) return unescape(r[2]);
    // }
    // goAnchor(getUrlParam("school"))
    // function goAnchor(id) {
    //     console.log(id)
    //     $("html").animate({
    //         scrollTop: $("#" + id).offset().top},
    //         800,
    //         "easeInOutExpo"
    //     )
    // }
    
    setInterval('AutoScroll(".comment-list-wrap")', 1e3)
})