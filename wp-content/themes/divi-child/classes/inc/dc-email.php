<?php

namespace DiviChild\Classes\Inc;

use DiviChild\Classes\PostTypes\DCTickets;

// Creates class if not exists.
if ( ! class_exists('DCEmail') ) {
    class DCEmail {
        private static function get_supported_roles() {
            return [
                'graphic_design_lead',
                'graphic_design_others',
                'website_update_lead',
                'website_update_others',
                'virtual_tours_lead',
                'virtual_tours_others',
                'support_lead',
                'support_others',
            ];
        }

        private static function get_supported_templates() {
            return [
                'ticket_assignment' => self::ticket_assignment_acf_fields(),
            ];
        }

        public static function default_ticket_assignment_fields() {
            return [
                'subject' => 'Ticket ${ticket_no} has been assigned',
                'content' => self::default_ticket_assignment_content()
            ];
        }

        private static function default_ticket_assignment_content() {
            $content = '';
            ob_start();

            echo sprintf( 'Hello ${user_name},' );
            echo PHP_EOL . PHP_EOL . PHP_EOL;
            
            echo sprintf( 'Ticket ${ticket_no} has been assigned to you by an admin.' );
            echo PHP_EOL . PHP_EOL;

            echo sprintf( 'Ticket details are as following:' );
            echo PHP_EOL . PHP_EOL;

            echo sprintf( '<strong>Title :</strong>' );
            echo sprintf( '${ticket_title}' );
            echo PHP_EOL . PHP_EOL;

            echo sprintf( '<strong>Ticket URL :</strong>' );
            echo sprintf( '<a href="${ticket_link}" rel="noopener" target="_blank">${ticket_link}</a>' );
            echo PHP_EOL . PHP_EOL . PHP_EOL;

            echo sprintf( 'Thank you,' );
            echo PHP_EOL;
            echo sprintf( '<strong>%s</strong>', get_bloginfo( 'name' ) );

            $content .= ob_get_clean();
            ob_flush();

            return $content;
        }

        private static function ticket_assignment_acf_fields() {
            return [
                'group' => 'ticket_assignment_',
                'subject' => 'email_subject_',
                'content' => 'email_content_',
            ];
        }

        private static function get_template_fields( $role, $template ) {
            $fields = self::default_ticket_assignment_fields();

            if ( empty( $role ) || empty( $template ) ) {
                return $fields;
            }

            $supported_roles = self::get_supported_roles();
            $supported_templates = self::get_supported_templates();
            $supported_templates_keys = array_keys( $supported_templates );

            if ( array_intersect( $supported_roles, [ $role ] ) &&
                array_intersect( $supported_templates_keys, [ $template ] )
            ) {                
                $acf_group_name = sprintf('%s%s', $supported_templates[ $template ]['group'], $role);

                $email_subject = sprintf('%s%s', $supported_templates[ $template ]['subject'], $role);
                $email_content = sprintf('%s%s', $supported_templates[ $template ]['content'], $role);

                $template = get_field($acf_group_name, 'option');

                if( $template ) {
                    $fields = [
                        'subject' => $template[ $email_subject ],
                        'content' => $template[ $email_content ]
                    ];
                }
            }

            return $fields;
        }

        private static function filter_email_template($body, $vars = []) {
            if ( empty( $vars ) ) {
                return $body;
            }

            $content = str_replace(
                array_keys($vars), 
                array_values($vars), 
                $body
            );

            return $content;
        }

        private static function send_email($user_email, $subject, $body, $headers) {
            wp_mail( $user_email, $subject, $body, $headers );
        }

        public static function send_assignment_notifications($ticket_id, $template) {
            $assignment = DCTickets::get_assigned_to($ticket_id);
            $ticket_url = esc_url_raw( get_edit_post_link($ticket_id) );
            
            $lead_role      = $assignment['lead']['role'];
            $lead_email_tpl = self::get_template_fields( $lead_role, $template );

            $others_role      = $assignment['others']['role'];
            $others_email_tpl = self::get_template_fields( $others_role, $template );

            // ticket has not been assigned to any user
            if ( empty( $assignment['lead']['email'] ) ) {
                return false;
            }
            
            $headers  = array('Content-Type: text/html; charset=UTF-8');

            $ticket_title = esc_html( get_the_title($ticket_id) );

            // replaces variables inside content
            $replace_content_variables_for_lead = array(
                '${ticket_title}' => ! empty( $ticket_title ) ? $ticket_title : '',
                '${ticket_no}' => ! empty( $ticket_id ) ? $ticket_id : 'LL000000',
                '${ticket_link}' => ! empty( $ticket_url ) ? esc_url( $ticket_url ) : '#',
                '${user_name}' => ! empty( $assignment['lead']['name'] ) ? $assignment['lead']['name'] : 'There',
            );

            $replace_content_variables_for_others = array(
                '${ticket_title}' => ! empty( $ticket_title ) ? $ticket_title : '',
                '${ticket_no}' => ! empty( $ticket_id ) ? $ticket_id : 'OO000000',
                '${ticket_link}' => ! empty( $ticket_url ) ? esc_url( $ticket_url ) : '#',
                '${user_name}' => ! empty( $assignment['others']['name'] ) ? $assignment['others']['name'] : 'There',
            );

            $lead_email_subject  = self::filter_email_template( $lead_email_tpl['subject'], $replace_content_variables_for_lead );
            $lead_email_body     = self::filter_email_template( $lead_email_tpl['content'], $replace_content_variables_for_lead );

            self::send_email($assignment['lead']['email'], $lead_email_subject, $lead_email_body, $headers);

            $others_email_subject = self::filter_email_template( $others_email_tpl['subject'], $replace_content_variables_for_others );
            $others_email_body    = self::filter_email_template( $others_email_tpl['content'], $replace_content_variables_for_others );

            self::send_email($assignment['others']['email'], $others_email_subject, $others_email_body, $headers);

            return true;
        }
    }
}

new DCEmail();
