<?php
/**
 * File with theme functions.
 *
 * @package olympus-wp
 */
if ( ! function_exists( 'olympus_return_memory_size' ) ) {

	/**
	 * Print Formatted memory size
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	function olympus_return_memory_size( $size ) {
		$symbol = substr( $size, - 1 );
		$return = (int) $size;
		switch ( strtoupper( $symbol ) ) {
			case 'P':
				$return *= 1024;
			case 'T':
				$return *= 1024;
			case 'G':
				$return *= 1024;
			case 'M':
				$return *= 1024;
			case 'K':
				$return *= 1024;
		}

		return $return;
	}

}

/**
 * Get enqueued styles handles
 *
 * @return array
 */
function olympus_enqueued_styles_handle() {
	global $wp_styles;
	$enqueued_styles_handle = array();
	foreach ( $wp_styles->queue as $handle ) {
		$enqueued_styles_handle[] = $wp_styles->registered[ $handle ]->handle;
	}

	return $enqueued_styles_handle;

}

if ( ! function_exists( 'olympus_is_composer' ) ) {

	/**
	 * Check if use js composer
	 *
	 * @package olympus-wp
	 *
	 * @param $post_id int
	 *
	 * @return bool
	 */
	function olympus_is_composer( $post_id = '' ) {

		$page_builder_status = false;

		if ( isset( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$wc_builder_meta = get_post_meta( $post_id, '_wpb_vc_js_status', true );
		$elementor_meta  = get_post_meta( $post_id, '_elementor_edit_mode', true );

		if (
			( isset( $wc_builder_meta ) && 'true' === $wc_builder_meta ) ||
			( isset( $elementor_meta ) && 'builder' === $elementor_meta )
		) {
			$page_builder_status = true;
		}

		return $page_builder_status;
	}

}
if ( ! function_exists( 'olympus_stunning_visibility' ) ) {

	/**
	 * Check if stunning header available for page/post
	 *
	 * @package olympus-wp
	 */
	function olympus_stunning_visibility() {
		$stunning_visibility = 'no';
		$olympus = Olympus_Options::get_instance();
		$prefix = $olympus->olympus_stunning_get_option_prefix();
		$stunning_visibility = $olympus->get_option_final( $prefix . 'header-stunning-visibility', 'yes', array( 'final-source' => 'customizer' ) );

		return $stunning_visibility;
	}

}

if ( ! function_exists( 'olympus_list_pages' ) ) {

	/**
	 * get an array of all pages
	 */
	function olympus_list_pages() {
		$pages     = get_pages();
		$result    = array();
		$result[0] = esc_html__( 'None', 'olympus' );
		foreach ( $pages as $page ) {
			$result[ $page->ID ] = $page->post_title;
		}

		return $result;
	}

}

if ( ! function_exists( 'olympus_font_url' ) ) {

	/**
	 * Provides theme font url
	 *
	 * @return string google fonts url
	 */
	function olympus_font_url() {
		static $font_url = null;

		if ( ! is_null( $font_url ) ) {
			return $font_url;
		}

		$font_families = array();
		$font_subsets  = array( 'latin' );

		$changed = 0;
		$tags    = array( 'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'nav' );

		if ( function_exists( 'fw_get_db_customizer_option' ) ) {

			foreach ( $tags as $single_tag ) {
				$font_options = wp_parse_args( fw_get_db_customizer_option( 'typography_' . $single_tag, array() ), array(
					'google_font'    => '',
					'subset'         => '',
					'variation'      => '',
					'family'         => '',
					'style'          => '',
					'weight'         => '',
					'size'           => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'color'          => '',
				) );

				if ( true !== olympus_akg( 'google_font', $font_options, false ) ) {
					continue;
				}

				$changed ++; // Mark font changed for this tag
				if ( ! in_array( $font_options['subset'], $font_subsets ) ) {
					$font_subsets[] = $font_options['subset'];
				}

				$font_options['variation'] = (int) $font_options['variation'];
				if ( ! isset( $font_families[ $font_options['family'] ] ) ) {
					$font_families[ $font_options['family'] ] = array(
						'variation' => array( $font_options['variation'] ),
					);

					continue;
				}

				if ( ! in_array( $font_options['variation'], $font_families[ $font_options['family'] ]['variation'] ) ) {
					$font_families[ $font_options['family'] ]['variation'][] = $font_options['variation'];
				}
			}
		}

		// Set default font if needed
		if ( $changed < count( $tags ) && ! isset( $font_families['Roboto'] ) ) {
			$font_families['Roboto'] = array(
				'variation' => array( 300, 400, 500, 700 ),
			);
		}

		//Prepare family
		$font_families_prepared = array();

		foreach ( $font_families as $f => $p ) {
			$font_families_prepared[] = str_replace( ' ', '+', $f ) . ':' . implode( ',', $p['variation'] );
		}

		$font_url = 'https://fonts.googleapis.com/css';
		$font_url = add_query_arg( 'family', implode( '|', $font_families_prepared ), $font_url );
		$font_url = add_query_arg( 'subset', implode( ',', $font_subsets ), $font_url );
		$font_url = add_query_arg( 'display', 'swap', $font_url );

		return $font_url;
	}

}

if ( ! function_exists( 'olympus_footer_backgrounds' ) ) {

	/**
	 * Return List of backgrounds patterns.
	 *
	 * @return array
	 */
	function olympus_footer_backgrounds() {
		$background_image['none'] = array(
			'icon' => get_template_directory_uri() . '/images/thumb/bg-0.png',
			'css'  => array(
				'background-image' => 'none',
			),
		);
		for ( $i = 1; $i < 22; $i ++ ) {
			$background_image[ 'bg-' . $i . '' ] = array(
				'icon' => get_template_directory_uri() . '/images/thumb/bg-' . $i . '.png',
				'css'  => array(
					'background-image' => 'url("' . get_template_directory_uri() . '/images/thumb/bg-' . $i . '.png")',
				),
			);
		}

		return $background_image;
	}

}

if ( ! function_exists( 'olympus_social_network_icons()' ) ) {

	/**
	 * List of social networks names with file names for options;
	 *
	 * @param bool $flip
	 *
	 * @return array
	 */
	function olympus_social_network_icons( $flip = false ) {
		$icons = array(
			esc_html__( '- select -', 'olympus' )      => '',
			esc_html__( 'Amazon', 'olympus' )          => 'amazon',
			esc_html__( 'Behance', 'olympus' )         => 'behance',
			esc_html__( 'Creative market', 'olympus' ) => 'creative-market',
			esc_html__( 'Deviantart', 'olympus' )      => 'deviantart',
			esc_html__( 'Dribbble', 'olympus' )        => 'dribbble',
			esc_html__( 'Dropbox', 'olympus' )         => 'dropbox',
			esc_html__( 'Envato', 'olympus' )          => 'envato',
			esc_html__( 'Facebook', 'olympus' )        => 'facebook',
			esc_html__( 'Flickr', 'olympus' )          => 'flickr',
			esc_html__( 'Google+', 'olympus' )         => 'google-plus',
			esc_html__( 'Instagram', 'olympus' )       => 'instagram',
			esc_html__( 'Kickstarter', 'olympus' )     => 'kickstarter',
			esc_html__( 'Linkedin', 'olympus' )        => 'linkedin',
			esc_html__( 'Medium', 'olympus' )          => 'medium',
			esc_html__( 'Periscope', 'olympus' )       => 'periscope',
			esc_html__( 'Pinterest', 'olympus' )       => 'pinterest',
			esc_html__( 'Quora', 'olympus' )           => 'quora',
			esc_html__( 'Reddit', 'olympus' )          => 'reddit',
			esc_html__( 'Shutterstock', 'olympus' )    => 'shutterstock',
			esc_html__( 'Skype', 'olympus' )           => 'skype',
			esc_html__( 'Slack', 'olympus' )           => 'slack',
			esc_html__( 'Snapchat', 'olympus' )        => 'snapchat',
			esc_html__( 'Soundcloud', 'olympus' )      => 'soundcloud',
			esc_html__( 'Spotify', 'olympus' )         => 'spotify',
			esc_html__( 'Telegram', 'olympus' )        => 'telegram',
			esc_html__( 'Trello', 'olympus' )          => 'trello',
			esc_html__( 'Tumblr', 'olympus' )          => 'tumblr',
			esc_html__( 'Twitter', 'olympus' )         => 'twitter',
			esc_html__( 'Vimeo', 'olympus' )           => 'vimeo',
			esc_html__( 'VK', 'olympus' )              => 'vk',
			esc_html__( 'WhatsApp', 'olympus' )        => 'whatsapp',
			esc_html__( 'Wikipedia', 'olympus' )       => 'wikipedia',
			esc_html__( 'WordPress', 'olympus' )       => 'wordpress',
			esc_html__( 'YouTube', 'olympus' )         => 'youtube',
			esc_html__( 'Fiverr', 'olympus' )          => 'fiverr',
		);

		return $flip ? array_flip( $icons ) : $icons;
	}

}

if ( ! function_exists( 'olympus_menu_fallback' ) ) {

	/**
	 * Callback function will be displayed if main menu is empty.
	 */
	function olympus_menu_fallback( $location = '' ) {
		if ( ! is_user_logged_in() ) {
			return;
		}
		if ( $location && is_string( $location ) ) {
			$location = $location;
		} else {
			$location = esc_html__( 'Primary', 'olympus' );
		}

		$output = '<ul class="primary-menu-menu"><li><div class="no-menu-box">';
		// Translators 1: Link to Menus, 2: Link to Customize.
		$output .= sprintf( esc_attr__( 'Please assign a menu to the %3$s menu location under %1$s or %2$s the design.', 'olympus' ), sprintf( wp_kses( __( '<a href="%s">Menus</a>', 'olympus' ), array( 'a' => array( 'href' => array() ) ) ), get_admin_url( get_current_blog_id(), 'nav-menus.php' )
		), sprintf( wp_kses( __( '<a href="%s">Customize</a>', 'olympus' ), array( 'a' => array( 'href' => array() ) ) ), get_admin_url( get_current_blog_id(), 'customize.php' )
		), $location
		);
		$output .= '</div></li></ul>';

		olympus_render( $output ); // WPCS: XSS ok.
	}

}

if ( ! function_exists( 'olympus_get_widget_columns' ) ) {

	/**
	 * Count Widgets
	 * Count the number of widgets to add dynamic column class
	 *
	 * @param string $sidebar_id id of sidebar
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function olympus_get_widget_columns( $sidebar_id ) {
		// Get the sidebar widgets.
		$the_sidebars = wp_get_sidebars_widgets();

		// If sidebar doesn't exist return error.
		if ( ! isset( $the_sidebars[ $sidebar_id ] ) ) {
			return false;
		}

		/*
		 * Count number of widgets in the sidebar
		 * and do some simple math to calculate the columns
		 */
		$num = floor( 12 / count( $the_sidebars[ $sidebar_id ] ) );

		return $num;
	}

}

if ( ! function_exists( 'olympus_sidebar_conf' ) ) {

	/**
	 * Return classes for content / sidebar positions.
	 *
	 * @param bool $is_page
	 *
	 * @return array
	 */
	function olympus_sidebar_conf( $sb_wide = false ) {
		$sidebar  = array( 'col-md-12', 'col-sm-12', 'youzer-sidebar', 'olympus-theme-sidebar' );
		$content  = array( 'col-md-12', 'col-sm-12' );
		$position = 'rigth';
		if(is_page()){
			$position = 'full';
		}

		if ( function_exists( 'fw_ext_sidebars_get_current_position' ) ) {
			$position = fw_ext_sidebars_get_current_position();
			if ( 'left' === $position ) {
				$content[] = 'order-lg-2';
				$sidebar[] = 'order-lg-1';
			}

			if ( ! $position ) {
				$position = 'full';
			}
		}

		//Prevent sidebar on search page
		if ( is_search() ) {
			$position = 'full';
		}

		if ( $sb_wide && $position !== 'full' ) {
			$content[] = 'col-lg-8';
			$sidebar[] = 'col-lg-4';
		} else if ( $position === 'full' ) {
			$content[] = 'col-lg-12';
			$sidebar[] = 'col-lg-12';
		} else {
			$content[] = 'col-lg-9';
			$sidebar[] = 'col-lg-3';
		}

		return array(
			'content-classes' => implode( ' ', $content ),
			'sidebar-classes' => implode( ' ', $sidebar ),
			'position'        => $position
		);
	}

}

if ( ! function_exists( 'olympus_get_column_classes' ) ) {

	/**
	 * Return classes for columns (main and sidebar).
	 *
	 * @param array $layout
	 *
	 * @return array
	 */
	function olympus_get_column_classes( $layout = array() ) {
		$column_class = array();

		$posts_in_row = isset( $layout['position'] ) && 'full' === $layout['position'] ? 3 : 2;

		if ( 3 === $posts_in_row ) {
			$column_class[] = 'col-xl-4 col-lg-4 col-md-6';
		} else {
			$column_class[] = 'col-xl-6 col-lg-6 col-md-6';
		}

		return $column_class;
	}

}

if ( ! function_exists( 'olympus_generate_font_styles' ) ) {

	/**
	 * Generate font css, from customizer "Typography" settings.
	 *
	 * @param string $tag tag to generate css.
	 *
	 * @return string font css
	 */
	function olympus_generate_font_styles( $tag ) {
		$olympus     = Olympus_Options::get_instance();
		$font        = $olympus->get_option( 'typography_' . $tag, array(), $olympus::SOURCE_CUSTOMIZER );
		$font_family = olympus_akg( 'family', $font, 'Default' );
		$font_color  = olympus_akg( 'color', $font, '' );
		$font_css    = '';

		if ( 'nav' === $tag ) {
			$font_css .= '.primary-menu-menu .menu-item > a,'
			             . '.primary-menu-responsive.primary-menu .showhide,'
			             . '.primary-menu-menu ul.sub-menu li a,'
						 . '.primary-menu-menu > li.menu-item-has-mega-menu .megamenu ul > li a,'
			             . '.primary-menu-menu > li.menu-item-has-mega-menu .megamenu .column-tittle {';
		} elseif ( 'body' === $tag ) {
			$font_css = 'body{';
		} elseif ( 'left_menu' === $tag ) {
			$font_css = '.left-menu .left-menu-title, .left-menu .universal-olympus-icon {';
		} else {
			$font_css = $tag . ', .' . $tag . '{';
		}
		if ( ! empty( $font_family ) && 'Default' !== $font_family ) {
			$font_css .= 'font-family:"' . $font_family . '", sans-serif;';
			$variant  = olympus_akg( 'variation', $font, '' );
			if ( $variant ) {
				if ( substr_count( $variant, 'italic' ) > 0 ) {
					$font_css .= 'font-style:italic;';
					$variant  = str_replace( 'italic', '', $variant );
				}
				$font_css .= 'font-weight:' . $variant . ';';
			} elseif ( false === $font['google_font'] ) {
				$font_css .= 'font-style:' . $font['style'] . ';';
				$font_css .= 'font-weight:' . $font['weight'] . ';';
			}
		}

		if ( !empty( $font_color ) ) {
			$font_css .= 'color:' . $font_color . ';';
		}

		$letter_spacing = olympus_akg( 'letter-spacing', $font, '' );
		if ( ! empty( $letter_spacing ) ) {
			$font_css .= 'letter-spacing:' . $letter_spacing . 'px;';
		}
		$size = olympus_akg( 'size', $font, '' );
		if ( ! empty( $size ) ) {
			$font_css .= 'font-size:' . $size . 'px;';
		}

		$font_css .= '}';

		return $font_css;
	}

}

if ( ! function_exists( 'olympus_svg_icon' ) ) {

	/**
	 * Generate html markup for Svg icons from olympus icon package.
	 *
	 * @param        $icon_id     string ID of icon from olympus package
	 * @param string $extra_class Extra class for icon
	 *
	 * @param string $atts        other svg tag attributes (string)
	 *
	 * @return string
	 */
	function olympus_svg_icon( $icon_id, $extra_class = '', $atts = '' ) {
		return '<svg class="' . esc_attr( $icon_id ) . ' ' . esc_attr( $extra_class ) . '" ' . $atts . '><use xlink:href="#' . esc_attr( $icon_id ) . '"></use></svg>';
	}

}

if ( ! function_exists( 'olympus_icon_font' ) ) {

	/**
	 * Generate html markup for icons from olympus icons font.
	 *
	 * @param string $extra_class Extra class for icon
	 *
	 * @param string $atts        other i tag attributes (string)
	 *
	 * @return string
	 */
	function olympus_icon_font( $extra_class = '', $atts = '' ) {
		return '<i class=" ' . esc_attr( $extra_class ) . '" ' . $atts . '></i>';
	}

}

if ( ! function_exists( 'olympus_generate_icon_html' ) ) {

	/**
	 * Generate html markup for icons from olympus icons option.
	 *
	 * @param array|string $meta Array with value from "Icon V2" option
	 * @param string       $extra_class
	 *
	 * @return string
	 */


	function olympus_generate_icon_html( $meta = '', $extra_class = '' ) {
		$data_icon = '';

		if ( empty( $meta ) ) {
			return $data_icon;
		}

		//fw_print($meta);

		$icon = ! is_array( $meta ) ? olympus_prepare_icon_params( $meta ) : $meta;

		if ( $icon['type'] === 'custom-upload' && ! empty( $icon['url'] ) ) {
			$file_parts = pathinfo( $icon['url'] );
			if ( 'svg' === $file_parts['extension'] ) {
				$data_icon = olympus_embed_custom_svg( $icon['url'], $extra_class );
			} else {
				$data_icon = olympus_html_tag( 'img', array(
					'class'   => $extra_class,
					'src'     => $icon['url'],
					'alt'     => '',
					'loading' => 'lazy'
				), false );
			}
		}

		if ( $icon['type'] === 'icon-font' && ! empty( $icon['icon-class'] ) ) {
			$data_icon = olympus_html_tag( 'i', array( 'class' => $extra_class . ' ' . $icon['icon-class'] ), true );
		}

		return $data_icon;
	}
}

if ( ! function_exists( 'olympus_embed_custom_svg' ) ) {

	/**
	 * TODO: Make a unyson extension with this cleaning functional
	 * https://github.com/darylldoyle/svg-sanitizer
	 *
	 * Insert into page svg icons as inline elements.
	 *
	 * @param $svg_url string Path to svg file
	 *
	 * @return bool|string
	 */
	function olympus_embed_custom_svg( $svg_url, $extra_class = '', $atts = '' ) {

		$svg_file = olympus_html_tag( 'div', array(
			'class'   => 'svg-mask '.$extra_class,
			'style'     => 'mask: url(' . esc_url( $svg_url ) . ') no-repeat center / contain; -webkit-mask: url(' . esc_url( $svg_url ) . ') no-repeat center / contain;',
		), true );

		return $svg_file;
	}

}

if ( ! function_exists( 'olympus_generate_thumbnail' ) ) {

	/**
	 * Generate HTML for post thumbnails
	 *
	 * @param int  $width  Thumbnail Width
	 * @param int  $height Thumbnail Height
	 * @param bool $crop   Crop image on resize
	 */
	function olympus_generate_thumbnail( $width, $height, $crop = true ) {
		$thumbnail_id = get_post_thumbnail_id();
		if ( ! empty( $thumbnail_id ) ) {
			$thumbnail       = get_post( $thumbnail_id );
			$img_url         = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			$thumbnail_title = $thumbnail->post_title;
		} else {
			$img_url         = get_template_directory_uri() . '/images/post-no-image.png';
			$thumbnail_title = '';
		}
		$image = olympus_resize( $img_url, $width, $height, $crop );
		echo olympus_html_tag( 'img', array(
			'src'    => esc_url( $image ),
			'width'  => esc_attr( $width ),
			'height' => esc_attr( $height ),
			'alt'    => esc_attr( $thumbnail_title )
		) );
	}

}

if ( ! function_exists( 'olympus_generate_thumbnail_bpt' ) ) {

	/**
	 * Generate thumbnail by post type
	 */
	function olympus_generate_thumbnail_bpt( $default = true ) {
		$olympus = Olympus_Options::get_instance();
		$format  = get_post_format();

		ob_start();
		if ( $format === 'audio' ) {
			$oembed = $olympus->get_option( 'audio_oembed', '', Olympus_Options::SOURCE_POST );

			if ( empty( $oembed ) ) {
				return;
			}
			?>
			<div class="post-thumb">
				<?php
				$frame = wp_oembed_get( $oembed );
				if ( $frame ) {
					olympus_render( $frame );
				} else {
					?>
					<p class="text-danger">
						<?php esc_html_e( 'Not found', 'olympus' ); ?>
					</p>
					<?php
				}
				?>
			</div>
			<?php
			return ob_get_clean();
		}

		if ( $format === 'video' ) {
			$oembed = $olympus->get_option( 'video_oembed', '', Olympus_Options::SOURCE_POST );

			if ( empty( $oembed ) ) {
				return;
			}
			?>
			<div class="post-thumb">
				<?php
				$frame = wp_oembed_get( $oembed );
				if ( $frame ) {
					olympus_render( $frame );
				} else {
					?>
					<p class="text-danger">
						<?php esc_html_e( 'Not found', 'olympus' ); ?>
					</p>
					<?php
				}
				?>
			</div>
			<?php
			return ob_get_clean();
		}

		if ( $format === 'gallery' ) {
			$gallery_images = $olympus->get_option( 'gallery_images', array(), Olympus_Options::SOURCE_POST );

			if ( empty( $gallery_images ) ) {
				return;
			}
			?>
			<div class="post-thumb crumina-module-slider">
				<div class="swiper-container">
					<div class="swiper-wrapper js-zoom-gallery">
						<?php foreach ( $gallery_images as $image ) { ?>
							<div class="swiper-slide">
								<img src="<?php echo esc_attr( $image['url'] ); ?>"
									 alt="<?php esc_attr_e( 'Gallery', 'olympus' ); ?>">
								<a href="<?php echo esc_url( $image['url'] ); ?>" class="post-type-icon">
									<?php echo olympus_icon_font( 'olympus-icon-Camera-Icon' ) ?>
								</a>
							</div>
						<?php } ?>
					</div>
					<!-- If we need pagination -->
					<div class="swiper-pagination"></div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		if ( $format === 'link' ) {
			$content  = get_the_content();
			$post_url = get_url_in_content( $content );

			if ( empty( $post_url ) ) {
				return;
			}

			$link_parts = parse_url( $post_url );
			?>
			<div class="post-thumb bg-link"
				 style="background-image: url(<?php echo esc_attr( get_the_post_thumbnail_url( get_the_ID() ) ) ?>)">

				<div class="overlay overlay-dark"></div>

				<div class="post-content">
					<?php the_title( '<a class="h2 post-title" href="' . esc_url( $post_url ) . '" rel="nofollow"  target="_blank">', '</a>' ); ?>

					<a href="<?php echo esc_url( $post_url ); ?>" class="site-link" rel="nofollow"
					   target="_blank"><?php echo esc_html( $link_parts['host'] ) ?></a>
					<a href="<?php echo esc_url( $post_url ); ?>" class="post-link" rel="nofollow" target="_blank">
						<?php echo olympus_icon_font( 'olympus-icon-Link-Icon' ); ?>
					</a>

				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		if ( $format === 'quote' ) {
			$post_options     = $olympus->get_option( '', array(), Olympus_Options::SOURCE_POST );
			$quote_author     = olympus_akg( 'quote_author', $post_options, '' );
			$quote_dopinfo    = olympus_akg( 'quote_dopinfo', $post_options, '' );
			$quote_avatar     = olympus_akg( 'quote_avatar/url', $post_options, '' );
			$quote_text_color = olympus_akg( 'overlay_color', $post_options, '' );

			if ( has_post_thumbnail() ) {
				$poster_class       = 'custom-bg';
				$post_thumbnail_id  = get_post_thumbnail_id( get_the_ID() );
				$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
				$poster_style       = 'style="background-image:url(' . esc_url( $post_thumbnail_url ) . ');"';
			} else {
				$poster_style = '';
				$poster_class = '';
			}
			$overlay_style = ! empty( $quote_overlay_color ) ? 'style="background-color:' . esc_attr( $quote_overlay_color ) . ';"' : '';
			?>
			<div class="post-thumb <?php echo esc_attr( $poster_class ); ?>" <?php olympus_render( $poster_style ); ?>>
				<div class="overlay" <?php olympus_render( $overlay_style ) ?>></div>

				<div class="post-content">
					<div class="quote-icon">
						<i class="olympus-icon-Quote-Icon"></i>
					</div>
					<div class="h2 post-title custom-color"><?php echo get_the_content(); ?></div>
					<div class="post__author author vcard">
						<?php
						if ( ! empty( $quote_avatar ) ) {
							echo '<div class="testimonial-img-author">';
							echo olympus_html_tag( 'img', array(
								'src' => olympus_resize( $quote_avatar, 80, 80, false ),
								'alt' => $quote_author
							), false );
							echo '</div>';
						}
						?>
						<?php if ( ! empty( $quote_author ) ) { ?>
							<span class="h6 post__author-name fn"><?php echo esc_html( $quote_author ) ?></span>
							<?php
						}
						if ( ! empty( $quote_dopinfo ) ) {
							?>
							<div class="author-prof"><?php echo esc_html( $quote_dopinfo ) ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		if ( $default && has_post_thumbnail() ) {
			?>
			<div class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php
			return ob_get_clean();
		}

		return '';
	}

}

if ( ! function_exists( 'olympus_generate_short_excerpt' ) ) {

	/**
	 * Generate short exerpt for blog posts.
	 *
	 * @param int  $post_id        Id of post.
	 * @param int  $excerpt_length Lengt for exertpt from post meta.
	 * @param bool $trim_excerpt   Cut custom excerpt.
	 *
	 * @return string
	 */
	function olympus_generate_short_excerpt(
		$post_id, $excerpt_length = 15, $trim_excerpt = false
	) {
		$post_excerpt = get_post_field( 'post_excerpt', $post_id );

		if ( ! empty( $post_excerpt ) ) {
			$trimmed_excerpt = $post_excerpt;
			if ( true === $trim_excerpt ) {
				$trimmed_excerpt = wp_trim_words( strip_shortcodes( $post_excerpt ), $excerpt_length );
			}
		} else {
			$excerpt         = get_the_content();
			$trimmed_excerpt = wp_trim_words( strip_shortcodes( $excerpt ), $excerpt_length );
		}

		return $trimmed_excerpt;
	}

}

if ( ! function_exists( '_olympus_callback_get_cat_color' ) ) {

	/**
	 * Generate colors for category labels.
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	function _olympus_callback_get_cat_color( $id = 0 ) {
		$class = 'post-category bg-primary';
		$style = '';
		if ( function_exists( 'fw_get_db_term_option' ) && 0 !== $id ) {
			$color_option = fw_get_db_term_option( $id, 'category', 'category_bg_color', '' );
			if ( ! empty( $color_option ) ) {
				$style = 'background-color:' . $color_option . ';';
				$class = 'post-category colored-category';
			}
		}

		return array(
			'class' => $class,
			'style' => $style
		);
	}

}

if ( ! function_exists( 'olympus_taxonomy_get_listing_categories' ) ) {

	/**
	 * @param int|array $term_ids
	 * @param string    $taxonomy
	 *
	 * @return array|WP_Error
	 */
	function olympus_taxonomy_get_listing_categories(
		$term_ids, $taxonomy = 'category'
	) {

		$args = array(
			'hide_empty' => false
		);

		if ( is_numeric( $term_ids ) ) {
			$args['parent'] = $term_ids;
		} elseif ( is_array( $term_ids ) ) {
			$args['include'] = $term_ids;
		}

		$categories = get_terms( $taxonomy, $args );

		if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {

			if ( count( $categories ) === 1 ) {
				$categories = array_values( $categories );
				$categories = get_terms( $taxonomy, array(
					'parent'     => $categories[0]->term_id,
					'hide_empty' => false
				) );
			}

			foreach ( $categories as $key => $category ) {
				$children                     = get_term_children( $category->term_id, $taxonomy );
				$categories[ $key ]->children = $children;

				//remove empty categories
				if ( ( $category->count == 0 ) && ( is_wp_error( $children ) || empty( $children ) ) ) {
					unset( $categories[ $key ] );
				}
			}

			return $categories;
		}

		return array();
	}

}

if ( ! function_exists( 'olympus_taxonomy_get_sort_classes' ) ) {

	/**
	 * @param WP_Post[] $items
	 * @param array     $categories
	 * @param string    $prefix
	 * @param string    $taxonomy
	 *
	 * @return array
	 */
	function olympus_taxonomy_get_sort_classes(
		array $items, array $categories, $taxonomy = 'category', $prefix = 'category_'
	) {

		$classes            = array();
		$categories_classes = array();
		foreach ( $items as $key => $item ) {
			$class_name = '';
			$terms      = wp_get_post_terms( $item->ID, $taxonomy );

			foreach ( $terms as $term ) {
				foreach ( $categories as $category ) {
					if ( $term->term_id == $category->term_id ) {
						$class_name                           .= $prefix . $category->term_id . ' ';
						$categories_classes[ $term->term_id ] = true;
					} else {
						if ( in_array( $term->term_id, $category->children, true ) ) {
							$class_name                           .= $prefix . $category->term_id . ' ';
							$categories_classes[ $term->term_id ] = true;
						}
					}
					$classes[ $item->ID ] = $class_name;
				}
			}
		}

		return $classes;
	}

}

if ( ! function_exists( 'olympus_custom_page_loop' ) ) {

	/**
	 *
	 *
	 * @param string $post_type
	 *
	 * @return array $args
	 */
	function olympus_custom_page_loop( $post_type = 'post' ) {
		if ( 'fw-portfolio' === $post_type ) {
			$per_page = fw_get_db_settings_option( 'per_page', 9 );
			$order    = fw_get_db_settings_option( 'order', 'DESC' );
			$orderby  = fw_get_db_settings_option( 'orderby', 'date' );
			$taxonomy = 'fw-portfolio-category';
		} else {
			$per_page = get_option( 'posts_per_page' );
			$order    = 'DESC';
			$orderby  = 'date';
			$taxonomy = 'category';
		}

		$meta_per_page          = fw_get_db_post_option( get_the_ID(), 'per_page' );
		$meta_order             = fw_get_db_post_option( get_the_ID(), 'order' );
		$meta_orderby           = fw_get_db_post_option( get_the_ID(), 'orderby' );
		$meta_custom_categories = fw_get_db_post_option( get_the_ID(), 'taxonomy_select' );
		$meta_exclude           = fw_get_db_post_option( get_the_ID(), 'exclude' );


		if ( ! empty( $meta_per_page ) ) {
			$per_page = $meta_per_page;
		}

		if ( ! empty( $meta_order ) && ! ( 'default' === $meta_order ) ) {
			$order = $meta_order;
		}

		if ( ! empty( $meta_orderby ) && ! ( 'default' === $meta_orderby ) ) {
			$orderby = $meta_orderby;
		}

		if ( is_front_page() ) {
			$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}

		$args = array(
			'post_type'      => $post_type,
			'paged'          => $paged,
			'posts_per_page' => $per_page,
			'order'          => $order,
			'orderby'        => $orderby
		);

		if ( ! empty( $meta_custom_categories ) ) {
			if ( true === $meta_exclude ) {
				$operator = 'NOT IN';
			} else {
				$operator = 'IN';
			}
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $meta_custom_categories,
					'operator' => $operator,
				),
			);
		}

		return $args;
	}

}

if ( ! function_exists( 'olympus_twitter_convert_links' ) ) {

	/**
	 * Convert text in tweets to links.
	 *
	 * @param string $status Tweet.
	 *
	 * @return mixed
	 */
	function olympus_twitter_convert_links( $status ) {

		$status = preg_replace_callback( '/((http:\/\/|https:\/\/)[^ )]+)/', function ( $matches ) {
			return '<a href="' . $matches[1] . '" class="link" title="' . $matches[1] . '" target="_blank" ><strong>' . ( ( strlen( $matches[1] ) >= 450 ? substr( $matches[1], 0, 450 ) . '...' : $matches[1] ) ) . '</strong></a>';
		}, $status );


		$status = preg_replace( "/(#([_a-z0-9\-]+))/i", "<a href=\"https://twitter.com/search?q=$2\" class=\"link\" title=\"Search $1\" target=\"_blank\"><strong>$1</strong></a>", $status );

		return $status;
	}

}

if ( ! function_exists( 'olympus_relative_time' ) ) {

	/**
	 * Convert dates to readable format.
	 *
	 * @param int $a Time in Unix format.
	 *
	 * @return string
	 */
	function olympus_relative_time( $a ) {
		// Get current timestampt.
		$b = strtotime( esc_html__( 'now', 'olympus' ) );
		// Get timestamp when tweet created.
		$c = strtotime( $a );
		// Get difference.
		$d = $b - $c;
		// Calculate different time values.
		$minute = 60;
		$hour   = $minute * 60;
		$day    = $hour * 24;

		if ( is_numeric( $d ) && $d > 0 ) {
			// If less then 3 seconds.
			if ( $d < 3 ) {
				return esc_html__( 'right now', 'olympus' );
			}
			// If less then minute.
			if ( $d < $minute ) {
				return floor( $d ) . esc_html__( 'seconds ago', 'olympus' );
			}
			// If less then 2 minutes.
			if ( $d < $minute * 2 ) {
				return esc_html__( 'about 1 minute ago', 'olympus' );
			}
			// If less then hour.
			if ( $d < $hour ) {
				return floor( $d / $minute ) . ' ' . esc_html__( 'minutes ago', 'olympus' );
			}
			// If less then 2 hours.
			if ( $d < $hour * 2 ) {
				return 'about 1 hour ago';
			}
			// If less then day.
			if ( $d < $day ) {
				return floor( $d / $hour ) . ' ' . esc_html__( 'hours ago', 'olympus' );
			}
			// If more then day, but less then 2 days.
			if ( $d > $day && $d < $day * 2 ) {
				return 'yesterday';
			}
			// If less then year.
			if ( $d < $day * 365 ) {
				return floor( $d / $day ) . ' ' . esc_html__( 'days ago', 'olympus' );
			}

			// Else return more than a year.
			return esc_html__( 'over a year ago', 'olympus' );
		} else {
			return '';
		}
	}

}

if ( ! function_exists( 'olympus_scrape_instagram' ) ) {

	/**
	 * Scrape instagram photos
	 * based on https://gist.github.com/cosmocatalano/4544576
	 *
	 * @param string $username Instagram username.
	 * @param int    $slice    Photos count.
	 * @param int    $cache    Cache duration.
	 *
	 * @return string
	 */
	function olympus_scrape_instagram( $username, $slice = 9, $cachetime = 2 ) {
		$username       = trim( strtolower( $username ) );
		$by_hashtag     = ( substr( $username, 0, 1 ) == '#' );
		$transient_name = 'crum_widget_instagram_' . sanitize_title_with_dashes( $username );
		$instagram      = get_transient( $transient_name );

		if ( false === $instagram ) {

			$request_param = ( $by_hashtag ) ? 'explore/tags/' . substr( $username, 1 ) : trim( $username );
			$remote        = wp_remote_get( 'https://instagram.com/' . $request_param );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'olympus' ) );
			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'olympus' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'olympus' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images    = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
				$page_info = array(
					'hash'      => false,
					'name'      => $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['username'],
					'full_name' => $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['full_name'],
					'thumb'     => $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['profile_pic_url'],
					'count'     => $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['count'],
					'follows'   => $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_follow']['count'],
					'followers' => $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_followed_by']['count'],
				);
			} elseif ( $by_hashtag && isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images    = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
				$page_info = array(
					'hash'      => true,
					'name'      => $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['name'],
					'full_name' => $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['name'],
					'thumb'     => $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['profile_pic_url'],
					'count'     => $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['count'],
				);
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'olympus' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'olympus' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				$image   = $image['node'];
				$caption = esc_html__( 'Instagram Image', 'olympus' );
				if ( ! empty( $image['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = $image['edge_media_to_caption']['edges'][0]['node']['text'];
				}

				$image['thumbnail_src'] = preg_replace( "/^https:/i", "", $image['thumbnail_src'] );
				$image['thumbnail']     = preg_replace( "/^https:/i", "", $image['thumbnail_resources'][0]['src'] );
				$image['medium']        = preg_replace( "/^https:/i", "", $image['thumbnail_resources'][2]['src'] );
				$image['large']         = $image['thumbnail_src'];

				$type = ( $image['is_video'] ) ? 'video' : 'image';

				$instagram[] = array(
					'description' => $caption,
					'link'        => '//instagram.com/p/' . $image['shortcode'],
					'comments'    => $image['edge_media_to_comment']['count'],
					'likes'       => $image['edge_liked_by']['count'],
					'thumbnail'   => $image['thumbnail'],
					'medium'      => $image['medium'],
					'large'       => $image['large'],
					'type'        => $type
				);
			}

			// Do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = json_encode( serialize( array(
					'media'     => $instagram,
					'page_info' => $page_info
				) ) );
				set_transient( 'crum_instagram_' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * $cachetime ) );
			}
		}
		if ( ! empty( $instagram ) ) {
			$instagram          = unserialize( json_decode( $instagram ) );
			$instagram['media'] = array_slice( $instagram['media'], 0, $slice );

			return $instagram;
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'olympus' ) );
		}
	}

}

if ( ! function_exists( 'olympus_get_cache_time_select_html' ) ) {

	/**
	 * Get cache time select html
	 *
	 * @param string $id       Select id.
	 * @param string $name     Select name.
	 * @param int    $duration Cache duration.
	 *
	 * @return string
	 */
	function olympus_get_cache_time_select_html(
		$id = '', $name = '', $duration = ''
	) {
		?>
		<label for="<?php olympus_render( $id ); ?>"><?php esc_html_e( 'Cache time', 'olympus' ); ?>:
			<select class="widefat" name="<?php olympus_render( $name ); ?>" id="<?php olympus_render( $id ); ?>">
				<option value="600" <?php selected( $duration, 600 ); ?>>10 minutes</option>
				<option value="1200" <?php selected( $duration, 1200 ); ?>>20 minutes</option>
				<option value="1800" <?php selected( $duration, 1800 ); ?>>30 minutes</option>
				<option value="3600" <?php selected( $duration, 3600 ); ?>>1 hour</option>
				<option value="7200" <?php selected( $duration, 7200 ); ?>>2 hours</option>
				<option value="10800" <?php selected( $duration, 10800 ); ?>>3 hours</option>
			</select>
		</label>
		<?php
	}

}

if ( ! function_exists( 'olympus_button_colors' ) ) {

	/**
	 * List of button color variations for options;
	 *
	 * @return array
	 */
	function olympus_button_colors() {
		$colors = array(
			'primary'     => esc_html__( 'Primary color', 'olympus' ),
			'secondary'   => esc_html__( 'Secondary color', 'olympus' ),
			'white'       => esc_html__( 'White', 'olympus' ),
			'dark'        => esc_html__( 'Dark', 'olympus' ),
			'gray'        => esc_html__( 'Gray', 'olympus' ),
			'blue'        => esc_html__( 'Blue', 'olympus' ),
			'purple'      => esc_html__( 'Purple', 'olympus' ),
			'breez'       => esc_html__( 'Breez', 'olympus' ),
			'orange'      => esc_html__( 'Orange', 'olympus' ),
			'yellow'      => esc_html__( 'Yellow', 'olympus' ),
			'green'       => esc_html__( 'Green', 'olympus' ),
			'dark-gray'   => esc_html__( 'Dark gray', 'olympus' ),
			'brown'       => esc_html__( 'Brown', 'olympus' ),
			'rose'        => esc_html__( 'Rose', 'olympus' ),
			'violet'      => esc_html__( 'Violet', 'olympus' ),
			'olive'       => esc_html__( 'Olive', 'olympus' ),
			'light-green' => esc_html__( 'Light green', 'olympus' ),
			'dark-blue'   => esc_html__( 'Dark blue', 'olympus' ),
		);

		return $colors;
	}

}

if ( ! function_exists( 'olympus_user_social_networks' ) ) {

	/**
	 * List of aviable social networks for user fields.
	 *
	 * @return array
	 */
	function olympus_user_social_networks() {
		$socials = array(
			'twitter'   => array(
				'label' => 'Twitter',
				'icon'  => 'fab fa-twitter',
			),
			'facebook'  => array(
				'label' => 'Facebook',
				'icon'  => 'fab fa-facebook-f',
			),
			'google'    => array(
				'label' => 'Google +',
				'icon'  => 'fab fa-google-plus-g',
			),
			'pinterest' => array(
				'label' => 'Pinterest',
				'icon'  => 'fab fa-pinterest-p',
			),
			'linkedin'  => array(
				'label' => 'Linkedin',
				'icon'  => 'fab fa-linkedin-in',
			),
			'vk'        => array(
				'label' => 'Vkontakte',
				'icon'  => 'fab fa-vk',
			),
		);

		return $socials;
	}

}

if ( ! function_exists( 'olympus_bbp_stristr_array' ) ) {

	/**
	 *
	 * @return array
	 */
	function olympus_bbp_stristr_array( $haystack, $needles ) {

		$elements = array();


		foreach ( $needles as $id => $needle ) {
			if ( stristr( $haystack, $needle ) ) {
				$elements[] = $id;
			}
		}

		return $elements;
	}

}

if ( ! function_exists( 'olympus_bbp_get_replies' ) ) {

	/**
	 * Get BBP replies.
	 *
	 * @return array
	 */
	function olympus_bbp_get_replies( $title = '' ) {
		global $wpdb;
		$topic_matches = array();

		/* First do a title search */
		$topics = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->posts . ' WHERE post_title LIKE "%' . esc_sql( trim( $title ) ) . '%" AND post_type="topic" AND post_status="publish"' );

		/* do a tag search if title search doesn't have results */
		if ( ! $topics ) {
			$topic_tags = get_terms( 'topic-tag' );

			if ( empty( $topic_tags ) ) {
				return $topic_matches;
			}

			foreach ( $topic_tags as $tid => $tag ) {
				$tags[ $tag->term_id ] = $tag->name;
			}

			$tag_matches = olympus_bbp_stristr_array( $title, $tags );

			$args = array(
				'post_type' => 'topic',
				'showposts' => - 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'topic-tag',
						'field'    => 'term_id',
						'terms'    => $tag_matches
					)
				)
			);

			$topics = get_posts( $args );
		}

		/* Compile results into array */
		foreach ( $topics as $topic ) {
			$topic_matches[ $topic->ID ]['name'] = $topic->post_title;
			$topic_matches[ $topic->ID ]['url']  = get_post_permalink( $topic->ID );
		}


		return $topic_matches;
	}

}

if ( ! function_exists( 'olympus_get_avatar' ) ) {

	/**
	 * Get User avatar
	 *
	 */
	function olympus_get_avatar( $image_args = array() ) {
		if ( function_exists( 'bp_is_active' ) ) {
			return bp_get_loggedin_user_avatar( $image_args );
		} else {
			return get_avatar( get_current_user_id() );
		}
	}

}

if ( ! function_exists( 'olympus_char_trim' ) ) {

	/**
	 * TRIM by characters
	 *
	 */
	function olympus_char_trim( $string, $count = 50, $ellipsis = false ) {
		$trimstring = substr( $string, 0, $count );
		if ( strlen( $string ) > $count ) {
			if ( is_string( $ellipsis ) ) {
				$trimstring .= $ellipsis;
			} elseif ( $ellipsis ) {
				$trimstring .= '&hellip;';
			}
		}

		return $trimstring;
	}

}

if ( ! function_exists( 'olympus_prepare_icon_params' ) ) {

	/**
	 * Prepare megamenu icon. Check if image instead icon font.
	 *
	 * @param string $meta Menu item meta. Mayby JSON.
	 *
	 * @return array
	 */
	function olympus_prepare_icon_params( $meta = '' ) {
		$parsed = (array) json_decode( urldecode( $meta ) );

		if ( $meta && ! $parsed ) {
			$parsed = array(
				'type'       => 'icon-font',
				'icon-class' => $meta
			);
		}

		return array_merge( array(
			'type'          => '',
			'icon-class'    => '',
			'attachment-id' => '',
			'url'           => ''
		), $parsed );
	}

}

if ( ! function_exists( 'olympus_mutual_friend_total_count' ) ) {

	/**
	 * Get the mutual friend count for the current user.
	 *
	 * @params $friend_user_id int
	 *
	 * @return mixed|void
	 */
	function olympus_mutual_friend_total_count(
		$current_user_id = 0, $friend_user_id = 0
	) {
		$result = 0;

		if ( ! $current_user_id || ! $friend_user_id ) {
			return $result;
		}

		$current_user_friends = friends_get_friend_user_ids( $current_user_id );

		$displayed_user_friends = friends_get_friend_user_ids( $friend_user_id );

		$result = count( array_intersect( $current_user_friends, $displayed_user_friends ) );

		return $result;
	}

}

if ( ! function_exists( 'olympus_is_user_online' ) ) {

	/**
	 * Check for user online
	 *
	 * @params $user_id int
	 * @params $time int
	 *
	 * @return bool
	 */
	function olympus_is_user_online( $user_id, $time = 5 ) {
		global $wp, $wpdb;

		$user_login = $wpdb->get_var( $wpdb->prepare( "
		SELECT u.user_login FROM $wpdb->users u JOIN $wpdb->usermeta um ON um.user_id = u.ID
		WHERE 	u.ID = $user_id 
		AND um.meta_key = 'last_activity'
		AND DATE_ADD( um.meta_value, INTERVAL $time MINUTE ) >= UTC_TIMESTAMP()
		"
		) );
		if ( isset( $user_login ) && $user_login != "" ) {
			return true;
		} else {
			return false;
		}
	}

}

if ( ! function_exists( 'olympus_get_menus_list' ) ) {

	/**
	 * Get menus list
	 *
	 * @return array
	 */
	function olympus_get_menus_list() {
		$menus_prepared = array(
			'' => '---select---'
		);
		$menus          = wp_get_nav_menus( array( 'hide_empty' => true, 'orderby' => 'name' ) );

		foreach ( $menus as $menu ) {
			$menus_prepared[ $menu->term_id ] = $menu->name;
		}

		return $menus_prepared;
	}

}

if ( ! function_exists( 'olympus_get_settings_url' ) ) {
	/**
	 * Get Settings Url.
	 */
	function olympus_get_settings_url( $slug = false, $user_id = null ) {

		if ( ! bp_is_active( 'settings' ) ) {
			return false;
		}

		// Get User ID.
		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		// Get User Settings Page Url.
		$url = bp_core_get_user_domain( $user_id );

		if ( $slug ) {
			$url = $url . $slug;
		}

		return $url;
	}
}

if ( ! function_exists( 'olympus_bp_menu' ) ) {

	/**
	 * Outputs the full BuddyPress menu
	 * @return bool
	 */
	function olympus_bp_menu() {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return false;
		}

		// New Array
		$links = array();

		if ( class_exists( 'Youzer' ) ) {
			// Profile Settings
			$links['profile'] = array(
				'icon'  => 'olympus-icon-User-Icon',
				'href'  => yz_get_profile_settings_url( false, $user_id ),
				'title' => __( 'Profile Settings', 'olympus' )
			);

			if ( bp_is_active( 'settings' ) ) {
				// Account Settings
				$links['account'] = array(
					'icon'  => 'olympus-icon-Settings-Icon',
					'href'  => olympus_get_settings_url( 'settings', $user_id ),
					'title' => __( 'Account Settings', 'olympus' )
				);
			}

			// Widgets Settings
			$links['widgets'] = array(
				'icon'  => 'olympus-icon-Manage-Widgets-Icon',
				'href'  => yz_get_widgets_settings_url( false, $user_id ),
				'title' => __( 'Widgets Settings', 'olympus' )
			);

			// Change Photo Link
			$links['change-photo'] = array(
				'icon'  => 'olympus-icon-Camera-Icon',
				'href'  => yz_get_profile_settings_url( 'change-avatar', $user_id ),
				'title' => __( 'Change Avatar', 'olympus' )
			);

			// Change Password Link
			$links['change-password'] = array(
				'icon'  => 'olympus-icon-Photo-Icon',
				'href'  => yz_get_profile_settings_url( 'change-cover-image', $user_id ),
				'title' => __( 'Change Cover Image', 'olympus' )
			);
		}

		// Logout Link
		$links['logout'] = array(
			'icon'  => 'olympus-icon-Logout-Icon',
			'href'  => wp_logout_url(),
			'title' => __( 'Logout', 'olympus' )
		);

		// Filter.
		$links = apply_filters( 'yz_get_profile_account_menu', $links, $user_id );
		?>
		<ul class="menu account-settings">
			<?php foreach ( $links as $link ) : ?>
				<li class="menu-item menu-item-has-icon">
					<a href="<?php echo esc_url( $link['href'] ); ?>">
						<i class="<?php echo esc_attr( $link['icon'] ); ?>"></i>

						<?php echo esc_html( $link['title'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

}

if ( ! function_exists( 'olympus_topbar_title' ) ) {

	/**
	 * Get top bar title
	 * @return string
	 */
	function olympus_topbar_title() {

		if ( is_home() || is_front_page() ) {
			return esc_html__( 'Home', 'olympus' );
		} elseif ( is_404() ) {
			return esc_html__( '404', 'olympus' );
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			if ( is_shop() && apply_filters( 'woocommerce_show_page_title', true ) ) {
				return woocommerce_page_title( false );
			} elseif ( is_product() ) {
				return esc_html__( 'Product', 'olympus' );
			} elseif ( is_cart() || is_checkout() || is_checkout_pay_page() ) {
				return get_the_title();
			}
		} elseif ( is_search() ) {
			return esc_html__( 'Search', 'olympus' );
		} elseif ( Olympus_Core::bp_current_component() ) {
			return esc_html__( 'Profile', 'olympus' );
		} elseif ( is_category() ) {
			return esc_html__( 'Category', 'olympus' );
		} elseif ( is_tag() ) {
			return esc_html__( 'Tag', 'olympus' );
		} elseif ( is_author() ) {
			return esc_html__( 'Author', 'olympus' );
		} elseif ( is_year() ) {
			return esc_html__( 'Year', 'olympus' );
		} elseif ( is_month() ) {
			return esc_html__( 'Month', 'olympus' );
		} elseif ( is_day() ) {
			return esc_html__( 'Day', 'olympus' );
		} elseif ( is_singular() ) {
			global $post, $wp_post_types;
			$obj = $wp_post_types[ $post->post_type ];

			return $obj->labels->singular_name;
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );

			return $tax->labels->singular_name;
		} else {
			return esc_html__( 'Archives', 'olympus' );
		}
	}

}

if ( ! function_exists( 'olympus_get_post_reactions' ) ) {

	/**
	 * Get post reactions
	 *
	 * @param string $type      Type of reactions all|compact|used|plain. Default 'all'.
	 * @param int    $postID    Post ID. Default current post.
	 * @param int    $reactions Counted reactions. Use -1 for recount. Default -1.
	 *
	 * @return string
	 */
	function olympus_get_post_reactions(
		$type = 'all', $postID = 0, $reactions = - 1
	) {
		$reactions_obj = Olympus_Core::get_extension( 'post-reaction' );
		if ( ! $reactions_obj ) {
			return '';
		}

		if ( $type === 'plain' ) {
			return $reactions_obj->getReactionsHtml();
		} else {
			return $reactions_obj->getReactionsCountHtml( $type );
		}
	}

}

if ( ! function_exists( 'olympus_highlight_searched' ) ) {

	/**
	 * Highlight searched words
	 *
	 * @param string $searched Searched text.
	 * @param string $text     Full text.
	 *
	 * @return string
	 */
	function olympus_highlight_searched( $searched = '', $text = '' ) {
		if ( ! $searched ) {
			return $text;
		}

		return preg_replace( '/' . $searched . '(?!([^<]+)?>)/i', '<span class="bg-green highlight-searched">$0</span>', $text );
	}

}

if ( ! function_exists( 'olympus_get_post_sort_panel' ) ) {

	/**
	 * Generate panel for sorting posts from unyson extension
	 *
	 * @return string
	 */
	function olympus_get_post_sort_panel() {
		$ajax_blog_panel = '';
		$ajax_blog_obj   = Olympus_Core::get_extension( 'ajax-blog' );
		if ( $ajax_blog_obj ) {
			$ajax_blog_panel = $ajax_blog_obj->getFilterPanelHtml();
		}

		return $ajax_blog_panel;
	}

}

if ( ! function_exists( 'olympus_google_map_custom_styles' ) ) {

	/**
	 * TODO:In future check - does it can be use with leaflet. Or delete that.
	 * Custom styles for map shortcode
	 *
	 * @return array
	 */
	function olympus_google_map_custom_styles() {
		return array(
			'default'            => array(
				esc_html__( "Default", 'olympus' ),
				""
			),
			'dark'               => array(
				esc_html__( "Dark", 'olympus' ),
				"[{'featureType':'all','elementType':'labels.text.fill','stylers':[{'saturation':36},{'color':'#000000'},{'lightness':40}]},{'featureType':'all','elementType':'labels.text.stroke','stylers':[{'visibility':'on'},{'color':'#000000'},{'lightness':16}]},{'featureType':'all','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#000000'},{'lightness':20}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#000000'},{'lightness':17},{'weight':1.2}]},{'featureType':'landscape','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':20}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':21}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#000000'},{'lightness':17}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#000000'},{'lightness':29},{'weight':0.2}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':18}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':16}]},{'featureType':'transit','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':19}]},{'featureType':'water','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':17}]}]"
			),
			'omni'               => array(
				esc_html__( "Omni", 'olympus' ),
				"[{'featureType':'landscape','stylers':[{'saturation':-100},{'lightness':65},{'visibility':'on'}]},{'featureType':'poi','stylers':[{'saturation':-100},{'lightness':51},{'visibility':'simplified'}]},{'featureType':'road.highway','stylers':[{'saturation':-100},{'visibility':'simplified'}]},{'featureType':'road.arterial','stylers':[{'saturation':-100},{'lightness':30},{'visibility':'on'}]},{'featureType':'road.local','stylers':[{'saturation':-100},{'lightness':40},{'visibility':'on'}]},{'featureType':'transit','stylers':[{'saturation':-100},{'visibility':'simplified'}]},{'featureType':'administrative.province','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':-25},{'saturation':-100}]},{'featureType':'water','elementType':'geometry','stylers':[{'hue':'#ffff00'},{'lightness':-25},{'saturation':-97}]}]"
			),
			'coy-beauty'         => array(
				esc_html__( "Coy Beauty", 'olympus' ),
				"[{'featureType':'all','elementType':'geometry.stroke','stylers':[{'visibility':'simplified'}]},{'featureType':'administrative','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'administrative','elementType':'labels','stylers':[{'visibility':'simplified'},{'color':'#a31645'}]},{'featureType':'landscape','elementType':'all','stylers':[{'weight':'3.79'},{'visibility':'on'},{'color':'#ffecf0'}]},{'featureType':'landscape','elementType':'geometry','stylers':[{'visibility':'on'}]},{'featureType':'landscape','elementType':'geometry.stroke','stylers':[{'visibility':'on'}]},{'featureType':'poi','elementType':'all','stylers':[{'visibility':'simplified'},{'color':'#a31645'}]},{'featureType':'poi','elementType':'geometry','stylers':[{'saturation':'0'},{'lightness':'0'},{'visibility':'off'}]},{'featureType':'poi','elementType':'geometry.stroke','stylers':[{'visibility':'off'}]},{'featureType':'poi.business','elementType':'all','stylers':[{'visibility':'simplified'},{'color':'#d89ca8'}]},{'featureType':'poi.business','elementType':'geometry','stylers':[{'visibility':'on'}]},{'featureType':'poi.business','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'saturation':'0'}]},{'featureType':'poi.business','elementType':'labels','stylers':[{'color':'#a31645'}]},{'featureType':'poi.business','elementType':'labels.icon','stylers':[{'visibility':'simplified'},{'lightness':'84'}]},{'featureType':'road','elementType':'all','stylers':[{'saturation':-100},{'lightness':45}]},{'featureType':'road.highway','elementType':'all','stylers':[{'visibility':'simplified'}]},{'featureType':'road.arterial','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'transit','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'all','stylers':[{'color':'#d89ca8'},{'visibility':'on'}]},{'featureType':'water','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'color':'#fedce3'}]},{'featureType':'water','elementType':'labels','stylers':[{'visibility':'off'}]}]"
			),
			'subtle-grayscale'   => array(
				esc_html__( "Subtle Grayscale", 'olympus' ),
				"[{'featureType':'landscape','stylers':[{'saturation':-100},{'lightness':65},{'visibility':'on'}]},{'featureType':'poi','stylers':[{'saturation':-100},{'lightness':51},{'visibility':'simplified'}]},{'featureType':'road.highway','stylers':[{'saturation':-100},{'visibility':'simplified'}]},{'featureType':'road.arterial','stylers':[{'saturation':-100},{'lightness':30},{'visibility':'on'}]},{'featureType':'road.local','stylers':[{'saturation':-100},{'lightness':40},{'visibility':'on'}]},{'featureType':'transit','stylers':[{'saturation':-100},{'visibility':'simplified'}]},{'featureType':'administrative.province','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':-25},{'saturation':-100}]},{'featureType':'water','elementType':'geometry','stylers':[{'hue':'#ffff00'},{'lightness':-25},{'saturation':-97}]}]"
			),
			'pale-dawn'          => array(
				esc_html__( "Pale Dawn", 'olympus' ),
				"[{'featureType':'water','stylers':[{'visibility':'on'},{'color':'#acbcc9'}]},{'featureType':'landscape','stylers':[{'color':'#f2e5d4'}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'color':'#c5c6c6'}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#e4d7c6'}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#fbfaf7'}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#c5dac6'}]},{'featureType':'administrative','stylers':[{'visibility':'on'},{'lightness':33}]},{'featureType':'road'},{'featureType':'poi.park','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':20}]},{},{'featureType':'road','stylers':[{'lightness':20}]}]"
			),
			'blue-water'         => array(
				esc_html__( "Blue water", 'olympus' ),
				"[{'featureType':'water','stylers':[{'color':'#46bcec'},{'visibility':'on'}]},{'featureType':'landscape','stylers':[{'color':'#f2f2f2'}]},{'featureType':'road','stylers':[{'saturation':-100},{'lightness':45}]},{'featureType':'road.highway','stylers':[{'visibility':'simplified'}]},{'featureType':'road.arterial','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'administrative','elementType':'labels.text.fill','stylers':[{'color':'#444444'}]},{'featureType':'transit','stylers':[{'visibility':'off'}]},{'featureType':'poi','stylers':[{'visibility':'off'}]}]"
			),
			'shades-of-grey'     => array(
				esc_html__( "Shades of Grey", 'olympus' ),
				"[{'featureType':'water','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':17}]},{'featureType':'landscape','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':20}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#000000'},{'lightness':17}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#000000'},{'lightness':29},{'weight':0.2}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':18}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':16}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':21}]},{'elementType':'labels.text.stroke','stylers':[{'visibility':'on'},{'color':'#000000'},{'lightness':16}]},{'elementType':'labels.text.fill','stylers':[{'saturation':36},{'color':'#000000'},{'lightness':40}]},{'elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'transit','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':19}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#000000'},{'lightness':20}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#000000'},{'lightness':17},{'weight':1.2}]}]"
			),
			'midnight-commander' => array(
				esc_html__( "Midnight Commander", 'olympus' ),
				"[{'featureType':'water','stylers':[{'color':'#021019'}]},{'featureType':'landscape','stylers':[{'color':'#08304b'}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#0c4152'},{'lightness':5}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#0b434f'},{'lightness':25}]},{'featureType':'road.arterial','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'road.arterial','elementType':'geometry.stroke','stylers':[{'color':'#0b3d51'},{'lightness':16}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#000000'}]},{'elementType':'labels.text.fill','stylers':[{'color':'#ffffff'}]},{'elementType':'labels.text.stroke','stylers':[{'color':'#000000'},{'lightness':13}]},{'featureType':'transit','stylers':[{'color':'#146474'}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#144b53'},{'lightness':14},{'weight':1.4}]}]"
			),
			'retro'              => array(
				esc_html__( "Retro", 'olympus' ),
				"[{'featureType':'administrative','stylers':[{'visibility':'off'}]},{'featureType':'poi','stylers':[{'visibility':'simplified'}]},{'featureType':'road','elementType':'labels','stylers':[{'visibility':'simplified'}]},{'featureType':'water','stylers':[{'visibility':'simplified'}]},{'featureType':'transit','stylers':[{'visibility':'simplified'}]},{'featureType':'landscape','stylers':[{'visibility':'simplified'}]},{'featureType':'road.highway','stylers':[{'visibility':'off'}]},{'featureType':'road.local','stylers':[{'visibility':'on'}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'visibility':'on'}]},{'featureType':'water','stylers':[{'color':'#84afa3'},{'lightness':52}]},{'stylers':[{'saturation':-17},{'gamma':0.36}]},{'featureType':'transit.line','elementType':'geometry','stylers':[{'color':'#3f518c'}]}]"
			),
			'light-monochrome'   => array(
				esc_html__( "Light Monochrome", 'olympus' ),
				"[{'featureType':'water','elementType':'all','stylers':[{'hue':'#e9ebed'},{'saturation':-78},{'lightness':67},{'visibility':'simplified'}]},{'featureType':'landscape','elementType':'all','stylers':[{'hue':'#ffffff'},{'saturation':-100},{'lightness':100},{'visibility':'simplified'}]},{'featureType':'road','elementType':'geometry','stylers':[{'hue':'#bbc0c4'},{'saturation':-93},{'lightness':31},{'visibility':'simplified'}]},{'featureType':'poi','elementType':'all','stylers':[{'hue':'#ffffff'},{'saturation':-100},{'lightness':100},{'visibility':'off'}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'hue':'#e9ebed'},{'saturation':-90},{'lightness':-8},{'visibility':'simplified'}]},{'featureType':'transit','elementType':'all','stylers':[{'hue':'#e9ebed'},{'saturation':10},{'lightness':69},{'visibility':'on'}]},{'featureType':'administrative.locality','elementType':'all','stylers':[{'hue':'#2c2e33'},{'saturation':7},{'lightness':19},{'visibility':'on'}]},{'featureType':'road','elementType':'labels','stylers':[{'hue':'#bbc0c4'},{'saturation':-93},{'lightness':31},{'visibility':'on'}]},{'featureType':'road.arterial','elementType':'labels','stylers':[{'hue':'#bbc0c4'},{'saturation':-93},{'lightness':-2},{'visibility':'simplified'}]}]"
			),
			'paper'              => array(
				esc_html__( "Paper", 'olympus' ),
				"[{'featureType':'administrative','stylers':[{'visibility':'off'}]},{'featureType':'poi','stylers':[{'visibility':'simplified'}]},{'featureType':'road','stylers':[{'visibility':'simplified'}]},{'featureType':'water','stylers':[{'visibility':'simplified'}]},{'featureType':'transit','stylers':[{'visibility':'simplified'}]},{'featureType':'landscape','stylers':[{'visibility':'simplified'}]},{'featureType':'road.highway','stylers':[{'visibility':'off'}]},{'featureType':'road.local','stylers':[{'visibility':'on'}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'visibility':'on'}]},{'featureType':'road.arterial','stylers':[{'visibility':'off'}]},{'featureType':'water','stylers':[{'color':'#5f94ff'},{'lightness':26},{'gamma':5.86}]},{},{'featureType':'road.highway','stylers':[{'weight':0.6},{'saturation':-85},{'lightness':61}]},{'featureType':'road'},{},{'featureType':'landscape','stylers':[{'hue':'#0066ff'},{'saturation':74},{'lightness':100}]}]"
			),
			'gowalla'            => array(
				esc_html__( "Gowalla", 'olympus' ),
				"[{'featureType':'road','elementType':'labels','stylers':[{'visibility':'simplified'},{'lightness':20}]},{'featureType':'administrative.land_parcel','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'landscape.man_made','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'transit','elementType':'all','stylers':[{'visibility':'off'}]},{'featureType':'road.local','elementType':'labels','stylers':[{'visibility':'simplified'}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'visibility':'simplified'}]},{'featureType':'road.highway','elementType':'labels','stylers':[{'visibility':'simplified'}]},{'featureType':'poi','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'road.arterial','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'all','stylers':[{'hue':'#a1cdfc'},{'saturation':30},{'lightness':49}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'hue':'#f49935'}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'hue':'#fad959'}]}]"
			),
			'greyscale'          => array(
				esc_html__( "Greyscale", 'olympus' ),
				"[{'featureType':'all','stylers':[{'saturation':-100},{'gamma':0.5}]}]"
			),
			'apple-maps-esque'   => array(
				esc_html__( "Apple Maps-esque", 'olympus' ),
				"[{'featureType':'water','elementType':'geometry','stylers':[{'color':'#a2daf2'}]},{'featureType':'landscape.man_made','elementType':'geometry','stylers':[{'color':'#f7f1df'}]},{'featureType':'landscape.natural','elementType':'geometry','stylers':[{'color':'#d0e3b4'}]},{'featureType':'landscape.natural.terrain','elementType':'geometry','stylers':[{'visibility':'off'}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#bde6ab'}]},{'featureType':'poi','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'poi.medical','elementType':'geometry','stylers':[{'color':'#fbd3da'}]},{'featureType':'poi.business','stylers':[{'visibility':'off'}]},{'featureType':'road','elementType':'geometry.stroke','stylers':[{'visibility':'off'}]},{'featureType':'road','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#ffe15f'}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#efd151'}]},{'featureType':'road.arterial','elementType':'geometry.fill','stylers':[{'color':'#ffffff'}]},{'featureType':'road.local','elementType':'geometry.fill','stylers':[{'color':'black'}]},{'featureType':'transit.station.airport','elementType':'geometry.fill','stylers':[{'color':'#cfb2db'}]}]"
			),
			'subtle'             => array(
				esc_html__( "Subtle", 'olympus' ),
				"[{'featureType':'poi','stylers':[{'visibility':'off'}]},{'stylers':[{'saturation':-70},{'lightness':37},{'gamma':1.15}]},{'elementType':'labels','stylers':[{'gamma':0.26},{'visibility':'off'}]},{'featureType':'road','stylers':[{'lightness':0},{'saturation':0},{'hue':'#ffffff'},{'gamma':0}]},{'featureType':'road','elementType':'labels.text.stroke','stylers':[{'visibility':'off'}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'lightness':20}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'lightness':50},{'saturation':0},{'hue':'#ffffff'}]},{'featureType':'administrative.province','stylers':[{'visibility':'on'},{'lightness':-50}]},{'featureType':'administrative.province','elementType':'labels.text.stroke','stylers':[{'visibility':'off'}]},{'featureType':'administrative.province','elementType':'labels.text','stylers':[{'lightness':20}]}]"
			),
			'neutral-blue'       => array(
				esc_html__( "Neutral Blue", 'olympus' ),
				"[{'featureType':'water','elementType':'geometry','stylers':[{'color':'#193341'}]},{'featureType':'landscape','elementType':'geometry','stylers':[{'color':'#2c5a71'}]},{'featureType':'road','elementType':'geometry','stylers':[{'color':'#29768a'},{'lightness':-37}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#406d80'}]},{'featureType':'transit','elementType':'geometry','stylers':[{'color':'#406d80'}]},{'elementType':'labels.text.stroke','stylers':[{'visibility':'on'},{'color':'#3e606f'},{'weight':2},{'gamma':0.84}]},{'elementType':'labels.text.fill','stylers':[{'color':'#ffffff'}]},{'featureType':'administrative','elementType':'geometry','stylers':[{'weight':0.6},{'color':'#1a3541'}]},{'elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#2c5a71'}]}]"
			),
			'flat-map'           => array(
				esc_html__( "Flat Map", 'olympus' ),
				"[{'stylers':[{'visibility':'off'}]},{'featureType':'road','stylers':[{'visibility':'on'},{'color':'#ffffff'}]},{'featureType':'road.arterial','stylers':[{'visibility':'on'},{'color':'#fee379'}]},{'featureType':'road.highway','stylers':[{'visibility':'on'},{'color':'#fee379'}]},{'featureType':'landscape','stylers':[{'visibility':'on'},{'color':'#f3f4f4'}]},{'featureType':'water','stylers':[{'visibility':'on'},{'color':'#7fc8ed'}]},{},{'featureType':'road','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'poi.park','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'color':'#83cead'}]},{'elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'landscape.man_made','elementType':'geometry','stylers':[{'weight':0.9},{'visibility':'off'}]}]"
			),
			'shift-worker'       => array(
				esc_html__( "Shift Worker", 'olympus' ),
				"[{'stylers':[{'saturation':-100},{'gamma':1}]},{'elementType':'labels.text.stroke','stylers':[{'visibility':'off'}]},{'featureType':'poi.business','elementType':'labels.text','stylers':[{'visibility':'off'}]},{'featureType':'poi.business','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'poi.place_of_worship','elementType':'labels.text','stylers':[{'visibility':'off'}]},{'featureType':'poi.place_of_worship','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'road','elementType':'geometry','stylers':[{'visibility':'simplified'}]},{'featureType':'water','stylers':[{'visibility':'on'},{'saturation':50},{'gamma':0},{'hue':'#50a5d1'}]},{'featureType':'administrative.neighborhood','elementType':'labels.text.fill','stylers':[{'color':'#333333'}]},{'featureType':'road.local','elementType':'labels.text','stylers':[{'weight':0.5},{'color':'#333333'}]},{'featureType':'transit.station','elementType':'labels.icon','stylers':[{'gamma':1},{'saturation':50}]}]"
			),
		);
	}

}

if ( ! function_exists( 'olympus_query_posts' ) ) {

	/**
	 * Generate
	 *
	 * @return WP_Query
	 */
	function olympus_query_posts() {
		//Get current params
		global $query_string;
		parse_str( $query_string, $args );

		//Fix for blog page tmp
		if ( isset( $args['pagename'] ) ) {
			unset( $args['pagename'] );
			$args['post_type'] = 'post';
		}

		$olympus = Olympus_Options::get_instance();

		$order    = $olympus->get_option_final( 'post_order', 'DESC', array( 'final-source' => 'customizer' ) );
		$orderby  = $olympus->get_option_final( 'post_order_by', 'date', array( 'final-source' => 'customizer' ) );
		$per_page = $olympus->get_option_final( 'posts_per_page', 12, array( 'final-source' => 'customizer' ) );

		$categories  = $olympus->get_option_final( 'categories', array(), array( 'final-source' => 'customizer' ) );
		$cat_exclude = $olympus->get_option_final( 'cat_exclude', false, array( 'final-source' => 'customizer' ) );

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$sticky_posts = get_option( 'sticky_posts' );

		// if we have any sticky posts and we are at the first page
		if ( is_array( $sticky_posts ) && ( 1 === $paged ) ) {
			// counnt the number of sticky posts
			$sticky_count = count( $sticky_posts );

			if ( is_numeric( $sticky_count ) && $per_page > $sticky_count ) {
				$per_page_tmp = $per_page - $sticky_count;

				if ( $per_page_tmp > 1 ) {
					$per_page = $per_page_tmp;
				}
			}
		}
		$args['posts_per_page'] = $per_page;
		$args['paged']          = $paged;
		$args['order']          = $order;
		$args['orderby']        = $orderby;
		$args['post_status']    = 'publish';

		if ( ! empty( $categories ) ) {

			if ( true === $cat_exclude ) {
				$operator = 'NOT IN';
			} else {
				$operator = 'IN';
			}
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $categories,
					'operator' => $operator,
				),
			);
		}

		query_posts( $args );
	}

}

if ( ! function_exists( 'olympus_luminance' ) ) {

	/**
	 * Luminance color
	 *
	 * @param string $hexcolor HEX color.
	 * @param string $percent  Darken or lightness step .
	 *
	 * @return string | bool
	 */
	function olympus_luminance( $hexcolor = '', $percent = '' ) {

		if ( ! preg_match( '/^\#[0-9a-z]{3,6}$/i', $hexcolor ) || ! is_numeric( $percent ) ) {
			return false;
		}

		if ( strlen( $hexcolor ) < 6 ) {
			$hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
		}
		$hexcolor = array_map( 'hexdec', str_split( str_pad( str_replace( '#', '', $hexcolor ), 6, '0' ), 2 ) );

		foreach ( $hexcolor as $i => $color ) {
			$from           = $percent < 0 ? 0 : $color;
			$to             = $percent < 0 ? $color : 255;
			$pvalue         = ceil( ( $to - $from ) * $percent );
			$hexcolor[ $i ] = str_pad( dechex( $color + $pvalue ), 2, '0', STR_PAD_LEFT );
		}

		return '#' . implode( $hexcolor );
	}

}

/**
 * Echo Sorting panel HTML.
 */
function olympus_post_sort_panel() {
	echo olympus_get_post_sort_panel();
}

/**
 * Echo data
 */
function olympus_render() {
	foreach ( func_get_args() as $arg ) {
		echo "{$arg}";
	}
}

/**
 * Find out if blog has more than one category.
 *
 * @return boolean true if blog has more than 1 category
 */
function olympus_theme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'olympus_theme_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );
		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'olympus_theme_category_count', $all_the_cool_cats );
	}
	if ( 1 !== (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so fw_theme_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so fw_theme_categorized_blog should return false
		return false;
	}
}

/**
 * From
 *
 * @return string number of theme version
 */
function olympus_get_theme_version() {
	$my_theme      = wp_get_theme();
	$theme_version = $my_theme->Version;
	if ( is_child_theme() ) {
		$my_theme      = $my_theme->parent();
		$theme_version = $my_theme->Version;
	}

	return $theme_version;
}

function olympys_string_short( $string, $length = 0, $postfix = '', $type = 's' ) {
	$string = str_replace( array( "\n", "\r", '<br>', '<br/>', '</p>' ), ' ', $string );
	$string = strip_tags( $string );

	if ( ! $length || mb_strlen( $string ) <= $length ) {
		return $string;
	}

	$length -= min( $length, mb_strlen( $postfix ) );

	switch ( strtolower( $type ) ) {

		case 's':
			$string = mb_substr( $string, 0, $length );
			preg_match( '/^(.+)([\.!?]+)(.*)$/u', $string, $matches );
			if ( ! empty( $matches[2] ) ) {
				$string = $matches[1] . $matches[2];
			}
			break;

		case 'w':
			$string = mb_substr( $string, 0, $length + 1 );
			preg_match( '/^(.*)([\W]+)(\w*)$/uU', $string, $matches );
			if ( ! empty( $matches[1] ) ) {
				$string = $matches[1];
			}
			break;

		default:
			$string = mb_substr( $string, 0, $length );
	}

	return $string . $postfix;
}

function olympus_empty_venue_address( $address = false ) {
	if ( ! is_string( $address ) ) {
		return 0;
	}

	return preg_match( '/\<span class\=\"tribe-address\"\>\s+\<\/span\>/', $address );
}

/**
 * Conditional tag to check if current page is displaying event query
 *
 * @return boolean
 */
function olympus_tribe_is_event_query() {
	if ( function_exists( 'tribe_is_event_query' ) ) {
		return tribe_is_event_query();
	} else {
		return false;
	}
}

/**
 * Get general background color
 *
 * @return string
 */
function olympus_general_body_bg_color() {
	$olympus = Olympus_Options::get_instance();

	$general_customize_design = $olympus->get_option_final( 'general-customize-design', array() );
	if ( olympus_akg( 'customize', $general_customize_design, 'no' ) === 'yes' ) {
		return olympus_akg( 'yes/general-customize-design-popup/general-body-bg-color', $general_customize_design, '' );
	} else {
		$color = get_background_color();

		return $color ? '#' . $color : '';
	}
}

/**
 * Get general background image
 *
 * @return array
 */
function olympus_general_body_bg_image() {
	$olympus = Olympus_Options::get_instance();

	$background = array(
		'background-image'      => '',
		'background-position'   => '',
		'background-size'       => '',
		'background-repeat'     => '',
		'background-attachment' => '',
	);

	$general_customize_design = $olympus->get_option_final( 'general-customize-design', array() );
	if ( olympus_akg( 'customize', $general_customize_design, 'no' ) === 'yes' ) {
		$background['background-image']      = olympus_akg( 'yes/general-customize-design-popup/general-body-bg-image/url', $general_customize_design, '' );
		$background['background-position']   = olympus_akg( 'yes/general-customize-design-popup/general-body-bg-position', $general_customize_design, '' );
		$background['background-size']       = olympus_akg( 'yes/general-customize-design-popup/general-body-bg-size', $general_customize_design, '' );
		$background['background-repeat']     = olympus_akg( 'yes/general-customize-design-popup/general-body-bg-repeat', $general_customize_design, '' );
		$background['background-attachment'] = olympus_akg( 'yes/general-customize-design-popup/general-body-bg-attachment', $general_customize_design, '' );
	} else {
		$background['background-image'] = get_background_image();
	}

	return $background;
}

/*
 * Inserts a new key/value after the key in the array.
 *
 * @param $key
 *   The key to insert after.
 * @param $array
 *   An array to insert in to.
 * @param $new_key
 *   The key to insert.
 * @param $new_value
 *   An value to insert.
 *
 * @return
 *   The new array if the key exists, FALSE otherwise.
 */

function olympus_array_insert_after( $key, array &$array, $new_key, $new_value ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach ( $array as $k => $value ) {
			$new[ $k ] = $value;
			if ( $k === $key ) {
				$new[ $new_key ] = $new_value;
			}
		}

		return $new;
	}

	return false;
}

/**
 * Get page welcome slug
 *
 * @return string
 */
function olympus_get_page_welcome_slug() {
	return 'olympus-about-page';
}

/**
 * Set redirect transition on update
 */
function olympus_page_welcome_set_redirect() {
	if ( ! is_network_admin() ) {
		set_transient( '_olympus_page_welcome_redirect', 1, DAY_IN_SECONDS );
	}
}

/**
 * Disable Unyson extension
 *
 * @param string $extension
 */
function olympus_disable_unyson_extension( $extension = '' ) {
	if ( ! $extension ) {
		return;
	}

	$extensions = get_option( 'fw_active_extensions' );

	if ( isset( $extensions[ $extension ] ) ) {
		unset( $extensions[ $extension ] );
		update_option( 'fw_active_extensions', $extensions );
	}
}

/**
 * Set youzer content width
 */
function olympus_set_youzer_content_width() {
	update_option( 'yz_plugin_content_width', 1300 );
}


/**
 * Check if left panel visible
 */
function olympus_is_left_panel_visible() {
	$olympus = Olympus_Options::get_instance();
	$visible = $olympus->get_option_final(
		'left-panel-fixed-options/show',
		'no',
		$atts = array(
			'final-source' => 'customizer'
		) );

	return ( $visible === 'yes' || ( $visible === 'yes_for_logged' && is_user_logged_in() ) ) ? true : false;
}

/**
 * Check if top menu panel visible
 */
function olympus_is_top_menu_panel_visible() {
	$olympus = Olympus_Options::get_instance();
	$visible = $olympus->get_option_final(
		'top-menu-panel-options/show',
		'yes',
		$atts = array(
			'final-source' => 'customizer'
		) );

	if ( $visible === 'yes' || ( $visible === 'yes_for_logged' && is_user_logged_in() ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if top user panel visible
 */
function olympus_is_top_user_panel_visible() {
	$olympus = Olympus_Options::get_instance();
	$visible = $olympus->get_option_final( 'top-user-panel-options/show', 'no',
		$atts = array(
			'final-source' => 'customizer'
		) );

	return ( $visible === 'yes' || ( $visible === 'yes_for_logged' && is_user_logged_in() ) ) ? true : false;
}


if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
if ( ! function_exists( 'olympus_folder_exist' ) ) {
	function olympus_folder_exist( $folder ) {
		// Get canonicalized absolute pathname
		$path = realpath( $folder );

		// If it exist, check if it's a directory
		return ( $path !== false AND is_dir( $path ) ) ? $path : false;
	}
}

/**
 * Array Merge.
 */
function olympus_array_merge( $array, $array2 ) {
	foreach ( $array2 as $k => $i ) {
		$array[ $k ] = $i;
	}

	return $array;
}

/**
 * Wordpress Hierarchical Menus.
 * Build a tree from a flat array in PHP
*/
function olympus_buildTree( array &$elements, $parentId = 0 )
{
    $branch = array();
    foreach ( $elements as &$element )
    {
        if ( $element['parent_id'] == $parentId )
        {
            $children = olympus_buildTree( $elements, $element['id'] );
            if ( $children )
            {
                $element['children'] = $children;
            }
            $branch[$element['id']] = $element;
            unset( $element );
        }
    }
    return $branch;
}
/**
 * Transform a navigational menu to it's tree structure
 *
 * @uses  olympus_buildTree()
 * @uses  wp_get_nav_menu_items()
 *
 * @param  String     $menud_id 
 * @return Array|null $tree 
 */
function olympus_wpse_nav_menu_2_tree( $menu_id )
{
    $items = wp_get_nav_menu_items( $menu_id );

    if( ! $items )
        return;
    
    $tmp = [];
	foreach( $items as $key => $item )
        $tmp[$item->ID] = [ 
            'id'        => $item->ID, 
            'parent_id' => $item->menu_item_parent, 
            'title'     => $item->title,
			'url'     => $item->url,
			'target' => $item->target,
        ];

    return olympus_buildTree( $tmp, 0 );
}

/**
 * Env Market api check
 *
 * @return bool
 */
if ( ! function_exists( 'olympus_env_api_check' ) ) {
	function olympus_env_api_check( $template_name = '' ){
		if($template_name == ''){
			$template_name = wp_get_theme(get_template())->get( 'Name' );
		}
		$res = false;
		if( function_exists( 'envato_market' ) ){
			$themes = envato_market()->api()->themes( 'purchased' );
			if(!empty($themes)){
				foreach($themes as $theme){
					if(isset($theme['name']) && strtolower($template_name) == strtolower($theme['name'])){
						$res = true;
					}
				}
			}
		}

		return $res;
	}
}

/**
 * Minify css
 * 
 * has to be inside wp_print_styles or wp_enqueue_scripts action
 *
 * @param $styles_array
 * Styles IDs array to minify.
 * @param $file_path_to_save
 * File path to save minify css.
 *
 */
if ( ! function_exists( 'olympus_minify_css' ) ){
	function olympus_minify_css( $styles_array, $file_path_to_save ){
		$minify_version = get_option('olympus_v_minify');
		if(!is_admin()){
			global $wp_filesystem;
			global $wp_styles;
			if (empty($wp_filesystem)) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
				WP_Filesystem();
			}

			$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'olympus-minify';
			if(!file_exists($uploads_dir)) wp_mkdir_p($uploads_dir);

			$files = $wp_styles->queue;

			if(!empty($files)){
				$css_string = '';
				foreach($files as $file){
					if( isset($wp_styles->registered[$file]->src) && in_array($file, $styles_array) ){
						if($minify_version != olympus_get_theme_version()){
							if( isset($wp_styles->registered[$file]->deps) && !empty($wp_styles->registered[$file]->deps) ){
								foreach($wp_styles->registered[$file]->deps as $dep){
									$dep_url = $wp_styles->registered[$dep]->src;
									$dep_cont = $wp_filesystem->get_contents($dep_url);
									$dep_css = str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$dep_cont)))));
									$css_string .= $dep_css;
								}
							}
							$file_url = $wp_styles->registered[$file]->src;
							$cont = $wp_filesystem->get_contents($file_url);
							$css = str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$cont)))));
							$css_string .= $css;
						}
						wp_dequeue_style($file);
					}
				}

				if($minify_version != olympus_get_theme_version()){
					if( $file_path_to_save != '' ){
						$wp_filesystem->put_contents( $uploads_dir . $file_path_to_save, $css_string, FS_CHMOD_FILE);
					}
				}
			}
			update_option('olympus_v_minify', olympus_get_theme_version());
		}
	}
}