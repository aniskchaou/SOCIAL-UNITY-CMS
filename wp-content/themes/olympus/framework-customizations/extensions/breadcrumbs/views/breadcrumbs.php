<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
} ?>
<?php if ( ! empty( $items ) ) : ?>
    <ul class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
		<?php for ( $i = 0; $i < count( $items ); $i ++ ) : ?>
			<?php if ( $i == ( count( $items ) - 1 ) ) : ?>
			<?php elseif ( $i == 0 ) : ?>
                <li class="breadcrumbs-item first-item" itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
				<?php if ( isset( $items[ $i ]['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $items[ $i ]['url'] ) ?>" itemprop="item"><span itemprop="name">
                            <?php echo esc_html( $items[ $i ]['name'] ) ?>
                        </span></a>
                    <meta itemprop="position" content="<?php echo esc_attr( $i ) ?>"/>
                    </li>
				<?php else : echo esc_html( $items[ $i ]['name'] ); endif ?>
			<?php
			else : ?>
				<?php olympus_render( $separator ) ?>
                <li class="breadcrumbs-item <?php olympus_render( $i - 1 ) ?>-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<?php if ( isset( $items[ $i ]['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $items[ $i ]['url'] ) ?>" itemprop="item">
                            <span itemprop="name"><?php echo esc_html( $items[ $i ]['name'] ) ?></span>
                        </a>
                        <meta itemprop="position" content="<?php echo esc_attr( $i ) ?>"/>
					<?php else : echo esc_html( $items[ $i ]['name'] ); endif ?>
                </li>
			<?php endif ?>
		<?php endfor ?>
    </ul>
<?php endif ?>