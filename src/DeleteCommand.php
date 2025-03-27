<?php

namespace WPfor\WpSeeder;

use WP_CLI;
use WP_CLI_Command;

class DeleteCommand extends WP_CLI_Command
{
    /**
     * Delete only seeded posts.
     *
     * ## OPTIONS
     * 
     * [--post_type=<type>]
     * : Post Type
     * ---
     *
     * ## EXAMPLES
     *     wp db:delete posts --post_type=post
     */
    public function posts( $args, $assoc_args ) {
        $post_type  = $assoc_args['post_type'] ?? null;

        if ( ! post_type_exists( $post_type ) ) {
            WP_CLI::error( sprintf( 'Post type "%s" doesn\'t exist', $post_type ) );
        }

        $posts = Helpers::get_seeded_posts( $post_type );

        if ( empty( $posts ) ) {
            WP_CLI::warning( 'No seeded posts found to delete.' );
            return;
        }

        foreach ( $posts as $post_id ) {
            wp_delete_post( $post_id, true );
        }

        WP_CLI::success( sprintf( 'Deleted "%d" seeded posts from the post type "%s".', count( $posts ), $post_type ) );
    }

    /**
     * Delete only seeded terms.
     *
     * ## OPTIONS
     * 
     * [--taxonomy=<taxonomy>]
     * : Exist Taxonomy
     * ---
     *
     * ## EXAMPLES
     *     wp db:delete terms --taxonomy=category
     */
    public function terms( $args, $assoc_args ) {
        $taxonomy  = $assoc_args['taxonomy'] ?? null;

        if ( ! taxonomy_exists( $taxonomy ) ) {
            WP_CLI::error( sprintf( 'Taxonomy "%s" doesn\'t exist', $taxonomy ) );
        }

        $terms = Helpers::get_seeded_terms( $taxonomy );

        if ( empty( $terms ) ) {
            WP_CLI::warning( 'No seeded terms found to delete.' );
            return;
        }

        foreach ($terms as $term) {
            wp_delete_term($term->term_id, $taxonomy);
        }

        WP_CLI::success( sprintf( 'Deleted "%d" seeded terms from the taxonomy "%s".', count( $terms ), $taxonomy ) );
    }
}
