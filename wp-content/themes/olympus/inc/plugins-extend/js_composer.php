<?php

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', '_action_olympus_vcSetAsTheme' );

function _action_olympus_vcSetAsTheme() {
	if ( function_exists( 'vc_set_as_theme' ) ) {
		vc_set_as_theme();
	}
}

add_action( 'vc_after_init', '_action_olympus_vc_after_init' );

function _action_olympus_vc_after_init() {

	//<editor-fold defaultstate="collapsed" desc="Update button params">
	$sizes				 = WPBMap::getParam( 'vc_btn', 'size' );
	$sizes[ 'value' ]	 = array(
		esc_html__( 'Normal', 'olympus' )	 => 'btn-md',
		esc_html__( 'Large', 'olympus' )	 => 'btn-lg',
		esc_html__( 'Medium', 'olympus' )	 => 'btn-md-2',
		esc_html__( 'Small', 'olympus' )	 => 'btn-sm',
	);
	vc_update_shortcode_param( 'vc_btn', $sizes );

	$btn_colors = WPBMap::getParam( 'vc_btn', 'color' );
	if ( $btn_colors ) {
		olympus_update_vc_colors( $btn_colors );
		vc_update_shortcode_param( 'vc_btn', $btn_colors );
	}

	$btn_colors_g1 = WPBMap::getParam( 'vc_btn', 'gradient_color_1' );
	if ( $btn_colors_g1 ) {
		olympus_update_vc_colors( $btn_colors_g1 );
		vc_update_shortcode_param( 'vc_btn', $btn_colors_g1 );
	}

	$btn_colors_g2 = WPBMap::getParam( 'vc_btn', 'gradient_color_2' );
	if ( $btn_colors_g2 ) {
		olympus_update_vc_colors( $btn_colors_g2 );
		vc_update_shortcode_param( 'vc_btn', $btn_colors_g2 );
	}

	$tta_tour_colors = WPBMap::getParam( 'vc_tta_tour', 'color' );
	if ( $tta_tour_colors ) {
		olympus_update_vc_colors( $tta_tour_colors );
		vc_update_shortcode_param( 'vc_tta_tour', $tta_tour_colors );
	}

	$tta_tour_pagination_colors = WPBMap::getParam( 'vc_tta_tour', 'pagination_color' );
	if ( $tta_tour_pagination_colors ) {
		olympus_update_vc_colors( $tta_tour_pagination_colors );
		vc_update_shortcode_param( 'vc_tta_tour', $tta_tour_pagination_colors );
	}

	$tta_tabs_colors = WPBMap::getParam( 'vc_tta_tabs', 'color' );
	if ( $tta_tabs_colors ) {
		olympus_update_vc_colors( $tta_tabs_colors );
		vc_update_shortcode_param( 'vc_tta_tabs', $tta_tabs_colors );
	}

	$tta_tabs_pagination_colors = WPBMap::getParam( 'vc_tta_tabs', 'pagination_color' );
	if ( $tta_tabs_pagination_colors ) {
		olympus_update_vc_colors( $tta_tabs_pagination_colors );
		vc_update_shortcode_param( 'vc_tta_tabs', $tta_tabs_pagination_colors );
	}

	$tta_pageable_pagination_colors = WPBMap::getParam( 'vc_tta_pageable', 'pagination_color' );
	if ( $tta_pageable_pagination_colors ) {
		olympus_update_vc_colors( $tta_pageable_pagination_colors );
		vc_update_shortcode_param( 'vc_tta_pageable', $tta_pageable_pagination_colors );
	}

	$tta_accordion_colors = WPBMap::getParam( 'vc_tta_accordion', 'color' );
	if ( $tta_accordion_colors ) {
		olympus_update_vc_colors( $tta_accordion_colors );
		vc_update_shortcode_param( 'vc_tta_accordion', $tta_accordion_colors );
	}

	$round_chart_stroke_color = WPBMap::getParam( 'vc_round_chart', 'stroke_color' );
	if ( $round_chart_stroke_color ) {
		olympus_update_vc_colors( $round_chart_stroke_color );
		vc_update_shortcode_param( 'vc_round_chart', $round_chart_stroke_color );
	}

	$round_chart_color = WPBMap::getParam( 'vc_round_chart', 'values' );
	if ( $round_chart_color ) {
		olympus_update_vc_colors_group( $round_chart_color, 'color' );
		vc_update_shortcode_param( 'vc_round_chart', $round_chart_color );
	}

	$progress_bar_color = WPBMap::getParam( 'vc_progress_bar', 'values' );
	if ( $progress_bar_color ) {
		olympus_update_vc_colors_group( $progress_bar_color, 'color' );
		vc_update_shortcode_param( 'vc_progress_bar', $progress_bar_color );
	}

	$progress_bar_bgcolor = WPBMap::getParam( 'vc_progress_bar', 'bgcolor' );
	if ( $progress_bar_bgcolor ) {
		olympus_update_vc_colors( $progress_bar_bgcolor );
		vc_update_shortcode_param( 'vc_progress_bar', $progress_bar_bgcolor );
	}

	$pie_color = WPBMap::getParam( 'vc_pie', 'color' );
	if ( $pie_color ) {
		olympus_update_vc_colors( $pie_color );
		vc_update_shortcode_param( 'vc_pie', $pie_color );
	}

	$line_chart_color = WPBMap::getParam( 'vc_line_chart', 'values' );
	if ( $line_chart_color ) {
		olympus_update_vc_colors_group( $line_chart_color, 'color' );
		vc_update_shortcode_param( 'vc_line_chart', $line_chart_color );
	}

	$hoverbox_background_color = WPBMap::getParam( 'vc_hoverbox', 'hover_background_color' );
	if ( $hoverbox_background_color ) {
		olympus_update_vc_colors( $hoverbox_background_color );
		vc_update_shortcode_param( 'vc_hoverbox', $hoverbox_background_color );
	}

	$cta_color = WPBMap::getParam( 'vc_cta', 'color' );
	if ( $cta_color ) {
		olympus_update_vc_colors( $cta_color );
		vc_update_shortcode_param( 'vc_cta', $cta_color );
	}
	// </editor-fold>
	//<editor-fold defaultstate="collapsed" desc="Update row params">
	WPBMap::addParam( 'vc_row', array(
		'type'		 => 'attach_image',
		'heading'	 => esc_html__( 'Animate background', 'olympus' ),
		'param_name' => 'animate_bg',
		'group'		 => esc_html__( 'Background Options', 'olympus' ),
	) );
	WPBMap::addParam( 'vc_row', array(
		"type"			 => "colorpicker",
		"class"			 => "",
		"heading"		 => esc_html__( "Font Color", 'olympus' ),
		"param_name"	 => "font_color",
		"description"	 => esc_html__( "Give it a nice paint!", 'olympus' ),
		'weight'		 => 5
	) );
	WPBMap::addParam( 'vc_row', array(
		'type'		 => 'dropdown',
		'heading'	 => esc_html__( 'Animate direction', 'olympus' ),
		'param_name' => 'animate_direction',
		'group'		 => esc_html__( 'Background Options', 'olympus' ),
		'value'		 => array(
			esc_html__( 'Left to right', 'olympus' )			 => 'animate-left-to-right',
			esc_html__( 'Right to left', 'olympus' )			 => 'animate-right-to-left',
			esc_html__( 'Diagonally left to right', 'olympus' )	 => 'animate-diagonally-left-to-right',
			esc_html__( 'Diagonally right to left', 'olympus' )	 => 'animate-diagonally-right-to-left',
		),
		'dependency' => array(
			'element'	 => "animate_bg",
			'not_empty'	 => true
		)
	) );
	WPBMap::addParam( 'vc_row', array(
		'type'		 => 'colorpicker',
		'heading'	 => esc_html__( 'Background overlay', 'olympus' ),
		'param_name' => 'animate_overlay',
		'group'		 => esc_html__( 'Background Options', 'olympus' ),
		'dependency' => array(
			'element'	 => "animate_bg",
			'not_empty'	 => true
		)
	) );
	WPBMap::addParam( 'vc_row', array(
		'type'			 => 'colorpicker',
		'heading'		 => esc_html__( 'Half background color', 'olympus' ),
		'param_name'	 => 'half_bg',
		'group'			 => esc_html__( 'Background Options', 'olympus' ),
		'description'	 => esc_html__( 'Not working if Animate background use.', 'olympus' ),
	) );
	WPBMap::addParam( 'vc_row', array(
		'type'		 => 'dropdown',
		'heading'	 => esc_html__( 'Half background position', 'olympus' ),
		'param_name' => 'half_bg_pos',
		'group'		 => esc_html__( 'Background Options', 'olympus' ),
		'value'		 => array(
			esc_html__( 'Top', 'olympus' )		 => 'half-bg-pos-top',
			esc_html__( 'Bottom', 'olympus' )	 => 'half-bg-pos-bottom',
		),
		'dependency' => array(
			'element'	 => "half_bg",
			'not_empty'	 => true
		)
	) );
	// </editor-fold>
	//<editor-fold defaultstate="collapsed" desc="Update CTA parameters">

	$h2				 = WPBMap::getParam( 'vc_cta', 'h2' );
	$h2[ 'group' ]	 = esc_html__( 'Content', 'olympus' );
	WPBMap::mutateParam( 'vc_cta', $h2 );

	$h4				 = WPBMap::getParam( 'vc_cta', 'h4' );
	$h4[ 'group' ]	 = esc_html__( 'Content', 'olympus' );
	WPBMap::mutateParam( 'vc_cta', $h4 );

	$use_custom_fonts_h2			 = WPBMap::getParam( 'vc_cta', 'use_custom_fonts_h2' );
	$use_custom_fonts_h2[ 'group' ]	 = esc_html__( 'Content', 'olympus' );
	WPBMap::mutateParam( 'vc_cta', $use_custom_fonts_h2 );

	$use_custom_fonts_h4			 = WPBMap::getParam( 'vc_cta', 'use_custom_fonts_h4' );
	$use_custom_fonts_h4[ 'group' ]	 = esc_html__( 'Content', 'olympus' );
	WPBMap::mutateParam( 'vc_cta', $use_custom_fonts_h4 );

	$cta_style														 = WPBMap::getParam( 'vc_cta', 'style' );
	$cta_style[ 'value' ][ esc_html__( 'Transparent', 'olympus' ) ]	 = 'transparent';
	WPBMap::mutateParam( 'vc_cta', $cta_style );

	$btn_title				 = WPBMap::getParam( 'vc_cta', 'btn_title' );
	$btn_title[ 'value' ]	 = esc_html__( 'Click here', 'olympus' );
	WPBMap::mutateParam( 'vc_cta', $btn_title );

	WPBMap::addParam( 'vc_cta', array(
		'type'		 => 'textarea',
		'holder'	 => 'div',
		'heading'	 => esc_html__( 'Description', 'olympus' ),
		'param_name' => 'description',
		'group'		 => esc_html__( 'Content', 'olympus' ),
	) );

	WPBMap::addParam( 'vc_cta', array(
		'type'		 => 'dropdown',
		'param_name' => 'modal_content',
		'group'		 => esc_html__( 'Content', 'olympus' ),
		'heading'	 => esc_html__( 'Modal content', 'olympus' ),
		'value'		 => array(
			esc_html__( 'Text', 'olympus' )			 => 'text',
			esc_html__( 'Register form', 'olympus' ) => 'reg_form',
		),
	) );

	$content = WPBMap::dropParam( 'vc_cta', 'content' );
	WPBMap::addParam( 'vc_cta', array(
		'heading'		 => esc_html__( 'Text', 'olympus' ),
		'param_name'	 => 'content',
		'type'			 => 'textarea_html',
		'value'			 => '',
		'description'	 => esc_html__( 'Not working if button has link.', 'olympus' ),
		'group'			 => esc_html__( 'Content', 'olympus' ),
		'dependency'	 => array(
			'element'	 => 'modal_content',
			'value'		 => array( 'text' ),
		),
	) );

	//</editor-fold>
}

//<editor-fold defaultstate="collapsed" desc="Functions">
function olympus_update_vc_colors( &$colors ) {
	foreach ( $colors[ 'value' ] as $key => $color ) {
		switch ( $color ) {
			case 'juicy-pink':
				unset( $colors[ 'value' ][ $key ] );
				$colors[ 'value' ][ esc_html__( 'Primary color', 'olympus' ) ]	 = 'juicy-pink';
				break;
			case 'sky':
				unset( $colors[ 'value' ][ $key ] );
				$colors[ 'value' ][ esc_html__( 'Theme blue', 'olympus' ) ]		 = 'sky';
				break;
			case 'violet':
				unset( $colors[ 'value' ][ $key ] );
				$colors[ 'value' ][ esc_html__( 'Theme purple', 'olympus' ) ]	 = 'violet';
				break;
		}
	}
}

function olympus_update_vc_colors_group( &$group, $field ) {

	foreach ( $group[ 'params' ] as $key => $param ) {
		if ( $param[ 'param_name' ] === $field ) {
			foreach ( $group[ 'params' ][ $key ][ 'value' ] as $k => $color ) {
				switch ( $color ) {
					case 'juicy-pink':
						unset( $group[ 'params' ][ $key ][ 'value' ][ $k ] );
						$group[ 'params' ][ $key ][ 'value' ][ esc_html__( 'Primary color', 'olympus' ) ]	 = 'juicy-pink';
						break;
					case 'sky':
						unset( $group[ 'params' ][ $key ][ 'value' ][ $k ] );
						$group[ 'params' ][ $key ][ 'value' ][ esc_html__( 'Theme blue', 'olympus' ) ]		 = 'sky';
						break;
					case 'violet':
						unset( $group[ 'params' ][ $key ][ 'value' ][ $k ] );
						$group[ 'params' ][ $key ][ 'value' ][ esc_html__( 'Theme purple', 'olympus' ) ]	 = 'violet';
						break;
				}
			}
		}
	}
}

//</editor-fold>