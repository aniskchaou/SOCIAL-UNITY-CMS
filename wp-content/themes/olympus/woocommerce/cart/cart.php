<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form cart-main" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table cart shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
        <tr>
            <th class="product-thumbnail"><?php esc_html_e( 'Item Description', 'olympus' ); ?></th>
            <th class="product-price"><?php esc_html_e( 'Unit Price', 'olympus' ); ?></th>
            <th class="product-quantity"><?php esc_html_e( 'Quantity', 'olympus' ); ?></th>
            <th class="product-subtotal"><?php esc_html_e( 'Total', 'olympus' ); ?></th>
            <th class="product-remove"><?php esc_html_e( 'Remove', 'olympus' ); ?></th>
        </tr>
        </thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                        <td class="product-thumbnail" data-title="<?php esc_attr_e( 'Item Description', 'olympus' ); ?>">

                            <div class="cart-product__item">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									olympus_render( $thumbnail ); // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
								?>

                                <div class="cart-product-content">
									<?php

									// Meta data
									echo wc_get_formatted_cart_item_data( $cart_item );

									if ( ! $product_permalink ) {
										echo olympus_html_tag('h6',array('class'=>'cartproduct-title'),apply_filters( 'woocommerce_cart_item_name', esc_html( $_product->get_name() ), $cart_item, $cart_item_key ));
									} else {
										echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s"  class="h6 cart-product-title">%s</a>', esc_url( $product_permalink ), esc_html( $_product->get_name() ) ), $cart_item, $cart_item_key );
									}

									olympus_shop_rating_html( $_product->get_average_rating() );
									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item );

									// Backorder notification
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'olympus' ) . '</p>';
									}
									?>
                                </div>
                            </div>
                        </td>

                        <td class="product-price" data-title="<?php esc_attr_e( 'Unit Price', 'olympus' ); ?>">
                            <h6 class="price amount">
								<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );  ?>
                            </h6>
                        </td>

                        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'olympus' ); ?>">

                            <div class="form-group label-floating quantity">
								<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
	                            ?>
                            </div>

                        </td>

                        <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'olympus' ); ?>">
                            <h6 class="total amount">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                            </h6>
                        </td>

                        <td class="product-remove" data-title="<?php esc_attr_e( 'Remove', 'olympus' ); ?>">
							<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="product-del" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'olympus' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() ),
								olympus_icon_font('olympus-icon-Close-Icon')
							), $cart_item_key );
							?>
                        </td>
                    </tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

            <tr>
                <td colspan="4" class="actions">

	                <?php if ( wc_coupons_enabled() ) { ?>
                    <div class="form-inline coupon">
                        <div class="form-group label-floating is-empty">
                            <label class="control-label" for="coupon_code"><?php esc_html_e( 'Enter Coupon:', 'olympus' ); ?></label>
                            <input class="form-control bg-white"  value="" type="text" name="coupon_code"  id="coupon_code" >
                        </div>
                        <button type="submit" name="apply_coupon" class="btn btn-primary btn-lg"><?php esc_html_e( 'Apply', 'olympus' ); ?></button>
	                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                    </div>
	                <?php } ?>
                    <div class="cart-subtotal">
	                    <?php esc_html_e( 'Cart Subtotal:', 'olympus' ); ?><span><?php wc_cart_totals_subtotal_html(); ?></span>
                    </div>
					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</td>
                <td>
                    <input type="submit" class="btn btn-primary" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'olympus' ); ?>" />
                </td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals medium-padding100">
	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>