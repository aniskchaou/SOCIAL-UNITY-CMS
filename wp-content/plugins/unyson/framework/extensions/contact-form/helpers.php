<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
	return;
}

class FW_Ext_Crumina_Contact_Form extends WPBakeryShortCode {

	public $slug = 'crumina_contact_form';

	function __construct() {
		add_action( 'init', array( $this, 'contact_form_mapping' ) );
		add_shortcode( $this->slug, array( $this, 'contact_form_html' ) );
	}

	public function contact_form_mapping() {
		if ( !defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'base'		 => $this->slug,
			'name'		 => __( 'Crumina Contact Form', 'crumina' ),
			'category'	 => __( 'Crumina', 'crumina' ),
//            'icon'     => get_template_directory_uri() . '/assets/img/vc-icon.png',
			'params'	 => $this->get_params()
		) );
	}

	public static function contact_form_html( $atts = array(), $current_form_id = 0 ) {
		$form			 = $class			 = $form_html		 = $submit_text	 = $submit_color	 = '';

		// Params extraction
		extract( $atts );

		$form_id		 = $current_form_id ? $current_form_id : (int) $form;
		$form_options	 = get_post_meta( $form_id, 'fw_options', true );

		if ( !empty( $form_id ) && !empty( $form_options ) && isset( $form_options[ 'form' ] ) ) {
			$styles		 = '';
			$submit_text = $submit_text ? $submit_text : __( 'Submit', 'crumina' );

			if ( $submit_color ) {
				$styles	 .= "background-color: {$submit_color} !important;";
				$styles	 .= "border-color: {$submit_color} !important;";
			}

			$submit_html = '<button type="submit" class="btn btn-primary" style="' . $styles . '">' . $submit_text . '</button>';

			ob_start();
			FW_Flash_Messages::_print_frontend();
			$messages = ob_get_clean();

			$form_html = '<div class="crumina-ext-contact-form standart-form-flex ' . $class . '">'
					. $messages
					. fw()->extensions->get( 'forms' )->render_form( $form_id, $form_options[ 'form' ], 'contact-forms', $submit_html ) 
					. '</div>';
		}

		return $form_html;
	}

	public function get_params() {
		$params = array(
			array(
				'type'			 => 'textfield',
				'heading'		 => __( 'Extra class name', 'crumina' ),
				'param_name'	 => 'class',
				'description'	 => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'crumina' ),
			)
		);

		$forms = $this->get_forms();

		if ( empty( $forms ) ) {
			array_unshift( $params, array(
				"type"		 => "textarea_html",
				"holder"	 => "div",
				"class"		 => "",
				"heading"	 => __( "No forms", "crumina" ),
				"param_name" => "no_forms",
				"value"		 => fw_render_view( fw()->extensions->get( 'contact-form' )->locate_path( '/views/text-no-forms.php' ) )
			) );
		} else {
			array_unshift( $forms, array(
				'' => '---'
			) );

			$params = array_merge( array(
				array(
					'type'		 => 'dropdown',
					'param_name' => 'form',
					'heading'	 => esc_html__( 'Select form', 'crumina' ),
					'value'		 => $forms,
				),
				array(
					'type'		 => 'textfield',
					'heading'	 => __( 'Submit text', 'crumina' ),
					'param_name' => 'submit_text',
				),
				array(
					'type'		 => 'colorpicker',
					'heading'	 => __( 'Submit color', 'crumina' ),
					'param_name' => 'submit_color',
				),
					), $params );
		}

		return $params;
	}

	public function get_forms() {
		$forms = get_posts( array(
			'post_type'		 => 'crum-form',
			'numberposts'	 => - 1
				) );

		if ( empty( $forms ) ) {
			return array();
		}

		$choices = array();
		foreach ( $forms as $form ) {
			$label				 = empty( $form->post_title ) ? esc_html__( '(no title)', 'utouch' ) : $form->post_title;
			$choices[ $label ]	 = $form->ID;
		}

		return $choices;
	}

}
