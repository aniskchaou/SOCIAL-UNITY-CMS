<?php
// Plugins extend.
if ( class_exists( 'WooCommerce' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/woocommerce.php' );
}
if ( class_exists( 'KingComposer' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/kingcomposer.php' );
}
if ( class_exists( 'Jetpack' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/jetpack.php' );
}
if ( class_exists( 'BuddyPress' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/buddypress.php' );
}
if ( class_exists( 'Tribe__Events__Main' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/the-events-calendar.php' );
}
if ( class_exists( 'Vc_Manager' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/js_composer.php' );
}
if (  did_action( 'elementor/loaded' ) ) {
	Olympus_Core::include_parent( 'inc/plugins-extend/elementor.php' );
}
if ( class_exists( 'RTMedia' ) && class_exists( 'BuddyPress' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/buddypress-media.php' );
}
if ( class_exists( 'LS_Sliders' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/layerslider.php' );
}
if ( class_exists( 'Youzer' ) ) {
	Olympus_Core::include_child_first( '/inc/plugins-extend/youzer.php' );
}
if ( class_exists( 'WordPressPopularPosts' ) ) {
    Olympus_Core::include_parent( '/inc/plugins-extend/wordpress-popular-posts.php' );
}
