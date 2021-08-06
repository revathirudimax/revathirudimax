// wishlist script //
$(document).ready(function() {
	$('.like-icon, .like-button').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('liked');
		$(this).children('.like-icon').toggleClass('liked');
	});
});

// menu script //
$ (document).ready(function() {
	var fixHeight = function() {
		$ (".navbar-nav").css(
			"max-height",
			document.documentElement.clientHeight - 8000
		);
	};
	
		fixHeight();
		
	$(window).resize(function() {
		fixHeight();
	});
	
	$(".navbar .navbar-toggler").on("click", function() {
		fixHeight();
	});			

	$ (".navbar-toggler, .overlay").on("click", function() {
		$ (".mobileMenu, .overlay").toggleClass("open");
		console.log("clicked");
	});
});


// === Dropdown === //

$('.ui.dropdown')
  .dropdown()
;

$('.dropdown').dropdown({transition: 'drop', on: 'hover' });


// === Model === //
$('.ui.modal')
  .modal({
    blurring: true
  })
  .modal('show')
;

// === Tab === //
$('.menu .item')
  .tab()
;

// === checkbox Toggle === //
$('.ui.checkbox')
  .checkbox()
;

// === Toggle === //
$('.enable.button')
  .on('click', function() {
    $(this)
      .nextAll('.checkbox')
        .checkbox('enable')
    ;
  })
 ;


// Payment Method Accordion //
$('input[name="paymentmethod"]').on('click', function () {
	var $value = $(this).attr('value');
	$('.return-departure-dts').slideUp();
	$('[data-method="' + $value + '"]').slideDown();
});



//  Countdown //
$(".product_countdown-timer").each(function(){
	var $this = $(this);
	$this.countdown($this.data('countdown'), function(event) {
	  $(this).text(
		event.strftime('%D days %H:%M:%S')
	  );
	});
});


// === Banner Home === //
$('.offers-banner').owlCarousel({
	loop:true,
    margin:30,
	nav:false,
	dots:false,
    autoplay:true,
    autoplayTimeout: 3000,
    autoplayHoverPause:true,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:2
		},
		1000:{
			items:2
		},
		1200:{
			items:3
		},
		1400:{
			items:3
		}
	}
})

// Category Slider
$('.cate-slider').owlCarousel({
	loop:true,
	margin:30,
	nav:true,
	dots:false,
	navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
	responsive:{
		0:{
			items:2
		},
		600:{
			items:4
		},
		1000:{
			items:4
		},
		1200:{
			items:6
		},
		1400:{
			items:6
		}
	}
})

// Featured Slider
$('.featured-slider').owlCarousel({
	items: 10,
	loop:false,
	margin:5,
	nav:true,
	dots:false,
	navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
	responsive:{
		0:{
			items:2
		},
		600:{
			items:3
		},
		1000:{
			items:4
		},
		1200:{
			items:5
		},
		1400:{
			items:5
		}
	}
})

// === Date Slider === //
$('.date-slider').owlCarousel({
	loop:false,
    margin:10,
	nav:false,
	dots:false,
	responsive:{
		0:{
			items:3
		},
		600:{
			items:4
		},
		1000:{
			items:5
		},
		1200:{
			items:6
		},
		1400:{
			items:7
		}
	}
})

// === Banner Home === //
$('.life-slider').owlCarousel({
	loop:true,
    margin:30,
	nav:true,
	dots:false,
    autoplay:true,
    autoplayTimeout: 3000,
    autoplayHoverPause:true,
	navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
	responsive:{
		0:{
			items:1
		},
		600:{
			items:2
		},
		1000:{
			items:2
		},
		1200:{
			items:3
		},
		1400:{
			items:3
		}
	}
})

// === Testimonials Slider === //
$('.testimonial-slider').owlCarousel({
	loop:true,
    margin:30,
	nav:true,
	dots:false,
	autoplay:true,
    autoplayTimeout: 3000,
    autoplayHoverPause:true,
	navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
	responsive:{
		0:{
			items:1
		},
		600:{
			items:2
		},
		1000:{
			items:2
		},
		1200:{
			items:3
		},
		1400:{
			items:3
		}
	}
})

// Category Slider
$('.team-slider').owlCarousel({
	loop:true,
	margin:30,
	nav:false,
	dots:false,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:2
		},
		1000:{
			items:3
		},
		1200:{
			items:4
		},
		1400:{
			items:4
		}
	}
})

$('.digit-group').find('input').each(function() {
	$(this).attr('maxlength', 1);
	$(this).on('keyup', function(e) {
		var parent = $($(this).parent());
		
		if(e.keyCode === 8 || e.keyCode === 37) {
			var prev = parent.find('input#' + $(this).data('previous'));
			
			if(prev.length) {
				$(prev).select();
			}
		} else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
			var next = parent.find('input#' + $(this).data('next'));
			
			if(next.length) {
				$(next).select();
			} else {
				if(parent.data('autosubmit')) {
					parent.submit();
				}
			}
		}
	});
});

