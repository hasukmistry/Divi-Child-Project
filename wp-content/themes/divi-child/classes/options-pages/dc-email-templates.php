<?php

namespace DiviChild\Classes\OptionsPages;

use DiviChild\Classes\Inc\DCEmail;

// Creates class if not exists.
if ( ! class_exists('DCEmailTemplates') ) {
    class DCEmailTemplates {
        private static $default_ticket_assignment_fields;

        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            self::$default_ticket_assignment_fields = DCEmail::default_ticket_assignment_fields();

            add_action( 'acf/init', array( $this, 'create_settings_page') );
        }

        public function create_settings_page() {
            if ( ! is_user_logged_in() ) {
                return;
            }

            // only in admin area
            if ( is_admin() ) {
                $args = array(
                    'page_title' => __('Email templates'),
                    'menu_title' => __('Email templates'),
                    'menu_slug'  => 'dc-email-template-settings',
                    'capability' => 'manage_options',
                    'redirect' 	 => false,
		            'parent'     => 'website-settings'
                );

                if( function_exists('acf_add_options_page') ) { acf_add_options_page( $args ); }
            }
        }

        // returns an array of acf fields
        public static function get_email_templates_for_administrator() {
            return [
                array(
                    'key' => 'field_1_600c27c6349da',
                    'label' => 'Information',
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
                    'message' => 'Currently, there are no email templates available under the administrator section.',
                    'new_lines' => 'wpautop',
                    'esc_html' => 1,
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_publisher() {
            return [
                array(
                    'key' => 'field_2_600c27c6349da',
                    'label' => 'Information',
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
                    'message' => 'Currently, there are no email templates available under the publisher section.',
                    'new_lines' => 'wpautop',
                    'esc_html' => 1,
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_sub_administrator() {
            return [
                array(
                    'key' => 'field_3_600c27c6349da',
                    'label' => 'Information',
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
                    'message' => 'Currently, there are no email templates available under the sub administrator section.',
                    'new_lines' => 'wpautop',
                    'esc_html' => 1,
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_graphic_design_lead() {
            return [
                array(
                    'key' => 'field_600c2df8b06a5',
                    'label' => '1) Lead >> Ticket Assignment',
                    'name' => 'ticket_assignment_graphic_design_lead',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c2e30b06a6',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_graphic_design_lead',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c2e62b06a7',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c2ec3b06a8',
                            'label' => 'Email Content',
                            'name' => 'email_content_graphic_design_lead',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_graphic_design_others() {
            return [
                array(
                    'key' => 'field_600c30a26bdd1',
                    'label' => '1) Others >> Ticket Assignment',
                    'name' => 'ticket_assignment_graphic_design_others',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c30a26fac0',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_graphic_design_others',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c30a26fafe',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c30a26fb37',
                            'label' => 'Email Content',
                            'name' => 'email_content_graphic_design_others',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_website_update_lead() {
            return [
                array(
                    'key' => 'field_600c31ec3a1e5',
                    'label' => '1) Lead >> Ticket Assignment',
                    'name' => 'ticket_assignment_website_update_lead',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c31ec4002e',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_website_update_lead',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c31ec4007c',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c31ec400b8',
                            'label' => 'Email Content',
                            'name' => 'email_content_website_update_lead',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_website_update_others() {
            return [
                array(
                    'key' => 'field_600c3202c8e46',
                    'label' => '1) Others >> Ticket Assignment',
                    'name' => 'ticket_assignment_website_update_others',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c3202cc1a2',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_website_update_others',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c3202cc1e0',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c3202cc21a',
                            'label' => 'Email Content',
                            'name' => 'email_content_website_update_others',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_virtual_tours_lead() {
            return [
                array(
                    'key' => 'field_600c31fd1962f',
                    'label' => '1) Lead >> Ticket Assignment',
                    'name' => 'ticket_assignment_virtual_tours_lead',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c31fd1c937',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_virtual_tours_lead',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c31fd1c974',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c31fd1c9af',
                            'label' => 'Email Content',
                            'name' => 'email_content_virtual_tours_lead',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_virtual_tours_others() {
            return [
                array(
                    'key' => 'field_600c320539bf4',
                    'label' => '1) Others >> Ticket Assignment',
                    'name' => 'ticket_assignment_virtual_tours_others',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c32053d591',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_virtual_tours_others',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c32053d5d2',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c32053d60d',
                            'label' => 'Email Content',
                            'name' => 'email_content_virtual_tours_others',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_support_lead() {
            return [
                array(
                    'key' => 'field_600c31ffadc5b',
                    'label' => '1) Lead >> Ticket Assignment',
                    'name' => 'ticket_assignment_support_lead',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c31ffb2b90',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_support_lead',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c31ffb2bd1',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c31ffb2c14',
                            'label' => 'Email Content',
                            'name' => 'email_content_support_lead',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }

        // returns an array of acf fields
        public static function get_email_templates_for_support_others() {
            return [
                array(
                    'key' => 'field_600c32080663a',
                    'label' => '1) Others >> Ticket Assignment',
                    'name' => 'ticket_assignment_support_others',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_600c32080abd2',
                            'label' => 'Email Subject',
                            'name' => 'email_subject_support_others',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['subject'],
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_600c32080ac1c',
                            'label' => 'Fields',
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
                            'message' => 'Available fields for template: <b>${ticket_title}, ${ticket_no}, ${ticket_link}, ${user_name}</b>',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_600c32080ac65',
                            'label' => 'Email Content',
                            'name' => 'email_content_support_others',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => self::$default_ticket_assignment_fields['content'],
                            'tabs' => 'text',
                            'media_upload' => 0,
                            'toolbar' => 'full',
                            'delay' => 0,
                        ),
                    ),
                ),
            ];
        }
    }
}

new DCEmailTemplates();