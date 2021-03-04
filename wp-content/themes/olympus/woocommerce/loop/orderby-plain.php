<?php
/**
 * Show options for ordering
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<select name="orderby" class="orderby selectpicker form-control">
    <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
        <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
    <?php endforeach; ?>
</select>