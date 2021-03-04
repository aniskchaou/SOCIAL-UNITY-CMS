<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * @var $css_animation
 * @var $animate_bg
 * @var $animate_direction
 * @var $animate_overlay
 * @var $half_bg
 * @var $font_color
 * @var $half_bg_pos
 * Shortcode class
 * @var $this    WPBakeryShortCode_VC_Row
 */
$el_class				 = $font_color				 = $full_height			 = $animate_bg				 = $animate_direction		 = $animate_overlay		 = $half_bg				 = $half_bg_pos			 = $parallax_speed_bg		 = $parallax_speed_video	 = $full_width				 = $equal_height			 = $flex_row				 = $columns_placement		 = $content_placement		 = $parallax				 = $parallax_image			 = $css					 = $el_id					 = $video_bg				 = $video_bg_url			 = $video_bg_parallax		 = $css_animation			 = '';
$disable_element		 = '';
$output					 = $before_output			 = $after_output			 = $before_inner_output	 = $after_inner_output		 = '';
$atts					 = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	$el_class,
	'row',
	'section-theme-padding',
	vc_shortcode_custom_css_class( $css ),
);

$row_cols_wrap_classes = array(
	'row-cols-wrap',
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_row-fluid',
);

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
			'border',
			'background',
		) ) || $video_bg || $parallax
 ) {
	$row_cols_wrap_classes[] = 'vc_row-has-fill';
}

if ( !empty( $atts[ 'gap' ] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts[ 'gap' ];
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( !empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

switch ( $full_width ) {
	case 'stretch_row':
		$before_inner_output = '<div class="container">';
		$after_inner_output	 = '</div>';
		$css_classes[]		 = 'inner-relative-wrapper';
		break;
	case 'stretch_row_content':
		$css_classes[]		 = 'inner-relative-wrapper';
		break;
	case 'stretch_row_content_no_spaces':
		$before_output		 = '<div class="inner-relative-wrapper">';
		$after_output		 = '</div>';
		$css_classes[]		 = 'vc_row-no-padding';
		$css_classes[]		 = 'inner-relative-wrapper';
		break;
	default:
		$before_output		 = '<div class="container inner-relative-wrapper">';

		$after_output = '</div>';
		break;
}

if ( !empty( $full_height ) ) {
	$row_cols_wrap_classes[] = 'vc_row-o-full-height';
	if ( !empty( $columns_placement ) ) {
		$flex_row				 = true;
		$row_cols_wrap_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$row_cols_wrap_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( !empty( $equal_height ) ) {
	$flex_row				 = true;
	$row_cols_wrap_classes[] = 'vc_row-o-equal-height';
}

if ( !empty( $content_placement ) ) {
	$flex_row				 = true;
	$row_cols_wrap_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( !empty( $flex_row ) ) {
	$row_cols_wrap_classes[] = 'vc_row-flex';
}

$has_video_bg = (!empty( $video_bg ) && !empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax		 = $video_bg_parallax;
	$parallax_speed	 = $parallax_speed_video;
	$parallax_image	 = $video_bg_url;
	$css_classes[]	 = 'vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( !empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[]	 = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[]			 = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[]			 = 'js-vc_parallax-o-fade';
		$wrapper_attributes[]	 = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( !empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id	 = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src	 = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( !empty( $parallax_image_src[ 0 ] ) ) {
			$parallax_image_src = $parallax_image_src[ 0 ];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( !$parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

if($font_color){
	$css_classes[] = 'color-inherit';

	$wrapper_attributes[] = 'style="color: ' . esc_attr( $font_color ) . '; fill: ' . esc_attr( $font_color ) . '"';
}

$css_class				 = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings[ 'base' ], $atts ) );

$wrapper_attributes[]	 = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= $before_output;
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= $before_inner_output;
$output .= '<div class="' . esc_attr( implode( ' ', $row_cols_wrap_classes ) ) . '">';
ob_start();
?>

<?php
if ( $animate_bg ) {
	$animate_bg			 = wp_get_attachment_image_src( $animate_bg, 'full' );
	$animate_bg_classes	 = array( 'content-bg-wrap', 'content-bg-wrap-row' );
	$overlay_bg_classes	 = array( 'content-bg-overlay' );
	$animate_bg_attrs	 = array();
	$overlay_bg_attrs	 = array();

	if ( $animate_direction ) {
		$animate_bg_classes[] = $animate_direction;
	}

	$animate_bg_attrs[ 'class' ] = esc_attr( implode( ' ', $animate_bg_classes ) );
	$overlay_bg_attrs[ 'class' ] = esc_attr( implode( ' ', $overlay_bg_classes ) );

	if ( isset( $animate_bg[ 0 ] ) ) {
		$animate_bg_attrs[ 'style' ] = "background-image: url({$animate_bg[ 0 ]});";
	}
	?>
	<div <?php echo olympus_attr_to_html( $animate_bg_attrs ); ?>></div>
	<?php if ( $animate_overlay ) { ?>
		<?php $overlay_bg_attrs[ 'style' ] = "background-color: {$animate_overlay};"; ?>
		<div <?php echo olympus_attr_to_html( $overlay_bg_attrs ); ?>></div>
	<?php } ?>
<?php } else if ( $half_bg ) { ?>
	<?php
	$half_bg_classes = array( 'content-half-bg-wrap' );
	$half_bg_attrs	 = array();

	if ( $half_bg_pos ) {
		$half_bg_classes[] = $half_bg_pos;
	}

	$half_bg_attrs[ 'class' ]	 = esc_attr( implode( ' ', $half_bg_classes ) );
	$half_bg_attrs[ 'style' ]	 = "background-color: {$half_bg};";
	?>
	<div <?php echo olympus_attr_to_html( $half_bg_attrs ); ?>></div>
<?php } ?>

<?php
$output	 .= ob_get_clean();
$output	 .= wpb_js_remove_wpautop( $content );
$output	 .= '</div>';
$output	 .= $after_inner_output;
$output	 .= '</div>';
$output	 .= $after_output;

olympus_render( $output );
