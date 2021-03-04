<?php
/**
 * @package olympus-wp
 */
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<li class="cart-menulocation">
	<a href="<?php echo wc_get_cart_url(); ?>" class="nav-link" title="<?php esc_attr_e( 'View your shopping cart', 'olympus' ); ?>">
		<?php echo olympus_icon_font( 'olympus-icon-Shop-Bag-Icon' ); ?>
		<span class="count-product"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	</a>
	<?php if ( 0 < count( WC()->cart->get_cart() ) ) { ?>
		<div class="more-dropdown shop-popup-cart">

			<div class="mCustomScrollbar">
				<ul class="woocommerce-mini-cart cart_list product_list_widget">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
							$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<li class="cart-product-item">
								<?php
								$image = get_the_post_thumbnail_url( $product_id );
								if ( $image ) {
									$thumb      = olympus_resize( $image, 35, 35, true );
									$thumb_html = olympus_html_tag( 'a', array( 'href' => $product_permalink ), '<img loading="lazy" src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $product_name ) . '">' );
									echo olympus_html_tag( 'div', array( 'class' => 'product-thumb' ), $thumb_html );
								}
								?>
								<div class="product-content">
									<h6 class="title"><?php echo olympus_html_tag( 'a', array( 'href' => $product_permalink ), esc_html( $product_name ) ); ?></h6>

									<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>

									<?php
									if ( get_option( 'woocommerce_enable_review_rating' ) !== 'no' ) {
										olympus_shop_rating_html( intval( $_product->get_average_rating() ) );
									}
									?>

									<?php echo olympus_html_tag( 'div', array( 'class' => 'counter' ), '&times;' . $cart_item['quantity'] ) ?>
								</div>
								<?php echo olympus_html_tag( 'div', array( 'class' => 'product-price' ), $product_price ); ?>
								<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), esc_html__( 'Remove this item', 'olympus' ), esc_attr( $product_id ), esc_attr( $cart_item_key ), esc_attr( $_product->get_sku() )
								), $cart_item_key );
								?>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="cart-subtotal"><?php esc_html_e( 'Cart Subtotal', 'olympus' ); ?>
				:<?php echo WC()->cart->get_cart_subtotal(); ?></div>

			<div class="cart-btn-wrap">
				<a href="<?php echo wc_get_cart_url(); ?>"
				   class="btn btn-primary btn-sm"><?php esc_html_e( 'Go to Your Cart', 'olympus' ); ?></a>
				<a href="<?php echo wc_get_checkout_url(); ?>"
				   class="btn btn-purple btn-sm"><?php esc_html_e( 'Go to Checkout', 'olympus' ); ?></a>
			</div>

		</div>
	<?php } ?>
</li>