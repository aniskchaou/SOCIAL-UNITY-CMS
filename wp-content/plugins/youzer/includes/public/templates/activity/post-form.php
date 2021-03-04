<?php

/**
 * BuddyPress - Activity Post Form
 */

do_action( 'bp_activity_before_post_form' );

if ( yz_is_wall_posting_form_active() ) :

?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="yz-wall-form" class="yz-wall-form" name="whats-new-form" enctype="multipart/form-data">

	<div class="yz-wall-options"><?php do_action( 'yz_activity_form_post_types' );  ?></div>

	<div id="whats-new-content" class="yz-wall-content">

		<div class="yz-wall-author" href="<?php echo bp_loggedin_user_domain(); ?>"><?php bp_loggedin_user_avatar(); ?></div>

			<textarea name="status" class="yz-wall-textarea bp-suggestions" id="whats-new" placeholder="<?php if ( bp_is_group() )
		printf( __( "What's new in %s, %s?", 'youzer' ), bp_get_group_name(), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	else
		printf( __( "What's new, %s?", 'youzer' ), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	?>" <?php if ( bp_is_group() ) : ?> data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
			><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo sanitize_textarea_field( $_GET['r'] ); ?> <?php endif; ?></textarea>


		<?php if ( 'on' == yz_option( 'yz_enable_posts_emoji', 'on' ) ) : ?><div class="yz-load-emojis yz-load-posts-emojis"><i class="far fa-smile"></i></div><?php endif; ?>

			<!--  -->
		<?php if ( 'on' == yz_option( 'yz_enable_wall_link', 'on' ) ) : ?>
		<div class="yz-wall-custom-form yz-wall-link-form" data-post-type="activity_link">

			<div class="yz-wall-cf-item">
				<input type="text" class="yz-wall-cf-input" name="link_url" placeholder="<?php _e( 'Add Link URL', 'youzer' ); ?>">
			</div>

			<div class="yz-wall-cf-item">
				<input type="text" class="yz-wall-cf-input" name="link_title" placeholder="<?php _e( 'Add Link Title', 'youzer' ); ?>">
			</div>

			<div class="yz-wall-cf-item">
				<textarea name="link_desc" class="yz-wall-cf-input" placeholder="<?php _e( 'Brief Link Description', 'youzer' ); ?>"></textarea>
			</div>

		</div>
		<?php endif; ?>

		<?php if ( 'on' == yz_option( 'yz_enable_wall_url_preview', 'on' ) ) : ?>
			<div class="yz-lp-prepost" data-loaded="false">
				<div class="lp-prepost-container">

			    <button class="lp-button-cancel"type="button"><i class="fas fa-times"></i></button>

			    <div class="lp-preview-image">

			        <span class="lp-preview-video-icon"><i class="fas fa-play lp-play"></i></span>
			    </div>

			    <div class="lp-prepost-wrap">

			        <div class="lp-preview-title-wrap"><span class="lp-preview-title">{{preview.title}}</span></div>

			        <div class="lp-preview-replace-title-wrap"></div>

			        <div class="lp-preview-canonical-url">{{preview.site}}</div>

			        <div class="lp-preview-description-wrap">
			            <div class="lp-preview-description">{{preview.description}}</div>
			        </div>

			        <div class="lp-preview-replace-description-wrap"></div>

			        <div class="clearfix lp-preview-pagination">

			            <span class="lp-preview-thubmnail-buttons">
			                <div class="yz-lp-previous-image"><i class="fas fa-caret-left"></i></div>
			                <div class="yz-lp-next-image"><i class="fas fa-caret-right"></i></div>
			            </span>

			            <span class="lp-preview-thubmnail-pagination">{{thumbnailPaginationText}}</span><span class="lp-pagination-of"><?php _e( 'of', 'youzer' ); ?></span><span class="lp-preview-thubmnail-text">{{thumbnailText}}</span>

			        </div>

			        <div class="lp-preview-no-thubmnail">
			            <label class="lp-preview-no-thubmnail-text">
			                <input name="url_preview_use_thumbnail" type="checkbox" class="yz-lp-use-thumbnail">
			                <span><?php _e( 'No thumbnail', 'youzer' ) ?></span>
			            </label>
			        </div>

			    </div>

				</div>

				<div class="clearfix lp-button lp-loading-text"><i class="fas fa-spinner fa-spin"></i></div>

			</div>

    	<?php endif; ?>

		<?php if ( 'on' == yz_option( 'yz_enable_wall_quote', 'on' ) ) : ?>
		<div class="yz-wall-custom-form yz-wall-quote-form" data-post-type="activity_quote">

			<div class="yz-wall-cf-item">
				<input type="text" class="yz-wall-cf-input" name="quote_owner" placeholder="<?php _e( 'Add Quote Owner', 'youzer' ); ?>">
			</div>

			<div class="yz-wall-cf-item">
				<textarea name="quote_text" class="yz-wall-cf-input" placeholder="<?php _e( 'Add Quote Text', 'youzer' ); ?>"></textarea>
			</div>

		</div>
		<?php endif; ?>

		<?php if ( 'on' == yz_option( 'yz_enable_wall_giphy', 'on' ) ) : ?>
		<div class="yz-wall-custom-form yz-wall-giphy-form" data-post-type="activity_giphy">

			<div class="yz-giphy-loading-preview"><i class="fas fa-spin fa-spinner"></i></div>

			<div class="yz-selected-giphy-item">
				<input type="hidden" name="giphy_image">
				<i class="fas fa-trash yz-delete-giphy-item"></i>
			</div>

			<div class="yz-wall-cf-item">
				<div class="yz-giphy-search-form">
					<input type="text" class="yz-wall-cf-input yz-giphy-search-input" name="giphy_search" placeholder="<?php _e( 'Search for GIFs...', 'youzer' ); ?>">
				</div>
				<i class="fas fa-spin fa-spinner yz-cf-input-loader"></i>
				<div class="yz-giphy-items-content">
					<div class="yz-load-more-giphys" data-page="2"><i class="fas fa-ellipsis-h"></i></div>
					<div class="yz-no-gifs-found"><i class="far fa-frown"></i><?php _e( 'No GIFs found', 'youzer' ); ?></div>
				</div>
			</div>

		</div>
		<?php endif; ?>

		<?php do_action( 'yz_after_wall_post_form_textarea' ); ?>

	</div>

	<div class="yz-wall-actions" id="yz-wall-actions">

		<?php do_action( 'yz_before_wall_post_form_actions' ); ?>

		<div class="yz-form-tools">

			<?php do_action( 'bp_activity_post_form_tools' ); ?>

			<?php do_action( 'bp_activity_before_post_form_tools' ); ?>

			<?php if ( apply_filters( 'yz_allow_wall_upload_attachments', true ) ) : ?>
				<div class="yz-wall-upload-btn yz-form-tool" data-yztooltip="<?php _e( 'Upload Attachment', 'youzer' ); ?>"><i class="fas fa-paperclip"></i></div>
			<?php endif; ?>

			<?php do_action( 'bp_activity_after_post_form_tools' ); ?>

		</div>
		<div class="yz-posting-form-actions">

			<?php if ( bp_is_active( 'groups' ) && ! bp_is_my_profile() && ! bp_is_group() ) : ?>

				<div id="whats-new-post-in-box">

					<label for="whats-new-post-in" ><?php _e( 'Post in:', 'youzer' ); ?></label>
					<select id="whats-new-post-in" name="whats-new-post-in">
						<option selected="selected" value="0"><?php _e( 'My Profile', 'youzer' ); ?></option>

						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
							while ( bp_groups() ) : bp_the_group(); ?>

								<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

							<?php endwhile;
						endif; ?>

					</select>
				</div>
				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups">

			<?php elseif ( bp_is_group_activity() ) : ?>

				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups">
				<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>">

			<?php endif; ?>

			<?php do_action( 'yz_wall_before_submit_form_action' ); ?>

			<button type="submit" name="aw-whats-new-submit" class="yz-wall-post" ><?php esc_attr_e( 'Post', 'youzer' ); ?></button>

			<?php do_action( 'yz_wall_after_submit_form_action' ); ?>

		</div>

			<?php

			/**
			 * Fires at the end of the activity post form markup.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_post_form_options' ); ?>

	</div>

	<?php do_action( 'bp_activity_post_form_after_actions' ) ?>

	<div class="yz-wall-attachments">
		<input hidden="true" class="yz-upload-attachments" type="file" name="attachments[]" multiple>
		<div class="yz-form-attachments"></div>
	</div>

	<?php wp_nonce_field( 'yz_post_update', '_yz_wpnonce_post_update' ); ?>

	<?php

	/**
	 * Fires after the activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_post_form' ); ?>

</form>

<?php endif; ?>