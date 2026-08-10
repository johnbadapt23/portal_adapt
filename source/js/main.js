(function($){

	$(document).ready(function (){

		// Accessibility: slick.js generates prev/next <button> arrows with no
		// accessible name, and a role="tablist" dots container whose <li>
		// children have no role="tab" (both fail axe checks). Bind before any
		// .slick() init below so this fires on every slider's 'init' event,
		// current and future, without having to touch each individual
		// .slick({...}) call.
		//
		// This previously used $(this).find(...) to locate the arrows/dots,
		// which silently did nothing on every carousel on this site: all of
		// them pass appendArrows/appendDots, which move slick's generated
		// markup OUT of the .slick-slider element entirely (into a sibling
		// container elsewhere in the DOM, per each carousel's own layout) -
		// confirmed live, 0 of the arrow/dot elements are actually descendants
		// of .slick-slider. Verified by manually re-triggering 'init' on a
		// live slider and checking $(this).find('.slick-prev').length: 0.
		// slick's own instance keeps direct references to these elements
		// regardless of where they were appended (this.$prevArrow/$nextArrow/
		// $dots, set in slick.js's buildOut()), and the 'init' event's second
		// argument is that instance - use that instead of DOM traversal.
		//
		// The dots role also needs to survive slick's own initADA() (slick.js's
		// built-in accessibility handling, runs whenever options.accessibility
		// isn't explicitly false - true here since none of this site's
		// .slick({...}) calls set it), which runs synchronously right after
		// the 'init' event finishes firing and unconditionally sets
		// role="presentation" on every dot <li> as part of its own (different)
		// tablist pattern - so a plain assignment inside this handler gets
		// immediately overwritten the instant it returns.
		//
		// A one-time setTimeout(0) (run after initADA finishes) fixed 4 of
		// this site's 5 carousels, verified live - but resources-featured-
		// slider (the one with fade: true + autoplay + a custom customPaging)
		// kept reverting back to role="presentation" sometime after that,
		// confirmed live by re-checking several seconds later. Couldn't
		// pin down a single slick.js call path that explains it (checked
		// checkResponsive/refresh - not it, this carousel has no responsive
		// breakpoints configured; updateDots - only ever toggles
		// slick-active, never touches role), so rather than chase slick's
		// internals further for one edge case, made the fix self-healing
		// instead: a MutationObserver watching for the role attribute
		// drifting away from "tab" and putting it straight back, which
		// verified live to survive multiple autoplay cycles regardless of
		// whatever is resetting it.
		$(document).on('init', '.slick-slider', function(event, slick) {
			if (!slick) return;

			if (slick.$prevArrow && slick.$prevArrow.length) {
				slick.$prevArrow.attr('aria-label', 'Previous slide');
			}
			if (slick.$nextArrow && slick.$nextArrow.length) {
				slick.$nextArrow.attr('aria-label', 'Next slide');
			}
			if (slick.$dots && slick.$dots.length) {
				var applyDotTabRoles = function () {
					slick.$dots.find('> li').each(function () {
						if (this.getAttribute('role') !== 'tab') {
							this.setAttribute('role', 'tab');
						}
					});
				};
				setTimeout(applyDotTabRoles, 0);
				new MutationObserver(applyDotTabRoles).observe(slick.$dots[0], {
					attributes: true,
					attributeFilter: ['role'],
					subtree: true
				});
			}
		});

		// STANDARD
		@@include('includes/_maps.js')

		resize();
		matchHeightInit();
		select2();
		outsideContainer();
		scrollMobile();

		if($('.progress-container').length ){
			scrollProgressBar();
		}

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

		$('li.dropDown.with-sub-menu > a').on('click', function(e) {
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

		// Snapshots

		// MAIN SLIDER
		$('.snapshot-slider-main').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			dots: false,
			fade: false,
			infinite: false,
			asNavFor: '.snapshot-slider-thumbs'
		});

		// THUMBNAILS (6 + peek of 7th)
		$('.snapshot-slider-thumbs').slick({
			slidesToShow: 6.3,
			slidesToScroll: 1,
			asNavFor: '.snapshot-slider-main',
			focusOnSelect: true,
			arrows: true,
			dots: false,
			infinite: false,
			swipeToSlide: true,
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 4.3
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2.3
					}
				}
			]
		});

		$('.snapshot-popup-trigger').on('click', function (e) {
			e.preventDefault();

			var startIndex = parseInt($(this).data('index'), 10) || 0;

			$.magnificPopup.open({
				items: {
					src: '#snapshot-popup',
					type: 'inline'
				},
				callbacks: {
					open: function () {

						var $slider = $('.snapshot-popup-slider');

						// init slick ONLY ONCE
						if (!$slider.hasClass('slick-initialized')) {
							$slider.slick({
								slidesToShow: 1,
								arrows: true,
								dots: false,
								infinite: false
							});
						}

						// jump to correct slide
						$slider.slick('slickGoTo', startIndex, true);
					}
				},
				mainClass: 'mfp-fade'
			});
		});



		// Speaker resources load more 

		var $module = $('#resourcesAdvisor');
		if ($module.length){

			var container = $module.find('.resources-column-container');
    var loadMoreBtn = $module.find('.resources-load-more');
    var loader = $module.find('.ajax-loader');

    var page = parseInt(container.data('page')) || 1;
    var perPage = parseInt(container.data('per-page')) || 6;
    var total = parseInt(container.data('total')) || 0;
    var postId = parseInt(container.data('post-id')) || 0;
    var loading = false;

    loadMoreBtn.on('click', function(e){
        e.preventDefault();
        if (loading) return;
        loading = true;
        loader.show();
        page++;

        $.ajax({
            url: ajaxobject.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_resources',
                page: page,
                per_page: perPage,
                post_id: postId // NEW
            },
            success: function(html){
                container.append(html);
                container.data('page', page);
                loading = false;
                loader.hide();

                if ((page * perPage) >= total) {
                    loadMoreBtn.hide();
                }
            },
            error: function(){
                loading = false;
                loader.hide();
            }
        });
    });
		}


// Filtering Ajax
$('.post-filtering-module').each(function(){

    var $module = $(this);
    var container = $module.find('#posts-container');
    var loadMoreBtn = $module.find('.load-more-btn');
    var resetBtn = $module.find('.reset-filters-btn');
    var loader = $module.find('.ajax-loader');
    var searchForm = $module.find('.post-search-form');
    var searchInput = $module.find('.post-search-input');

    var postsPage = 1;
    var postsMaxPages = 1;
    var loading = false;

    var postType = $module.data('post-type') || 'post';
    var isFavourites = $module.data('is-favourites') === 1;

    // ===============================
    // FILTER STATE
    // ===============================
    var filters = {
        topic: [],
        type: [],
        'trending-themes': [],
        event: [],
        persona: [],
        sector: []
    };

    var currentDate = [];
	if ($module.find('.filter-dropdown[data-filter="date"]').length) {
		filters.date = [];
		// initialize "All (last 3 months)" if needed
		var $allDateBtn = $module.find('.filter-dropdown[data-filter="date"] .filter-button.all');
		if ($allDateBtn.length) {
			filters.date = normalizeFilterValue($allDateBtn.data('value'));
			currentDate = filters.date;
		}
	}
    var currentSearch = '';
    var currentSort = 'featured';

    var hasEventFilter = $module.find('.filter-dropdown[data-filter="event"]').length > 0;

    // ===============================
    // QUERY MAP
    // ===============================
    var queryMap = {
        type: 'type',
        topic: 'topicType',
        persona: 'persona',
        sector: 'sector',
        event: 'eventType',
        'trending-themes': 'theme'
    };

    // ===============================
    // NORMALISE
    // ===============================
    function normalizeFilterValue(value) {
        if ($.isArray(value)) return value;
        if (typeof value === 'string') return [value];
        return value ? [value] : [];
    }

    // ===============================
    // ACTIVE FILTERS BODY CLASSES
    // ===============================
    function updateActiveFilterClasses() {
        var activeDropdowns = 0;

        $('body').removeClass(function(index, className){
            return (className.match(/(^|\s)filter-active-\S+/g) || []).join(' ');
        });
        $('body').removeClass(function(index, className){
            return (className.match(/(^|\s)\S+-active/g) || []).join(' ');
        });

        for (var key in filters) {
            if (filters.hasOwnProperty(key)) {
                var $dropdown = $('.filter-dropdown[data-filter="' + key + '"]');
                var $activeBtn = $dropdown.find('.filter-button.active').not('.all');

                if ($activeBtn.length) {
                    activeDropdowns++;
                    $('body').addClass(key + '-active');
                }
            }
        }

        $('body').addClass('filter-active-' + activeDropdowns);
    }

    // ===============================
    // BUILD ACTIVE FILTER PILLS
    // ===============================
    function buildActiveFilterPills() {
        var pillsWrap = $module.find('.active-filter-pills');
        var searchLabel = $module.find('.search-results-label');
        if (!pillsWrap.length) return;

        pillsWrap.empty();
        var hasPills = false;

        $module.find('.filter-dropdown').each(function(){
            var $dropdown = $(this);
            var filter = $dropdown.data('filter');

            if ($dropdown.find('.dropdown-title').hasClass('disabled-dropdown') || filter === 'sort') return;

            var $activeBtn = $dropdown.find('.filter-button.active').not('.all');
            if (!$activeBtn.length) return;

            hasPills = true;
            var label = $.trim($activeBtn.text());

            var pill = $('<button type="button" class="filter-pill"><span>' + label + '</span><span class="pill-close">×</span></button>');

            pill.on('click', function(){
                var $allBtn = $dropdown.find('.filter-button.all');
                $dropdown.find('.filter-button').removeClass('active');
                $allBtn.addClass('active');
                $dropdown.find('.dropdown-title').removeClass('filter-active');

                filters[filter] = normalizeFilterValue($allBtn.data('value'));
                if(filter === 'date') currentDate = filters[filter];
                postsPage = 1;
                loadPosts(1, false);
                loadFeaturedPostsIfNeeded();
                updateActiveFilterClasses();
                // Do NOT push All to URL
                updateURL(false);
            });

            pillsWrap.append(pill);
        });

        pillsWrap.toggle(hasPills);

        if (currentSearch !== '') {
            searchLabel.text('Results for "' + currentSearch + '"').show();
        } else {
            searchLabel.hide();
        }

        updateActiveFilterClasses();
    }

    // ===============================
    // UPDATE URL
    // ===============================
    function updateURL(push) {
        if (typeof push === 'undefined') push = true;

        var url = new URL(window.location.href);

        for (var key in queryMap) {
            if (queryMap.hasOwnProperty(key)) {
                url.searchParams.delete(queryMap[key]);
            }
        }
        url.searchParams.delete('search');

        for (var fKey in filters) {
            if (filters.hasOwnProperty(fKey) && queryMap.hasOwnProperty(fKey)) {
                var $dropdown = $module.find('.filter-dropdown[data-filter="' + fKey + '"]');
                var $allBtn = $dropdown.find('.filter-button.all.active');
                // Only set URL param if not All
                if (!($allBtn.length) && filters[fKey] && filters[fKey].length) {
                    url.searchParams.set(queryMap[fKey], filters[fKey].join(','));
                }
            }
        }

        if (currentSearch) {
            url.searchParams.set('search', currentSearch);
        }

        var stateData = {
            filters: JSON.parse(JSON.stringify(filters)),
            search: currentSearch
        };

        if (push) {
            history.pushState(stateData, '', url.toString());
        } else {
            history.replaceState(stateData, '', url.toString());
        }
    }

    // ===============================
    // APPLY FILTERS FROM URL
    // ===============================
    function applyFiltersFromURL() {
        var url = new URL(window.location.href);

        for (var fKey in queryMap) {
            if (queryMap.hasOwnProperty(fKey)) {
                var param = queryMap[fKey];
                var value = url.searchParams.get(param);
                if (value) {
                    filters[fKey] = value.split(',');
                }
            }
        }

        var searchParam = url.searchParams.get('search') || url.searchParams.get('s');
		if (searchParam) {
			currentSearch = searchParam;
			searchInput.val(currentSearch);
		}
    }

    applyFiltersFromURL();

    // ===============================
    // INITIALIZE FILTERS FROM ACTIVE BUTTONS
    // ===============================
    $module.find('.filter-dropdown').each(function(){
        var $dropdown = $(this);
        var filter = $dropdown.data('filter');
        var $activeBtn = $dropdown.find('.filter-button.active').not('.all');
        var $allBtn = $dropdown.find('.filter-button.all.active');

        if ($activeBtn.length) {
            filters[filter] = normalizeFilterValue($activeBtn.data('value'));
        } else if ($allBtn.length) {
            filters[filter] = normalizeFilterValue($allBtn.data('value'));
        } else {
            filters[filter] = [];
        }
    });

    // ===============================
    // AJAX LOAD POSTS
    // ===============================
    function loadPosts(page, append) {
        if (loading) return;
        loading = true;
        loader.show();

		var ajaxData = {
			action: isFavourites ? 'load_favourite_posts' : 'load_filtered_posts',
			page: page,
			post_type: postType,
			topic: filters.topic,
			type: filters.type,
			trending_themes: filters['trending-themes'],
			event: filters.event,
			persona: filters.persona,
			sector: filters.sector,
			search: currentSearch,
			sort: currentSort
		};
		// Add research_type_order only if the input exists
		var $researchInput = $module.find('input[name="research_type_order"]');
		if ($researchInput.length) {
			ajaxData.research_type_order = $researchInput.val();
		}
		// Only include date if the date filter exists
		if ($module.find('.filter-dropdown[data-filter="date"]').length) {
			ajaxData.date = currentDate;
		}

        $.ajax({
            url: ajaxobject.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: ajaxData,
            success: function(response){
				console.log('AJAX Debug:', response.data.debug);
                if (!response || !response.success) {
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

                if(postsPage >= postsMaxPages) {
                    loadMoreBtn.hide();
                } else {
                    loadMoreBtn.show();
                }

                try { buildActiveFilterPills(); } catch(e) {}
                try { hideEmptyFilters(response.data.visible_terms); } catch(e) {}

                loading = false;
                loader.hide();
                loadFeaturedPostsIfNeeded();
            },
            error: function(){
                loading = false;
                loader.hide();
            }
        });
    }

    // ===============================
    // FEATURED POSTS
    // ===============================
    function loadFeaturedPost(type, termSlug) {
        var container = $module.find('#featured-post-' + type);
        if (!container.length) return;

        var inner = container.find('.container');
        if (!inner.length) return;

        if (!termSlug || termSlug === '') {
            inner.html('');
            container.hide();
            return;
        }

        $.ajax({
            url: ajaxobject.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'load_featured_post',
                type: type,
                term_slug: termSlug
            },
            success: function(response) {
                if (response && response.success && response.data && response.data.has_post) {
                    inner.html(response.data.html);
                    container.show();
                } else {
                    inner.html('');
                    container.hide();
                }
            },
            error: function() {
                inner.html('');
                container.hide();
            }
        });
    }

    function loadFeaturedPostsIfNeeded() {
        if ($module.find('#featured-post-persona').length) {
            var personaSlug = filters.persona.length ? filters.persona[0] : '';
            loadFeaturedPost('persona', personaSlug);
        }
        if ($module.find('#featured-post-sector').length) {
            var sectorSlug = filters.sector.length ? filters.sector[0] : '';
            loadFeaturedPost('sector', sectorSlug);
        }
    }

    // ===============================
    // INITIAL LOAD
    // ===============================
    // loadPosts(1, false);
	loader.hide();
    buildActiveFilterPills();
	loader.hide();
    // loadFeaturedPostsIfNeeded();
    // updateURL(false);

    // ===============================
    // CLICK HANDLERS
    // ===============================
    $module.find('.filter-dropdown .filter-button').on('click', function(e){
        e.preventDefault();

        var $btn = $(this);
        var $dropdown = $btn.closest('.filter-dropdown');
        var filter = $dropdown.data('filter');

        if ($dropdown.find('.dropdown-title').hasClass('disabled-dropdown')) return;

        if (filter === 'date') {
			currentDate = normalizeFilterValue($btn.data('value'));
			filters.date = currentDate;
		} else {
			if ($btn.hasClass('all')) {
				filters[filter] = normalizeFilterValue($btn.data('value'));
			} else {
				filters[filter] = normalizeFilterValue($btn.data('value'));
			}
		}

        $btn.siblings().removeClass('active');
        $btn.addClass('active');

        var $title = $dropdown.find('.dropdown-title');
        if (!$btn.hasClass('all')) {
            $title.addClass('filter-active').removeClass('active');
        } else {
            $title.removeClass('filter-active active');
        }

        postsPage = 1;
        loadPosts(1, false);
        loadFeaturedPostsIfNeeded();
        updateActiveFilterClasses();
        // Only update URL if NOT All
        updateURL(!$btn.hasClass('all'));

		// ----------------------------
		// CLOSE DROPDOWN AFTER CLICK
		// ----------------------------
		$dropdown.find('.dropdown-list').slideUp(200);
		$title.removeClass('active');
	});

    searchForm.on('submit', function(e){
        e.preventDefault();
        currentSearch = searchInput.val().trim();
        postsPage = 1;
        loadPosts(1, false);
        loadFeaturedPostsIfNeeded();
        updateActiveFilterClasses();
        updateURL(true);
    });

    loadMoreBtn.on('click', function(e){
        e.preventDefault();
		if( !postsPage ){
			postsPage = 1;
		}

		postsPage++;
		loadPosts(postsPage, true);

        // if (postsPage < postsMaxPages) {
        //     postsPage++;
        //     loadPosts(postsPage, true);
        // }
    });

    resetBtn.on('click', function(e){
        e.preventDefault();

        for (var key in filters) {
            if (filters.hasOwnProperty(key)) filters[key] = [];
        }

        $module.find('.filter-dropdown .filter-button').removeClass('active');
        $module.find('.filter-dropdown .filter-button.all').addClass('active');
        $module.find('.dropdown-title').removeClass('filter-active');

        currentSearch = '';
        searchInput.val('');

        postsPage = 1;
        loadPosts(1, false);
        loadFeaturedPostsIfNeeded();
        updateActiveFilterClasses();
        updateURL(true);
    });

    // ===============================
    // HIDE EMPTY FILTER BUTTONS
    // ===============================
    function hideEmptyFilters(visibleTerms){
        var taxonomyMap = {
            topic: 'topic',
            type: 'filter-types',
            persona: 'persona-mapping',
            sector: 'sector-analysis',
            event: 'insights-event',
            'trending-themes': 'trending-themes',
			date: 'date'
        };

        $module.find('.filter-dropdown').each(function(){
            var $dropdown = $(this);
            var filter = $dropdown.data('filter');
            var taxonomyKey = taxonomyMap[filter] || filter;

            var allowedRaw = visibleTerms ? visibleTerms[taxonomyKey] : [];
            var allowed = [];

            if ($.isArray(allowedRaw)) {
                allowed = allowedRaw;
            } else if (allowedRaw && typeof allowedRaw === 'object') {
                for (var k in allowedRaw) { if (allowedRaw.hasOwnProperty(k)) allowed.push(allowedRaw[k]); }
            }

            $dropdown.find('.filter-button').each(function(){
                var $btn = $(this);
                if ($btn.hasClass('all')) { $btn.show(); return; }

                var val = $btn.data('value');
                var values = $.isArray(val) ? val : [val];

                var shouldShow = false;
                for (var i=0; i<values.length; i++) {
                    if (allowed.indexOf(values[i]) !== -1) {
                        shouldShow = true; break;
                    }
                }

                if (shouldShow) $btn.show();
                else $btn.hide();
            });
        });
    }

    // ===============================
    // POPSTATE (back/forward)
    // ===============================
    window.addEventListener('popstate', function(event){
        if (!event.state) return;
        filters = event.state.filters || filters;
        currentSearch = event.state.search || '';
        searchInput.val(currentSearch);
        postsPage = 1;
        loadPosts(1, false);
        buildActiveFilterPills();
        loadFeaturedPostsIfNeeded();
        updateActiveFilterClasses();
    });

});




$(document).on('click', function(e) {
    $('.filters-wrapper .filter-dropdown').each(function() {
        var $dropdown = $(this);

        // If the click was outside this dropdown
        if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
            $dropdown.find('.dropdown-list').slideUp(200);
            $dropdown.find('.dropdown-title').removeClass('active');
        }
    });
});
		// Partners Ajax 

		$('.speaker-module').each(function() {

    var $module = $(this);
    var container = $module.find('.speakers');
    var loadMoreBtn = $module.find('.load-more-btn');
    var resetBtn = $module.find('.reset-filters-btn');
    var loader = $module.find('.ajax-loader');
    var searchForm = $module.find('.partner-search-form');
    var searchInput = $module.find('.partner-search-input');

    var partnersPage = 1;
    var partnersMaxPages = 1;
    var loading = false;

    var currentExpertise = $module.find('.filter-dropdown[data-filter="expertise"] .filter-button.active').data('value') || '';
    var currentIndustry = $module.find('.filter-dropdown[data-filter="industry"] .filter-button.active').data('value') || '';
    var currentSearch = searchInput.length ? searchInput.val().trim() : '';

    var partnerTypeId = $module.data('partner-type-id');

    // ===============================
    // Build Active Filter Pills
    // ===============================
    function buildActiveFilterPills() {
        var pillsWrap = $module.find('.active-filter-pills');
        if (!pillsWrap.length) return;

        pillsWrap.empty();
        var hasPills = false;

        // Expertise
        var $expertiseBtn = $module.find('.filter-dropdown[data-filter="expertise"] .filter-button.active').not('[data-value=""]');
        if ($expertiseBtn.length) {
            hasPills = true;
            var pill = $('<button type="button" class="filter-pill"><span>' + $.trim($expertiseBtn.text()) + '</span><span class="pill-close">×</span></button>');
            pill.on('click', function() {
                $expertiseBtn.removeClass('active');
                $module.find('.filter-dropdown[data-filter="expertise"] .filter-button[data-value=""]').addClass('active');
                $module.find('.filter-dropdown[data-filter="expertise"] .dropdown-title').removeClass('filter-active');
                currentExpertise = '';
                partnersPage = 1;
                loadPartners(1, false);
            });
            pillsWrap.append(pill);
        }

        // Industry
        var $industryBtn = $module.find('.filter-dropdown[data-filter="industry"] .filter-button.active').not('[data-value=""]');
        if ($industryBtn.length) {
            hasPills = true;
            var pill = $('<button type="button" class="filter-pill"><span>' + $.trim($industryBtn.text()) + '</span><span class="pill-close">×</span></button>');
            pill.on('click', function() {
                $industryBtn.removeClass('active');
                $module.find('.filter-dropdown[data-filter="industry"] .filter-button[data-value=""]').addClass('active');
                $module.find('.filter-dropdown[data-filter="industry"] .dropdown-title').removeClass('filter-active');
                currentIndustry = '';
                partnersPage = 1;
                loadPartners(1, false);
            });
            pillsWrap.append(pill);
        }

        // Search pill
        if (currentSearch !== '') {
            hasPills = true;
            var pill = $('<button type="button" class="filter-pill"><span>Search: "' + currentSearch + '"</span><span class="pill-close">×</span></button>');
            pill.on('click', function() {
                currentSearch = '';
                searchInput.val('');
                partnersPage = 1;
                loadPartners(1, false);
            });
            pillsWrap.append(pill);
        }
		console.log('hasPills => ', hasPills);
        pillsWrap.toggle(hasPills);
    }

    // ===============================
    // AJAX loader
    // ===============================
    function loadPartners(page, append) {
        if (loading) return;
        loading = true;
        loader.show();

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
            success: function(response) {
                if (!response || !response.success) {
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

                buildActiveFilterPills(); // <-- update pills
                loading = false;
                loader.hide();
            },
            error: function() {
                loading = false;
                loader.hide();
            }
        });
    }

    // ===============================
    // Initial Load
    // ===============================
    // loadPartners(1, false);
    // buildActiveFilterPills();
	loader.hide();

    // ===============================
    // Filter buttons
    // ===============================
    $module.find('.filter-dropdown .filter-button').on('click', function(e) {
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
        loadPartners(1, false);
    });

    // ===============================
    // Search form
    // ===============================
    if (searchForm.length) {
        searchForm.on('submit', function(e) {
            e.preventDefault();
            currentSearch = searchInput.val().trim();
            partnersPage = 1;
            loadPartners(1, false);
        });
    }

    // ===============================
    // Load More
    // ===============================
    loadMoreBtn.on('click', function(e) {
        e.preventDefault();
		if( !partnersPage ){
			partnersPage = 1;
		}

		partnersPage++;
		loadPartners(partnersPage, true);

        // if (partnersPage < partnersMaxPages) {
        //     partnersPage++;
        //     loadPartners(partnersPage, true);
        // }
    });

    // ===============================
    // Reset filters
    // ===============================
    resetBtn.on('click', function(e) {
        e.preventDefault();

        currentExpertise = '';
        currentIndustry = '';
        currentSearch = '';
        partnersPage = 1;

        $module.find('.filter-button').removeClass('active');
        $module.find('.filter-button[data-value=""]').addClass('active');
        $module.find('.dropdown-title').removeClass('filter-active');
        if (searchInput.length) searchInput.val('');

        loadPartners(1, false);
    });

});


		function closeAllDropdowns($context) {
			$context.find('.dropdown-title').removeClass('active');
			$context.find('.dropdown-list').slideUp(300);
		}

		// New partners 

		$(document).on('click', '.filter-dropdown .dropdown-title', function(e){
			e.preventDefault();
			e.stopPropagation();

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


		// Past events 

		var $containerPast = $('#past-sessions-container .upcoming-listing');
var $buttonPast = $('#load-more-past-sessions');

if ($containerPast.length && $buttonPast.length) {
    $buttonPast.on('click', function(e) {
        e.preventDefault();

        var offset = parseInt($buttonPast.data('offset')) || 0;
        var perpage = parseInt($buttonPast.data('perpage')) || 18;

        $.ajax({
            url: ajaxobject.ajax_url,
            type: 'POST',
            data: {
                action: 'load_past_sessions_unique',
                offset: offset,
                perpage: perpage
            },
            beforeSend: function() {
                $buttonPast.text('Loading...').prop('disabled', true);
            },
            success: function(response) {
                if (!response.trim()) {
                    $buttonPast.hide();
                    return;
                }

                $containerPast.append(response);

                // Update offset
                offset += perpage;
                $buttonPast.data('offset', offset);

                // Hide button if fewer posts returned than perpage
                if (response.trim().length < 10) { // rough check for last batch
                    $buttonPast.hide();
                } else {
                    $buttonPast.text('Load More').prop('disabled', false);
                }
            },
            error: function() {
                $buttonPast.text('Load More').prop('disabled', false);
            }
        });
    });
}


		// benchmarking 

		// Customer Events Sliders 

		// Two column speaker image slider
		
		var $slides = $('.customer-events-image-slider .slide');
		var currentIndex = 0;
		var autoplayInterval;

		function setActiveSlide(index) {
			$slides.removeClass('active');
			$slides.eq(index).addClass('active');
			// resetProgressBar($slides.eq(index));
			currentIndex = index;
		}

		function autoplaySlides() {
			autoplayInterval = setInterval(function() {
				var nextIndex = (currentIndex + 1) % $slides.length;
				setActiveSlide(nextIndex);
			}, 5000); // 5 seconds per slide
		}

		function resetAutoplay() {
			clearInterval(autoplayInterval);
			autoplaySlides();
		}

		function resetProgressBar($slide) {
			var $progressInner = $slide.find('.progress-inner');

			// Reset width to 0
			$progressInner.css('width', '0');

			// Force reflow to flush the style change
			void $progressInner[0].offsetWidth;

			// Then set to 100% to trigger the transition
			$progressInner.css('width', '100%');
		}


		$slides.on('click', function() {
			var index = $(this).index();
			setActiveSlide(index);
			resetAutoplay();
		});

		// Start autoplay initially
		setActiveSlide(0);
		autoplaySlides();
		
		// Speaker Text Slide


		// Two column speaker image slider
		var $slidesSpeakerImage = $('.speaker-slider-image-outer .speaker-slide-image');
		var $slidesSpeakerText = $('.speaker-slider-text-outer .speaker-slide-text');
		var currentIndexSpeaker = 0;
		var autoplayIntervalSpeaker;

		// Function to set the active slide
		function setActiveSlideSpeaker(index) {
			$slidesSpeakerImage.removeClass('active');
			$slidesSpeakerText.removeClass('active');
			
			$slidesSpeakerImage.eq(index).addClass('active');
			$slidesSpeakerText.eq(index).addClass('active');
			
			currentIndexSpeaker = index;
		}

		// Autoplay functionality for screens larger than 767px
		function autoplaySlidesSpeaker() {
			if ($(window).width() > 767) {
				autoplayIntervalSpeaker = setInterval(function() {
					var nextIndexSpeaker = (currentIndexSpeaker + 1) % $slidesSpeakerImage.length;
					setActiveSlideSpeaker(nextIndexSpeaker);
				}, 6000); // 5 seconds per slide
			}
		}

		// Reset autoplay (clear and restart) for screens larger than 767px
		function resetAutoplaySpeaker() {
			if ($(window).width() > 767) {
				clearInterval(autoplayIntervalSpeaker);
				autoplaySlidesSpeaker();
			}
		}

		// Stop autoplay for small screens
		function stopAutoplaySpeaker() {
			clearInterval(autoplayIntervalSpeaker);
		}

		// Start autoplay initially if screen is larger than 767px
		$(window).on('load resize', function() {
			if ($(window).width() > 767) {
				autoplaySlidesSpeaker();
			} else {
				stopAutoplaySpeaker();  // Stop autoplay on smaller screens
			}
		});

		// Click event for slides, active on all screens
		$slidesSpeakerText.on('click', function() {
			var index = $(this).index();
			setActiveSlideSpeaker(index);
			if ($(window).width() > 767) {
				resetAutoplaySpeaker();  // Reset autoplay if applicable
			}
		});

		// EVR progress tracking

		var $trackingLine = $('.steps-container .tracking-line');
		var $steps = $('.steps-container .step');
		var $window = $(window);

		$window.on('scroll', function () {
			var scrollTop = $window.scrollTop();
			var windowHeight = $window.height();
			var windowWidth = $window.width();
			var isSmallScreen = windowWidth <= 767;

			if (!$steps.length || !$trackingLine.length) return;

			$steps.each(function () {
				var $step = $(this);
				var stepOffset = $step.offset().top;
				var stepHeight = $step.outerHeight();
				
				// Trigger step activation earlier on small screens
				var stepMidPoint = stepOffset + stepHeight / (isSmallScreen ? 1.5 : 2);

				if (scrollTop + windowHeight / (isSmallScreen ? 1.2 : 2) >= stepMidPoint - 24) {
					$step.find('.step-counter').addClass('active');
				} else {
					$step.find('.step-counter').removeClass('active');
				}
			});

			// Update tracking line height
			var firstStepTop = $steps.first().offset().top + 56;
			var lastStepBottom = $steps.last().offset().top + $steps.last().outerHeight() + 180;

			var maxLineHeight = lastStepBottom - firstStepTop;
			var scrolledDistance = scrollTop + windowHeight / (isSmallScreen ? 1.2 : 2) - firstStepTop;
			var newLineHeight = Math.min(maxLineHeight, Math.max(0, scrolledDistance));

			if (isSmallScreen) {
				newLineHeight += 175; // Faster growth for smaller screens
			}

			if (scrollTop + windowHeight > firstStepTop && scrollTop < lastStepBottom) {
				$trackingLine.css({
					'height': newLineHeight + 'px',
					'top': isSmallScreen ? '-175px' : '0px'
				});
			} else if (scrollTop + windowHeight / 2 <= firstStepTop) {
				$trackingLine.css('height', '0px');
			} else {
				$trackingLine.css('height', (lastStepBottom - firstStepTop + (isSmallScreen ? 175 : 0)) + 'px');
			}
		});

		// Form popup slider

		$('.form-popup-slider').on('afterChange', function(event, slick, currentSlide){
			var slidesToShow = slick.slickGetOption('slidesToShow');
			var totalSlides = slick.slideCount - slidesToShow + 1; // Adjust based on slidesToShow
			var progress = ((currentSlide) / (totalSlides - 1)) * 100;

			$('.progress-bar-form-popup').css('width', progress + '%');		
		});

		// Initialize progress on load
		$('.form-popup-slider').on('init', function(event, slick){
			$('.progress-bar-form-popup').css('width', '0%');

			if (typeof FormCraftsPopup !== 'undefined' && typeof FormCraftsPopup.scanPage === 'function') {
				FormCraftsPopup.scanPage();
			}
		});

		$('.form-popup-slider').slick({
			slidesToShow: 2,
			slidesToScroll: 1,
			arrows: true,
			infinite: false,
			responsive: [
			  {
			   breakpoint: 1023,
			   settings: {
				slidesToShow: 1,
			   }
			  }	
			]	
		});

		if (typeof FormCraftsPopup !== 'undefined' && typeof FormCraftsPopup.scanPage === 'function') {
			FormCraftsPopup.scanPage();
		}

		// Full suite slider 

		$('.full-suite-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			infinite: false
		});

		$('.card-container.card-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: false
		});

		$('.full-suite-slider').on('afterChange', function(event, slick, currentSlide){
			var slidesToShow = slick.slickGetOption('slidesToShow');
			var totalSlides = slick.slideCount - slidesToShow + 1; // Adjust based on slidesToShow
			var progress = ((currentSlide) / (totalSlides - 1)) * 100;

			$('.progress-bar-form-suite').css('width', progress + '%');		
		});

		if ($('.large-quote-slide-container').length) {

			var $sliderLargeQuote = $('.large-quote-slide-container');
			var $timerLargeQuote = $('.quote-slider-timer-inner');
			var autoplaySpeedLargeQuote = 8000; // 8 seconds
			var timerLargeQuote;

			// Init slick
			$sliderLargeQuote.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				infinite: true,
				fade: true,
				autoplay: true,
				autoplaySpeed: autoplaySpeedLargeQuote,
				pauseOnHover: false,
				pauseOnFocus: false
			});

			// Start progress bar
			function startProgress() {
				clearTimeout(timerLargeQuote);
				$timerLargeQuote.stop(true).css({ width: 0 }).animate(
					{ width: '100%' },
					autoplaySpeedLargeQuote,
					'linear'
				);
			}

			// Restart bar on init + after every slide change
			$sliderLargeQuote.on('init reInit afterChange', function() {
				startProgress();
			});

			// Reset bar if user clicks nav
			$sliderLargeQuote.on('beforeChange', function() {
				$timerLargeQuote.stop(true).css({ width: 0 });
			});

			// Run once after first init
			startProgress();
		}

		// if ($('.auto-card-slider').length) {

		// 	var $autoCardSlider = $('.auto-card-slider');

		// 	$autoCardSlider.slick({
		// 		slidesToShow: 3,
		// 		slidesToScroll: 1,
		// 		infinite: true,
		// 		arrows: false,
		// 		autoplay: true,
		// 		autoplaySpeed: 0, 
		// 		speed: 8000,
		// 		cssEase: 'linear',
		// 		pauseOnHover: true,
		// 		pauseOnFocus: true,
		// 	});

		// 	// Pause on hover anywhere inside the slider
		// 	$autoCardSlider.on("mouseenter", function () {
		// 		$autoCardSlider.slick("slickPause");
		// 	});

		// 	$autoCardSlider.on("mouseleave", function () {
		// 		$autoCardSlider.slick("slickPlay");
		// 	});
		// }


		$('.gtm-icon-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: false
		});

		// GTM mobile map scroller

		$('.mobile-gtm-card-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: true,           // loop
			autoplay: true,           // autoplay
			autoplaySpeed: 3000,      // 3 seconds per card
			speed: 300,               // 300ms transition
			fade: true,               // fade effect
			cssEase: 'linear'         // smoother fade
		});
		

		// Handle the slide link clicks
		$('.icon-slide-link').on('click', function(e) {
			e.preventDefault();

			// Remove 'active' class from all slide links
			$('.icon-slide-link').removeClass('active');

			// Add 'active' class to the clicked link
			$(this).addClass('active');

			// Get the index of the clicked link
			var slideIndexIcon = $(this).index();

			// Scroll to the corresponding slide
			$('.gtm-icon-slider').slick('slickGoTo', slideIndexIcon);
		});

		// Optional: Add the 'active' class to the first slide link by default
		$('.icon-slide-link').first().addClass('active');
		

		// story landing slider mobile 

		$('.mobile-story-slider').slick({
		  // centerMode: false,
		  arrows: false,
		  dots: true,
		  infinite: true,
		  // centerMode: true,
		  // centerPadding: paddingWidthPx,
		  autoplay: false,
		  slidesToShow: 3,
		  focusOnSelect: true,
		  responsive: [
		    {
		      breakpoint: 1023,
		      settings: {
		        slidesToShow: 2,
		      }
		  	},
			{
			 breakpoint: 767,
			 settings: {
			   slidesToShow: 1
			 }
		   }
		  ]
		});
		
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

		// Benchmarking templates 

			// Slide Stack
		var track = $('.slider-track');
		var trackSlides = track.find('.slide');
		var contentSlides = $('.slider-content .slide');
		var DURATION = 300; // transition duration
		var busy = false;

		var positions = [
			{ y: -60, scale: 0.75, z: 5 },
			{ y: -48, scale: 0.8, z: 6 },
			{ y: -36, scale: 0.85, z: 7 },
			{ y: -24, scale: 0.9, z: 8 },
			{ y: -12, scale: 0.95, z: 9 },
			{ y: 0, scale: 1, z: 10 }
		];

		// ensure positions match slide count
		if (trackSlides.length < positions.length) {
			positions = positions.slice(positions.length - trackSlides.length);
		}

		/* -----------------------------------------------------
		⭐ FIX: Reverse the initial DOM order so highest-numbered
		slide is on top (closest to index 0)
		----------------------------------------------------- */
		track.append(track.children().get().reverse());
		trackSlides = track.find('.slide');
		/* ----------------------------------------------------- */

		function applyPositions(){
			trackSlides.each(function(i){
				var pos = positions[i];
				$(this).css({
					transform:'translateY(' + pos.y + 'px) scale(' + pos.scale + ')',
					'z-index': pos.z,
					opacity: 1
				});
			});

			// sync content
			var activeNumber = trackSlides.eq(-1).data('slide-number');
			contentSlides.removeClass('active')
				.filter('[data-slide-number="'+activeNumber+'"]')
				.addClass('active');
		}

		applyPositions();

		function prevSlide(){
			if(busy) return;
			busy = true;

			var front = trackSlides.eq(0);

			// Step 1: fade out front slide
			front.css('opacity', 0);

			setTimeout(function(){

				// Step 2: move front slide to back in DOM
				front.appendTo(track);

				// refresh trackSlides
				trackSlides = track.find('.slide');

				// Apply positions
				applyPositions();

				busy = false;
			}, DURATION);
		}

		function nextSlide(){
			if(busy) return;
			busy = true;

			var back = trackSlides.last();

			// Step 1: fade out back slide
			back.css('opacity', 0);

			setTimeout(function(){

				// Step 2: move back slide to front in DOM
				back.prependTo(track);

				// refresh trackSlides
				trackSlides = track.find('.slide');

				// Apply positions
				applyPositions();

				busy = false;
			}, DURATION);
		}

		$('.slide-stack .slide-next').on('click', nextSlide);
		$('.slide-stack .slide-prev').on('click', prevSlide);

		// GTM Map hovers

		$('.map-container .card-hover-container').each(function() {
			var $container = $(this);
			var $trigger = $container.find('.card-hover-trigger');
			var $card = $container.find('.gtm-card');

			$trigger.on('mouseenter', function() {
				$('.map-container .card-hover-trigger, .map-container .gtm-card').removeClass('active');
				$trigger.addClass('active');
				$card.addClass('active');
			});
		});

		// Customer events scroll magic

		// ScrollMagic was removed when this project migrated to GSAP
		// ScrollTrigger, but this section (and "scrolling grow text" below)
		// were never ported and still call the old ScrollMagic API. That threw
		// an uncaught ReferenceError on every page load, which also silently
		// killed the "scrolling grow text" block since both were more or less
		// back-to-back in the same execution. This guard just stops the error;
		// both scroll-pin animations remain non-functional until someone
		// ports them to ScrollTrigger's native pin: true.
		if (typeof ScrollMagic !== 'undefined') {

		// Ensure GSAP and ScrollMagic are loaded
		TweenLite.defaultEase = Linear.easeNone;
		var controllerTeam = new ScrollMagic.Controller();

		const $scrollContainers = $('.fixed-scroller-inner');

		if ($scrollContainers.length && $(window).width() > 767) {
			$scrollContainers.each(function() {
				const $scrollContainer = $(this);
				const $featuredContainerOuter = $scrollContainer.closest('.fixed-scroller');
				const $featuredContainer = $scrollContainer.closest('.fixed-scroller-container');
				const $teamMembers = $scrollContainer.find('.fixed-scroll-item');
				const memberWidth = 965;
				const gapWidth = 32;
				const totalWidth = ($teamMembers.length * (memberWidth + gapWidth));

				$scrollContainer.css('width', totalWidth + 'px');

				// Get the width of the featured-scroller-container
				const containerWidth = $featuredContainer.length ? $featuredContainer.width() : $(window).width();
				const endScroll = Math.max(0, totalWidth - containerWidth); // Ensure endScroll is not negative

				// Create a TimelineMax instance
				var tl = new TimelineMax();

				// Add the horizontal scroll animation to the timeline
				tl.to($scrollContainer[0], { x: -endScroll, ease: 'none' });

				// Horizontal scroll scene
				new ScrollMagic.Scene({
					triggerElement: $featuredContainerOuter[0],
					triggerHook: 0,
					duration: endScroll
				})
				.setPin($featuredContainer[0])
				.setTween(tl)
				.addTo(controllerTeam);

				// Vertical scroll continuation
				new ScrollMagic.Scene({
					triggerElement: $featuredContainerOuter[0],
					triggerHook: 0,
					duration: $(window).height()
				})
				.setTween(gsap.to($featuredContainer[0], { height: 'auto', ease: 'none' }))  // GSAP 3 syntax
				.addTo(controllerTeam);
			});

			// Handle window resize to destroy scenes if width drops below 767px
			$(window).on('resize', function() {
				if ($(window).width() <= 767) {
					$scrollContainers.each(function() {
						const $scrollContainer = $(this);
						const horizontalScene = $scrollContainer.data('horizontalScene');
						const verticalScene = $scrollContainer.data('verticalScene');

						if (horizontalScene) horizontalScene.destroy(true);
						if (verticalScene) verticalScene.destroy(true);
					});
				}
			});

			AOS.refresh();
		}

		// scrolling grow text 
		// Ensure GSAP and ScrollMagic are loaded
		var controllerText = new ScrollMagic.Controller();

		var $scrollingContainers = $('.scrolling-container');

		if ($scrollingContainers.length && $(window).width() > 767) {
			$scrollingContainers.each(function() {
				var $scrollContainer = $(this);
				var $fixedScrollerContainer = $scrollContainer.closest('.map-fixed-scroller-container');
				var $columns = $scrollContainer.find('.column');
				var $titles = $scrollContainer.find('.growing-title');

				// Ensure there are columns to work with
				if ($columns.length === 0) {
					console.error('No .column elements found.');
					return;
				}

				// Calculate the total width needed for horizontal scrolling
				var memberWidth = $columns.outerWidth(true);
				var gapWidth = 32; // Adjust this if necessary
				var columnCount = $columns.length - 1;
				var totalWidth = (columnCount * (memberWidth + gapWidth)) - gapWidth; // Subtract gapWidth as it's added at the end

				// Set the width of the scrolling container
				$scrollContainer.css('width', totalWidth + 'px');

				// Create GSAP timeline for horizontal scrolling
				var tlHorizontal = new TimelineMax();
				tlHorizontal.to($scrollContainer[0], 1, { x: -totalWidth, ease: Linear.easeNone });

				// Create ScrollMagic scene for horizontal scrolling
				new ScrollMagic.Scene({
					triggerElement: $fixedScrollerContainer[0],
					triggerHook: 0,
					duration: totalWidth
				})
				.setPin($fixedScrollerContainer[0])
				.setTween(tlHorizontal)
				.addTo(controllerText);

				// Create GSAP animations for each title
				$titles.each(function() {
					var $title = $(this);
					var titleWidth = $title.outerWidth(true);
					var titleOffset = $title.position().left; // Offset relative to the container

					// Create GSAP ScrollTrigger for each title
					gsap.to($title[0], {
						fontSize: '160px', // End font size
						ease: 'none',
						scrollTrigger: {
							trigger: $title[0],
							containerAnimation: tlHorizontal, // Link to horizontal scroll animation
							start: "center 100%",
							end: "center 10%",
							scrub: true
						}
					});
				});

			});

			// Handle window resize to destroy scenes if width drops below 767px
			$(window).on('resize', function() {
				if ($(window).width() <= 767) {
					controllerText.destroy(true);
				}
			});
		}

		} // end ScrollMagic guard


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
