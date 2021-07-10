<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5e49302647d5b',
        'title' => 'Fields',
        'fields' => array(
            array(
                'key' => 'field_5e49313a8c83a',
                'label' => 'Photographer Email',
                'name' => 'photographer_email',
                'type' => 'email',
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
            ),
            array(
                'key' => 'field_5e4935090826c',
                'label' => 'Submission Type',
                'name' => 'submission_type',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => get_submission_types(),
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
                'key' => 'field_5e4935abbab92',
                'label' => 'Spouse #1 First Name',
                'name' => 'spouse_1_first_name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'wedding',
                        ),
                    ),
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'engagement',
                        ),
                    ),
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'bridals',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5e493600bab94',
                'label' => 'Spouse #2 First Name',
                'name' => 'spouse_2_first_name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'wedding',
                        ),
                    ),
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'engagement',
                        ),
                    ),
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'bridals',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_608d7b0bc48df',
                'label' => 'Styled Shoot Title (OPTIONAL : a short, catchy title for the shoot)',
                'name' => 'styled_shoot_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5e4935090826c',
                            'operator' => '==',
                            'value' => 'styled_shoot',
                        ),
                    ),
                ),
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
            ),
            array(
                'key' => 'field_6027c6da88ffb',
                'label' => 'OPTIONAL: Email for the Couple (We like to notify them after we publish!)',
                'name' => 'email_of_couple',
                'type' => 'email',
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
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_5e493854aa78e',
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
                'message' => 'Please provide a gallery link below. If you prefer to submit specific images, please upload them to Dropbox or other online service and share the link with us!',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_5e49382eaa78d',
                'label' => 'Gallery Link',
                'name' => 'gallery_link',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '100',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'Pixieset, Dropbox, etc.',
            ),
            array(
                'key' => 'field_5e493a6c126c6',
                'label' => 'Gallery Password (if applicable)',
                'name' => 'notes_for_accessing_gallery',
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
                'maxlength' => '',
                'new_lines' => '',
            ),
                array(
                'key' => 'field_7fa54rtr34ch8',
                'label' => 'Download Pin (if applicable)',
                'name' => 'download_pin',
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
                'maxlength' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_5e493d9d49598',
                'label' => 'Vendor List For Submission',
                'name' => 'publisher_notes',
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
                'placeholder' => 'A vendor list must be provided for a submission to be published. You can paste the vendor list here, or email it to us after the submission is approved.',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => '',
            ),
            array(
                'key' => 'field_jf8s9fyeh47d6',
                'label' => '(OPTIONAL) Instagram Post Description',
                'name' => 'ig_description',
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
                'placeholder' => 'If you want us to also publish this submission on Instagram, please provide a short description here. If possible, please tag any vendors that you believe should be included!',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'submission',
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

// Admin fields
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_608d68109839d',
        'title' => 'Issue',
        'fields' => array(
            array(
                'key' => 'field_608d68486a991',
                'label' => 'Original Issue',
                'name' => 'original_issue',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'issue',
                ),
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'submission',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'administrator',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'submission',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'sub_admin',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'submission',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'publisher',
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

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5e497a4a09e6d',
        'title' => 'Action Fields',
        'fields' => array(
            array(
                'key' => 'field_5e497a6041fce',
                'label' => 'Status',
                'name' => 'submission_status',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => get_submission_status_list(),
                'default_value' => array(
                    0 => 'pending',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5e497ab841fcf',
                'label' => 'Placement',
                'name' => 'placement',
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
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'submission',
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