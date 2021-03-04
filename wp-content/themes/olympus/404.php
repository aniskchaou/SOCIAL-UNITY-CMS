<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package olympus
 */

get_header();

$olympus = Olympus_Options::get_instance();
$olympus_not_found_page_image = $olympus->get_option( 'not_found_page_image', '', $olympus::SOURCE_CUSTOMIZER );
$olympus_not_found_page_title = $olympus->get_option( 'not_found_page_title', esc_html__('A wild ghost appears! Sadly, not what you were looking for...', 'olympus' ), $olympus::SOURCE_CUSTOMIZER );
$olympus_not_found_page_text = $olympus->get_option( 'not_found_page_text', esc_html__('Sorry! The page you were looking for has been moved or doesn\'t exist. If you like, you can return to our homepage, or try a search?', 'olympus' ), $olympus::SOURCE_CUSTOMIZER );
$olympus_not_found_page_button_link = $olympus->get_option( 'not_found_page_button_link', esc_url(home_url()), $olympus::SOURCE_CUSTOMIZER );
$olympus_not_found_page_button_text = $olympus->get_option( 'not_found_page_button_text', esc_html__( 'Go to Homepage', 'olympus' ), $olympus::SOURCE_CUSTOMIZER );
?>
    <section class="padding40">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 m-auto col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="page-404-content">
                        <?php if( empty($olympus_not_found_page_image) ){ ?>
						    <img loading="lazy" src="<?php echo esc_url( get_template_directory_uri() ) ?>/images/404.png" width="636" height="304" alt="<?php esc_attr_e( 'Not found', 'olympus' ); ?>">
                        <?php }else{
                            echo wp_get_attachment_image($olympus_not_found_page_image['attachment_id'], 'full');
                        } ?>
                        <div class="crumina-module crumina-heading align-center">
                            <h2 class="h1 heading-title"><?php echo esc_html($olympus_not_found_page_title); ?></h2>
                            <p class="heading-text">
	                            <?php echo esc_html($olympus_not_found_page_text); ?>
                            </p>
                        </div>

	                    <?php get_search_form(); ?>

                        <div class="padding40">
                            <a href="<?php echo esc_url($olympus_not_found_page_button_link); ?>" class="btn btn-primary btn-lg"><?php echo esc_html($olympus_not_found_page_button_text); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
get_footer();