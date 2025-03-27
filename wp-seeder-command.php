<?php

namespace WPfor\WpSeeder;

if (defined('WP_CLI')) {
    \WP_CLI::add_command('db:seed', __NAMESPACE__ . '\\SeedCommand::class');
    \WP_CLI::add_command('db:link', __NAMESPACE__ . '\\LinkCommand::class');
    \WP_CLI::add_command('db:delete', __NAMESPACE__ . '\\DeleteCommand::class');
}