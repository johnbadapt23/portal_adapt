(function($){

	$(document).ready(function (){

		// STANDARD
		@@include('includes/_maps.js')

		resize();
		matchHeightInit();
		select2();
		outsideContainer();
		scrollMobile();

		// var uri = window.location.href.toString();
		// if (uri.indexOf("?membership=") > 0) {
		//     var clean_uri = uri.substring(0, uri.indexOf("?"));
		//     window.history.replaceState({}, document.title, clean_uri);
		// }

		if($('.progress-container').length ){
			scrollProgressBar();
		}

		$(document).on( 'nfFormReady', function( e, layoutView ) {
			select2();
		})

		// Search results clear

		$('.clear-search').on('click', function(e) {
			$(this).siblings('form').children('.searchInput').val('');
		});

		// Interactive prompts
		$('.full-screen-scrolldown').on('click', function(e) {
			var $windowHeight = $(window).height();
			var $heightOffset = $windowHeight -100;
			$('html, body').animate({ scrollTop: $('.full-screen-prompt').offset().top - $heightOffset}, 1000);
		});

		$('.full-screen-prompt').on('click', function(e) {
			$(".tableauPlaceholder").contents().find(".enterFullscreen").click();
		});

		// SCROLL UP TO SEE FULL MENU

		var lastScrollTop = 0;
		$(window).scroll(function(event){
		   var st = $(this).scrollTop();
		   if (st > lastScrollTop){
		        $('header').removeClass('scrolledUp');
		   } else {
		        $('header').addClass('scrolledUp');
		   }
		   lastScrollTop = st;
		});

		function updateUserInterests() {
			var filter = $('#updateUserInterests');
			$.ajax({
				url: filter.attr('action'),
				data: filter.serialize(), // form data
				type: filter.attr('method'), // POST
				beforeSend: function(xhr) {
					$('#responseText').text('Processing...'); // Show processing message
				},
				success: function(data) {
					$('#responseText').text(''); // Clear processing message
					// Optionally, handle the response here
				}
			});
		}

		// Debounced AJAX call
		const debouncedUpdate = debounce(updateUserInterests, 10000); // Delay set to 10 second

		// Immediate label and class updates on checkbox change
		$('#updateUserInterests input[type=checkbox]').change(function(e) {
			if (this.checked) {
				$(this).siblings('label').text('following');
				$(this).siblings('span.see-more-topic').addClass('active');
			} else {
				$(this).siblings('label').text('follow');
				$(this).siblings('span.see-more-topic').removeClass('active');
			}

			// Call the debounced function for AJAX
			debouncedUpdate();
		});

		// Download ajax

		$('.preview-module .download').on('click', function(e) {
			// Ensure the default action (e.g., file download) happens
			var downloadUrl = $(this).attr('href');

			// Send AJAX request without preventing the default download behavior
			$.ajax({
				url: '/wp-admin/admin-ajax.php',
				type: 'POST',
				data: {
					action: 'update_download_counter'
				},
				success: function(response) {
					console.log('Download counter updated successfully');
				},
				error: function(xhr, status, error) {
					console.log('Error updating download counter: ' + error);
				}
			});

			// Allow the default behavior to proceed (e.g., file download)
		});

		// SWITCH BETWEEN GRID AND LIST VIEW ON SUB TOPICS

		$('body').addClass('gridStyle');
		$('.gridIcon').addClass('active');

		$('span.gridIcon').on('click', function(e) {
			$('body').addClass('gridStyle');
			$('body').removeClass('listStyle');
			$('.gridIcon').addClass('active');
			$('.listIcon').removeClass('active');
		});

		$('span.listIcon').on('click', function(e) {
			$('body').removeClass('gridStyle');
			$('body').addClass('listStyle');
			$('.gridIcon').removeClass('active');
			$('.listIcon').addClass('active');
		});

		// FILTERS REVEAL TOPICS

		$('.search-toggle').on('click', function(e) {
			if ( $(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).parent('.search').removeClass('active');
				$('header').removeClass('search-active');
			} else {
				$(this).addClass('active');
				$(this).parent('.search').addClass('active');
				$('header').addClass('search-active');
			}
		});

		$('.search-close-mobile').on('click', function(e) {
			$(this).siblings('search-toggle').removeClass('active');
			$(this).parent('.search').removeClass('active');
			$('header').removeClass('search-active');
		});

		// Search toggle

		$('.filtersButtonMobile').on('click', function(e) {
			$(this).toggleClass('active');
			$('.dropDowns').toggleClass('active');
		});

		// Disable right click on video blocks

   		$('video').bind('contextmenu',function() { return false; });

		// AUDIO PLAYER REVEAL

		$('a.audioReveal').on('click', function(e) {
			e.preventDefault();
			$('.audioWrapper').toggleClass('show');
		});

		// MEDIA ELEMENT PLAYER

		$('audio').mediaelementplayer({

		});

		// Show password

		$('img.show-password').on('click', function(e) {
			var x = document.getElementById("user_pass");
			if (x.type === "password") {
				x.type = "text";
				$(this).addClass('active');
			} else {
				x.type = "password";
				$(this).removeClass('active');
			}
		});

		// MEGA MENU

		var timer;

		$('.dropdown').on('mouseover',function (e){
			if ($(window).width() >= 1023) {
				clearTimeout(timer);
				$(this).siblings('.dropdown').removeClass('active');
				$(this).addClass('active');
				openOverlay();
			}
		});

		$('.dropdown').on('click', function(e) {
			if ($(window).width() < 1023) {
				// e.preventDefault();
				if($(this).hasClass('active')){
					$('.megaMenu').removeClass('active');
					$('.dropdown').removeClass('active');
					if ($(window).width() >= 1023) {
						$('main').removeClass('menu-open');
						$('section.navigation').css('z-index', 100);
						$('header').addClass('menu-open');
						$('header span.logo').addClass('menu-open');
					}
				} else {
					$('.megaMenu').removeClass('active');
					$('.dropdown').removeClass('active');
					if ($(window).width() >= 1023) {
						$('main').removeClass('menu-open');
						$('section.navigation').css('z-index', 98);
						$('header').removeClass('menu-open');
						$('header span.logo').removeClass('menu-open');
					}
					$(this).addClass('active');
					if($(this).hasClass('research-menu')) {
						$('.megaMenu.researchMenu').addClass('active');
					}
					if($(this).hasClass('events-menu')) {
						$('.megaMenu.eventsMenu').addClass('active');
					}
					if($(this).hasClass('adapt-menu')) {
						$('.megaMenu.adaptMenu').addClass('active');
					}
				}
			}
		});

		$('.mobile-menu-title').on('click', function(e) {
			e.preventDefault();
			if ($(window).width() < 1023) {
				$('.megaMenu').removeClass('active');
				$('.dropdown').removeClass('active');
			}
		});


		$('.dropdown').on('mouseout',function (e){
			if ($(window).width() >= 1023) {
				timer = setTimeout(function(){
					$('.megaMenu').removeClass('active');
					$('.dropdown').removeClass('active');
					closeOverlay();
				}, 500);
			}
		});

		$('.megaMenu').on('mouseover',function (e){
			clearTimeout(timer);
		});

		function openOverlay() {
			$('.menu-overlay').addClass('active');
		}

		function closeOverlay() {
			$('.menu-overlay').removeClass('active');
		}

		// Checkbox click, uncheck siblings

		$('.checkboxButton').on('click', function(e) {
			$(this).siblings().children('label').children('input').prop("checked", false);
		});

		// FIXED HEADER OPACITY

		$(window).on('scroll', function () {
			headerSet();
		});

		$('.downArrow').on('click', function(e) {
			$('html, body').animate({ scrollTop: $(this).parent().parent('section').next('section').offset().top - 90}, 1000);
		});

		$('.nextSection').on('click', function(e) {
			$('html, body').animate({ scrollTop: $(this).parent().parent('section').next('article').offset().top - 90}, 1000);
		});

		$('.backTop').on('click', function(e) {
			$('html, body').animate({ scrollTop: $('body').offset().top - 0}, 1000);
		});


		$('.download-popup-button').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).parent('.container').siblings('.downloadPopupContainer').children('.downloadPopup'),
					type: 'inline'
				},
				mainClass: 'download-container',
				callbacks: {
				  open: function() {
					  $(window).trigger('resize');
				  }
	  	  		}
			});
		});


		$('.download-popup-button-multi').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).siblings('.downloadPopupContainer').children('.downloadPopup'),
					type: 'inline'
				},
				mainClass: 'download-container',
				callbacks: {
				  open: function() {
					  $(window).trigger('resize');
				  }
	  	  		}
			});
		});

		$('.formPopupCardButton').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).parent('.twoColumnCard').children('.cardPopupContainer').children('.cardPopup'),
					type: 'inline'
				},
				mainClass: 'form-container'
			});
		});

		$('.formPopupPartners').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'form-container'
			});
		});

		$('.formPopupHubspot').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'form-container-preview'
			});
		});

		
		$('.locked-request.download').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'form-container-preview'
			});
		});

		$('.formPopupRegister').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'form-container'
			});
		});

		$('.formPopupCardTextButton').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).parent('.textContainer').parent('.twoColumnCard').children('.cardPopupContainer').children('.cardPopup'),
					type: 'inline'
				},
				mainClass: 'form-container'
			});
		});

		$(".excerpt-scroll-to-content").html(function(){
		  var text= $(this).text().trim().split(" ");
		  var last = text.pop();
		  return text.join(" ") + (text.length > 0 ? " <span class='excerpt-scroll-to-content-button'>" + last + "</span>" : last);
		});

		$(".speaker-details-excerpt").html(function(){
		  var text= $(this).text().trim().split(" ");
		  var last = text.pop();
		  return text.join(" ") + (text.length > 0 ? " <span class='speaker-excerpt-see-all'>" + last + "</span>" : last);
		});

		$('.speaker-excerpt-see-all').on('click', function(e) {
			$(this).parents('.speaker-details-excerpt').hide();
			$(this).parents('.speaker-details-excerpt').siblings('.speaker-details').slideDown(300);

			return;
		});

		$('.speaker-details-less').on('click', function(e) {
			$(this).parents('.speaker-details').hide();
			$(this).parents('.speaker-details').siblings('.speaker-details-excerpt').show();

			return;
		});


		$('.excerpt-scroll-to-content-button').on('click', function(e) {
			e.preventDefault();
			if($('section.webinar-article').length){
				$('html, body').animate({ scrollTop: $('section.webinar-article').offset().top - 100}, 800);
			}

		});

		$('.scroll-to-overview').on('click', function(e) {
			e.preventDefault();
			if($('.article-content').length){
				$('html, body').animate({ scrollTop: $('.article-content').offset().top - 150}, 800);
			}

		});

		$('.article-content .articleWrapper a').on( 'click', function(){
		    var target = this.hash;
			if( target ){
				$target = $(target);
			    $('html, body').animate({ scrollTop: $target.offset().top-120}, 1000);
			}
		});

		$('.popup').magnificPopup({
        	type: 'image',
          	closeOnContentClick: true,
          	mainClass: 'mfp-img-mobile',
          	image: {
            	verticalFit: true
          	}
        });

		$('.popup-vimeo').magnificPopup({
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
		  enableEscapeKey: true,
          preloader: false,
          fixedContentPos: false
        });

		$('.popup-podcast').magnificPopup({
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,
          fixedContentPos: false
        });

		// Mobile App form popup

		$('.popup-link-init').magnificPopup({
			type: 'inline',
			mainClass: 'mobile-app-download-container',
			callbacks: {
			  open: function() {
				  $(window).trigger('resize');
			  }
			}
		});

		// EVR dropdowns 

		$('.dropdown-button').on('click', function(e) {
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('.other-dropdown').removeClass('active');
			} else {
				$(this).addClass('active');
				$('.other-dropdown').addClass('active');
			}
		});

		$('.close-dropdown').on('click', function(e) {
			$('.dropdown-button').removeClass('active');
			$('.other-dropdown').removeClass('active');
		});

		// Scroll To Button

		$('.scroll-to-button').on( 'click', function(e){
			e.preventDefault();
			$section = $(this).attr('href');
			if($(window).width() > 900) {
		    	$('html, body').animate({ scrollTop: $($section).offset().top - 80 }, 1000);
			} else {
				$('html, body').animate({ scrollTop: $($section).offset().top - 60 }, 1000);
			}
		});

		// Post article images popup

		var images = $('div.articleWrapper img');
		$(images).each(function() {
			var imageSrc = $(this).attr('src');

		   $(this).wrap('<a class="post-popup" href="'+ imageSrc +'"></a>');
		   $(this).parents('a.post-popup').append('<span class="enlarge-image"></span>');
		});

		var imagesOther = $('div.article-content img');
		$(imagesOther).each(function() {
			var imageSrc = $(this).attr('src');

		   $(this).wrap('<a class="post-popup" href="'+ imageSrc +'"></a>');
		   $(this).parents('a.post-popup').append('<span class="enlarge-image"></span>');
		});

		$('.post-popup').magnificPopup({
			type: 'image',
			mainClass: 'mfp-post-img'
		})

		$('.button-form-container .form-button.ios-button').on('click', function(e) {
			e.preventDefault();
			$('.button-form-container .form-button').hide();
			$(this).siblings('.form-container.ios-form').show();
		});

		$('.button-form-container .form-button.android-button').on('click', function(e) {
			e.preventDefault();
			$('.button-form-container .form-button').hide();
			$(this).siblings('.form-container.android-form').show();
		});


		$('a.fixednav').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')) {
				if($('section.navigation').hasClass('open')) {
					$('section.navigation').removeClass('open');
				}
				if($('section.navigation').hasClass('fixed')) {
					$('section.navigation').removeClass('open');
				}
				if($('body').hasClass('post')) {
					$('header').removeClass('hamburger-menu-open');
				}
				$(this).removeClass('active');
			} else {
				if($('body').hasClass('post')) {
					$('header').addClass('hamburger-menu-open');
				}
				if($('section.navigation').hasClass('fixed')) {
					$('section.navigation').addClass('open');
				}
				$(this).addClass('active');
				$('section.navigation').addClass('open');
			}
		});

		// Generic Register form hidden fields

		if($('.webinar-register-form').length ){

			var hiddenName = $('.hidden-name').text();
			var hiddenEvent = $('.hidden-event').text();
			var hiddenDate = $('.hidden-date').text();
			var hiddenID = $('.hidden-id').text();
			var genericForm = $('.webinar-register-form .form-container form');
			setTimeout(function(){
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_name').children('div.input').children('input').attr('value', hiddenName);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_name').children('div.input').children('input').val(hiddenName).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_title').children('div.input').children('input').attr('value', hiddenEvent);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_title').children('div.input').children('input').val(hiddenEvent).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_date').children('div.input').children('input').attr('value', hiddenDate);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_date').children('div.input').children('input').val(hiddenDate).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_sf_id').children('div.input').children('input').attr('value', hiddenID);
				$('.webinar-register-form .form-container form').find('.hs-hidden_sf_id').children('div.input').children('input').val(hiddenID).change();
			}, 2000);

			if($('.gift-opt-in-text').length ){
				var giftOptIn = $('.gift-opt-in-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_gift_opt_in').children('legend.hs-field-desc').html(giftOptIn);
				}, 2000);
			}

			if($('.marketing-text').length ){
				var marketingOptIn = $('.marketing-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_single_client_opt_in').children('.input').children('ul').children('li').children('label').children('span').html(marketingOptIn);
				}, 2000);
			}

			if($('.umbrella-help-text').length ){
				var umbrellaHelp = $('.umbrella-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_opt_in').children('legend.hs-field-desc').html(umbrellaHelp);
				}, 2000);
			}

			if($('.umbrella-text').length ){
				var umbrellaOptIn = $('.umbrella-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_opt_in').children('.input').children('ul').children('li').children('label').children('span').html(umbrellaOptIn);
				}, 2000);
			}
		}


		// MOBILE NAV

		$('a.nav').on('click', function(e) {
		e.preventDefault();
		if($(this).hasClass('active')) {
			$('.megaMenu').removeClass('active');
			$('.dropdown').removeClass('active');
			$(this).removeClass('active');
			$('nav.mobileMenu').removeClass('active');
			$('body').removeClass('mobileMenu');
			$('html').removeClass('fixed');
			$('li.dropDown.topics ul.sub-menu.active').removeClass('active');

		} else {
			$(this).addClass('active');
			$('nav.mobileMenu').addClass('active');
			$('body').addClass('mobileMenu');
			$('html').addClass('fixed');
			}
		});

		$('a.nav').on('click', function(e) {
			$('span.ham').toggleClass('active');
		});

		$('li.dropDown.topics > a').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).siblings('ul.sub-menu').removeClass('active');

			} else {
				$(this).addClass('active');
				$(this).siblings('ul.sub-menu').addClass('active');
			}
		});

		if ($(window).width() <= 767) {
			$('.research-top-navigation .navigation-container .column:first-child').addClass('active');
			$('.research-top-navigation .navigation-container .column:first-child .columnTitle').addClass('active');
			$('.research-top-navigation .navigation-container .column:first-child .dropDownSection ul').show();
		}

		$('.research-top-navigation .navigation-container .column .columnTitle').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).parent('.dropDownSection').parent('.column').removeClass('active');
				$(this).siblings('ul').slideUp(300);
			} else {
				$(this).parent('.dropDownSection').parent('.column').siblings('.column').removeClass('active');
				$(this).parent('.dropDownSection').parent('.column').siblings('.column').children('.dropDownSection').children('.columnTitle').removeClass('active');
				$(this).parent('.dropDownSection').parent('.column').siblings('.column').children('.dropDownSection').children('ul').slideUp(300);
				$(this).addClass('active');
				$(this).parent('.dropDownSection').parent('.column').addClass('active');
				$(this).siblings('ul').slideDown(300);
			}
		});

		$('li.parent').on('click', function(e) {
			$(this).parent('.sub-menu.active').removeClass('active');
			$(this).parent('.sub-menu').siblings('a').removeClass('active');
		});

		$('li.dropDown.events > a').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).siblings('ul.sub-menu').removeClass('active');

			} else {
				$(this).addClass('active');
				$(this).siblings('ul.sub-menu').addClass('active');
			}
		});

		// Fixed menu mobile functionality

		if ($(window).width() <= 767) {
			$('section.navigation.fixed-menu ul').on('click', function(e) {
				e.preventDefault();
				if($(this).hasClass('active')){
					$(this).removeClass('active');
				} else {
					$(this).addClass('active');
				}
			});
		}

		// webinar filter buttons

		$('.register-filter .register-toggle.all-button').on('click', function(e) {
			$('.register-filter .register-toggle.upcoming-toggle-button').removeClass('active');
			$('.register-filter .register-toggle.past-button').removeClass('active');
			$(this).addClass('active');
			$('.register-listing-container').addClass('active');
		});

		$('.register-filter .register-toggle.upcoming-toggle-button').on('click', function(e) {
			$('.register-filter .register-toggle.all-button').removeClass('active');
			$('.register-filter .register-toggle.past-button').removeClass('active');
			$(this).addClass('active');
			$('.register-listing-container.upcoming').addClass('active');
			$('.register-listing-container.past-sessions').removeClass('active');
		});

		$('.register-filter .register-toggle.past-button').on('click', function(e) {
			$('.register-filter .register-toggle.all-button').removeClass('active');
			$('.register-filter .register-toggle.upcoming-toggle-button').removeClass('active');
			$(this).addClass('active');
			$('.register-listing-container.upcoming').removeClass('active');
			$('.register-listing-container.past-sessions').addClass('active');
		});

		// Ecosystem Partners switchers 
		$('.menu-item-container .partners-content-switch-trigger').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')){

			} else {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
				thisEcoIndex = $(this).index();
				if($(this).hasClass('partners-content-switch-trigger-testimonials')){					
					$('.quote-slider-module').slick('refresh');
					$('.quote-slider-module').slick('slickGoTo', 0);
				}
				$('.partners-switch-content .switch-content').eq(thisEcoIndex).siblings().removeClass('active');
				$('.partners-switch-content .switch-content').eq(thisEcoIndex).addClass('active');
				$target = $('#partnerContent');
				$('html, body').animate({ scrollTop: $target.offset().top-110}, 500);
			}			
		});

		// Partners mobile filter 

		$('.mobile-filter-trigger').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.partners-form').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.partners-form').slideDown(300);
			}
		});

		// Ecosystem who we help excerpt dropdown

		$('.partners-excerpt-readmore.read-more').on('click', function(e) {
			$(this).parents('.partners-help-excerpt').hide();
			$(this).parents('.partners-help-excerpt').siblings('.partners-help-details').slideDown(300);			
		});

		$('.partners-excerpt-less.read-less').on('click', function(e) {
			$(this).parents('.partners-help-details').siblings('.partners-help-excerpt').slideDown(300);
			$(this).parents('.partners-help-details').hide();			
		});

		// Ecosystem team popup 

		$('a.speaker-popup').magnificPopup({
			type: 'inline',
			mainClass: 'mfp-speakers',
			preloader: false,
			gallery: {
				enabled: $('a.speaker-popup').length > 1
			},
			callbacks: {
				change: function () {
				var $about = this.content.find('.about-text');
				if ($about.length && typeof PerfectScrollbar !== 'undefined') {
					try {
						if ($about[0]._perfectScrollbar) {
							$about[0]._perfectScrollbar.destroy();
							$about[0]._perfectScrollbar = null;
						}
					} catch(e) {}
					setTimeout(function(){
						try { $about[0]._perfectScrollbar = new PerfectScrollbar($about[0]); } catch(e) {}
					}, 1);
				}
				},
				buildControls: function () {
				if (this.arrowLeft && this.arrowRight) {
					this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
				}
				}
			}
			});


		// KYC switchers 
		$('.chapters-container .chapter-selector').on('click', function(e) {
			if($(this).hasClass('active')){

			} else {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
				thisKYCIndex = $(this).index();
				$('.chapters-content-container .chapter-content').eq(thisKYCIndex).siblings().removeClass('active');
				$('.chapters-content-container .chapter-content').eq(thisKYCIndex).addClass('active');
			}			
		});

		$('.content-switch-container .kyc-switch').on('click', function(e) {
			if($(this).hasClass('active')){

			} else {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
				if($(this).hasClass('overview-switch')){
					$(this).parent().siblings('.kyc-chapter-content-container').children('.resources-content').removeClass('active');
					$(this).parent().siblings('.kyc-chapter-content-container').children('.overview-content').addClass('active');
				} else {					
					$(this).parent().siblings('.kyc-chapter-content-container').children('.overview-content').removeClass('active');
					$(this).parent().siblings('.kyc-chapter-content-container').children('.resources-content').addClass('active');
				}
			}			
		});

		// FILTER SHOW MORE

		$('.formContainer .categories .more').on('click', function(e) {
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('.formContainer form').removeClass('active');
				$('.formContainer form .categories').removeClass('active');
				$(this).text('more');
			} else {
				$(this).addClass('active');
				$(this).text('close');
				$('.formContainer form').addClass('active');
				$('.formContainer form .categories').addClass('active');
			}
		});

		$('.categories .checkboxButton').on('click', function(e) {
			$('.formContainer .categories .more').addClass('active');
			$('.formContainer .categories .more').text('close');
			$('.formContainer form').addClass('active');
			$('.formContainer form .categories').addClass('active');
		});

		// Kits Isotope Filtering 

		// init Isotope
        // Initialize Isotope
		var grids = document.querySelectorAll('.grid'); // Use querySelectorAll to get all grid elements
		var isos = [];

		grids.forEach(function(grid) {
			var iso = new Isotope(grid, {
				itemSelector: '.kit-item',
				layoutMode: 'fitRows'
			});

			isos.push(iso);
		});

        // filter functions
        var filterFns = {
            // show if number is greater than 50
            numberGreaterThan50: function( itemElem ) {
                var number = itemElem.querySelector('.number').textContent;
                return parseInt( number, 10 ) > 50;
            },
            // show if name ends with -ium
            ium: function( itemElem ) {
                var name = itemElem.querySelector('.name').textContent;
                return name.match( /ium$/ );
            }
        };

       // Bind filter button click for each button group
		var filterGroups = document.querySelectorAll('.filter-group-listing');

		filterGroups.forEach(function(filtersElem) {
			filtersElem.addEventListener('click', function(event) {
				var filterValue;
				var target = event.target;

				// Find the nearest <a> element by traversing the DOM
				var anchorElement = target.closest('a');

				if (anchorElement) {
					filterValue = anchorElement.getAttribute('data-filter');


					// Toggle the 'is-checked' class on the clicked <a>
					anchorElement.classList.toggle('is-checked');

					// Handle the "All" filter
					if (anchorElement.classList.contains('all-filter')) {
						// If "All" is clicked, uncheck all other filter buttons
						document.querySelectorAll('.kit-filter').forEach(function(button) {
							if (button !== anchorElement) {
								button.classList.remove('is-checked');
							}
						});
					} else {
						// If any other filter is clicked, uncheck the "All" filter
						document.querySelector('.kit-filter.all-filter').classList.remove('is-checked');
					}

					// Initialize an array to store multiple filter values
					var filterValues = [];

					// Loop through checked filter buttons to collect filter values
					var checkedFilterButtons = document.querySelectorAll('.kit-filter.is-checked');
					checkedFilterButtons.forEach(function(button) {
						filterValues.push(button.getAttribute('data-filter'));
					});

					 // If no filters are checked, check the "All" filter
					if (filterValues.length === 0) {
						document.querySelector('.kit-filter.all-filter').classList.add('is-checked');
					}

					// Combine the filter values using a comma to select multiple items
					filterValue = filterValues.join(', ');

					// Apply filter to each Isotope instance
					isos.forEach(function(iso) {
						iso.arrange({ filter: filterValue });
					});
				}
			});
		});

		// // Change is-checked class on buttons for each button group
		// var buttonGroups = document.querySelectorAll('.button-group');

		// buttonGroups.forEach(function(buttonGroup) {
		// 	radioButtonGroup(buttonGroup);
		// });

		// function radioButtonGroup(buttonGroup) {
		// 	buttonGroup.addEventListener('click', function(event) {
		// 		var target = event.target;

		// 		var anchorElement = target.closest('a');
				
		// 		if (anchorElement) {
		// 			 // Check if the clicked <a> already has 'is-checked' class
		// 			 anchorElement.classList.toggle('is-checked');
		// 		}
		// 	});
		// }

		// Know your customer slider 

		$('.kit-slider-container .kit-slider').slick({
		  arrows: false,
		  dots: true,
		  autoplay: false,		
		  slidesToShow: 1,
		  fade: true,
		  speed: 300,
		  cssEase: 'linear'		  
		});

		// MAGNIFIC FORM POPUP

		$('.popup-modal').magnificPopup({
			type: 'inline',
			preloader: false,
			// modal: true
		});

		$('.sharepopup').magnificPopup({
			type: 'inline',
			preloader: false,
			mainClass: 'mfp-registration'
			// modal: true
		});

		$('.datasharepopup').magnificPopup({
			type: 'inline',
			preloader: false,
			mainClass: 'mfp-datasharepopup'
			// modal: true
		});

		$('.loginPopupButton').magnificPopup({
			type: 'inline',
			preloader: false,
			// modal: true
		});


		$('.loginButton').magnificPopup({
			type: 'inline',
			preloader: false,
			modal: true
		});

		$('.loginPopupButton').on('click', function(e){
			if($('input[name="redirect_to"]').length ){
				var inputVal = $('input[name="redirect_to"]').val();
				var newVal = inputVal.replace('+', '%C2%A0');
				$('input[name="redirect_to"]').val(newVal);
			}
		});

		$('.register-scroll-button').magnificPopup({
			type: 'inline',
			preloader: false,
			mainClass: 'mfp-registration',
			callbacks: {
			  open: function() {
				  $(window).trigger('resize');
			  }
			}
		});

		$('.popup-modal-dismiss').on('click', function(e) {
			e.preventDefault();
			$.magnificPopup.close();
		});

		// NEWS PAGINATION

		$('#pagination a').on('click', function(e){
          e.preventDefault();
          $(this).addClass('loading').text('Loading...');
          $.ajax({
              type: "GET",
              url: $(this).attr('href') + '#loop',

              dataType: "html",
              success: function(out){

                  var result = $(out).find('#loop .postLink');
                  var nextlink = $(out).find('#pagination a').attr('href');
                  $('#loop').append(result.fadeIn(300));
				  if($('#loop').hasClass('list')) {
					  $('#loop .postLink').addClass('list-view');
				  }
				  matchHeightInit();
                  $('#pagination a').removeClass('loading').text('Load More');
                  if (nextlink != undefined) {
                      $('#pagination a').attr('href', nextlink);
                  } else {
                      $('#pagination').remove();
                  }
             }
          });
       });

		// BACK TO TOP

		$('.backTop').on('click', function(e) {
	        e.preventDefault();
			$('html, body').animate({ scrollTop: 0}, 1000);
		});

		// AGENDA DAY SCROLL TO

		$('section.navigation .container ul li a.scroll-button').on('click', function(e) {
		    e.preventDefault();
		    var target = this.hash;
		    $target = $(target);
			$('section.navigation .container ul li a.scroll-button').removeClass('active');
			$(this).addClass('active');
		    $('html, body').animate({ scrollTop: $target.offset().top-120}, 1000);
			setTimeout(function(){
				$('section.navigation ul.active').removeClass('active');
			},100);
		});

		// ACCORDION AGENDA

		var speed = "500";

		$('section.itineraryBlock .agendaBlock .item .container .inner .read-more-container .read-more').on('click', function(e) {
			$(this).parents('.read-more-container').prev().slideToggle(speed);

			$(this).parent().parent().siblings().children('.hidden').slideUp(speed);

			var img = $(this);

			$('img').not(img).removeClass('rotate');

			img.toggleClass('rotate');

			if($(img).hasClass('rotate')){
				$(img).text('- Read Less');
			} else {
				$(img).text('+ Read More');
			}


		});

		// Filter mobile dropdown

		$('.mobile-filter-dropdown').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.mobile-filter-content').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.mobile-filter-content').slideDown(300);
			}
		});		

		// FAQ Accordion

		$('.faq-title').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.faq-content').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.faq-content').slideDown(300);
			}
		});

		// Home Accordion 

		$('.accordion-item .question').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.answer').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.answer').slideDown(300);
			}
		});

		// Kit filter dropdowns 

		$('.filter-group-toggle.with-buttons').on('click', function(e) {
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.filter-group-listing').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.filter-group-listing').slideDown(300);
			}
		});

		


		$('.popup-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			mainClass: 'mfp-img-mobile',
			closeOnContentClick: true,
			gallery: {
				verticalFit: true,
				enabled: true,
				navigateByImgClick: true
			},
		});

		// FEATURED SLIDER CAROUSEL

		$('.featuredSlider.portal .slider').slick({
		  arrows: true,
		  dots: true,
		  autoplay: true,
		  autoplaySpeed: 8000,
		  slidesToShow: 1,
		  responsive: [
		    {
		      breakpoint: 1023,
		      settings: {
		        arrows: false,
		        slidesToShow: 1
		      }
		    }
		  ]
		});

		$('.featuredSlider.portal .slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){

		});

		$('.featuredSlider.portal .slider').on('afterChange', function(event, slick, currentSlide, nextSlide){

		});

		$('.data-article-slider').slick({
		  arrows: false,
		  dots: true,
		  fade: true,
		  autoplay: true,
		  autoplaySpeed: 8000,
		  slidesToShow: 1,
		  responsive: [
		    {
		      breakpoint: 1023,
		      settings: {
		        arrows: false,
		        slidesToShow: 1
		      }
		    }
		  ]
		});

		// EVENT SLIDER
		var containerWidth = $('.container').width();
		var windowWidth = $(window).width();
		var paddingWidth = (windowWidth - containerWidth ) / 2 - 8;
		var paddingWidthPx = paddingWidth + 'px';
		// console.log(paddingWidthPx);
		$('.eventSlider .slider').slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  centerMode: true,
		  centerPadding: paddingWidthPx,
		  autoplay: false,
		  slidesToShow: 3,
		  responsive: [
		    {
		      breakpoint: 768,
		      settings: {
		        arrows: false,
		        centerMode: false,
		        slidesToShow: 2,
		      }
		  	},
			{
			 breakpoint: 640,
			 settings: {
			   arrows: false,
			   centerMode: true,
			   slidesToShow: 2
			 }
		   }
		  ]
		});

		$('.eventSlider .slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
	        $('.leftslideCover').addClass('active');
			$('section.eventSlider .slideContainer button.slick-prev').addClass('active');
		});

		// CENTER MODE SLICK CAROUSEL

		$('.center').slick({
		  centerMode: true,
		  arrows: true,
		  dots: true,
		  centerPadding: '0px',
		  autoplay: true,
		  autoplaySpeed: 5000,
		  slidesToShow: 3,
		  responsive: [
		    {
		      breakpoint: 768,
		      settings: {
		        arrows: false,
		        centerMode: true,
		        centerPadding: '0px',
		        slidesToShow: 1
		      }
		    }
		  ]
		});

		// Preview Slider Galleries

		$('.preview-main-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: false,
			fade: true,
			asNavFor: '.preview-thumbnail-slider'
		});

		$('.preview-thumbnail-slider').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.preview-main-slider',
			dots: false,
			infinite: false,
			arrows: true,
			// centerMode: true,
			focusOnSelect: true
		});

		// data and insights related slider (mobile)

		if ($(window).width() <= 767) {
			$('.data-insights-related .gridWrapper').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				dots: false,
			});
		}

		// Testimonial slider

		$('.quote-slider-module').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			autoplay: true,
			autoplaySpeed: 5000,
			infinite: true,
			fade: true,
			speed: 500,
			cssEase: "linear",
		});


		$('.preview-thumbnail-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.preview-thumbnail-slider button.slick-prev').addClass('active');
		});

		$('.sliderBlockCarousel').owlCarousel({
            loop:true,
            items:1,
            nav:true,
			navText: false,
            dots: true,
			touchDrag : true,
			mouseDrag : true,
			center: true,
			stagePadding: 300,
			margin: 10,
			autoplay: 5000,
			smartSpeed: 800,
			responsive : {
				0 : {
					stagePadding: 0
				},
				641 : {
					stagePadding: 100
				},
				1024 : {
					stagePadding: 150
				},
				1280 : {
					stagePadding: 300
				}
			}
        });

		$('.fullPageSliderCarousel').owlCarousel({
            loop:true,
            items:1,
            nav:true,
			navText: false,
            dots: true,
			touchDrag : true,
			mouseDrag : true,
			autoplay: 5000,
			center: true,
			stagePadding: 0,
			margin: 0,
			smartSpeed: 800
        });

		$('.speaker-gallery').owlCarousel({
            loop:true,
            items:1,
            nav:true,
            dots: true,
			touchDrag : true,
			mouseDrag : true,
			autoplay: 5000,
			center: true,
			stagePadding: 0,
			margin: 0,
			smartSpeed: 800
        });

		$('.quote').owlCarousel({
            loop:true,
            items:1,
            nav:true,
            dots: true,
			touchDrag : true,
			mouseDrag : true,
			autoplay: 5000,
			autoplayHoverPause: true,
			center: true,
			stagePadding: 0,
			margin: 0,
			autoHeight: true,
			smartSpeed: 800
        });

		$('.articlesCarousel').owlCarousel({
            loop:true,
            nav:true,
			// navText: true,
            dots: true,
			touchDrag : true,
			mouseDrag : true,
			center: true,
			stagePadding: 0,
			autoplay: 5000,
			margin: 16,
			smartSpeed: 800,
			responsive : {
				0 : {
					items:1,
				},
				768 : {
					items:2,
				},
				1024 : {
					items:3,
				},
			}
        });

		$('.articlesCarouselTaxonomies').owlCarousel({
            loop:true,
            nav:true,
			// navText: true,
            dots: true,
			touchDrag : true,
			mouseDrag : true,
			center: true,
			slideBy: 1,
			stagePadding: 0,
			autoplay: 5000,
			margin: 16,
			smartSpeed: 800,
			responsive : {
				0 : {
					items:1,
					slideBy: 1,
					dots: false,
				},
				768 : {
					items:2,
					slideBy: 1,
					dots: false,
				},
				1024 : {
					items:3,
					dots: false,
				},
			}
        });

		if ($(window).width() <= 767) {
			$('.radioSlideContainer.desktop .radioSlide').on('click', function(e){
				thisRadioIndex = $(this).parents().parents('.slick-slide').index();
				mobileRadioIndex = $('.radioSlideContainer.mobile .radioSlide').eq(thisRadioIndex);
				$(mobileRadioIndex).children('label').children('input').prop("checked", true);
				$('.filter .mobile-form-container').addClass('active');
			});
		}	
		
		// Resources Feature Slider

		$('.resources-featured-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			fade: true,
			speed: 500,
	        cssEase: "linear",
			infinite: true,
			autoplay: true,
			autoplaySpeed: 3000,
			arrows: false,
			dots: true,
			customPaging : function(slider, i) {
			   var thumb = $(slider.$slides[i]).data();
			   var i = i + 1;
			   return '<a>0'+i+'</a>';
		   },
	   });

	   // KEYNOTE SLIDER
		$('.keynote-slider-module').each(function () {
			var $module = $(this);
			var $slickElementKeynote = $module.find('.keynote-slider');

			/**
			 * FIX JUMPING ANIMATION
			 */
			$slickElementKeynote.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
				var direction,
					slideCountZeroBased = slick.slideCount - 1;

				if (nextSlide == currentSlide) {
					direction = "same";
				} else if (Math.abs(nextSlide - currentSlide) == 1) {
					direction = (nextSlide - currentSlide > 0) ? "right" : "left";
				} else {
					direction = (nextSlide - currentSlide > 0) ? "left" : "right";
				}

				if (direction == 'right') {
					$('.slick-cloned[data-slick-index="' + (nextSlide + slideCountZeroBased + 1) + '"]', $slickElementKeynote)
						.addClass('slick-current-clone-animate');
				}

				if (direction == 'left') {
					$('.slick-cloned[data-slick-index="' + (nextSlide - slideCountZeroBased - 1) + '"]', $slickElementKeynote)
						.addClass('slick-current-clone-animate');
				}
			});

			$slickElementKeynote.on('afterChange', function () {
				$('.slick-current-clone-animate', $slickElementKeynote).removeClass('slick-current-clone-animate');
			});

			/**
			 * INIT SLICK
			 */
			$slickElementKeynote.slick({
				arrows: true,
				dots: true,
				infinite: true,
				autoplay: false,
				slidesToShow: 2,
				focusOnSelect: true,

				appendArrows: $module.find('.keynote-slider-arrows'),
				appendDots: $module.find('.keynote-slider-dots'),

				prevArrow: '<button type="button" class="slick-prev"></button>',
				nextArrow: '<button type="button" class="slick-next"></button>',

				responsive: [
					{
						breakpoint: 640,
						settings: {
							slidesToShow: 1
						}
					}
				]
			});

			/**
			 * CLICK STATE
			 */
			$slickElementKeynote.on('beforeChange', function () {
				$module.addClass('clicked');
				$module.find('.slide').addClass('first-click');
				$module.find('button.slick-prev').addClass('active');
			});
		});


		// Post SLIDER

		$('.portal-post-slider').each(function () {
			var $section = $(this);

			$section.find('.slider').slick({
				arrows: true,
				dots: true,
				autoplay: false,
				slidesToShow: 3,
				appendArrows: $section.find('.slider-arrows'),
				appendDots: $section.find('.slider-dots'),

				prevArrow: '<button type="button" class="slick-prev"></button>',
				nextArrow: '<button type="button" class="slick-next"></button>',

				responsive: [
					{
						breakpoint: 768,
						settings: {
							centerMode: false,
							slidesToShow: 2
						}
					},
					{
						breakpoint: 640,
						settings: {
							centerMode: false,
							slidesToShow: 1
						}
					}
				]
			});
		});


		$('.portal-post-slider .slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
			var $slider  = $(this);
			var $section = $slider.closest('.portal-post-slider');
			$section.find('.slideContainer button.slick-prev').addClass('active');
		});

		// Upcoming Events Slider

		$('.upcoming-events-slider-section').each(function () {
			var $section = $(this);

			$section.find('.upcoming-events-slider').slick({
				arrows: true,
				dots: true,
				autoplay: false,
				slidesToShow: 2,
				infinite: false,
				appendArrows: $section.find('.event-slider-arrows'),
				appendDots: $section.find('.event-slider-dots'),

				prevArrow: '<button type="button" class="slick-prev"></button>',
				nextArrow: '<button type="button" class="slick-next"></button>',

				responsive: [
					{
						breakpoint: 768,
						settings: {
							
							slidesToShow: 2
						}
					},
					{
						breakpoint: 640,
						settings: {
							
							slidesToShow: 1
						}
					}
				]
			});
		});


		$('.upcoming-events-slider-section .upcoming-events-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
	        $('upcoming-events-slider-section .leftslideCover').addClass('active');
		});

		$(document).on('click','body .ajax-search-button',function(){
		    //  $(this) = your current element that clicked.
		    $('.promagnifier').trigger('click');
		});

		$('.mobile-form-container .back').on('click', function(e){
			$('.filter .mobile-form-container').removeClass('active');
			$('.radioSlideContainer .radioSlide').children('label').children('input').prop("checked", false);
		});

		$('.formContainer form.new-filter.desktop .mobile-view-all').on('click', function(e){
			$('.filter .mobile-form-container').addClass('active');
		});

		if($('.hidden-keyword').length) {
			var keyword = $('.hidden-keyword').text();
			 $('.ajax-search-container input[type=search]').attr("placeholder", keyword);
		}

		$('.formContainer .ajax-search-container .clear-keyword').on('click', function(e){
			$('.formContainer .ajax-search-container .proclose').trigger('click');
			$('.ajax-search-container input[type=search]').attr("placeholder", "Try searching... CIO Edge, AI, Customer experience...");
			$('.formContainer form .search .searchInput').attr('value', '');
		});

		$('form.new-filter.mobile .topics.mobile .title').on('click', function(e){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('span.radioSlideContainer.mobile').slideUp(300);
			} else {
				$(this).addClass('active');
				$('span.radioSlideContainer.mobile').slideDown(300);
			}
		});

		$('form.new-filter.mobile .sort-by-mobile .title').on('click', function(e){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('span.mobile-sort-container').slideUp(300);
			} else {
				$(this).addClass('active');
				$('span.mobile-sort-container').slideDown(300);
			}
		});

		$('span.mobile-filter-container').slideUp(300);
		$('span.mobile-sort-container').slideUp(300);

		// Show password

		$('img.show-password').on('click', function(e){
			var x = document.getElementById("user_pass");
			if (x.type === "password") {
				x.type = "text";
				$(this).addClass('active');
			} else {
				x.type = "password";
				$(this).removeClass('active');
			}
		});

		$('form.new-filter.mobile .filter-by-mobile .title').on('click', function(e){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('span.mobile-filter-container').slideUp(300);
			} else {
				$(this).addClass('active');
				$('span.mobile-filter-container').slideDown(300);
			}
		});

		$('.owl-carousel.sliderBlockCarousel').each(function() {
			pl = $(this).children('.owl-dots').width();
			ml = pl / 2;
		});

		//progressBar();

		headerSet();

		$('.grid').css({'visibility':'visible', 'opacity':'1'});

		// Video buttons



		$('a.playBtn').on('click', function(e) {
			e.preventDefault();
			thisIndex = $(this).parent().parent().parent().parent('li').index();
			thisVideo = ('.videoPlayerContainer');
			$(thisVideo).eq(thisIndex).show();
			$('body').addClass('fixed');
			$(thisVideo).eq(thisIndex).children('.videoWrapper').children('video').get(0).play();
		});

		$('a.playBtnGrid').on('click', function(e) {
			e.preventDefault();
			thisIndex = $(this).parent().parent().parent('.column').index();
			thisContainer = $(this).parent().parent().parent('.column').parent().parent('.container');
			thisVideo = $(thisContainer).siblings('.videoPlayerContainerGrid');
			$(thisVideo).eq(thisIndex).show();
			$('body').addClass('fixed');
			$(thisVideo).eq(thisIndex).children('.videoWrapper').children('video').get(0).play();
		});

		$('a.postPlayBtn').on('click', function(e) {
			e.preventDefault();
			$(this).parent().parent().css('z-index', '999');
			thisVideo = $(this).parent().siblings('.videoPlayerContainer');
			thisVideo.show();
			$('body').addClass('fixed');
			thisVideo.children('.videoWrapper').children('video').get(0).play();
		});

		$('a.how-to-get-started').on('click', function(e) {
			e.preventDefault();
			$(this).parent().parent().parent().css('z-index', '999');
			thisVideo = $(this).parent().parent().siblings('.videoPlayerContainer');
			thisVideo.show();
			$('body').addClass('fixed');
			thisVideo.children('.videoWrapper').children('video').get(0).play();
		});


		$('a.videoBlockPlay').on('click', function(e) {
			e.preventDefault();
			thisIndex = $(this).parent().parent().parent().parent('li').index();
			thisVideoBlock = ('.videoPlayerContainer');
			$(thisVideoBlock).eq(thisIndex).show();
			$('body').addClass('fixed');
			$(thisVideoBlock).eq(thisIndex).children('.videoWrapper').children('video').get(0).play();
		});

		$('a.playBtnVideoBlock').on('click', function(e) {
			e.preventDefault();
			thisVideo = $(this).parent().parent().parent().siblings('.videoPlayerContainer');
			thisVideo.show();
			$('body').addClass('fixed');
			thisVideo.children('.videoWrapper').children('video').get(0).play();
		});

		$('span.closeVideo').on('click', function(e) {
			e.preventDefault();
			$('.expertPresentationFeatured').css('z-index', '1');
			$('.videoBlock').css('z-index', '1');
			$(this).siblings('.videoWrapper').children('video').trigger('pause');
			$('body').removeClass('fixed');
			$(this).parent('.videoPlayerContainer').hide();
			$(this).parent('.videoPlayerContainerGrid').hide();
		});

		$(document).keyup(function(e) {
			if (e.keyCode === 27) $('span.closeVideo').click();
		});

		if($("#loop span.results").length == 0) {
	  	} else {
		  $('body.template-insights section.postHeader .filter .formContainer').addClass('results');
	  	}

		// New partners 

		$('.filter-dropdown .dropdown-title').on('click', function (e) {
			e.preventDefault();

			var $currentTitle = $(this);
			var $currentList  = $currentTitle.siblings('.dropdown-list');

			// Close all other dropdowns
			$('.filter-dropdown .dropdown-title')
				.not($currentTitle)
				.removeClass('active');

			$('.filter-dropdown .dropdown-list')
				.not($currentList)
				.slideUp(300);

			// Toggle current
			if ($currentTitle.hasClass('active')) {
				$currentTitle.removeClass('active');
				$currentList.slideUp(200);
			} else {
				$currentTitle.addClass('active');
				$currentList.slideDown(200);
			}
		});


		// Post Ajax

		// $('.post-filtering-module').each(function(){

		// 	var $module = $(this);
		// 	var container = $module.find('#posts-container');
		// 	var loadMoreBtn = $module.find('.load-more-btn');
		// 	var resetBtn = $module.find('.reset-filters-btn');

		// 	// Search
		// 	var searchForm  = $module.find('.post-search-form');
		// 	var searchInput = $module.find('.post-search-input');

		// 	// Pagination
		// 	var postsPage = 1;
		// 	var postsMaxPages = 1;
		// 	var loading = false;

		// 	// Module data
		// 	var postType = $module.data('post-type') || 'post';
		// 	var isFavourites = $module.data('is-favourites') === 1;

		// 	// Current filters (ARRAYS now)
		// 	var currentTopic     = [];
		// 	var currentType      = [];
		// 	var currentEvent	 = [];
		// 	var currentDate      = '';
		// 	var currentSearch    = '';
		// 	var currentSort      = '';
		// 	var currentTrending  = [];

		// 	// ======================================================
		// 	// AJAX loader
		// 	// ======================================================
		// 	function loadPosts(page, append) {

		// 		if (loading) return;
		// 		loading = true;

		// 		$.ajax({
		// 			url: ajaxobject.ajax_url,
		// 			type: 'POST',
		// 			dataType: 'json',
		// 			data: {
		// 				action: isFavourites ? 'load_favourite_posts' : 'load_filtered_posts',
		// 				page: page,
		// 				post_type: postType,
		// 				topic: currentTopic,
		// 				type: currentType,
		// 				trending_themes: currentTrending,
		// 				date: currentDate,
		// 				search: currentSearch
		// 			},
		// 			success: function(response){

		// 				if (!response || !response.success) {
		// 					console.log('AJAX response error', response);
		// 					loading = false;
		// 					return;
		// 				}

		// 				postsMaxPages = parseInt(response.data.max_pages, 10) || 0;

		// 				if (append) {
		// 					container.append(response.data.html);
		// 				} else {
		// 					container.html(response.data.html);
		// 				}

		// 				if (postsPage >= postsMaxPages || postsMaxPages === 0) {
		// 					loadMoreBtn.hide();
		// 				} else {
		// 					loadMoreBtn.show();
		// 				}

		// 				loading = false;
		// 			},
		// 			error: function(xhr, status, error){
		// 				console.log('AJAX error:', status, error);
		// 				console.log(xhr.responseText);
		// 				loading = false;
		// 			}
		// 		});
		// 	}

		// 	// ======================================================
		// 	// Initial load
		// 	// ======================================================
		// 	loadPosts(1, false);

		// 	// ======================================================
		// 	// Filters (Topic / Type / Trending / Date)
		// 	// ======================================================
		// 	$module.find('.filter-dropdown .filter-button').on('click', function(e){
		// 		e.preventDefault();

		// 		var $btn = $(this);
		// 		var $dropdown = $btn.closest('.filter-dropdown');
		// 		var filter = $dropdown.data('filter');
		// 		var value = $btn.data('value');

		// 		// UI state
		// 		$btn
		// 			.siblings().removeClass('active')
		// 			.end()
		// 			.addClass('active');

		// 		$btn.closest('.dropdown-list').slideUp(300);
		// 		$dropdown
		// 			.find('.dropdown-title')
		// 			.addClass('filter-active');

		// 		// Contextual "All"
		// 		if (value === '') {
		// 			var allowed = $dropdown.data('allowed') || [];

		// 			if (filter === 'topic') currentTopic = allowed;
		// 			if (filter === 'type') currentType = allowed;
		// 			if (filter === 'trending-themes') currentTrending = allowed;
		// 			if (filter === 'date') currentDate = '';

		// 		} else {
		// 			if (filter === 'topic') currentTopic = [value];
		// 			if (filter === 'type') currentType = [value];
		// 			if (filter === 'trending-themes') currentTrending = [value];
		// 			if (filter === 'date') currentDate = value;
		// 		}

		// 		postsPage = 1;
		// 		closeAllDropdowns($module);
		// 		loadPosts(1, false);
		// 	});

		// 	// ======================================================
		// 	// Load More
		// 	// ======================================================
		// 	loadMoreBtn.on('click', function(e){
		// 		e.preventDefault();

		// 		if (postsPage < postsMaxPages) {
		// 			postsPage++;
		// 			loadPosts(postsPage, true);
		// 		}
		// 	});

		// 	// ======================================================
		// 	// Search (submit only)
		// 	// ======================================================
		// 	if (searchForm.length) {
		// 		searchForm.on('submit', function(e){
		// 			e.preventDefault();

		// 			currentSearch = searchInput.val().trim();
		// 			postsPage = 1;

		// 			loadPosts(1, false);
		// 		});
		// 	}

		// 	// ======================================================
		// 	// Reset
		// 	// ======================================================
		// 	resetBtn.on('click', function(e){
		// 		e.preventDefault();

		// 		currentTopic     = [];
		// 		currentType      = [];
		// 		currentTrending  = [];
		// 		currentDate      = '';
		// 		currentSearch    = '';

		// 		postsPage = 1;

		// 		// UI reset
		// 		$module.find('.filter-button').removeClass('active');
		// 		$module.find('.dropdown-title').removeClass('filter-active');
		// 		$module.find('.filter-button[data-value=""]').addClass('active');

		// 		if (searchInput.length) {
		// 			searchInput.val('');
		// 		}

		// 		loadPosts(1, false);
		// 	});

		// });

$('.post-filtering-module').each(function(){

    var $module = $(this);
    var container = $module.find('#posts-container');
    var loadMoreBtn = $module.find('.load-more-btn');
    var resetBtn = $module.find('.reset-filters-btn');
	var loader = $module.find('.ajax-loader');

    // Search
    var searchForm  = $module.find('.post-search-form');
    var searchInput = $module.find('.post-search-input');

    // Pagination
    var postsPage = 1;
    var postsMaxPages = 1;
    var loading = false;

    // Module data
    var postType = $module.data('post-type') || 'post';
    var isFavourites = $module.data('is-favourites') === 1;

    // ===============================
    // FILTER STATE (arrays by default)
    // ===============================
    var filters = {
        topic: [],
        type: [],
        'trending-themes': [],
        event: [],
        persona: [],
        sector: []
    };

    var currentDate   = '';
    var currentSearch = '';

    // ======================================================
    // Detect if events filter exists on the page
    // ======================================================
    var hasEventFilter = $module.find('.filter-dropdown[data-filter="event"]').length > 0;

    // ======================================================
    // Initialize filters on page load
    // ======================================================
    $module.find('.filter-dropdown').each(function(){
        var $dropdown = $(this);
        var filter = $dropdown.data('filter');
        var allowed = $dropdown.data('allowed') || [];

        if (typeof allowed === 'string') {
            try { allowed = JSON.parse(allowed); } catch(e){ allowed = []; }
        }

        var $active = $dropdown.find('.filter-button.active');
        if ($active.length) {
            var value = $active.data('value');

            try {
                value = (typeof value === 'string' && value.startsWith('[')) ? JSON.parse(value) : value;
            } catch(e){}

            // Empty = all posts
            if (!value || (Array.isArray(value) && value.length === 0)) {
                filters[filter] = [];
            } else {
                filters[filter] = Array.isArray(value) ? value : [value];
            }
        }
    });

    // ======================================================
    // AJAX loader
    // ======================================================
    function loadPosts(page, append) {

        if (loading) return;
        loading = true;
		loader.show();
        console.log('Loading page', page, 'filters:', filters); // debug

        $.ajax({
            url: ajaxobject.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: isFavourites ? 'load_favourite_posts' : 'load_filtered_posts',
                page: page,
                post_type: postType,

                // Filters
                topic: filters.topic,
                type: filters.type,
                trending_themes: filters['trending-themes'],
                event: filters.event, // send selected events array (empty = all)
                persona: filters.persona,
                sector: filters.sector,

                date: currentDate,
                search: currentSearch,

                has_event_filter: hasEventFilter ? 1 : 0 // backend can output eventtype=yes
            },
            success: function(response){

                if (!response || !response.success) {
                    console.log('AJAX response error', response);
                    loading = false;
					loader.hide();
                    return;
                }

                postsMaxPages = parseInt(response.data.max_pages, 10) || 0;

                if (append) {
                    container.append(response.data.html);
                } else {
                    container.html(response.data.html);
                }

                // Load More visibility
                if (postsPage >= postsMaxPages || postsMaxPages === 0) {
                    loadMoreBtn.hide();
                } else {
                    loadMoreBtn.show();
                }

                loading = false;
				loader.hide();
            },
            error: function(xhr, status, error){
                console.log('AJAX error:', status, error);
                console.log(xhr.responseText);
                loading = false;
				loader.hide();
            }
        });
    }

    // ======================================================
    // Initial load
    // ======================================================
    loadPosts(1, false);

    // ======================================================
    // Filters (generic handler)
    // ======================================================
    $module.find('.filter-dropdown .filter-button').on('click', function(e){
        e.preventDefault();

        var $btn = $(this);
        var $dropdown = $btn.closest('.filter-dropdown');
        var filter = $dropdown.data('filter');
        var rawValue = $btn.data('value');

        var value;
        try {
            value = (typeof rawValue === 'string' && rawValue.startsWith('[')) ? JSON.parse(rawValue) : rawValue;
        } catch(e){ value = rawValue; }

        if (!value || (Array.isArray(value) && value.length === 0)) {
        if (filter === 'date') {
            currentDate = '';
        } else {
            filters[filter] = [];
        }
		} else {
			if (filter === 'date') {
				currentDate = value;
			} else {
				filters[filter] = Array.isArray(value) ? value : [value];
			}
		}

        // UI state
        $btn.siblings().removeClass('active').end().addClass('active');
        $btn.closest('.dropdown-list').slideUp(300);
        $dropdown.find('.dropdown-title').addClass('filter-active');

        postsPage = 1;
        closeAllDropdowns($module);
        loadPosts(1, false);
    });

    // ======================================================
    // Load More
    // ======================================================
    loadMoreBtn.on('click', function(e){
        e.preventDefault();
        if (postsPage < postsMaxPages) {
            postsPage++;
            loadPosts(postsPage, true);
        }
    });

    // ======================================================
    // Search
    // ======================================================
    if (searchForm.length) {
        searchForm.on('submit', function(e){
            e.preventDefault();
            currentSearch = searchInput.val().trim();
            postsPage = 1;
            loadPosts(1, false);
        });
    }

    // ======================================================
    // Reset
    // ======================================================
    resetBtn.on('click', function(e){
        e.preventDefault();

        Object.keys(filters).forEach(function(key){
            filters[key] = []; // empty = all posts
        });
        currentDate   = '';
        currentSearch = '';
        postsPage     = 1;

        $module.find('.filter-button').removeClass('active');
        $module.find('.dropdown-title').removeClass('filter-active');
        $module.find('.filter-button[data-value=""]').addClass('active');

        if (searchInput.length) searchInput.val('');

        loadPosts(1, false);
    });

});

		// Partners Ajax 

		$('.speaker-module').each(function(){

			var $module = $(this);
			var container = $module.find('.speakers');
			var loadMoreBtn = $module.find('.load-more-btn');
			var resetBtn = $module.find('.reset-filters-btn');
			var loader = $module.find('.ajax-loader');

			// NEW: search elements
			var searchForm = $module.find('.partner-search-form');
			var searchInput = $module.find('.partner-search-input');

			var partnersPage = 1;
			var partnersMaxPages = 1;

			var currentExpertise = '';
			var currentIndustry = '';
			var currentSearch = '';

			var loading = false;

			var partnerTypeId = $module.data('partner-type-id');
			console.log("Module partnerTypeId =", partnerTypeId);

			// =================================================================
			// AJAX loader
			// =================================================================
			function loadPartners(page, append) {

				if (loading) return;
				loading = true;
				loader.show();

				console.log("Calling AJAX", {
					page: page,
					partner_type_id: partnerTypeId,
					expertise: currentExpertise,
					industry: currentIndustry,
					search: currentSearch
				});

				$.ajax({
					url: ajaxobject.ajax_url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'load_partners',
						page: page,
						partner_type_id: partnerTypeId,
						expertise: currentExpertise,
						industry: currentIndustry,
						search: currentSearch
					},
					success: function(response){

						if (!response || !response.success) {
							console.log("AJAX Response Error:", response);
							loading = false;
							loader.hide();
							return;
						}

						partnersMaxPages = parseInt(response.data.max_pages);

						if (append) {
							container.append(response.data.html);
						} else {
							container.html(response.data.html);
						}

						if (partnersPage >= partnersMaxPages) {
							loadMoreBtn.hide();
						} else {
							loadMoreBtn.show();
						}

						loading = false;
						loader.hide();
					},
					error: function(xhr, status, error){
						console.log("AJAX error:", status, error);
						console.log("Response:", xhr.responseText);
						loading = false;
						loader.hide();
					}
				});
			}

			// =================================================================
			// Initial Load
			// =================================================================
			loadPartners(1, false);

			// =================================================================
			// Expertise / Industry Filters
			// =================================================================
			$module.find('.filter-dropdown .filter-button').on('click', function(e){
				e.preventDefault();

				var $btn = $(this);
				var filter = $btn.closest('.filter-dropdown').data('filter');
				var value = $btn.data('value');

				$btn.siblings().removeClass('active');
				$btn.addClass('active');
				$btn.parent('.dropdown-list').slideUp(300);
				$btn.parent('.dropdown-list').siblings('.dropdown-title').removeClass('active');
				$btn.parent('.dropdown-list').siblings('.dropdown-title').addClass('filter-active');
				if (filter === 'expertise') currentExpertise = value;
				if (filter === 'industry') currentIndustry = value;

				partnersPage = 1;
				closeAllDropdowns($module);
				loadPartners(1, false);
			});

			// =================================================================
			// Load More
			// =================================================================
			loadMoreBtn.on('click', function(e){
				e.preventDefault();
				if (partnersPage < partnersMaxPages) {
					partnersPage++;
					loadPartners(partnersPage, true);
				}
			});

			// =================================================================
			// SEARCH — only on Form Submit
			// =================================================================
			if (searchForm.length) {
				searchForm.on('submit', function(e){
					e.preventDefault();

					currentSearch = searchInput.val().trim();

					partnersPage = 1;
					loadPartners(1, false);
				});
			}

			// =================================================================
			// RESET BUTTON
			// =================================================================
			resetBtn.on('click', function(e){
				e.preventDefault();

				// reset filter states
				currentExpertise = '';
				currentIndustry = '';
				currentSearch = '';

				partnersPage = 1;

				// UI reset
				$module.find('.filter-button').removeClass('active');
				$module.find('.dropdown-title').removeClass('filter-active');				
				$module.find('.filter-button[data-value=""]').addClass('active');

				if (searchInput.length) {
					searchInput.val('');
				}

				loadPartners(1, false);
			});

		});

		function closeAllDropdowns($context) {
			$context.find('.dropdown-title').removeClass('active');
			$context.find('.dropdown-list').slideUp(300);
		}
	});

	$('.get-in-touch a').on('click', function(e) {
		e.preventDefault();
		if (window.HubSpotConversations) {
		  window.HubSpotConversations.widget.open()
		}
	});

	$('a.chat-button').on('click', function(e) {
		e.preventDefault();
		if (window.HubSpotConversations) {
		  window.HubSpotConversations.widget.open()
		}
	});

	$(window).scroll(function(){
		resize();
		scrollMenu();
		scrollMobile();
		var viewportWidth = jQuery(window).width();
		if($('.post-title-block').length ){
			if (viewportWidth > 1023) {
				var targetScroll = $('.post-title-block').offset().top + $('.post-title-block').outerHeight();
				if($(window).scrollTop() > targetScroll){
					$('.single-post-sticky').addClass('scrolled');					
				} else {
					$('.single-post-sticky').removeClass('scrolled');					
				}
			}
		}

	});

	$(window).on('load',function (){

		popupInit();

		$('#user_login').attr('placeholder', 'Email address');
		$('#user_pass').attr('placeholder', 'Password');

		multipartBreadcrumb();
		select2();
		matchHeightInit();
		outsideContainer();
		if($('input[name="redirect_to"]').length ){
			var inputVal = $('input[name="redirect_to"]').val();
			var newVal = inputVal.replace('+', '%C2%A0');
			$('input[name="redirect_to"]').val(newVal);
		}

		$('img.show-password').on('click', function(e){
			var x = document.getElementById("user_pass");
			if (x.type === "password") {
				x.type = "text";
				$(this).addClass('active');
			} else {
				x.type = "password";
				$(this).removeClass('active');
			}
		});


		// Populate hubspot hidden field

		if($('#sharepopupcontainer').length ){
			var shareDownload = $('.hidden-share-link').text();
			var shareName = $('.hidden-share-name').text();
			var shareTitle= $('.hidden-share-title').text();
			var sharedDescription = $('.hidden-share-excerpt').text();
			setTimeout(function(){
				var shareForm = $('#sharepopupcontainer form');
				$(shareForm).find('.hs_hidden_download_field').children('div.input').children('input').attr('value', shareDownload);
				$(shareForm).find('.hs_hidden_download_field').children('div.input').children('input').val(shareDownload).change();
				$(shareForm).find('.hs-hidden_content_shared_by_field').children('div.input').children('input').attr('value', shareName);
				$(shareForm).find('.hs-hidden_content_shared_by_field').children('div.input').children('input').val(shareName).change();
				$(shareForm).find('.hs_hidden_content_shared_article_title_field').children('div.input').children('input').attr('value', shareTitle);
				$(shareForm).find('.hs_hidden_content_shared_article_title_field').children('div.input').children('input').val(shareTitle).change();
				$(shareForm).find('.hs_hidden_content_shared_description_field').children('div.input').children('input').attr('value', sharedDescription);
				$(shareForm).find('.hs_hidden_content_shared_description_field').children('div.input').children('input').val(sharedDescription).change();
			}, 1000);
		}

		// Populate datashare hubspot hidden field

		if($('#datasetsharepopupcontainer').length ){
			var shareDownload = $('.hidden-share-link').text();
			var shareName = $('.hidden-share-name').text();
			var shareTitle= $('.hidden-share-title').text();
			var sharedDescription = $('.hidden-share-excerpt').text();
			setTimeout(function(){
				var shareForm = $('#datasetsharepopupcontainer form');
				$(shareForm).find('.hs_hidden_download_field').children('div.input').children('input').attr('value', shareDownload);
				$(shareForm).find('.hs_hidden_download_field').children('div.input').children('input').val(shareDownload).change();
				$(shareForm).find('.hs-hidden_content_shared_by_field').children('div.input').children('input').attr('value', shareName);
				$(shareForm).find('.hs-hidden_content_shared_by_field').children('div.input').children('input').val(shareName).change();
				$(shareForm).find('.hs_hidden_content_shared_article_title_field').children('div.input').children('input').attr('value', shareTitle);
				$(shareForm).find('.hs_hidden_content_shared_article_title_field').children('div.input').children('input').val(shareTitle).change();
				$(shareForm).find('.hs_hidden_content_shared_description_field').children('div.input').children('input').attr('value', sharedDescription);
				$(shareForm).find('.hs_hidden_content_shared_description_field').children('div.input').children('input').val(sharedDescription).change();
				$(shareForm).find('.hs-message').children('div.input').children('textarea').attr('placeholder', 'Hi, I thought you would like to see "' + shareTitle + '".');
			}, 1000);
		}


		// LOADING OVERLAY

		setTimeout(function(){
			$('span.loading').addClass('loaded');
		}, 0);

		//AOS.init();

		// SITE TRANSPARENCY ON LOAD

		$('main').addClass('active');

		$('span.desktopNav').addClass('loaded');

		$('section.banner').flexslider({
			animation: "fade",              //String: Select your animation type, "fade" or "slide"
			direction: "horizontal",   //String: Select the sliding direction, "horizontal" or "vertical"
			useCSS:true,
			touch:true,
			slideshow: true,                //Boolean: Animate slider automatically
			slideshowSpeed: 5000, //Integer: Set the speed of the slideshow cycling, in milliseconds
			animationDuration: 500,         //Integer: Set the speed of animations, in milliseconds
			directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
			controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
			keyboardNav: false,              //Boolean: Allow slider navigating via keyboard left/right keys
			mousewheel: false,              //Boolean: Allow slider navigating via mousewheel
			prevText: "Previous",           //String: Set the text for the "previous" directionNav item
			nextText: "Next",               //String: Set the text for the "next" directionNav item
			pausePlay: false,               //Boolean: Create pause/play dynamic element
			pauseText: 'Pause',             //String: Set the text for the "pause" pausePlay item
			playText: 'Play',               //String: Set the text for the "play" pausePlay item
			randomize: false,               //Boolean: Randomize slide order
			slideToStart: 0,                //Integer: The slide that the slider should start on. Array notation (0 = first slide)
			animationLoop: true,            //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
			pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
			pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
			controlsContainer: "",          //Selector: Declare which container the navigation elements should be appended too. Default container is the flexSlider element. Example use would be ".flexslider-container", "#container", etc. If the given element is not found, the default action will be taken.
			manualControls: "",             //Selector: Declare custom control navigation. Example would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
			start: function(){},            //Callback: function(slider) - Fires when the slider loads the first slide
			before: function(){},           //Callback: function(slider) - Fires asynchronously with each slider animation
			after: function(){},            //Callback: function(slider) - Fires after each slider animation completes
			end: function(){}
		});

		$(document).on('click','.help',function(e){
			e.preventDefault();
			if (window.HubSpotConversations) {
		      window.HubSpotConversations.widget.open()
		    }
		});


	});

	$(window).on('resize',function (){
		resize();
		select2();
		matchHeightInit();
		outsideContainer();
		scrollMobile();
	});

	function debounce(func, delay) {
		var debounceTimer;
		return function() {
			var context = this;
			var args = arguments;
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function() {
				func.apply(context, args);
			}, delay);
		};
	}

	function scrollProgressBar() {

		var progressBar = $('.progress-bar');
		var max = 0;

		function getMax() {
			return document.documentElement.scrollHeight - window.innerHeight;
		}

		function getValue() {
			return window.pageYOffset || document.documentElement.scrollTop;
		}

		function setWidth() {
			var value = getValue();
			max = getMax();

			var percent = max > 0 ? (value / max) * 100 : 0;
			percent = Math.min(Math.max(percent, 0), 100);

			progressBar.css('width', percent + '%');
		}

		// scroll
		window.addEventListener('scroll', setWidth, { passive: true });

		// resize / orientation
		window.addEventListener('resize', setWidth);
		window.addEventListener('orientationchange', setWidth);

		// images / lazy content can change height
		window.addEventListener('load', setWidth);

		// initial
		setWidth();
	}




	function resize() {
		wh = $(window).height();
	}

	function multipartBreadcrumb() {
		var number = $('.nf-breadcrumbs li').length;
		var width = 100/number
		$('.nf-breadcrumbs li').css('width', width + '%');
	}


	function headerSet() {
		var header = $('header');
		var range = 200;
		var winHeight =$(window).height();
		var scrollTop = $(this).scrollTop();

		if($('body').hasClass('home')) {
			if(scrollTop > 1) {
				header.children('span.logo').addClass('scrolled');
				header.addClass('scrolled');
			} else {
				header.children('span.logo').removeClass('scrolled');
				header.removeClass('scrolled');
			}
		}

		if (ww < 767) {
			if(scrollTop > 25) {
				$('.backTop').addClass('active');
			} else {
				$('.backTop').removeClass('active');
			}
		};

		if (ww < 767) {
			if(scrollTop > 125) {
				$('.webinar-mobile-sticky-footer').addClass('active');
			} else {
				$('.webinar-mobile-sticky-footer').removeClass('active');
			}
		};
	}

	function matchHeightInit(){
		$('.topicGrid.sector-grid .item .textContainer').matchHeight();
		$('.cardContainer .twoColumnCard .textContainer').matchHeight();
		$('.kits-listing .kit-item').matchHeight();
		$('.partners-listing .partner-item .partner-inner').matchHeight();
		$('.links-container .data-link').matchHeight();
		$('.item.full-width .item-column').matchHeight();
		$('.webinar-column').matchHeight();
		$('.cta-content-container .column').matchHeight();
		$('.register-listing-container .item').matchHeight();
		$('.popup-container .column.one-half').matchHeight();
		$('.researchMenu .half-links .link').matchHeight();
		$('.imageGridBlock.speaker .item').matchHeight();
		$('.imageGridBlock.standard .item').matchHeight();
		$('.imageGridBlock.speakerBlock .item').matchHeight();
		$('.textImageBlock .item').matchHeight();
		$('.halfHalfBlock .textBlock, .halfHalfBlock .imageBlock').matchHeight();
		$('.speakerQuoteCarousel .imageContainer, .speakerQuoteCarousel .textBlock').matchHeight();
		$('.agendaBlock.single .item .wrapper > div').matchHeight();
		$('.agendaBlock.double .item .time, .agendaBlock.double .detailWrap .left .title, .agendaBlock.double .detailWrap .right .title').matchHeight();
		$('section.relatedSpeakerArticles a.item').matchHeight();
		$('section.relatedArticlesCarousel .postDetails').matchHeight();
		$('section.blogWrapper .grid .postLink').matchHeight();
		$('.pricingBlockItem.first .innerWrapper, .pricingBlockItem.last .innerWrapper').matchHeight();
		$('section.blogWrapper .grid .postLink, .grid .postLink.layout-1 .linkWrapper .imageContainer').matchHeight();
		$('section.blogWrapper .grid .postLink, .grid .postLink.layout8 .linkWrapper .imageContainer').matchHeight();
		$('section.blogWrapper .grid .postLink, .grid .postLink.layout6 .linkWrapper .imageContainer').matchHeight();
		$('section.blogWrapper .grid .postLink, .grid .postLink.layout13 .linkWrapper .imageContainer').matchHeight();
		$('section.blogWrapper .grid .postLink, .grid .postLink.layout4 .linkWrapper .imageContainer').matchHeight();
		$('section.blogWrapper .grid .postLink, .grid .postLink.layout18 .linkWrapper .imageContainer').matchHeight();
		$('.footer .middle .column.first, .footer .middle .column.second').matchHeight();
		$('.footer .middle .column.third, .footer .middle .column.fourth').matchHeight();
		$('.footer .middle .column.fifth, .footer .middle .column.sixth').matchHeight();
		$('body.template-events-portal .blogWrapper a.postLink .articleLink').matchHeight();
		$('body.template-events-portal .blogWrapper a.postLink .bottom').matchHeight();
		$('body.template-events-portal .blogWrapper a.postLink span.excerpt').matchHeight();
		$('body.template-get-advice .imageSizeContainer, body.template-get-advice .textContainer').matchHeight();
		$('.megaMenu .column').matchHeight();
	}

	function select2(){
		if($('form').hasClass('hs-form')){
		} else {
			if($('form').hasClass('mepr-form')){
				$('select').select2();
			} else {
				$('select').select2({minimumResultsForSearch: -1});
			}
		}
	}

	function popupInit() {
		if(Cookies.get('popup') == 'displayed') {
			// $('.popup-link-init').trigger('click');
		} else {
			// ww = $(window).width();
			// if (ww > 1024) {
			// 	setTimeout(function() {
			// 		$('.popup-link-init').trigger('click');
			// 		Cookies.set('popup', 'displayed', { expires: 999 });
			// 	}, 120000);
			// }
		}
	}

	function outsideContainer() {
		ww = $(window).width();
		container = $('.eventSlider .container').width();
		outsideContainerWidth = ww - container;
		slideWidth = $('.eventSlider div.item').width();
		arrowLeftPos = outsideContainerWidth / 2 - 66;
		arrowRightPos = outsideContainerWidth / 2 + 66;
		coverWidth = outsideContainerWidth / 2;
		$('.eventSlider .slideContainer .rightslideCover').css('width', coverWidth);
		$('.eventSlider .slideContainer .leftslideCover').css('width', coverWidth);
		containerPortal = $('.portal-post-slider .container').width();
		outsideContainerWidthPortal = ww - containerPortal;
		slideWidthPortal = $('.portal-post-slider .article-column').width();
		coverWidthPortal = outsideContainerWidthPortal / 2;
		$('.portal-post-slider .slideContainer .rightslideCover').css('width', coverWidth);
		$('.portal-post-slider .slideContainer .leftslideCover').css('width', coverWidth);
	}

	function scrollMobile() {

		ww = $(window).width();

		if (ww < 767) {
			if ($(this).scrollTop() > 100){
				$('header').addClass("scrolled");
			} else {
				$('header').removeClass("scrolled");
			}
		};
	}

	function scrollMenu() {
		$('section.navigation.event').each(function() {

			refElement = $(this).siblings('section.banner');

			elementTop = refElement.position().top;
			elementBottom = elementTop + $(refElement).outerHeight();

			var viewportTop = $(document).scrollTop();
			var headerHeight = $('header span.logo').outerHeight();
			var viewportTopHeader = viewportTop + headerHeight;

			if (elementBottom <= viewportTopHeader) {
				$('section.navigation.event').addClass('fixed');

			} else {
				$('section.navigation.event').removeClass('fixed');
			}

	    });

		$('section.navigation.fixed-menu').each(function() {

			if($('body').hasClass('template-flexible')){
				if($('main').hasClass('no-banner-top') ) {
					navTop = $(this).offset().top;
					// console.log(navTop);
					var viewportTop = $(document).scrollTop();
					var headerHeight = $('header span.logo').outerHeight();
					var viewportTopHeader = viewportTop + headerHeight;
					// console.log(viewportTopHeader);
					if (navTop <= viewportTopHeader) {
						$('section.navigation.fixed-menu').addClass('fixed');

					} else {
						$('section.navigation.fixed-menu').removeClass('fixed');
					}

				} else {
					refElement = $(this).siblings('section.banner');
					navTop = $(this).offset().top;

					elementTop = refElement.offset().top;
					elementBottom = elementTop + $(refElement).outerHeight();
					var viewportTop = $(document).scrollTop();
					var headerHeight = $('header span.logo').outerHeight();
					var viewportTopHeader = viewportTop + headerHeight - 28;
					if (elementBottom <= viewportTopHeader) {
						$('section.navigation.fixed-menu').addClass('fixed');

					} else {
						$('section.navigation.fixed-menu').removeClass('fixed');
					}
				}
			} else if ($('body').hasClass('post')) {
				refElement = $(this).prev('.container').children('.featureBlock');
				navTop = $(this).offset().top;
				elementTop = refElement.offset().top;
				elementBottom = elementTop + $(refElement).outerHeight();
				var viewportTop = $(document).scrollTop();
				var viewportTopHeader = viewportTop - 28;
				if (elementBottom <= viewportTopHeader) {
					$('section.navigation.fixed-menu').addClass('fixed');
				} else {
					$('section.navigation.fixed-menu').removeClass('fixed');
					// $('header').removeClass('hamburger-menu-open');
				}
			} else {
				refElement = $(this).prev('.container').children('.featureBlock');
				navTop = $(this).offset().top;

				elementTop = refElement.offset().top;
				elementBottom = elementTop + $(refElement).outerHeight();
				var viewportTop = $(document).scrollTop();
				var headerHeight = $('header span.logo').outerHeight();
				var viewportTopHeader = viewportTop + headerHeight - 28;
				if (elementBottom <= viewportTopHeader) {
					$('section.navigation.fixed-menu').addClass('fixed');

				} else {
					$('section.navigation.fixed-menu').removeClass('fixed');
				}
			}


	    });

		var sections = $('.scrollPos')
		  , nav = $('section.navigation.fixed-menu')
		  , nav_height = nav.outerHeight();


		var cur_pos = $(this).scrollTop();

		sections.each(function() {
			if($(this).is('[id]')){
				var top = $(this).offset().top - ( nav_height + 90),
					bottom = top + $(this).outerHeight();

				if (cur_pos >= top && cur_pos <= bottom) {
				  nav.find('a').removeClass('active');
				  nav.find('a').parent('li').removeClass('hide');
				  sections.removeClass('active');

				  $(this).addClass('active');
				  nav.find('a[href="#'+$(this).attr('id')+'"]').addClass('active');

				  // if ($(window).width() <= 767) {
					//   nav.find('a.active').parent('li').addClass('hide');
					//   text = nav.find('a[href="#'+$(this).attr('id')+'"]').text();
					//   $('a.activeMenuItem').text(text);
				  // }
				}
			}
		});

	}



})(window.jQuery);
