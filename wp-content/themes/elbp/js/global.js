jQuery(document).ready(function($) {
	// Mobile Menu
	$("#navigation").mmenu(
		{
			offCanvas: {
				'position': 'right'
			},
			navbars: {
				height: 3,
				content: [
					'<a class="contact_header inline-block" target="blank" href="tel:0800 121 4458"><i class="ion-ios-telephone-outline"></i></a>',
					'<a href="#/" class="inline-block"><img class="mobile_logo" src="https://directory.getsetforgrowth.com/wp-content/themes/elbp/images/svg/elbp-logo_white.svg" class="logo" alt="" width="210"></a>',
					'<a class="contact_header inline-block" target="blank" href="mailto:london@getsetforgrowth.com"><i class="ion-android-drafts"></i></a>'
				]
			},
			extensions: [
            	"pagedim-black",
            ],
			slidingSubmenus: false
		}
	);
	var mobilemenu = $("#navigation").data('mmenu');
	
	mobilemenu.bind('opened', function () {
		$('.hamburger').toggleClass('is-active');
	});
	mobilemenu.bind('closing', function () {
		$('.hamburger').removeClass('is-active');
	});

// NAV fix
	var bottom_nav = $('.nav_point').offset().top;
	$(window).scroll(function(){    
	    if ($(this).scrollTop() > bottom_nav){ 
	        $('header').addClass('fixed'); 
	    }
	    else{
	        $('header').removeClass('fixed');
	    }
	});													   	
});


