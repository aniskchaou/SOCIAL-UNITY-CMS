<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

class FW_Extension_Post_Share extends FW_Extension {

    protected function _init() {
        
    }

    public static function get_option( $option_id, $default_value = '', $source = 'settings', $atts = array() ) {
        $obj = get_queried_object();

        switch ( $source ) {
            case 'settings':
                return fw_get_db_settings_option( $option_id, $default_value );
            case 'customizer':
                return fw_get_db_customizer_option( $option_id, $default_value );
            case 'post':
                if ( isset( $atts[ 'ID' ] ) ) {
                    $atts[ 'ID' ] = (int) $atts[ 'ID' ];
                } else if ( isset( $obj->ID ) ) {
                    $atts[ 'ID' ] = $obj->ID;
                } else {
                    return $default_value;
                }
                return fw_get_db_post_option( $atts[ 'ID' ], $option_id, $default_value );
            case 'taxonomy':
                if ( isset( $atts[ 'term_id' ] ) ) {
                    $atts[ 'term_id' ] = (int) $atts[ 'term_id' ];
                } else if ( isset( $obj->term_id ) ) {
                    $atts[ 'term_id' ] = $obj->term_id;
                } else {
                    return $default_value;
                }

                if ( isset( $atts[ 'taxonomy' ] ) ) {
                    $atts[ 'taxonomy' ] = (string) $atts[ 'taxonomy' ];
                } else if ( isset( $obj->taxonomy ) ) {
                    $atts[ 'taxonomy' ] = $obj->taxonomy;
                } else {
                    return $default_value;
                }
                return fw_get_db_term_option( $atts[ 'term_id' ], $atts[ 'taxonomy' ], $option_id, $default_value );
            default:
                return $default_value;
        }
    }

    public static function get_option_final( $option_id, $default_value = '', $atts = array( 'final-source' => 'settings' ) ) {
        $option = '';

        if ( is_singular() ) {
            $option = self::get_option( $option_id, 'default', 'post' );
        } elseif ( is_archive() ) {
            $option = self::get_option( $option_id, 'default', 'taxonomy' );
        }

        //Fix for WooCommerce
        if ( function_exists( 'is_shop' ) && is_shop() ) {
            $shop_id = wc_get_page_id( 'shop' );

            if ( $shop_id > 0 ) {
                $option = self::get_option( $option_id, 'default', 'post', array(
                    'ID' => $shop_id
                        ) );
            }
        }

        if ( empty( $option ) || ($option === 'default') ) {
            switch ( $atts[ 'final-source' ] ) {
                case 'customizer':
                    $source = 'customizer_option';
                    break;
                case 'current-type':
                    if ( is_singular() ) {
                        $source = 'post';
                    } elseif ( is_archive() ) {
                        $source = 'taxonomy';
                    } else {
                        $source = 'settings';
                    }
                    break;
                default:
                    $source = 'settings';
                    break;
            }

            $option = self::get_option( $option_id, $default_value, $source );
        }

        return $option;
    }
}
