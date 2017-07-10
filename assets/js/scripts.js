(function($) {
  
	"use strict";

	$(document).ready(function() {

    function toggleDeviceState() {
      var windowsize = $(window).innerWidth();
      if(windowsize > 768) {
        $('body').addClass('desktop-site').removeClass('mobile-site');
      } else {

        $('body').addClass('mobile-site').removeClass('desktop-site');
      }
    }

    toggleDeviceState();

    $(window).bind('resize',function(){
      toggleDeviceState();
    });

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

			var navigation = $('.navigation');
		  	var menu = $('#navigation-menu');
			var menuToggle = $('#js-mobile-menu');
			var menuLink = $('.navigation .nav-link a');
			var siteLogo = $('.js-logo');

			if(menu.is(':hidden')) {
				menu.removeAttr('style');
			}

		  	$(menuToggle).on('click', function(e) {
		    	e.preventDefault();

          if(menu.is(':hidden')) {
            menuToggle.text('Close');
            navigation.toggleClass('mobile-navigation-active');
            siteLogo.toggleClass('invert-logo');
          } else {
						menuToggle.text('Menu');
            navigation.toggleClass('mobile-navigation-active');
            siteLogo.toggleClass('invert-logo');
					}
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

		// Results Behaviours

    var $circuitDetailResultsEl = $('.js-circuit-detail-result');

    $circuitDetailResultsEl.each(function() {

    	var $this = $(this);
			var $currentPlacement = $this.find('.circuit-detail__result-placement');
      var currentPlacementText = $currentPlacement.text();

      var lastCharacters = currentPlacementText.slice(-2);
      var initialCharacters = currentPlacementText.slice(0,-2);

      $currentPlacement.html(initialCharacters + '<span class="circuit-detail__mini-text">' + lastCharacters + '</span>' );

		});

		// RESPONSIVE VIDEOS - FITVIDS

		// OWL CAROUSEL SLIDERS

		var $blockTeasersEl = $(".block-blog-teasers");

		var $currentScheduledItem = $blockTeasersEl.find('.blog-item-current');
		var $currentScheduledItemNumber = ($currentScheduledItem.data('blognumber') - 1);

		var showNav;

		if($blockTeasersEl.data('show-nav') !== undefined) {
			showNav = true;
    }

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
      $blockTeasersEl.owlCarousel({
				items : 1,
				video : true,
        pagination:false,
				nav : showNav,
				autoplayTimeout : 4000,
				autoplayHoverPause : true,
				responsive : {
			        480 : {
								items : 1
			        },
			        767 : {
								items : 2
			        },
			        991 : {
								items : 3,
                dots:false
			        }
			    }
			});
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

    //$blockTeasersEl.trigger('next.owl.carousel');
		if($currentScheduledItemNumber) {
      $blockTeasersEl.trigger('to.owl.carousel', [$currentScheduledItemNumber, 0, true]);
    }

		// Countdown Clock

    // Set the date we're counting down to

		var countdownClockEl = $('#js-countdown-clock');

		if(countdownClockEl.length > 0) {
      var countdownClockEventDate = countdownClockEl.data('eventtime');
      var countdownClockEventTime = new Date(countdownClockEventDate.replace(/\s/, 'T')).getTime();

      // Update the count down every 1 second
      var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countdownClockEventTime - now;

        // Time calculations for days, hours, minutes and seconds
        var days = ("0" + Math.floor(distance / (1000 * 60 * 60 * 24))).slice(-3);
        var hours = ("0" + Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).slice(-2);
        var minutes = ("0" + Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).slice(-2);
        var seconds = ("0" + Math.floor((distance % (1000 * 60)) / 1000)).slice(-2);

        // Display the result in the element with id="demo"
        countdownClockEl.html('<div class="countdown-clock__timer"><span class="countdown-clock__date">' + days + '</span>' + '<span class="countdown-clock__date">' + hours + '</span>' + '<span class="countdown-clock__date">' + minutes + '</span>' + '<span class="countdown-clock__date">' + seconds + '</span></div><div class="countdown-clock__labels"><span class="countdown-clock__label">Days</span><span class="countdown-clock__label">Hours</span><span class="countdown-clock__label">Mins</span><span class="countdown-clock__label">Secs</span></div>');

        // If the count down is finished, write some text
        if (distance < 0) {
          clearInterval(x);
          // Display the result in the element with id="demo"
          countdownClockEl.html('<div class="countdown-clock__timer"><span class="countdown-clock__date">' + '000' + '</span>' + '<span class="countdown-clock__date">' + '00' + '</span>' + '<span class="countdown-clock__date">' + '00' + '</span>' + '<span class="countdown-clock__date">' + '00' + '</span></div><div class="countdown-clock__labels"><span class="countdown-clock__label">Days</span><span class="countdown-clock__label">Hours</span><span class="countdown-clock__label">Mins</span><span class="countdown-clock__label">Secs</span></div>');

        }
      }, 1000);

    }

    /** Inject logo into carousel **/

    var $carouselScope = $('.home');
    var $carouselContainer = $carouselScope.find('#section-intro');

    $carouselContainer.append('<div class="hero-carousel__logo-overlay"></div>');


		// FLEXSLIDER

		try {
			$(".flexslider").flexslider();
		} catch (e) {
			// TODO: handle exception
			console.log( e.message );
		}

	});
  

})(jQuery);