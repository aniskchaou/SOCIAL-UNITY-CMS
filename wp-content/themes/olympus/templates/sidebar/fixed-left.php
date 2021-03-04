<?php
if ( ! olympus_is_left_panel_visible() ) {
	return;
}

$olympus         = Olympus_Options::get_instance();
$left_panel_icon = $olympus->get_option( 'left-panel-options-icon', '', $olympus::SOURCE_CUSTOMIZER );

if ( is_array( $left_panel_icon ) && $left_panel_icon['type'] !== 'none' ) {
	$open_menu_icon = olympus_generate_icon_html( $left_panel_icon, 'universal-olympus-icon' );
} else {
	$open_menu_icon = '<i class="olympus-icon-Menu-Icon"></i>';
}

$menu_items = array();
$sortedItems = array();
$menu_name  = 'fixed-left';
$locations  = get_nav_menu_locations();

if ( $locations && isset( $locations[ $menu_name ] ) ) {
	$menu       = wp_get_nav_menu_object( $locations[ $menu_name ] );
	$menu_items = wp_get_nav_menu_items( $menu );
	$sortedItems = olympus_wpse_nav_menu_2_tree($menu);
}

?>
<div id="fixed-sidebar-left"
	 class="fixed-sidebar left"
	 x-data="{ sidebarOpen: false }"
	 x-bind:class="{ 'menu-open': sidebarOpen }"
	 x-on:keydown.escape="sidebarOpen = false"
	 x-on:click.away="sidebarOpen = false"
>
	<div
		class="side-menu-open js-sidebar-open"
		@click="sidebarOpen = ! sidebarOpen"
		x-bind:class="{ 'active': sidebarOpen }">
		<span class="olymp-menu-icon" data-toggle="tooltip" data-placement="right" data-original-title="<?php esc_attr_e( 'Open menu', 'olympus' ); ?>">
			<?php olympus_render( $open_menu_icon ); ?>
		</span>

		<i class="olymp-close-icon olympus-icon-Close-Icon"
		   data-toggle="tooltip" data-placement="right"
		   data-original-title="<?php esc_attr_e( 'Close menu', 'olympus' ); ?>"></i>
	</div>

	<div class="fixed-sidebar-left sidebar--small" id="sidebar-left" x-bind:class="{ 'menu-open': sidebarOpen }">
		<div class="mCustomScrollbar" data-mcs-theme="dark">
			<ul class="left-menu">
				<?php
				if ( ! empty( $sortedItems ) ) {
					foreach ( $sortedItems as $idx => $item ) {
						$meta  = fw_ext_mega_menu_get_meta( $item['id'], "icon" );
						$icon  = olympus_generate_icon_html( $meta, 'universal-olympus-icon' );
						?>
						<li>
							<a <?php if($item['target'] == '_blank'){echo 'target="_blank"';} ?> href="<?php echo esc_attr( $item['url'] ); ?>">
								<span data-toggle="tooltip" data-placement="right" data-original-title="<?php echo esc_attr( $item['title'] ); ?>">
									<?php olympus_render( $icon ); ?>
								</span>
							</a>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
	</div>

	<div class="fixed-sidebar-left sidebar--large" id="sidebar-left-1">
		<div class="mCustomScrollbar" data-mcs-theme="dark">
			<ul class="left-menu">
				<?php
				if ( ! empty( $sortedItems ) ) {
					foreach ( $sortedItems as $item ) {
						$meta  = fw_ext_mega_menu_get_meta( $item['id'], "icon" );
						$icon  = olympus_generate_icon_html( $meta, 'universal-olympus-icon' );
						?>
						<li>
							<a <?php if($item['target'] == '_blank'){echo 'target="_blank"';} ?> href="<?php echo esc_attr( $item['url'] ); ?>">
								<?php olympus_render( $icon ); ?>
								<span class="left-menu-title"><?php echo esc_html( $item['title'] ); ?></span>
							</a>
							<?php if(isset($item['children'])){ ?>
							<ul class="sub-menu">
								<?php foreach ( $item['children'] as $child ) {
								$meta_child  = fw_ext_mega_menu_get_meta( $child['id'], "icon" );
								$icon_child  = olympus_generate_icon_html( $meta_child, 'universal-olympus-icon' );	
								?>
								<li>
									<a <?php if($child['target'] == '_blank'){echo 'target="_blank"';} ?> href="<?php echo esc_attr( $child['url'] ); ?>">
										<?php olympus_render( $icon_child ); ?>
										<span class="left-menu-title"><?php echo esc_html( $child['title'] ); ?></span>
									</a>
								</li>
								<?php } ?>
							</ul>
							<?php } ?>
						</li>
						<?php
					}
				} else {
					olympus_menu_fallback( esc_html__( 'Left Menu Panel', 'olympus' ) );
				}
				?>
			</ul>
		</div>
	</div>
</div>
