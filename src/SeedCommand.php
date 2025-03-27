<?php

namespace WPfor\WpSeeder;

use WP_CLI;
use WP_CLI_Command;
use Faker\Factory;

class SeedCommand extends WP_CLI_Command
{
    /**
     * Seed posts
     *
     * ## OPTIONS
     *
     * [--post_type=<type>]
     * : Exist post type.
     * ---
     * 
     * [--count=<number>]
     * : How many posts do you need?
     * ---
     * default: 10
     * ---
     *
     * ## EXAMPLES
     *     wp db:seed posts --post_type=post --count=20
     *     wp db:seed posts --post_type=ssm_team --count=5
     */
    public function posts( $args, $assoc_args ) {
        $post_type  = $assoc_args['post_type'] ?? null;
        $post_count = isset( $assoc_args['count'] ) ? intval( $assoc_args['count'] ) : 10;

        if( ! post_type_exists( $post_type ) ) {
			WP_CLI::error( sprintf( 'Post type "%s" doesn\'t exist', $post_type ) );
		}

        $faker      = Factory::create();

        for ( $i = 0; $i < $post_count; $i++ ) {
           $post_id = wp_insert_post( [
                'post_title'   => ucwords( $faker->words( $faker->numberBetween( 5, 9 ), true ) ),
                'post_content' => $faker->paragraphs( $faker->numberBetween( 5, 10 ), true ),
                'post_excerpt' => $faker->optional( '0.7', '' )->sentence( $faker->numberBetween( 20, 30 ) ),
                'post_date'    => $faker->dateTimeBetween('-1 years')->format('Y-m-d H:i:s'),
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => $post_type,
            ] );

            if( is_wp_error( $post_id ) ) {
				WP_CLI::warning( $post_id );
				continue;
			}

            update_post_meta( $post_id, '_wp_seeded_post_at', time() );
        }
        
        WP_CLI::success( sprintf( 'Seeded %d posts to post type: "%s".', $post_count, $post_type ) );
    }

    /**
     * Seed terms.
     *
     * ## OPTIONS
     *
     * [--count=<number>]
     * : How many terms do you need?
     * ---
     * default: 5
     * ---
     *
     * [--taxonomy=<taxonomy>]
     * : Exist taxonomy.
     * ---
     * 
     * * ## EXAMPLES
     *     wp db:seed terms --taxonomy=category --count=5
     *     wp db:seed terms --taxonomy=ssm_resource_type --count=8
     */
    public function terms($args, $assoc_args) {
        $terms_count = isset( $assoc_args['count'] ) ? intval( $assoc_args['count'] ) : 5;
        $taxonomy    = $assoc_args['taxonomy'] ?? null;

        if( ! taxonomy_exists( $taxonomy ) ) {
			WP_CLI::error( sprintf( 'Taxonomy "%s" doesn\'t exist', $taxonomy ) );
		}

        $faker = Factory::create();

        for ( $i = 0; $i < $terms_count; $i++ ) {
            $term_name   = ucwords( $faker->words( $faker->numberBetween( 1, 3 ), true ) );

            $term_args   = [
                'description'   => $faker->optional( '0.7', '' )->paragraph( $faker->numberBetween( 2, 4 ) ),
            ];

            if( term_exists( $term_name, $taxonomy ) ) {
				continue;
			}

            $term = wp_insert_term( $term_name, $taxonomy, $term_args );

            if ( is_wp_error( $term ) ) {
                WP_CLI::warning( $term ); 
                continue;
            }

            update_term_meta( $term['term_id'], '_wp_seeded_term_at', time() );
        }

        WP_CLI::success( sprintf( 'Seeded %d terms to taxonomy: "%s".', $terms_count, $taxonomy ) );
    }
}
