<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}
/**
 * @var string $title
 * @var string $widget_id
 * @var string $before_widget
 * @var string $after_widget
 */
olympus_render( $before_widget );
olympus_render( $title );
?>


	<div class="widget w-about">

		<a href="<?php echo esc_attr( $link ? $link : '#' ); ?>" class="logo">
			<?php if ( $logo ) { ?>
				<div class="img-wrap">
                    <img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( $title ? $title : esc_html__( 'Company', 'olympus' ) ); ?>">
				</div>
			<?php } ?>
			<div class="title-block">
				<?php if ( $logo_title ) { ?>
					<h6 class="logo-title"><?php echo esc_html( $logo_title ); ?></h6>
				<?php } ?>
				<?php if ( $logo_subtitle ) { ?>
					<div class="sub-title"><?php echo esc_html( $logo_subtitle ); ?></div>
				<?php } ?>
			</div>
		</a>
		<p><?php olympus_render( $description ); ?></p>

		<?php
		if ( ! empty( $links ) ) {
			?>
			<ul class="socials">
				<?php
				foreach ( $links as $link ) {
					$url  = isset( $link['link'] ) ? $link['link'] : '';
					$type = isset( $link['icon'] ) ? $link['icon'] : '';

					if ( ! $url || ! $type ) {
						continue;
					}
					?>

					<li>
						<a href="<?php echo esc_attr( $url ); ?>" class="<?php echo esc_attr( $type ); ?>">
							<?php get_template_part( "/templates/socials/{$type}" ); ?>
						</a>
					</li>

					<?php
				}
				?>
			</ul>
			<?php
		}
		?>
	</div>


<?php
olympus_render( $after_widget );
