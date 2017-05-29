(function($) {
  
	"use strict";

	$(document).ready(function() {
		
		try {
			// PARALLAX BACKGROUNDS FOR DESKTOPS
			
			if ($(this).width() > 768) {
				$(window).stellar({
					horizontalScrolling: false
				});
			}		
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

		try {
			$("#navigation").headroom();
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}


		try {
			// MOBILE NAVIGATION MENU DROPDOWN
		
		  	var menu = $('#navigation-menu');
			var menuToggle = $('#js-mobile-menu');
			var menuLink = $('.navigation .nav-link a');
			
			if(menu.is(':hidden')) {
				menu.removeAttr('style');
			}
	
		  	$(menuToggle).on('click', function(e) {
		    	e.preventDefault();
		    	menu.slideToggle(function(){
					if(menu.is(':hidden')) {
						menu.removeAttr('style');
					}
		    	});
		  	});
		
		  	$(menuLink).on('click', function() {
				if( $(".navigation-menu-button").css('display') === 'block') {
					menu.slideToggle(function(){
						if(menu.is(':hidden')) {
							menu.removeAttr('style');
						}
					});
				}
			});	
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}


		try {
			// ADD ACTIVE CLASS TO NAVIGATION ITEM WHEN CLICKED
		
			$('.navigation a').on('click', function() {
				$('.navigation li').removeClass('active');
				$(this).closest('li').addClass('active');
			});
			
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

		// SMOOTH SCROLLING BETWEEN SECTIONS 
		try {
			$('a[href*="#section-"]').on('click', function() {
		
				var navHeight = $('header#navigation').height();
				var top = $(document).scrollTop();
		
			    if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') || location.hostname === this.hostname) {
		
			        var target = $(this.hash);
			        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
		           	
		           	if (target.length) {
		
			           	if ($(this.hash).offset().top < top ) {
			           		
			           		// scroll position includes nav height
			                $('html,body').animate({
				                 scrollTop: (target.offset().top - navHeight)
				            }, 600);
				            return false;
		
			            } else {
		
			            	// scroll position without nav height
			                $('html,body').animate({
				                 scrollTop: (target.offset().top + 2)
				            }, 600);
				            return false;
			            }
			        }
			    }
			});	
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}
		
		try {
			$('.fitvids').fitVids();
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

		// RESPONSIVE VIDEOS - FITVIDS

		// OWL CAROUSEL SLIDERS

		try {
			$("#showcase-slider, #quote-slider").owlCarousel({
				items : 1,
				loop : true,
		      	autoplay : true,
	    		autoplayTimeout : 4000
			});			
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

		try {
			$(".block-blog-teasers").owlCarousel({
				items : 1, 
				video : true,
				autoplay : true,
				autoplayTimeout : 4000,
				autoplayHoverPause : true,
				responsive : {
			        480 : {
			            items : 1,
			        },
			        767 : {
			            items : 2,
			        },
			        991 : {
			            items : 3,
			        }
			    }
			});	
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}


		// FLEXSLIDER

		try {
			$(".flexslider").flexslider();
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

	});
  

})(jQuery);