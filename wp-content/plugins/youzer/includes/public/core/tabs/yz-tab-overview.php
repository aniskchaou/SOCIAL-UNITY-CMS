<?php

class YZ_Overview_Tab {

	/**
	 * Tab Core
	 */
	function tab() {

		// Get Overview Widgets
		$profile_widgets = apply_filters(
			'yz_profile_main_widgets',
			yz_option(
				'yz_profile_main_widgets', array(
	            'slideshow'  => 'visible',
	            'project'    => 'visible',
	            'skills'     => 'visible',
	            'portfolio'  => 'visible',
	            'quote'      => 'visible',
	            'instagram'  => 'visible',
	            'services'   => 'visible',
	            'post'       => 'visible',
	            'link'       => 'visible',
	            'video'      => 'visible',
	            'reviews'    => 'visible',
	        	)
			)
		);

		// Get Tab Content.
		echo '<div class="yz-tab yz-overview">';
		yz_widgets()->get_widget_content( $profile_widgets );
		echo '</div>';

	}

}