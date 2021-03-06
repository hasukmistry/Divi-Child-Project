<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5e6e40b674d4a',
        'title' => 'Advertiser Fields',
        'fields' => array(
 /*            array(
                 'key' => 'field_5e6e40faf0574',
                 'label' => 'Advertiser/Vendor',
                'name' => 'advertiser_vendor',
                 'type' => 'post_object',
                 'instructions' => '',
                 'required' => 1,
                 'conditional_logic' => 0,
                 'wrapper' => array(
                     'width' => '',
                     'class' => '',
                     'id' => '',
                 ),
                 'post_type' => array(
                     0 => 'vendor',
                 ),
                 'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                 'return_format' => 'id',
                 'ui' => 1,
             ),
            array(
                'key' => 'field_6037e65dfe897',
                'label' => 'Advertiser/Vendor',
                'name' => 'advertiser_vendor_txt',
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
                'key' => 'field_5e6e4151e8b99',
                'label' => 'Issue Advertising In',
                'name' => 'issue_advertising_in',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 1,
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
            array(
                'key' => 'field_5e6e41ccbffeb',
                'label' => 'Ad Size',
                'name' => 'ad_size',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    '1by8_page' => '1/8 Page',
                    '1by4_page' => '1/4 Page',                    
                    '1by2_page' => '1/2 Page',
                    'full_page' => 'Full Page', 
                    'coupon_ad' => 'Coupon Ad',                    
                    '2_page_spread' => '2 Page Spread',
                    'front_cover' => 'Front Cover',                    
                    'back_cover' => 'Front Cover',
                    'back_coupon' => 'Back Cover Coupon',
                    'still_deciding' => 'Still Deciding',                     
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
                'key' => 'field_5e6e41b4a04b5',
                'label' => 'Placement',
                'name' => 'ad_placement',
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
            array(
                'key' => 'field_5e6e4252a372a',
                'label' => 'Artwork By',
                'name' => 'artwork_by',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'central' => 'Central',
                    'advertiser' => 'Advertiser',

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
                'key' => 'field_5e6e42a7b5d43',
                'label' => 'Payment Status',
                'name' => 'payment_status',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'pending' => 'Pending',
                    'invoice_sent' => 'Invoice Sent',
                    'paid' => 'Paid',
                    'free' => 'Free Ad',
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
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'advertiser',
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