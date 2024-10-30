(function( $ ) {
	'use strict';

	$(document).ready(function(){


		$('.social-checkbox').change(function(){
		    isCheckboxChecked();
		});

		$('.random-post-checkbox').change(function(){
		    checkGeneralCheckbox();
		});

		function isCheckboxChecked(){

			if(!$('.social-checkbox').length){
				$('.social-input').prop( "disabled", false );
			}else{
				if ($('.social-checkbox').is(':checked')) {
					$('.social-input').prop( "disabled", false );
				}else{
					$('.social-input').prop( "disabled", true );
				}
			}
		}

		function checkGeneralCheckbox(){
			//social-num-posts
			//random-posts

			if(!$('.random-post-checkbox').length){
				$('.social-num-posts').show();
			}else{
				if ($('.random-post-checkbox').is(':checked')) {
									
					hideElementAndTr('.social-num-posts');
					showElementAndTr('.random-posts');

				}else{
					showElementAndTr('.social-num-posts');
					hideElementAndTr('.random-posts');
				}
			}
		}

		function showElementAndTr(elClass){
			$(elClass).show();
			$(elClass).closest('tr').show();
		}

		function hideElementAndTr(elClass){
			$(elClass).hide();
			$(elClass).closest('tr').hide();
		}

		isCheckboxChecked();
		checkGeneralCheckbox();




		$('.insta-client').change(function(){
			updateInstaTokenUrl();
		});

		//insta-redirect
		$('.insta-redirect').change(function(){
			updateInstaTokenUrl();
		});

		function updateInstaTokenUrl(){

			var linkEl = $('#insta-token-link');
			var client = $('.insta-client').val();
			var redirect = $('.insta-redirect').val();

			var newLink = 'https://api.instagram.com/oauth/authorize/?client_id='+ client +'&redirect_uri='+ redirect +'&response_type=token';
			linkEl.attr('href',newLink);
		}

		$( "#little_social_media_settings" ).submit(function( event ) {
			//have to set disabled to false so settings arent cleared
		  $('.social-input').prop( "disabled", false );
		});

	});

})( jQuery );
