<?php
/**
 * Related posts function.
 *
 * @param string $taxonomy_1
 * @param string $taxonomy_2
 * @param int    $total_posts
 *
 * @return bool|WP_Query
 */

function olympus_get_related_posts( $taxonomy_1 = 'post_tag', $taxonomy_2 = 'category', $total_posts = 4 )
{
	// First, make sure we are on a single page, if not, bail
	if ( !is_single() )
		return false;

	if ( 'category' !== $taxonomy_2 ) {
		$taxonomy_2 = filter_var( $taxonomy_2, FILTER_SANITIZE_STRING );
		if ( !taxonomy_exists( $taxonomy_2 ) )
			return false;
	}

	if ( 4 !== $total_posts ) {
		$total_posts = filter_var( $total_posts, FILTER_VALIDATE_INT );
		if ( !$total_posts )
			return false;
	}

	// Everything checks out and is sanitized, lets get the current post
	$current_post = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );

	// Lets get the first taxonomy's terms belonging to the post
	$terms_1 = get_the_terms( $current_post, $taxonomy_1 );

	// Set a varaible to hold the post count from first query
	$count = 0;
	// Set a variable to hold the results from query 1
	$q_1   = array();

	// Make sure we have terms
	if ( $terms_1 ) {
		// Lets get the term ID's
		$term_1_ids = wp_list_pluck( $terms_1, 'term_id' );

		// Lets build the query to get related posts
		$args_1 = array(
			'post_type'      => $current_post->post_type,
			'post__not_in'   => array($current_post->ID),
			'posts_per_page' => $total_posts,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy'         => $taxonomy_1,
					'terms'            => $term_1_ids,
					'include_children' => false
				)
			),
		);
		$q_1 = get_posts( $args_1 );
		// Count the total amount of posts
		$q_1_count = count( $q_1 );

		// Update our counter
		$count = $q_1_count;
	}

	// We will now run the second query if $count is less than $total_posts
	if ( $count < $total_posts ) {
		$terms_2 = get_the_terms( $current_post, $taxonomy_2 );
		// Make sure we have terms
		if ( $terms_2 ) {
			// Lets get the term ID's
			$term_2_ids = wp_list_pluck( $terms_2, 'term_id' );

			// Calculate the amount of post to get
			$diff = $total_posts - $count;

			// Create an array of post ID's to exclude
			if ( $q_1 ) {
				$exclude = array_merge( array($current_post->ID), $q_1 );
			} else {
				$exclude = array($current_post->ID);
			}

			$args_2 = array(
				'post_type'      => $current_post->post_type,
				'post__not_in'   => $exclude,
				'posts_per_page' => $diff,
				'fields'         => 'ids',
				'tax_query'      => array(
					array(
						'taxonomy'         => $taxonomy_2,
						'terms'            => $term_2_ids,
						'include_children' => false
					)
				),
			);
			$q_2 = get_posts( $args_2 );

			if ( $q_2 ) {
				// Merge the two results into one array of ID's
				$q_1 = array_merge( $q_1, $q_2 );
			}
		}
	}

	// Make sure we have an array of ID's
	if ( !$q_1 )
		return false;

	// Run our last query, and output the results
	$final_args = array(
		'ignore_sticky_posts' => 1,
		'post_type'           => $current_post->post_type,
		'posts_per_page'      => count( $q_1 ),
		'post__in'            => $q_1,
		'order'               => 'ASC',
		'orderby'             => 'post__in',
		'suppress_filters'    => true,
		'no_found_rows'       => true
	);
	$final_query = new WP_Query( $final_args );

	return $final_query;
}