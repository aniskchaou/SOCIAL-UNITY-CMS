<?php
/**
 * @var object $the_query
 * @var int $found_posts
 * @var string $query
 */
?>

<div class="search-help-result">
	<h3 class="search-help-result-title">
		<span class="count-result"><?php olympus_render( $found_posts, ' ', _n( 'user', 'users', $found_posts, 'crum-ext-extended-search' ) ); ?></span>
		<?php esc_html_e( 'found', 'crum-ext-extended-search' ); ?>
	</h3>
	<?php if ( !empty($the_query) ) { ?>
		<ul id="yz-members-list" class="yz-card-show-avatar-border yz-card-avatar-border-circle <?php echo yz_members_list_class() ?>" aria-live="assertive" aria-relevant="all">
			<?php foreach ( (array) $the_query as $member ) {
                $image_args	 = array(
                    'item_id' => $member->ID,
                    'width' => 100,
                    'height' => 100,
                    'type' => 'full'
                );
                $image = olympus_get_avatar( $image_args );
            ?>
                <li class="yz-show-cover">
                    <div class="yzm-user-data">
                        <?php yz_members_directory_user_cover( $member->ID ); ?>

                        <a href="<?php echo esc_url( bp_core_get_user_domain( $member->ID ) ); ?>" class="yz-item-avatar">
                            <div class="yz-member-avatar">
                                <?php olympus_render( $image ); ?>
                            </div>
                        </a>

                        <div class="item">
						    <div class="item-title">
                                <a class="yz-fullname" href="<?php echo esc_url( bp_core_get_user_domain( $member->ID ) ); ?>"><?php echo olympus_highlight_searched( $query, esc_html( $member->display_name ) ); ?></a>
                                <br />
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
		</ul>
		<?php
	} else {
		?>
		<div class="row">
			<?php get_template_part( 'templates/content/content-search-none' ); ?>
		</div>
		<?php
	}
	?>
</div>