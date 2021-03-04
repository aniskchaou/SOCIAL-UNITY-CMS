( function( $ ) {

    'use strict';

	$( document ).ready( function() {

	    $.youzer_sliders_init = function() {

			// Set Up Variables.
			var $progressBar, $bar, $elem, isPause, tick, percentTime, time = Youzer.slideshow_speed;
			
			var yz_auto_slideshow = ( Youzer.slideshow_auto == '1' ) ? true : false;

		    // Init progressBar where elem is $(".yz-slider")
		    function progressBar( elem ) {
		    	
		    	if ( ! yz_auto_slideshow ) {
		    		return;
		    	}

			    $elem = elem;
			    // build progress bar elements
			    buildProgressBar();
			    // start counting
			    start();
		    }

		    // Create div#progressBar and div#bar then prepend to the slider.
		    function buildProgressBar() {
				$progressBar = $( '<div>', { id: 'yz-progressBar' } );
				$bar 		 = $( '<div>', { id: 'yz-bar' } );
				$progressBar.append( $bar ).prependTo( $elem );
		    }

		    function start() {
		    	// Reset timer
		    	percentTime = 0;
		    	isPause 	= false;
		    	// Run interval every 0.01 second
		    	tick = setInterval( interval, 10 );
		    };

		    function interval() {
		      	if ( isPause === false ) {
			        percentTime += 1 / time;
			        $bar.css( {
			           width: percentTime+"%"
			        } );

		        //if percentTime is equal or greater than 100
		        if ( percentTime >= 100 ) {
					//slide to next item
					$elem.trigger( 'owl.next' )
		        }
		      }
		    }

		    // Pause while dragging
		    function pauseOnDragging() {
		   		isPause = true;
		    }

		    // Moved callback
		    function moved(){
		    	
		    	if ( ! yz_auto_slideshow ) {
		    		return;
		    	}

				clearTimeout( tick );
				start();
		    }

			/**
			 * SlideShow
			 */
			var yz_slides_height = ( Youzer.slides_height_type == 'auto' ) ? true : false; 
			var slideshow_attr = {
					paginationSpeed : 1000,
					singleItem 		: true,
					navigation 		: true,
					afterMove 		: moved,
					transitionStyle : 'fade',
					afterInit 		: progressBar,
					startDragging 	: pauseOnDragging,
			    	autoHeight		: yz_slides_height
			   };
	    	if ( $( '.yz-slider' )[0] && $( '.yz-slider li' ).length > 1 ) {

			    // Init the carousel
			    $( '.yz-slider' ).yz_owlCarousel( slideshow_attr );
			}

		    $.yz_wall_slider = function() {

			    // Init the carousel
			    $( '.yzw-slider' ).yz_owlCarousel( slideshow_attr );
		    }

		    $( '.yzw-slider' ).each( function( i, obj ) {
		    	if ( ! $( obj ).hasClass( 'owl-carousel' ) ) {
		    		$( obj ).yz_owlCarousel( slideshow_attr );
		    	}
			});

		}
		
		$.youzer_sliders_init();

		// Init Effect On the appended elements also.
		$( '#activity-stream' ).on( 'append', function( e ) {
			$.youzer_sliders_init();
	    });

	});

})( jQuery );