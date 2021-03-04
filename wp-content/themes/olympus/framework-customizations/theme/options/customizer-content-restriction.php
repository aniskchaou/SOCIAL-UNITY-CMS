<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$activity_pages = ( function_exists('bp_core_get_directory_page_ids') ) ? bp_core_get_directory_page_ids() : array();
$activity_pages_array = array();
if(!empty($activity_pages)){
    foreach($activity_pages as $activity_pages_val){
        $activity_pages_array[$activity_pages_val] = get_the_title($activity_pages_val);
    }
}
$options = array(
    'content-restriction' => array(
        'title'   => esc_html__( 'Content restriction', 'olympus' ),
        'options' => array(
            'all_pages'     => array(
                'title'   => esc_html__( 'All pages', 'olympus' ),
                'options' => array(
                    'show-pages-not-login' => array(
                        'type'    => 'multi-picker',
                        'label'   => false,
                        'desc'    => false,
                        'picker'  => array(
                            'enable' => array(
                                'label'        => esc_html__( 'Show for logged in users only', 'olympus' ),
                                'type'         => 'switch',
                                'left-choice'  => array(
                                    'value' => 'no',
                                    'label' => esc_html__( 'No', 'olympus' )
                                ),
                                'right-choice' => array(
                                    'value' => 'yes',
                                    'label' => esc_html__( 'Yes', 'olympus' )
                                ),
                                'value' => 'no',
                            ),
                        ),
                        'choices' => array(
                            'yes' => array(
                                'page_redirect' => array(
                                    'type' => 'multi-select',
                                    'label' => esc_html__( 'Redirect non-logged users to the page', 'olympus' ),
                                    'population' => 'posts',
                                    'source' => 'page',
                                    'limit' => 1,
                                ),
                                'excude_pages' => array(
                                    'type' => 'multi-select',
                                    'label' => esc_html__( 'Exclude pages', 'olympus' ),
                                    'population' => 'posts',
                                    'source' => 'page',
                                    'limit' => 100,
                                )
                            )
                        )
                    )
                )
            ),
            'activity_pages'     => array(
                'title'   => esc_html__( 'Activity pages', 'olympus' ),
                'options' => array(
                    'show-activity-pages-not-login' => array(
                        'type'    => 'multi-picker',
                        'label'   => false,
                        'desc'    => false,
                        'picker'  => array(
                            'enable' => array(
                                'label'        => esc_html__( 'Show for logged in users only', 'olympus' ),
                                'type'         => 'switch',
                                'left-choice'  => array(
                                    'value' => 'no',
                                    'label' => esc_html__( 'No', 'olympus' )
                                ),
                                'right-choice' => array(
                                    'value' => 'yes',
                                    'label' => esc_html__( 'Yes', 'olympus' )
                                ),
                                'value' => 'no',
                            ),
                        ),
                        'choices' => array(
                            'yes' => array(
                                'page_redirect' => array(
                                    'type' => 'multi-select',
                                    'label' => esc_html__( 'Redirect non-logged users to the page', 'olympus' ),
                                    'population' => 'posts',
                                    'source' => 'page',
                                    'limit' => 1,
                                ),
                                'excude_pages' => array(
                                    'type' => 'multi-select',
                                    'label' => esc_html__( 'Exclude pages', 'olympus' ),
                                    'choices' => $activity_pages_array,
                                    'limit' => 50,
                                )
                            )
                        )
                    )
                )
            )    
        )
    )
);