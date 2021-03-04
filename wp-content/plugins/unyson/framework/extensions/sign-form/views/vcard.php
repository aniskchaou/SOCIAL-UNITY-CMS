<?php
/**
 * @var object $ext
 * @var string $vcard_title
 * @var string $vcard_subtitle
 * @var string $vcard_profile_btn
 */
$user_ID = get_current_user_id();

if ( !$user_ID ) {
	return;
}

$use_buddypress = $ext->useBuddyPress();

$author_name = wp_get_current_user()->display_name;

if ( $use_buddypress ) {
	$author_url			 = bp_core_get_user_domain( $user_ID );
	$author_cover_image	 = bp_attachments_get_attachment( 'url', array(
		'object_dir' => 'members',
		'item_id'	 => $user_ID,
			) );
} else {
	$author_url			 = get_author_posts_url( $user_ID );
	$author_cover_image	 = '';
}

$author_cover_image = $author_cover_image ? "background-image: url({$author_cover_image})" : '';
?>

<div class="ui-block user-welcomeback">
    <div class="featured-background" style="<?php echo esc_attr( $author_cover_image ); ?>"></div>
    <div class="user-active">
        <a href="<?php echo esc_url( $author_url ); ?>" class="author-thumb">
			<?php echo get_avatar( $user_ID, 90 ); ?>
        </a>
        <div class="author-content">
			<?php if ( $vcard_title ) { ?>
				<?php 
					echo do_shortcode($vcard_title);
				?>
			<?php } else { ?>
				<?php esc_html_e( 'Welcome Back', 'crum-ext-sign-form' ); ?>
				<a href="<?php echo esc_url( $author_url ); ?>" class="author-name"><?php echo $author_name ; ?></a>!
			<?php } ?>

        </div>
    </div>
	<?php if ( $use_buddypress ) { ?>
		<div class="you-can-do">
			<?php
			if ( $vcard_subtitle ) {
				echo do_shortcode($vcard_subtitle);
			} else {
				esc_html_e( 'here\'s what you can do!', 'crum-ext-sign-form' );
			}
			?>
		</div>

		<?php
		$menu_items	 = array();
		$menu_name	 = $ext->get_config( 'menuLocation' );
		$locations	 = get_nav_menu_locations();

		if ( $locations && isset( $locations[ $menu_name ] ) ) {
			$menu		 = wp_get_nav_menu_object( $locations[ $menu_name ] );
			$menu_items	 = wp_get_nav_menu_items( $menu );
		}
		?>

		<div class="links">
			<?php
			if ( !empty( $menu_items ) ) {
				foreach ( $menu_items as $idx => $item ) {

					$meta	 = fw_ext_mega_menu_get_meta( $item->ID, "icon" );
					$icon	 = $ext::prepareMmIconPrm( $meta );

					if ( $icon[ 'type' ] === 'custom-upload' && !empty( $icon[ 'url' ] ) ) {
						$file_parts = pathinfo( $icon[ 'url' ] );
						if ( 'svg' === $file_parts[ 'extension' ] ) {
							$data_icon = $ext::embedCustomSvg( $icon[ 'url' ], 'link-item-icon' );
						} else {
							$data_icon = '<img class="link-item-icon" src="' . esc_attr( $icon[ 'url' ] ) . '" alt="' . esc_attr( $item->title ) . '" />';
						}
						$menu_items[ $idx ]->icon = $data_icon;
					} elseif ( $icon[ 'type' ] === 'icon-font' && !empty( $icon[ 'icon-class' ] ) ) {
						$menu_items[ $idx ]->icon = '<i class="link-item-icon ' . esc_attr( $icon[ 'icon-class' ] ) . '"></i>';
					} else {
						$menu_items[ $idx ]->icon = '<i class="link-item-icon fa fa-star"></i>';
					}
					?>

					<a class="link-item" href="<?php echo esc_attr( $item->url ); ?>">
						<?php echo $item->icon; ?>
						<div class="title"><?php echo esc_html( $item->title ); ?></div>
						<div class="sup-title"><?php echo $item->description; ?></div>
					</a>
					<?php
				}
			} else {
				echo $ext->get_view( 'default-links', array(
					'ext' => $ext,
				) );
			}
			?>
		</div>
	<?php } ?>
    <div class="ui-block-content">
        <a href="<?php echo esc_url( $author_url ); ?>" class="btn btn-lg btn-primary mb-0 full-width">
			<?php
			if ( $vcard_profile_btn ) {
				echo $vcard_profile_btn;
			} else {
				esc_html_e( 'Go to your Profile Page', 'crum-ext-sign-form' );
			}
			?>
        </a>
    </div>
</div>