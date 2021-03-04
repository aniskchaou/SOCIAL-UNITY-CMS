<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<?php wp_head(); ?>
</head>
	<body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">

	<?php wp_body_open(); ?>
	<div id="overflow-x-wrapper"  x-data="olympusModal()" @keydown.escape="modalClose()">
<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
            /**
         * Hook: crumina_body_start.
         *
         * @hooked _action_olympus_tracking_scripts - 10
         * @hooked _action_olympus_add_left_panel - 10
         * @hooked _action_olympus_add_header - 10
         */
        do_action( 'crumina_body_start' );
	?>

<div id="content" class="site-content hfeed site">
<?php } ?>