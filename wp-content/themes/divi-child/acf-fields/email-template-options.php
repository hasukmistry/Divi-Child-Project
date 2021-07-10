<?php

if( function_exists('acf_add_local_field_group') ):
    list($admin_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_administrator();
    list($publisher_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_publisher();
    list($subadmin_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_sub_administrator();

    list($graphic_design_lead_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_graphic_design_lead();
    list($graphic_design_others_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_graphic_design_others();

    list($website_update_lead_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_website_update_lead();
    list($website_update_others_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_website_update_others();

    list($virtual_tours_lead_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_virtual_tours_lead();
    list($virtual_tours_others_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_virtual_tours_others();

    list($support_lead_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_support_lead();
    list($support_others_templates) = DiviChild\Classes\OptionsPages\DCEmailTemplates::get_email_templates_for_support_others();

    acf_add_local_field_group(array(
        'key' => 'group_600c235909f33',
        'title' => 'Templates',
        'fields' => array(
            array(
                'key' => 'field_600c2394729b2',
                'label' => 'Administrator',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $admin_templates,
            array(
                'key' => 'field_600c28ce793e5',
                'label' => 'Sub Admin',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $subadmin_templates,
            array(
                'key' => 'field_600c241e5403e',
                'label' => 'Publisher',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $publisher_templates,
            array(
                'key' => 'field_600c2a06b9a90',
                'label' => 'Graphic Design: Lead',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 1,
            ),
            $graphic_design_lead_templates,
            array(
                'key' => 'field_600c2a20b9a91',
                'label' => 'Graphic Design: Others',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $graphic_design_others_templates,
            array(
                'key' => 'field_600c2a35b9a92',
                'label' => 'Website Update: Lead',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 1,
            ),
            $website_update_lead_templates,
            array(
                'key' => 'field_600c2a4fb9a93',
                'label' => 'Website Update: Others',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $website_update_others_templates,
            array(
                'key' => 'field_600c2a64b9a94',
                'label' => 'Virtual Tours: Lead',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 1,
            ),
            $virtual_tours_lead_templates,
            array(
                'key' => 'field_600c2a7cb9a95',
                'label' => 'Virtual Tours: Others',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $virtual_tours_others_templates,
            array(
                'key' => 'field_600c2a93b9a96',
                'label' => 'Support: Lead',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 1,
            ),
            $support_lead_templates,
            array(
                'key' => 'field_600c2aa2b9a97',
                'label' => 'Support: Others',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            $support_others_templates,
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'dc-email-template-settings',
                ),
                array(
                    'param' => 'current_user_role',
                    'operator' => '==',
                    'value' => 'administrator',
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