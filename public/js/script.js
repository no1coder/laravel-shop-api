!function (e) {
    "use strict";
    e(window).on("load", function () {
        e("#status").fadeOut(), e("#preloader").css({height: "0%"}), e("body").delay(350).css({"overflow-x": "visible"})
    });
    var t = e(".video");
    t.length > 0 && t.magnificPopup({
        type: "iframe",
        iframe: {markup: '<style>.mfp-iframe-holder .mfp-content {max-width: 400px;height:600px}</style><div class="mfp-iframe-scaler" ><div class="mfp-close"></div><iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe></div></div>'}
    });
    var a = e(".counter");
    a.length > 0 && a.counterUp({delay: 20, time: 500});
    var s = e(".parallaxie");
    s.length > 0 && s.parallaxie({speed: .5});
    var i = e(".screen");
    i.length > 0 && i.owlCarousel({
        loop: !0,
        nav: !1,
        autoplay: !0,
        autoplayTimeout: 2e3,
        margin: 25,
        responsive: {320: {items: 1, margin: 0}, 481: {items: 3, margin: 60}, 991: {items: 4}}
    });
    var n = e(".quote");
    n.length > 0 && n.owlCarousel({
        loop: !0,
        autoplay: !0,
        autoplayTimeout: 2500,
        margin: 5,
        nav: !1,
        responsive: {300: {items: 1}, 480: {items: 2}, 768: {items: 3}, 1200: {items: 4}}
    });
    var o = e(".app-slide-auto");
    o.length > 0 && o.owlCarousel({
        dots: !1,
        loop: !0,
        center: !0,
        margin: 0,
        autoplay: !0,
        autoplayTimeout: 2500,
        autoWidth: !0,
        ouchDrag: !1,
        mouseDrag: !1
    });
    var l = e(".app-slide");
    l.length > 0 && (l.owlCarousel({
        loop: !0,
        center: !0,
        margin: 0,
        autoWidth: !0,
        navs: !1,
        dots: !0,
        ouchDrag: !1,
        mouseDrag: !1,
        dotsContainer: !1
    }), e(".owl-dot").on("click", function () {
        l.trigger("to.owl.carousel", [e(this).index(), 300])
    }));
    var r = e(".feat-slide");
    r.length > 0 && r.owlCarousel({
        loop: !0,
        autoplay: !0,
        autoplayTimeout: 2500,
        nav: !0,
        margin: 30,
        navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        responsive: {300: {items: 1}, 400: {items: 2, margin: 10}, 768: {items: 3}, 1200: {items: 4}}
    }), (new WOW).init(), e(".subscribe form").submit(function (t) {
        t.preventDefault();
        var a = e(".subscribe form").serialize();
        e.ajax({
            type: "POST", url: "assets/subscribe.php", data: a, dataType: "json", success: function (t) {
                0 == t.valid ? (e(".success-message").hide(), e(".error-message").hide(), e(".error-message").html(t.message), e(".error-message").fadeIn("fast", function () {
                    e(".subscribe form").addClass("animated shake").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                        e(this).removeClass("animated shake")
                    })
                })) : (e(".error-message").hide(), e(".success-message").hide(), e(".subscribe form").hide(), e(".success-message").html(t.message), e(".success-message").fadeIn("fast", function () {
                    e(".top-content").backstretch("resize")
                }))
            }
        })
    });
    var c = e(".right-nav a, .demo a");

    function m(t) {
        e(t.target).prev(".panel-heading").find(".more-less").toggleClass("glyphicon-plus glyphicon-minus")
    }

    c.length > 0 && c.on("click", function (t) {
        if (e(document).off("scroll"), location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") || location.hostname == this.hostname) {
            var a = e(this.hash), s = e(".navbar").height() - 2;
            if ((a = a.length ? a : e("[name=" + this.hash.slice(1) + "]")).length) return e("html,body").animate({scrollTop: a.offset().top - s}, 1e3), !1
        }
    }), e(".panel-group").on("hidden.bs.collapse", m), e(".panel-group").on("shown.bs.collapse", m);
    var d = e("#contact-form");
    d.validator(), d.on("submit", function (t) {
        if (!t.isDefaultPrevented()) {
            return e.ajax({
                type: "POST", url: "assets/contact.php", data: e(this).serialize(), success: function (e) {
                    var t = "alert-" + e.type, a = e.message,
                        s = '<div class="alert ' + t + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + a + "</div>";
                    t && a && (d.find(".messages").html(s), d[0].reset())
                }
            }), !1
        }
    });
    var u = document.getElementById("filt-monthly");
    if ("undefined" != u && null != u) {
        var g = document.getElementById("filt-monthly"), h = document.getElementById("filt-hourly"),
            p = document.getElementById("switcher"), f = document.getElementById("monthly"),
            v = document.getElementById("hourly");
        g.addEventListener("click", function () {
            p.checked = !1, g.classList.add("toggler--is-active"), h.classList.remove("toggler--is-active"), f.classList.remove("none"), v.classList.add("none")
        }), h.addEventListener("click", function () {
            p.checked = !0, h.classList.add("toggler--is-active"), g.classList.remove("toggler--is-active"), f.classList.add("none"), v.classList.remove("none")
        }), p.addEventListener("click", function () {
            h.classList.toggle("toggler--is-active"), g.classList.toggle("toggler--is-active"), f.classList.toggle("none"), v.classList.toggle("none")
        })
    }



}(jQuery);


