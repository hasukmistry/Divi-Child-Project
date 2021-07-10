<?php

namespace DiviChild\Classes\Inc;

// Creates class if not exists.
if ( ! class_exists('DCPageLinks') ) {
    class DCPageLinks {
        /**
         * An array that stores template pages
         */
        private static $pages = [
            'terms_and_conditions'  => 'publisher-privacy-policy.php',
        ];

        /**
         * Common function to retrieve page obj from given template
         *
         * @param String $tpl
         * @return String
         */
        private static function page_obj( $tpl ) {
            $obj = [];
            
            $id = 0;
            $title = '';
            $url = esc_url( home_url( '/' ) );

            if ( empty( $tpl ) ) {
                return $url;
            }

            $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => $tpl
            ));

            if ( $pages ) {
                foreach($pages as $page){
                    $id = $page->ID;
                    $title = $page->post_title;
                    $url = get_permalink( $page );
                }
            }

            return [
                'id' => $id,
                'title' => $title,
                'url' => $url,
            ];
        }

        private static function page_link( $tpl ) {
            return self::page_obj($tpl)['url'];
        }

        public static function terms_and_conditions() {
            return self::page_link( self::$pages['terms_and_conditions'] );
        }

        public static function terms_and_conditions_obj() {
            return self::page_obj( self::$pages['terms_and_conditions'] );
        }
    }
}

