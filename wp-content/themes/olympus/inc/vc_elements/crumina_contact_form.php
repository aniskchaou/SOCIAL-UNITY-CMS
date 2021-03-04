<?php

if ( ! class_exists( 'WPBakeryShortCode' ) ) {
	return false;
}
	if ( ! function_exists( 'get_crumina_form_list' ) ) {
		function get_crumina_form_list() {
			$forms = get_posts(
				array(
					'post_type'   => 'crum-form',
					'numberposts' => - 1
				)
			);

			if ( empty( $forms ) ) {
				return array();
			}

			$choices = array();
			foreach ( $forms as $form ) {
				$label           = empty( $form->post_title ) ? esc_html__( '(no title)', 'olympus' ) : $form->post_title;
				$choices[$label] = $form->ID;
			}

			return $choices;
		}
	}

	if ( ! function_exists( 'get_crumina_form_params' ) ) {
		function get_crumina_form_params() {
			$params = array(
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Extra class name', 'olympus' ),
					'param_name'  => 'class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'olympus' ),
				)
			);
			$forms  = get_crumina_form_list();
			if ( empty( $forms ) ) {
				/* Text that will be displayed if no forms created*/
				ob_start(); ?>
				<h1 style="font-weight:100; text-align:center;"><?php echo esc_html__( 'No Forms Available', 'olympus' ); ?></h1>
				<p style="text-align:center">
					<em>
						<?php
						echo sprintf(
							__( 'No Forms created yet. Please go to the %sForms page and %s.', 'olympus' ), '<br />', '<a href="' . admin_url( 'post-new.php?post_type=fw-form' ) . '" target="_blank">' . esc_html__( 'create a new Form', 'olympus' ) . '</a>'
						);
						?>
					</em>
				</p>
				<?php
				$no_forms_html = ob_get_clean();
				array_unshift(
					$params, array(
						"type"       => "textarea_html",
						"holder"     => "div",
						"class"      => "",
						"heading"    => __( "No forms", "olympus" ),
						"param_name" => "no_forms",
						"value"      => $no_forms_html
					)
				);
			} else {
				array_unshift(
					$forms, array(
						'' => '---'
					)
				);

				$params = array_merge(
					array(
						array(
							'type'       => 'dropdown',
							'param_name' => 'form',
							'heading'    => esc_html__( 'Select form', 'olympus' ),
							'value'      => $forms,
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Submit text', 'olympus' ),
							'param_name' => 'submit_text',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Submit color', 'olympus' ),
							'param_name' => 'submit_color',
						),
					), $params
				);
			}

			return $params;
		}
	}

	add_action(
		'vc_before_init', function () {
		vc_map(
			array(
				'base'     => 'crumina_contact_form',
				'name'     => __( 'Crumina Contact Form', 'olympus' ),
				'category' => __( 'Olympus', 'olympus' ),
				'params'   => get_crumina_form_params()
			)
		);
	}
	);

class WPBakeryShortCode_Crumina_Contact_Form extends WPBakeryShortCode {

}
