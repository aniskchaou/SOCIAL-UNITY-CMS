<?php

add_action( 'admin_footer', '_action_fw_ext_contact_form_crumina_enable_fw_forms' );

function _action_fw_ext_contact_form_crumina_enable_fw_forms() {
    if ( fw()->extensions->manager->can_activate() ) {
        fw()->extensions->manager->activate_extensions( array( 'forms' => array(), 'builder' => array() ) );
    }
}

add_action( 'updated_post_meta', '_action_fw_ext_contact_form_crumina_post_form_save', 0, 4 );

function _action_fw_ext_contact_form_crumina_post_form_save() {
    global $post;
    if ( $post && $post->post_type == 'crum-form' ) {
        $form_meta = get_post_meta( $post->ID, 'fw_options', true );
        if ( !empty( $form_meta ) ) {
            update_option( 'fw:ext:cf:fd:' . $post->ID, $form_meta );
        }
    }
}

add_action( 'trashed_post', '_action_fw_ext_contact_form_crumina_post_form_delete' );

function _action_fw_ext_contact_form_crumina_post_form_delete( $post_id ) {
    if ( 'crum-form' == get_post_type( $post_id ) ) {
        delete_option( 'fw:ext:cf:fd:' . $post_id );
    }
}

add_action( 'vc_before_init', '_action_fw_ext_contact_form_crumina_add_builder_component' );

function _action_fw_ext_contact_form_crumina_add_builder_component (){
    new FW_Ext_Crumina_Contact_Form();
}

add_filter( 'fw_post_options', '_filter_fw_ext_contact_form_crumina_post_options', 10, 2 );

function _filter_fw_ext_contact_form_crumina_post_options( $options, $post_type ) {
    if ( $post_type !== 'crum-form' ) {
        return $options;
    }

    $variables = fw_get_variables_from_file(
    fw()->extensions->get( 'contact-form' )->locate_path( '/options/posts/crum-form.php' ), array( 'options' => array() )
    );

    return $variables[ 'options' ];
}

add_filter( 'fw_builder_item_widths:form-builder', '_filter_ext_contact_form_crumina_builder_item_widths' );

function _filter_ext_contact_form_crumina_builder_item_widths( $widths ) {
    foreach ( $widths as &$width ) {
        $width[ 'frontend_class' ] .= ' form-builder-item';
    }

    return $widths;
}
