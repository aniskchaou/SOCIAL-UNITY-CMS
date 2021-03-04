<?php

class Youzer_Extensions {

	function __construct() {

		// Add Youzer Plugin Admin Pages.
		add_action( 'admin_menu', array( $this, 'add_extensions_page' ), 99 );

		// Load Admin Scripts & Styles .
		add_action( 'admin_print_styles', array( $this, 'extensions_styles' ) );
	}

	/**
	 * Add Extensions Page.
	 */
	function add_extensions_page() {

		// Show Youzer Panel to Admin's Only.
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

	    // Add "Extensions" Page .
	    add_submenu_page(
	    	'youzer-panel',
	    	__( 'Youzer - Extensions', 'youzer' ),
	    	__( 'Extensions (Add-Ons)', 'youzer' ),
	    	'administrator',
	    	'yz-extensions',
	    	array( $this, 'extensions_page_content' )
	    );
	}

	/**
	 * Add Extensions Page.
	 */
	function extensions_page_content() {

		// Get Products.
		$extensions = $this->get_products();

		if ( empty( $extensions ) ) {
			_e( 'We couldn\'t display the list of extensions. Please try again later', 'youzer' );
			return;
		}

		?>

		<div id="youzer-extensions">
			<?php foreach ( $extensions['products'] as $extension ) : ?>

				<div class="yz-ext-item">
					<div class="yz-ext-container">
						<a class="yz-ext-thumb" href="<?php echo $extension['info']['link']; ?>" style="background-image: url(<?php echo $extension['info']['thumbnail']; ?>);">
							<div class="yz-ext-price"><?php echo reset( $extension['pricing'] ); ?>$</div>
						</a>
						<div class="yz-ext-content">
							<div class="yz-ext-title"><a href="<?php echo $extension['info']['link']; ?>"><?php echo $extension['info']['title']; ?></a></div>
							<div class="yz-ext-desc"><?php echo $extension['info']['excerpt']; ?></div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * Add Extensions Page.
	 */
	function get_products() {

        // Get Products
        $products_url = 'https://www.kainelabs.com/edd-api/products/';

        $remote = wp_remote_get( $products_url );

        // Check if remote is returning a false answer
        if ( is_wp_error( $remote ) ) {
            return false;
        }

        // Check If Url Is working.
        if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
           return false;
        }

        // GET User Data.
        $response = wp_remote_retrieve_body( $remote );
        if ( $response === false ) {
            return false;
        }

        // Decode Data.
        $data = json_decode( $response, true );
        if ( $data === null ) {
            return false;
        }

        return $data;
	}

	/**
	 * # Extensions Styles.
	 */
	function extensions_styles() {

		if ( isset( $_GET['page'] ) && 'yz-extensions' == $_GET['page'] ) {
	    	// Load Settings Style
		    wp_enqueue_style( 'yz-extensions', YZ_AA . 'css/yz-extensions.css', array(), YZ_Version );
	        wp_enqueue_style( 'yz-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:100,400,600', array(), YZ_Version );
		    wp_enqueue_style( 'yz-icons' );
		}
	}

}

		$extentions = new Youzer_Extensions();
