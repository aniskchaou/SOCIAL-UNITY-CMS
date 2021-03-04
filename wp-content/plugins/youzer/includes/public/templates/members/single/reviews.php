<div id="yz-main-reviews" class="yz-tab yz-tab-reviews">
<?php

$user_id = bp_displayed_user_id();

$args = array(
	'pagination' => true,
	'user_id' => $user_id,
);


// Get Reviews.
echo yz_get_user_reviews( $args );

// Get Loading Spinner.
yz_loading();

?>

</div>
