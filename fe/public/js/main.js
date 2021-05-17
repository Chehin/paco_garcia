jQuery(document).ready(function() {
"use strict";

$('#btn_tp').css('width', $('#btn_mp').outerWidth());

/******************************************
   Newsletter popup
******************************************/
 
  /*jQuery('#myModal').appendTo("body");
          function show_modal(){
            jQuery('#myModal').modal('show');
          }

            jQuery('#myModal').modal({
            keyboard: false,
           backdrop:false
          }); */


/******************************************
   Featured slider
******************************************/

jQuery("#featured-slider .slider-items").owlCarousel({
		items: 3,
		itemsDesktop: [1024, 3],
		itemsDesktopSmall: [900, 2],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1,
		autoPlay: false
	}),


/******************************************
	Top sellers slider
******************************************/

	jQuery("#top-sellers-slider .slider-items").owlCarousel({
		items: 3,
		itemsDesktop: [1024, 3],
		itemsDesktopSmall: [900, 2],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1,
		autoPlay: true
	}),

/******************************************
	Special products slider
******************************************/

	jQuery("#special-products-slider .slider-items").owlCarousel({
		items: 5,
		itemsDesktop: [1024, 5],
		itemsDesktopSmall: [900, 3],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1,
		autoPlay: false
	}),




/******************************************
	Our clients slider
******************************************/

	jQuery("#our-clients-slider .slider-items").owlCarousel({
		items: 8,
		itemsDesktop: [1024, 8],
		itemsDesktopSmall: [900, 3],
		itemsTablet: [640, 2],
		itemsMobile: [480, 2],
		navigation: false,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: false,
		autoPlay: true
	}),

/******************************************
	Latest news slider
******************************************/

	jQuery("#latest-news-slider .slider-items").owlCarousel({
		autoplay: !0,
		items: 2,
		itemsDesktop: [1024, 2],
		itemsDesktopSmall: [900, 2],
		itemsTablet: [640, 1],
		itemsMobile: [480, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1
	}),


/******************************************
	Mobile menu
******************************************/

	jQuery("#mobile-menu").mobileMenu({
		MenuWidth: 250,
		SlideSpeed: 300,
		WindowsMaxWidth: 767,
		PagePush: !0,
		FromLeft: !0,
		Overlay: !0,
		CollapseMenu: !0,
		ClassName: "mobile-menu"

	}),

/******************************************
	Mega Menu
******************************************/

	jQuery('.mega-menu-title').on('click', function() {
		if (jQuery('.mega-menu-category').is(':visible')) {
			jQuery('.mega-menu-category').slideUp();
		} else {
			jQuery('.mega-menu-category').slideDown();
		}
	});

	jQuery('.navleft-container').hover(function() {
		if (jQuery('.mega-menu-category').is(':visible')) {
			jQuery('.mega-menu-category').slideUp();
		} else {
			jQuery('.mega-menu-category').slideDown();
		}
	}
	);

jQuery('.mega-menu-category .nav > li').hover(function() {
	jQuery(this).addClass("active");
	jQuery(this).find('.popup').stop(true, true).fadeIn('slow');
}, function() {
	jQuery(this).removeClass("active");
	jQuery(this).find('.popup').stop(true, true).fadeOut('slow');
});


jQuery('.mega-menu-category .nav > li.view-more').on('click', function(e) {
	if (jQuery('.mega-menu-category .nav > li.more-menu').is(':visible')) {
		jQuery('.mega-menu-category .nav > li.more-menu').stop().slideUp();
		jQuery(this).find('a').text('More category');
	} else {
		jQuery('.mega-menu-category .nav > li.more-menu').stop().slideDown();
		jQuery(this).find('a').text('Close menu');
	}
	e.preventDefault();
});

/******************************************
   Category desc slider
******************************************/

jQuery("#category-desc-slider .slider-items").owlCarousel({
	autoPlay: true,
	items: 1, //10 items above 1000px browser width
	itemsDesktop: [1024, 1], //5 items between 1024px and 901px
	itemsDesktopSmall: [900, 1], // 3 items betweem 900px and 601px
	itemsTablet: [600, 1], //2 items between 600 and 0;
	itemsMobile: [320, 1],
	navigation: true,
	navigationText: ["<a class=\"flex-prev\"></a>", "<a class=\"flex-next\"></a>"],
	slideSpeed: 500,
	pagination: false
});

/******************************************
   Upsell product slider
******************************************/

jQuery("#upsell-product-slider .slider-items").owlCarousel({
		items: 4,
		itemsDesktop: [1024, 4],
		itemsDesktopSmall: [900, 3],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1,
		autoPlay: false
	}),

/******************************************
	Related product slider
******************************************/

	jQuery("#related-product-slider .slider-items").owlCarousel({
		items: 4,
		itemsDesktop: [1024, 4],
		itemsDesktopSmall: [900, 3],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1,
		autoPlay: true
	}),

/******************************************
	Related posts
******************************************/

	jQuery("#related-posts .slider-items").owlCarousel({
		items: 3,
		itemsDesktop: [1024, 3],
		itemsDesktopSmall: [900, 3],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: !1,
		autoPlay: true
	}),


/******************************************
	testimonials slider
******************************************/

	jQuery("#testimonials-slider .slider-items").owlCarousel({
		items: 2,
		itemsDesktop: [1024, 2],
		itemsDesktopSmall: [900, 1],
		itemsTablet: [640, 1],
		itemsMobile: [390, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		pagination: false,
		autoPlay: true,
		singleItem: true,
        transitionStyle: "goDown"
	}),

/******************************************
	Home testimonials slider
******************************************/

	jQuery(".subDropdown")[0] && jQuery(".subDropdown").on("click", function() {
		jQuery(this).toggleClass("plus"), jQuery(this).toggleClass("minus"), jQuery(this).parent().find("ul").slideToggle()
	})
if (jQuery('#home-testimonials-slider').length) {
	jQuery('#home-testimonials-slider').bxSlider({
		auto: true,
		infiniteLoop: true,
		hideControlOnEnd: true
	});
}

/******************************************
    PRICE FILTER
******************************************/

jQuery('.slider-range-price').each(function() {
	var min = jQuery(this).data('min');
	var max = jQuery(this).data('max');
	var unit = jQuery(this).data('unit');
	var value_min = jQuery(this).data('value-min');
	var value_max = jQuery(this).data('value-max');
	var label_reasult = jQuery(this).data('label-reasult');
	var t = jQuery(this);
	jQuery(this).slider({
		range: true,
		min: min,
		max: max,
		values: [value_min, value_max],
		slide: function(event, ui) {
			var result = label_reasult + " " + unit + ui.values[0] + ' - ' + unit + ui.values[1];
			
			t.closest('.slider-range').find('.amount-range-price').html(result);
		}
	});
})

/******************************************
    Footer expander
******************************************/

jQuery(".collapsed-block .expander").on("click", function(e) {
	var collapse_content_selector = jQuery(this).attr("href");
	var expander = jQuery(this);
	if (!jQuery(collapse_content_selector).hasClass("open")) expander.addClass("open").html("&minus;");
	else expander.removeClass("open").html("+");
	if (!jQuery(collapse_content_selector).hasClass("open")) jQuery(collapse_content_selector).addClass("open").slideDown("normal");
	else jQuery(collapse_content_selector).removeClass("open").slideUp("normal");
	e.preventDefault()
});

/******************************************
    Category sidebar
******************************************/

jQuery(function() {
	jQuery(".category-sidebar ul > li.cat-item.cat-parent > ul").hide();
	jQuery(".category-sidebar ul > li.cat-item.cat-parent.current-cat-parent > ul").show();
	jQuery(".category-sidebar ul > li.cat-item.cat-parent.current-cat.cat-parent > ul").show();
	jQuery(".category-sidebar ul > li.cat-item.cat-parent").on("click", function() {
		if (jQuery(this).hasClass('current-cat-parent')) {
			var li = jQuery(this).closest('li');
			li.find(' > ul').slideToggle('fast');
			jQuery(this).toggleClass("close-cat");
		} else {
			var li = jQuery(this).closest('li');
			li.find(' > ul').slideToggle('fast');
			jQuery(this).toggleClass("cat-item.cat-parent open-cat");
		}
	});
	jQuery(".category-sidebar ul.children li.cat-item,ul.children li.cat-item > a").on("click", function(e) {
		e.stopPropagation();
	});
});

/******************************************
    colosebut
******************************************/

jQuery("#colosebut1").on("click", function() {
	jQuery("#div1").fadeOut("slow");
});
jQuery("#colosebut2").on("click", function() {
	jQuery("#div2").fadeOut("slow");
});
jQuery("#colosebut3").on("click", function() {
	jQuery("#div3").fadeOut("slow");
});
jQuery("#colosebut4").on("click", function() {
	jQuery("#div4").fadeOut("slow");
});


/******************************************
    totop
******************************************/
if (jQuery('#back-to-top').length) {
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = jQuery(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                jQuery('#back-to-top').addClass('show');
            } else {
                jQuery('#back-to-top').removeClass('show');
            }
        };
    backToTop();
    jQuery(window).on('scroll', function () {
        backToTop();
    });
    jQuery('#back-to-top').on('click', function (e) {
        e.preventDefault();
        jQuery('html,body').animate({
            scrollTop: 0
        }, 700);
    });
}
/******************************************
Stick menu
******************************************/

jQuery(window).scroll(function() {
	jQuery(this).scrollTop() > 110 ? jQuery("nav").addClass("stick") : jQuery("nav").removeClass("stick")
}),

/******************************************
Stick menu
******************************************/

jQuery(window).scroll(function() {
	jQuery(this).scrollTop() > 110 ? jQuery("nav").addClass("stick") : jQuery("nav").removeClass("stick"), jQuery(this).scrollTop() > 110 ? jQuery(".top-cart").addClass("stick") : jQuery(".top-cart").removeClass("stick")
}),
/******************************************
    tooltip
******************************************/


jQuery('[data-toggle="tooltip"]').tooltip();

/* ---------------------------------------------
    slider typist
--------------------------------------------- */

if (typeof Typist == 'function') {
	new Typist(document.querySelector('.typist-element'), {
		letterInterval: 60,
		textInterval: 3000
	});
}

})
