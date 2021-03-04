<?php
/**
 * Wall Feeling / Activity.
 */
class Youzer_Mood {

	function __construct( ) {

		// Add Tool
		add_action( 'bp_activity_after_post_form_tools', array( $this, 'tool' ) );
		add_action( 'yz_after_wall_post_form_textarea', array( $this, 'search_box' ) );

		// Handle Save Form Post - Ajax Request.
		add_action( 'wp_ajax_yz_feeling_activity_get_categories', array( $this, 'get_categories_list' ) );

		// Add Posts Mood Action.
		add_filter( 'yz_activity_post_mood', array( $this, 'action' ), 10, 2 );

	}
	
	/**
	 * Mood Action.
	 */
	function action( $action, $activity ) {
		// Get Post Mood
		$mood = bp_activity_get_meta( $activity->id, 'mood' );

		if ( empty( $mood ) ) {
			return $mood;	
		}

		$moods = yz_wall_mood_categories();

		if ( isset( $moods[ $mood['type'] ] ) ) {
			$data = $moods[ $mood['type'] ];
			
			$mood_title = yz_get_mood_feeling_emojis();

			// Get Mood Icon.
			$icon = '<i class="' . $data['icon'] . '" style="background-color:' . $data['color'] . ';"></i>';

			if ( $mood['type'] == 'feeling' ) {
				$icon = '<img class="yz-mood-feeling-image" src="' . yz_get_mood_emojis_image( $mood['value'] ) . '" alt="">';
			}

			$mood_value = isset( $mood_title[ $mood['value'] ] ) ?  $mood_title[ $mood['value'] ] : $mood['value'];
			$action .= ' ' . sprintf( '<span class="yz-wall-mood">%1s %2s %3s<span>', $icon, $data['title'], $mood_value );
		}

		return $action;
	}

	/**
	 * Add Feeling/Activity Tool.
	 */
	function tool() {	
		if ( apply_filters( 'yz_enable_activity_form_mood', true ) ) { ?>
		<div class="yz-user-mood-tool yz-form-tool" data-yztooltip="<?php _e( 'Feeling / Activity', 'youzer' ); ?>"><i class="far fa-smile"></i></div>
		<?php }
	}

	/**
	 * Search Box.
	 */
	function search_box() { ?>

		<div class="yz-wall-list yz-wall-feeling">

			<div class="yz-list-selected-items yz-feeling-selected-items">
				<input type="hidden" name="mood_type" value="">
				<div class="yz-list-items-title yz-feeling-title"></div>
			</div>

			<div class="yz-list-search-form yz-feeling-form">
				<div class="yz-list-search-box yz-feeling-search-box">
					<div class="yz-list-search-container">
						<div class="yz-list-search-icon yz-feeling-search-icon"><i class="fas fa-search"></i></div>
						<input type="text" class="yz-list-search-input yz-feeling-search-input" name="mood_search" placeholder="<?php _e( 'Choose a feeling or activity !', 'youzer' ); ?>" >
						<div class="yz-list-submit-button yz-feeling-submit-button"><?php _e( 'Enter', 'youzer' ); ?></div>
						<div class="yz-list-close-icon yz-feeling-close-icon"><i class="fas fa-times"></i></div>
					</div>
				</div>
				<div class="yz-wall-list-items yz-wall-feeling-list"></div>
			</div>

		</div>

		<?php
	
	}
	
	/**
	 * Get User Friends.
	 */
	function get_categories_list() {

		// Get Current User Friends.
		$categories = yz_wall_mood_categories();
		
		ob_start();

		if ( empty( $categories ) ) { ?>
			<div class="yz-list-notice"><i class="fas fa-times"></i><?php _e( 'No categories found !', 'youzer' ); ?></div>
		<?php } else {

			echo '<div class="yz-list-categories">';

			foreach ( $categories as $cat_name => $category ) { ?>

				<div class="yz-list-item yz-feeling-item yz-category-<?php echo $cat_name; ?>" data-category="<?php echo $cat_name; ?>" data-category-title="<?php echo $category['title']; ?>">
					<div class="yz-item-icon"><i class="<?php echo $category['icon']; ?>"></i></div>
					<div class="yz-item-content">
						<div class="yz-item-left">
							<div href=">" class="yz-item-title"><?php echo $category['title']; ?></div>
							<div class="yz-item-description"><?php echo $category['question']; ?></div>
						</div>
						<div class="yz-item-right">
							<div class="yz-item-button yz-feeling-button"><i class="fas fa-chevron-right"></i></div>
						</div>
					</div>
				</div>

			<?php } ?>
						
			</div>
			
			<div class="yz-list-category-items" data-category="feeling">
				<?php $feeling_emojis = yz_get_mood_feeling_emojis(); foreach ( $feeling_emojis as $name => $title ) : ?>
					<div class="yz-list-item yz-feeling-item yz-feeling-emoji-<?php echo $name; ?>" data-emoji="<?php echo $name; ?>" data-category-title="<?php echo $title; ?>">
						<div class="yz-item-img" style="background-image: url(<?php echo yz_get_mood_emojis_image( $name ); ?>"></div>
						<div class="yz-item-content">
							<div class="yz-item-left">
								<div href=">" class="yz-item-title"><?php echo $title; ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php do_action( 'yz_mood_categories_items_list' ); ?>

		<?php }

		$content = ob_get_clean();

		wp_send_json_success( $content );

		die();
	}

}

$mood = new Youzer_Mood();
