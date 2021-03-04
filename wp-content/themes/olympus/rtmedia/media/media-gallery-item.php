<?php
/** That's all, stop editing from here * */
global $rtmedia_backbone;

$rtmedia_backbone				 = array(
	'backbone'			 => false,
	'is_album'			 => false,
	'is_edit_allowed'	 => false,
);
//todo: nonce verification
$rtmedia_backbone[ 'backbone' ]	 = filter_input( INPUT_POST, 'backbone', FILTER_VALIDATE_BOOLEAN );

$is_album = filter_input( INPUT_POST, 'is_album', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
if ( isset( $is_album[ 0 ] ) ) {
	$rtmedia_backbone[ 'is_album' ] = $is_album[ 0 ];
}

$is_edit_allowed = filter_input( INPUT_POST, 'is_edit_allowed', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
if ( isset( $is_edit_allowed[ 0 ] ) ) {
	$rtmedia_backbone[ 'is_edit_allowed' ] = $is_edit_allowed[ 0 ];
}

$allowed_html = array(
	'span' => array(
		'class' => array(),
	),
);
?>
<?php // rtmedia_id is return backbone object if we use esc_attr then it will create > & < into &lt; &gt; it will not interpret backbone object into media id             ?>
<li class="photo-album-item-wrap rtmedia-list-item col-3-width" id="<?php echo rtmedia_id(); // @codingStandardsIgnoreLine             ?>">
    <div class="photo-album-item">
		<?php do_action( 'rtmedia_before_item' ); ?>

        <div class="rtmedia-album-media-count">
			<?php echo wp_kses( rtmedia_duration(), $allowed_html ); ?>
        </div>

        <a href="<?php rtmedia_permalink(); ?>" title="<?php echo esc_attr( rtmedia_title() ); ?>"
           class="<?php echo esc_attr( apply_filters( 'rtmedia_gallery_list_item_a_class', 'rtmedia-list-item-a' ) ); ?>">

			<?php
			global $rtmedia_query;

			$alt_text		 = rtmedia_image_alt( false, false );
			$rtmedia_media	 = '';
			if ( !empty( $rtmedia_query ) && isset( $rtmedia_query->rtmedia ) ) {
				$rtmedia_media = $rtmedia_query->rtmedia;
			}

			$thumb_media_type		 = '';
			$thumb_media_wrap_class	 = '';
			if ( (isset( $rtmedia_media->media_type ) && isset( $rtmedia_media->id )) ) {
				if ( $rtmedia_media->media_type === 'video' ) {
					$thumb_media_wrap_class = 'rtmedia-item-thum-has-custom-ico';
					$thumb_media_type = 'olympus-icon-Play-Icon-Big';
				}

				if ( $rtmedia_media->media_type === 'music' ) {
					$thumb_media_wrap_class = 'rtmedia-item-thum-has-custom-ico';
					$thumb_media_type = 'olympus-icon-Sound-Icon';
				}
			}
			?>

            <div style="background-image: url(<?php rtmedia_image( 'rt_media_activity_image' ); ?>);" class="rtmedia-item-thumbnail photo-item <?php echo esc_attr($thumb_media_wrap_class); ?>">
				<?php if ( $thumb_media_type ) { ?>
					<i class="rtmedia-item-thumb-type-icon <?php echo esc_attr( $thumb_media_type ); ?>"></i>
				<?php } ?>
				<div class="overlay overlay-dark"></div>
			</div>

			<?php
			/**
			 * Filter to hide or show media titles in gallery.
			 *
			 * @param bool true Default value is true.
			 */
			if ( apply_filters( 'rtmedia_media_gallery_show_media_title', true ) ) {
				?>

				<div class="content">
					<h5 class="title"><?php echo esc_html( rtmedia_title() ); ?></h5>
					<?php olympus_rtmedia_media_description(); ?>
				</div>

			<?php } ?>

		</a>

		<?php do_action( 'rtmedia_after_item' ); ?>
    </div>
</li>
