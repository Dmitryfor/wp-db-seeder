<?php

namespace WPfor\WpSeeder;

class Helpers {
    
    public static function get_seeded_posts( $post_type ) {
        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
            'meta_key'       => '_wp_seeded_post_at',
        ];

        return get_posts($args) ?: [];
    }   

    public static function get_seeded_terms( $taxonomy ) {
        $args = [
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'meta_key'   => '_wp_seeded_term_at'
        ];

        return get_terms( $args ) ?: [];
    }   
}