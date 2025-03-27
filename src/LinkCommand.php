<?php

namespace WPfor\WpSeeder;

use WP_CLI;
use WP_CLI_Command;

class LinkCommand extends WP_CLI_Command
{
    /**
     * Link seeded terms to seeded posts.
     *
     * ## OPTIONS
     *
     * [--post_type=<type>]
     * : Exist post type.
     * ---
     * 
     * [--taxonomy=<taxonomy>]
     * : Exist Taxonomy
     * ---
     *
     * ## EXAMPLES
     *     wp db:link terms_to_posts --post_type=post --taxonomy=category
     */
    public function terms_to_posts( $args, $assoc_args ) {
        $post_type = $assoc_args['post_type'] ?? null;
        $taxonomy  = $assoc_args['taxonomy'] ?? null;

        if( ! post_type_exists( $post_type ) ) {
			WP_CLI::error( sprintf( 'Post type "%s" doesn\'t exist', $post_type ) );
		}

        if ( ! taxonomy_exists( $taxonomy ) ) {
            WP_CLI::error( sprintf( 'Taxonomy "%s" doesn\'t exist', $taxonomy ) );
        }

        $posts = Helpers::get_seeded_posts( $post_type );
        $terms = Helpers::get_seeded_terms( $taxonomy );

        if ( empty( $posts ) || empty( $terms ) ) {
            WP_CLI::warning( 'No posts or terms found to assign.' );
            return;
        }

        foreach ( $posts as $post_id ) {
            $max_count   = 4 > count( $terms ) ? count( $terms ) : 4;
            $random_keys = (array) array_rand( $terms, rand( 1, $max_count ) );
            $random_term_values = array_map( fn( $key ) => $terms[$key]->term_id, $random_keys );

            wp_set_object_terms( $post_id, $random_term_values, $taxonomy );
        }

        WP_CLI::success( sprintf( 'Assigned terms from the taxonomy "%s" for the post type "%s".', $taxonomy, $post_type ) );
    }
}
