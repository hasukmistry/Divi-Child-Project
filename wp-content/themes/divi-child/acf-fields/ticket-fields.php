<?php

if ( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5ee45549a911b',
        'title' => 'Ticket Information',
        'fields' => array(
            array(
                'key' => 'field_5ee4555911297',
                'label' => 'Request type',
                'name' => 'request_type',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'graphic_design' => 'Ad Design',
                ),
                'default_value' => array(
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
                    array(
                        'key' => 'field_s9736fhvn9s7f',
                        'label' => 'Does this ticket need RUSH DELIVERY',
                        'name' => 'rush_delivery',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                             ),
                        'choices' => get_priority_types(),
                        'default_value' => array(
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
            ),            
            array(
                'key' => 'field_5ee4560be1024',
                'label' => 'Advertising Information',
                'name' => 'advertising_information',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5ee4555911297',
                            'operator' => '==',
                            'value' => 'graphic_design',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5ee45649e1025',
                        'label' => 'Ad Size',
                        'name' => 'ad_size',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => get_ad_sizes_graphic_design_ticket(),
                        'default_value' => array(
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                  /*  array(
                        'key' => 'field_5ee45660e1026',
                        'label' => 'Advertiser Name',
                        'name' => 'advertiser_name',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),*/
                    array(
                        'key' => 'field_5ee45670e1027',
                        'label' => 'Advertiser Website',
                        'name' => 'advertiser_website',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_hf75a443akc90',
                        'label' => 'Expiration Date for Coupons',
                        'name' => 'expiration_date',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),                    
                    array(
                        'key' => 'field_7flwh6283bkd8',
                        'label' => 'Coupon #1',
                        'name' => 'coupon_one',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),    
                    array(
                        'key' => 'field_f7jsu6f4sna98',
                        'label' => 'Coupon #2',
                        'name' => 'coupon_two',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),  
                    array(
                        'key' => 'field_9oslg75ns7hc2',
                        'label' => 'Coupon #3',
                        'name' => 'coupon_three',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),                     
                ),
            ),
            array(
                'key' => 'field_5ee4571017be7',
                'label' => 'Ad Notes from Publisher',
                'name' => 'notes',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => '',
            ),
            array(
                'key' => 'field_5f65db7072aea',
                'label' => 'Revision Requests',
                'name' => 'revision_requests',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => '',
            ),
                    array(
                        'key' => 'field_5ef6sg7f6shvi',
                        'label' => 'Dropbox Share Link',
                        'name' => 'dropbox_link',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),            
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
endif;

// status fields
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5ee46984b3367',
        'title' => 'Status Information',
        'fields' => array(
            array(
                'key' => 'field_5ee4578a17be9',
                'label' => 'Ticket Status',
                'name' => 'ticket_status',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => get_ticket_status_list(),
                'default_value' => array(
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
endif;

// progress fields
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5ee46018a3a7b',
        'title' => 'Progress Information',
        'fields' => array(            
            array(
                'key' => 'field_5ee45819c587a',
                'label' => 'Assigned to (Lead)',
                'name' => 'assigned_to_graphic_designers',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'graphic_design_lead',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_5ee458d0c587b',
                'label' => 'Assigned to (Lead)',
                'name' => 'assigned_to_website_developers',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'website_update_lead',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_5ee458fbc587c',
                'label' => 'Assigned to (Lead)',
                'name' => 'assigned_to_virtual_tours',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'virtual_tours_lead',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_5ee45929c587d',
                'label' => 'Assigned to (Lead)',
                'name' => 'assigned_to_support',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'support_lead',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'administrator',
                ),
            ),
        ),
        'menu_order' => 1,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
endif;

// progress field for lead role 
if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_5ee499064ab12',
        'title' => 'Work Information',
        'fields' => array(
            array(
                'key' => 'field_5ee49921f1fd7',
                'label' => 'Assigned to',
                'name' => 'assigned_to_others1',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'graphic_design_others',
                    1 => 'sub_admin',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_5ee4a3b5e0ffd',
                'label' => 'Assigned to',
                'name' => 'assigned_to_others2',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'website_update_others',
                    1 => 'sub_admin',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_5ee4a3ba7bac8',
                'label' => 'Assigned to',
                'name' => 'assigned_to_others3',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'virtual_tours_others',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_5ee4a3e8d273d',
                'label' => 'Assigned to',
                'name' => 'assigned_to_others4',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'role' => array(
                    0 => 'support_others',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'graphic_design_lead',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'website_update_lead',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'virtual_tours_lead',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'support_lead',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ticket',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'administrator',
                ),
            ),
        ),
        'menu_order' => 2,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));        
    
endif;

// available to edit screen only
if ( !empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'edit' ) {
    $user = wp_get_current_user();

    $currentPost = new stdClass();
    $currentPost->ID = $_REQUEST['post'];

    $info = DiviChild\Classes\Inc\DCAssignmentInfo::get_assigned_to( $currentPost );

    if ( $user->user_email !== $info['user_email'] ) {
        // assignment user information
        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_5ee46ba6b2084',
                'title' => 'Assigned To',
                'fields' => array(
                    array(
                        'key' => 'field_5ee46badc97b4',
                        'label' => '',
                        'name' => '',
                        'type' => 'message',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '-- None --',
                        'new_lines' => '',
                        'esc_html' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'ticket',
                        ),
                    ),
                ),
                'menu_order' => 3,
                'position' => 'side',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ));
            
        endif;
    }   
}
