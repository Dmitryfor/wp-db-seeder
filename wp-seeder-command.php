<?php

namespace WPfor\WpSeeder;

if ( defined( 'WP_CLI' ) ) {
    \WP_CLI::add_command( 'db:seed', SeedCommand::class );
    \WP_CLI::add_command( 'db:link', LinkCommand::class );
    \WP_CLI::add_command( 'db:delete', DeleteCommand::class );
}