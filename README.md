# WordPress Database Seeder

A WordPress CLI package that provides convenient commands for seeding and managing test data in your WordPress database.

## Description

WP DB Seeder allows developers to quickly populate their WordPress installations with dummy data for posts, terms, and other content types. It's perfect for development, testing, and demo environments where you need realistic data without the hassle of manual content creation.

## Installation

### Using Composer

```bash
composer require wpfor/wp-db-seeder
```

### Manual Installation

1. Download or clone this repository
2. Place it in your WordPress plugins or mu-plugins directory
3. Require the main file in your WordPress installation

## Requirements

- WordPress 5.0+
- PHP 7.4+
- WP-CLI

## Commands

#### Seed Posts

```bash
wp db:seed posts --post_type=<post_type> --count=<number>
```

**Options:**
- `--post_type`: (Required) The post type to seed (must exist)
- `--count`: (Optional) Number of posts to generate (default: 10)

**Examples:**
```bash
wp db:seed posts --post_type=post --count=20
wp db:seed posts --post_type=page --count=5
```

This command generates posts with random titles, content, and excerpts using Faker, and marks them with a special meta key for future management.

#### Seed Terms

```bash
wp db:seed terms --taxonomy=<taxonomy> --count=<number>
```

**Options:**
- `--taxonomy`: (Required) The taxonomy to seed (must exist)
- `--count`: (Optional) Number of terms to generate (default: 5)

**Examples:**
```bash
wp db:seed terms --taxonomy=category --count=8
wp db:seed terms --taxonomy=post_tag --count=15
```

This command creates terms with random names and descriptions, and marks them with a special meta key for future management.

#### Link Terms to Posts

```bash
wp db:link terms_to_posts --post_type=<post_type> --taxonomy=<taxonomy>
```

**Options:**
- `--post_type`: (Required) The post type of the posts to link (must exist)
- `--taxonomy`: (Required) The taxonomy of the terms to link (must exist)

**Examples:**
```bash
wp db:link terms_to_posts --post_type=post --taxonomy=category
wp db:link terms_to_posts --post_type=product --taxonomy=product_cat
```

This command assigns random terms from the specified taxonomy to posts of the specified post type, creating realistic relationships between your seeded content.

#### Delete Seeded Posts

```bash
wp db:delete posts --post_type=<post_type>
```

**Options:**
- `--post_type`: (Required) The post type to delete from (must exist)

**Examples:**
```bash
wp db:delete posts --post_type=post
wp db:delete posts --post_type=page
```

This command deletes only the posts that were created using the `db:seed posts` command, leaving your real content untouched.

#### Delete Seeded Terms

```bash
wp db:delete terms --taxonomy=<taxonomy>
```

**Options:**
- `--taxonomy`: (Required) The taxonomy to delete from (must exist)

**Examples:**
```bash
wp db:delete terms --taxonomy=category
wp db:delete terms --taxonomy=post_tag
```

This command deletes only the terms that were created using the `db:seed terms` command, preserving your real taxonomy terms.

## How It Works

The plugin adds metadata to each seeded post or term, allowing it to identify and manage them separately from your real content. This approach ensures that:

1. You can easily delete just the seeded content
2. Your real content remains untouched
3. You can run the commands multiple times without conflicts