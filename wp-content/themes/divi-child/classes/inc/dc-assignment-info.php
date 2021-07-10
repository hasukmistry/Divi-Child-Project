<?php

namespace DiviChild\Classes\Inc;

if ( ! class_exists('DCAssignmentInfo') ) {
    class DCAssignmentInfo {
        public static function get_assigned_to( $post ) {
            $assignment = \DiviChild\Classes\PostTypes\DCTickets::get_assigned_to($post->ID);

            $content = '';
            ob_start();

            if ( !empty( $assignment['others']['name'] ) ) {
                echo sprintf('<b>Name:</b> %s', $assignment['others']['name']);

                echo sprintf('<div style="margin:8px 0;"></div>');

                echo sprintf('<b>Email:</b>');

                echo sprintf('<input readonly type="text" class="assigned_to_email" name="assigned_to_email" id="assigned_to_email" value="%s" />', $assignment['others']['email']);

                echo sprintf('<div style="margin:10px 0;"></div>');

                echo sprintf('<a class="button button-primary button-large" href="javascript:copyEmail(\'assigned_to_email\');">Copy Email</a>');
            } else {
                echo '--None--';
            }

            $content .= ob_get_clean();
            ob_flush();

            return [
                'content' => $content,
                'user_email' => !empty( $assignment['others']['email'] ) ? $assignment['others']['email'] : '',
            ];
        }

        public static function get_auto_assigned_to() {
            $assignment = [
                'lead' => [
                    'user' => ''
                ],
                'other' => [
                    'user' => ''
                ]
            ];

            $leadUser = get_field('auto_lead', 'option');
            $otherUser = get_field('auto_other_user', 'option');

            if( $leadUser ) {
                $assignment['lead']['user'] = $leadUser->ID;
            }

            if ( $otherUser ) {
                $assignment['other']['user'] = $otherUser->ID;
            }

            return $assignment;
        }

        public static function get_assigned_to_for_issue( $post ) {
            $assignment = \DiviChild\Classes\PostTypes\DCIssues::get_assigned_to( $post->ID );

            $content = '';
            ob_start();

            if ( !empty( $assignment['name'] ) ) {
                echo sprintf('<b>Name:</b> %s', $assignment['name']);

                echo sprintf('<div style="margin:8px 0;"></div>');

                echo sprintf('<b>Email:</b>');

                echo sprintf('<input readonly type="text" class="assigned_to_email" name="assigned_to_email_in_issue" id="assigned_to_email_in_issue" value="%s" />', $assignment['email']);

                echo sprintf('<div style="margin:10px 0;"></div>');

                echo sprintf('<a class="button button-primary button-large" href="javascript:copyEmail(\'assigned_to_email_in_issue\');">Copy Email</a>');
            } else {
                echo '--None--';
            }

            $content .= ob_get_clean();
            ob_flush();

            return [
                'content' => $content,
                'user_email' => !empty( $assignment['email'] ) ? $assignment['email'] : '',
            ];
        }
    }
}
