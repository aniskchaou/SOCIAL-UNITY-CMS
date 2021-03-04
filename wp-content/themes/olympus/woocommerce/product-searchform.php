<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form role="search"  method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="w-search full-width woocommerce-product-search">
    <div class="form-group with-button is-empty">
        <input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field form-control" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'olympus' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button>
			<?php echo olympus_icon_font('olympus-icon-Magnifying-Glass-Icon') ?>
        </button>
        <span class="material-input"></span>
    </div>
    <input type="hidden" name="post_type" value="product" />
</form>