<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$rating	 = $product->get_average_rating();
?>
<div id="reviews" class="woocommerce-Reviews">

    <div class="comments-title-wrap" x-data="olympusModal()">
        <div class="block-title">
            <h2 class="title">
	            <?php
	            $count = $product->get_review_count();
	            if ( $count && wc_review_ratings_enabled() ) {
		            /* translators: 1: reviews count 2: product name */
		            $reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'olympus' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
		            echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
	            } else {
		            esc_html_e( 'Reviews', 'olympus' );
	            }
	            ?>
            </h2>
			<?php
			if ( $rating && 'yes' === get_option( 'woocommerce_enable_review_rating' ) ) {
				olympus_shop_rating_html( $rating, true );
			}
			?>
        </div>
		<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
			<span class="btn btn-primary btn-sm"
			   @click="modalOpen"
			><?php esc_html_e( 'Write a Review', 'olympus' ) ?></span>
		   <?php else : ?>
			<span class="btn disabled"><?php esc_html_e( 'Reviews only for buyers', 'olympus' ) ?></span>
		<?php endif; ?>

		<!-- Popup Write Rewiev -->

		<div class="crumina-module crumina-window-popup" id="popup-write-rewiev" tabindex="-1" role="dialog" aria-hidden="true"
			 x-show="isModalOpen()"
			 x-cloak
			 x-transition:enter="transition ease-out duration-300"
			 x-transition:enter-start="opacity-0 transform -translate-y-40"
			 x-transition:enter-end="opacity-100 transform translate-y-0"
			 x-transition:leave="transition ease-in duration-300"
			 x-transition:leave-start="opacity-100 transform translate-y-0"
			 x-transition:leave-end="opacity-0 transform -translate-y-40"
		>
			<div class="modal-dialog ui-block window-popup popup-write-rewiev" @click.away="modalClose" >
				<span class="close icon-close" @click="modalClose" >
				    <?php echo olympus_icon_font( 'olympus-icon-Close-Icon' ); ?>
				</span>
			    <?php
			    $commenter			 = wp_get_current_commenter();
			    $author_field_class	 = empty( $commenter[ 'comment_author' ] ) ? 'is-empty' : '';
			    $email_field_class	 = empty( $commenter[ 'comment_author_email' ] ) ? 'is-empty' : '';

			    $fields = array(
				    'author' => '<div class="row"><div class="col-lg-6 col-md-6">
			<div class="form-group label-floating ' . esc_attr( $author_field_class ) . '">
            <label class="control-label">' . esc_html__( 'Your Name', 'olympus' ) . '</label>
            <input class="form-control" name="author" value="' . esc_attr( $commenter[ 'comment_author' ] ) . '" type="text" required>
            </div></div>',
				    'email'	 => '<div class="col-lg-6 col-md-6">
            <div class="form-group label-floating ' . esc_attr( $email_field_class ) . '">
            <label class="control-label">' . esc_html__( 'Your Email', 'olympus' ) . '</label>
            <input class="form-control" name="email" value="' . esc_attr( $commenter[ 'comment_author_email' ] ) . '" type="email" required>
            </div></div></div>',
			    );

			    $comment_form = array(
				    'title_reply'			 => esc_html__( 'Leave a Reply', 'olympus' ),
				    'title_reply_to'		 => esc_html__( 'Leave a Reply to %s', 'olympus' ),
				    'class_submit'			 => 'd-none',
				    'class_form'			 => 'form-write-rewiev',
				    'name_submit'			 => 'submit',
				    'title_reply_before'	 => '<div class="ui-block-title"><h6 class="title">',
				    'title_reply_after'		 => '</h6></div>',
				    'comment_notes_after'	 => '<button class="btn btn-primary btn-lg full-width">' . esc_html__( 'Post your Comment', 'olympus' ) . '</button>',
				    'fields'				 => apply_filters( 'comment_form_default_fields', $fields ),
				    'label_submit'			 => esc_html__( 'Post your Review', 'olympus' ),
				    'logged_in_as'			 => '',
				    'comment_field'			 => '',
			    );

			    if ( function_exists( 'yz_is_logy_active' ) && yz_is_logy_active() ) {
				    $account_page_url = logy_page_url( 'login' );
			    } else {
				    $account_page_url = wc_get_page_permalink( 'myaccount' );
			    }
			    if ( ! empty($account_page_url) ) {
				    $comment_form[ 'must_log_in' ]	 = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'olympus' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
			    }
			    $comment_form[ 'comment_field' ] = '<div class="form-group label-floating">
                        <label class="control-label">' . esc_html__( 'Review Title', 'olympus' ) . '</label>
                        <input class="form-control"  type="text" value="" name="crum_comment_title">
                </div>';

			    if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
				    $comment_form[ 'comment_field' ] .= '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'olympus' ) . '</label><select name="rating" id="rating" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'olympus' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'olympus' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'olympus' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'olympus' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'olympus' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'olympus' ) . '</option>
						</select></div>';
			    }

			    $comment_form[ 'comment_field' ] .= '<div class="form-group label-floating">
                        <label class="control-label">' . esc_html__( 'Write a little description about the review', 'olympus' ) . ' </label>
                        <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" required></textarea>
                    </div>';

			    comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
			    ?>
			</div>

		</div>
		<!-- ... end Popup Write Rewiev -->
    </div>


	<?php if ( have_comments() ) : ?>

		<ol class="commentlist">
			<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
		</ol>

		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			echo '<nav class="woocommerce-pagination">';
			paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
				'prev_text'	 => '&larr;',
				'next_text'	 => '&rarr;',
				'type'		 => 'list',
			) ) );
			echo '</nav>';
		endif;
		?>

	<?php else : ?>

		<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'olympus' ); ?></p>

	<?php endif; ?>


    <div class="clear"></div>
</div>


