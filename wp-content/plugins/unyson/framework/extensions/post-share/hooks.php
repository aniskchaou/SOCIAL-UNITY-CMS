<?php

//Add options to customizer
add_filter( 'crumina_section_single_post_elements', 'add_post_share_options' );
function add_post_share_options( $opt ){
    $keys = array_keys($opt);
    $index = array_search('single_share_show', $keys, true) + 1;

    $opt = array_slice($opt, 0, $index, true) +
        array(
            'share_buttons_options' => array(
                'type'  => 'checkboxes',
                'label' => esc_html__( 'Share post links', 'crum-ext-post-share' ),
                'value' => array(
                    'facebook' => true,
                    'twitter' => true,
                    'pocket' => true,
                    'whatsapp' => true,
                    'linkedin' => true,
                ),
                'choices' => array(
                    'facebook' => esc_html__( 'Facebook', 'crum-ext-post-share' ),
                    'twitter' => esc_html__( 'Twitter', 'crum-ext-post-share' ),
                    'pocket' => esc_html__( 'Pocket', 'crum-ext-post-share' ),
                    'whatsapp' => esc_html__( 'Whatsapp', 'crum-ext-post-share' ),
                    'linkedin' => esc_html__( 'Linkedin', 'crum-ext-post-share' ),
                    'telegram' => esc_html__( 'Telegram', 'crum-ext-post-share' ),
                    'blogger' => esc_html__( 'Blogger', 'crum-ext-post-share' ),
                    'reddit' => esc_html__( 'Reddit', 'crum-ext-post-share' ),
                    'viber' => esc_html__( 'Viber', 'crum-ext-post-share' ),
                    'pinterest' => esc_html__( 'Pinterest', 'crum-ext-post-share' ),
                    'tumblr' => esc_html__( 'Tumblr', 'crum-ext-post-share' ),
                    'xing' => esc_html__( 'Xing', 'crum-ext-post-share' ),
                    'myspace' => esc_html__( 'Myspace', 'crum-ext-post-share' ),
                ),
            )
        ) +
    array_slice($opt, $index, count($opt)-$index, true);
    return $opt;
}