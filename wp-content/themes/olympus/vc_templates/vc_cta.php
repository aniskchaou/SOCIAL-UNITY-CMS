<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class
 * @var $this    WPBakeryShortCode_VC_Cta
 */
$modal_content = 'text';
$btn_link      = $btn_el_id = $btn_el_class = $btn_custom_onclick_code = '';

extract( $atts );
$link = vc_build_link( $btn_link );

$init_atts = $atts;
$atts      = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->buildTemplate( $atts, '' );
$containerClass = trim( 'vc_cta3-container ' . esc_attr( implode( ' ', $this->getTemplateVariable( 'container-class' ) ) ) );
$cssClass       = trim( 'vc_general ' . esc_attr( implode( ' ', $this->getTemplateVariable( 'css-class' ) ) ) );
$show_actions   = true;
if ( empty( $btn_custom_onclick_code ) && $modal_content === 'reg_form' && is_user_logged_in() ) {
	$show_actions = false;
}
if ($show_actions && function_exists( 'crumina_get_reg_form_html' )){
	add_action('wp_footer','olympus_append_login_form_to_html');
}
$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}

?>
<section class="<?php echo esc_attr( $containerClass ); ?>" <?php echo implode( ' ', $wrapper_attributes ); ?> >
	<?php

	?>
	<div class="<?php echo esc_attr( $cssClass ); ?>"<?php
	if ( $this->getTemplateVariable( 'inline-css' ) ) {
		olympus_render( ' style="' . esc_attr( implode( ' ', $this->getTemplateVariable( 'inline-css' ) ) ) . '"' );
	}
	?>>
		<?php olympus_render( $this->getTemplateVariable( 'icons-top' ) ); ?>
		<?php olympus_render( $this->getTemplateVariable( 'icons-left' ) ); ?>
		<div class="vc_cta3_content-container">
			<?php
			if ( $show_actions ) {
				olympus_render('<div @click="modalOpen">');
				olympus_render( $this->getTemplateVariable( 'actions-top' ) );
				olympus_render( $this->getTemplateVariable( 'actions-left' ) );
				olympus_render('</div>');
			}
			?>
			<div class="vc_cta3-content">
				<header class="vc_cta3-content-header">
					<?php olympus_render( $this->getTemplateVariable( 'heading1' ) ); ?>
					<?php olympus_render( $this->getTemplateVariable( 'heading2' ) ); ?>
				</header>
				<?php olympus_render( isset( $init_atts['description'] ) ? wpb_js_remove_wpautop( $init_atts['description'], true ) : '' ); ?>
			</div>
			<?php
			if ( $show_actions ) {
				olympus_render('<div @click="modalOpen">');
				olympus_render( $this->getTemplateVariable( 'actions-bottom' ) );
				olympus_render( $this->getTemplateVariable( 'actions-right' ) );
				olympus_render('</div>');
			}
			?>
		</div>
		<?php olympus_render( $this->getTemplateVariable( 'icons-bottom' ) ); ?>
		<?php olympus_render( $this->getTemplateVariable( 'icons-right' ) ); ?>
	</div>
</section>

<!--$content-->