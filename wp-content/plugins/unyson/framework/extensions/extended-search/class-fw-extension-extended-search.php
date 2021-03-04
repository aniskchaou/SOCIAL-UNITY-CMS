<?php
if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Extension_Extended_Search extends FW_Extension {

	protected function _init() {
		add_shortcode( $this->get_config( 'shortcodeName' ), array( $this, 'shortcode' ) );
	}

	public function shortcode( $atts ) {

		$builder_type = isset( $atts[ 'builder_type' ] ) ? $atts[ 'builder_type' ] : '';

		if ( $builder_type !== 'kc' && function_exists( 'vc_map_get_attributes' ) ) {
			$atts = vc_map_get_attributes( $this->get_config( 'shortcodeName' ), $atts );
		}

		$atts = shortcode_atts( array(
				), $atts );

		wp_localize_script( 'extended-search', 'extendedSearchParams', array(
			'nonce'	 => wp_create_nonce( 'extended-search-nonce' ),
			'ext'	 => $this,
			'atts'	 => $atts,
		) );

		return $this->render_view( 'search', array(
					'ext'	 => $this,
					'atts'	 => $atts,
				) );
	}

	/**
	 * @param string $name View file name (without .php) from <extension>/views directory
	 * @param  array $view_variables Keys will be variables names within view
	 * @param   bool $return In some cases, for memory saving reasons, you can disable the use of output buffering
	 * @return string HTML
	 */
	final public function get_view( $name, $view_variables = array(), $return = true ) {
		$full_path = $this->locate_path( '/views/' . $name . '.php' );

		if ( !$full_path ) {
			trigger_error( 'Extension view not found: ' . $name, E_USER_WARNING );
			return;
		}

		return fw_render_view( $full_path, $view_variables, $return );
	}

	public static function kc_mapping() {
		$shortcodeName = fw_ext( 'extended-search' )->get_config( 'shortcodeName' );

		if ( function_exists( 'kc_add_map' ) ) {
			kc_add_map( array(
				$shortcodeName => array(
					'name'		 => esc_html__( 'Extended Search', 'crum-ext-extended-search' ),
					'category'	 => esc_html__( 'Crumina', 'crum-ext-extended-search' ),
					'icon'		 => 'kc-extended-search-icon',
					'params'	 => array(
						array(
							'type'	 => 'hidden',
							'name'	 => 'builder_type',
							'value'	 => 'kc'
						)
					)
				)
			) );
		}
	}

	public static function vc_mapping() {
		$ext			 = fw_ext( 'extended-search' );
		$shortcodeName	 = $ext->get_config( 'shortcodeName' );

		if ( function_exists( 'vc_map' ) ) {
			vc_map( array(
				'base'		 => $shortcodeName,
				'name'		 => esc_html__( 'Extended Search', 'crum-ext-extended-search' ),
				'category'	 => esc_html__( 'Crumina', 'crum-ext-extended-search' ),
				'icon'		 => $ext->locate_URI( '/static/img/builder-ico.svg' ),
				'params'	 => array(
					array(
						'type'		 => 'hidden',
						'param_name' => 'builder_type',
						'value'		 => 'vc'
					)
				)
			) );
		}
	}

	public static function getUserAvatar( $image_args = array() ) {
		if ( function_exists( 'bp_is_active' ) ) {
			return bp_get_loggedin_user_avatar( $image_args );
		} else {
			return get_avatar( get_current_user_id() );
		}
	}

}
