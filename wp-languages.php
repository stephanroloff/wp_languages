<?php
/**
 * Plugin Name: WP Languages
 * Plugin URI: https://your-website.com
 * Description: A plugin that creates a custom post type for Projects.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: wp-languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_LANGUAGES_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_LANGUAGES_PLUGIN_PATH', plugin_dir_path(__FILE__));


/**
 * Include required files
 */
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/config.php';
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/cpt_others.php';
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/language-switcher.php';
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/post-connections.php';
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/language-switcher-shortcode.php';
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/yoast-breadcrumb-fix.php';
require_once WP_LANGUAGES_PLUGIN_PATH . 'includes/enqueue_assets.php';