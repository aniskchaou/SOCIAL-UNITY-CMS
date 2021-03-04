<?php

if ( !defined( 'FW' ) ) {
    return;
}

/**
 * Search members by display_name
 * 
 * @param array $users
 * @return string $search
*/
if( !function_exists('extended_search_user_by_name') ){
    function extended_search_user_by_name( $users, $search = '' ){
        $result = array();
        if( !empty($users) && $search != '' ){
            foreach( $users as $user_k => $user_v ){
                $user_display_name = $user_v->display_name;
                if( preg_match("/{$search}/i", $user_display_name) ){
                    array_push( $result, $user_v );
                }
            }
        }

        return $result;
    }
}

/**
 * Search groups by name and description
 * 
 * @param array $groups
 * @return string $search
*/
if( !function_exists('extended_search_group_by_name') ){
    function extended_search_group_by_name( $groups, $search = '' ){
        $result = array();
        if( !empty($groups) && $search != '' ){
            foreach( $groups as $group_k => $group_v ){
                $group_name = $group_v->name;
				$group_descr = $group_v->description;
                if( preg_match("/{$search}/i", $group_name) || preg_match("/{$search}/i", $group_descr) ){
                    array_push( $result, $group_v );
                }
            }
        }

        return $result;
    }
}