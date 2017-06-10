(function($) {
	"use strict";
	$(document).ready(function() {	
		
		try {
			$.browserSelector();
			// Adds window smooth scroll on chrome.
			if($("html").hasClass("chrome")) {
				//$.smoothScroll();
			}
		} catch(e) {
			console.log( e.message );
		}		
		
		try {
			/* ================ ANIMATED CONTENT ================ */
	        if ($(".animated")[0]) {
	            $('.animated').css('opacity', '0');
	        }

	        $('.triggerAnimation').waypoint(function() {
	            var animation = $(this).attr('data-animate');
	            $(this).css('opacity', '');
	            $(this).addClass("animated " + animation);

	        },
	                {
	                    offset: '95%',
	                    triggerOnce: true
	                }
	        );
		}  catch(e) {
			console.log( e.message );
		}		
		
		try{
			jQuery("a.player").mb_YTPlayer();
		}  catch(e) {
			console.log( e.message );
		}		
		$('form#newsletter').submit(function(){
			var data_form = $(this).serialize();
			var submit_label			=	$('input[name="submit_label"]').val();
			var submit_label_waiting	=	$('input[name="submit_label_waiting"]').val();
			var submit_label_success	=	$('input[name="submit_label_success"]').val();
			var submit_label_error		=	$('input[name="submit_label_error"]').val();			
			jQuery.ajax({
				type:'POST',
				data:data_form,
				url:jsvars.ajaxurl,
				beforeSend:function(){
					$('#newsletter-error').html('');
					$('form#newsletter input[type="submit"]').val( submit_label_waiting );
				},
				success:function(data){
					var result = '';
					var data = $.parseJSON(data);
					if( data.resp == 'success' ){
						result = '<div class="form-feedback form-success">'+data.message+'</div>';
					}
					else{
						result = data.message;
					}
					$('#newsletter-error').html(result);
					$('form#newsletter input[type="submit"]').val( submit_label );
				}
			});
			return false;
		});
				
		// END OF SCRIPTS
	});
})(jQuery);