<?php

/**
 * # Add Patches Settings Tab
 */
function yz_patches_settings() {

	do_action( 'yz_patches_settings' );

	wp_enqueue_script( 'jquery' );

	?>

	<script type="text/javascript">

	( function( $ ) {

		/**
		 * Process Updating Fields.
		 */
		$.yzc_patch_process_step = function( current_button, action, step, perstep, total, self ) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: action,
					step: step,
					total: total,
					perstep: perstep,
				},
				dataType: "json",
				success: function( response ) {
					if ( 'done' == response.step ) {

						current_button.addClass( 'yz-is-updated' );

						// window.location = response.url;
						current_button.html( '<i class="fas fa-check"></i>Done !' );

					} else {

						current_button.find( '.yz-button-progress' ).animate({
							width: response.percentage + '%',
						}, 50, function() {
							// Animation complete.
						});

						var total_items = ( response.step * response.perstep ) - response.perstep,
							items = total_items < response.total ? total_items : response.total;

						current_button.find( '.yz-items-count' ).html( items );

						$.yzc_patch_process_step( current_button, action, parseInt( response.step ), parseInt( response.perstep ), parseInt( response.total ), self );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}


		/**
		 * Process Updating Fields.
		 */
		$.yzc_run_patch = function( current_button, action, self ) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: action,
				},
				dataType: "json",
				success: function( response ) {

					if ( 'done' == response.step ) {

						current_button.addClass( 'yz-is-updated' );

						// window.location = response.url;
						current_button.html( '<i class="fas fa-check"></i>Done !' );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}

		$( 'body' ).on( 'click', 'a[data-run-single-patch="true"]', function(e) {

			if ( $( this ).hasClass( 'yz-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating...' );

			// Start The process.
			$.yzc_run_patch( $( this ), $( this ).data( 'action' ), self );


		});

		$( 'body' ).on( 'click', 'a[data-run-patch="true"]', function(e) {

			if ( $( this ).hasClass( 'yz-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			var per_step = $( this ).data( 'perstep' );
			var total = $( this ).data( 'total' );
			var action = $( this ).data( 'action' );

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating <div class="yz-button-progress"></div><span class="yz-items-count">' + 1 + '</span>' + ' / ' + total + ' ' + $( this ).data( 'items' ) );

			// Start The process.
			$.yzc_patch_process_step( $( this ), action, 1, per_step, total, self );

		});

	})( jQuery );

	</script>

	<?php

}