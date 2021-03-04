<?php
$product = wc_get_product( get_the_ID() );
$query   = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
?>

<div class="row">
    <div class="col-sm-2">
		<?php olympus_render( $product->get_image( array( 150, 150 ) ) ); ?>
    </div>
    <div class="col-sm-10">
        <div class="main-content-wrap">
            <div class="block-title">
				<?php echo wc_get_product_category_list( get_the_ID(), ', ', '<span class="product-category">', '</span>' ); ?>
                <h2>
                    <a href="<?php the_permalink(); ?>"><?php echo olympus_highlight_searched( $query, $product->get_title() ); ?></a>
                </h2>
				<?php olympus_shop_rating_html( intval( $product->get_average_rating() ) ); ?>
            </div>

            <div class="block-price">
                <div class="product-price"><?php olympus_render( $product->get_price_html() ); ?></div>
            </div>

        </div>

		<?php echo olympus_highlight_searched( $query, $product->get_description() ); ?>
    </div>
</div>


