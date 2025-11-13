<?php

function wp_languages_scripts_and_styles() {
    wp_enqueue_style('wp_languages_styles', WP_LANGUAGES_PLUGIN_URL . 'build/index.css', array(), '1.0', 'all');
    wp_enqueue_script('wp_languages_scripts', WP_LANGUAGES_PLUGIN_URL . 'build/index.js', array('jquery'), '1.0.0', true);
}

function wp_languages_scripts_and_styles_editor() {
    wp_enqueue_style('wp_languages_styles_editor', WP_LANGUAGES_PLUGIN_URL . 'build/editor.css');
    wp_enqueue_script('wp_languages_scripts_editor', WP_LANGUAGES_PLUGIN_URL . 'build/editor.js', array('jquery','wp-element', 'wp-editor', 'wp-blocks'), '1.0.0', true);
}

function wp_languages_scripts_and_styles_frontend() {
    wp_enqueue_style('wp_languages_styles_frontend', WP_LANGUAGES_PLUGIN_URL . 'build/frontend.css');
    wp_enqueue_script('wp_languages_scripts_frontend', WP_LANGUAGES_PLUGIN_URL . 'build/frontend.js', array('jquery'), '1.0.0', true);
}   

function wp_languages_scripts_and_styles_admin() {
    wp_enqueue_style('wp_languages_styles_admin', WP_LANGUAGES_PLUGIN_URL . 'build/admin.css');
    wp_enqueue_script('wp_languages_scripts_admin', WP_LANGUAGES_PLUGIN_URL . 'build/admin.js', array('jquery'), '1.0.0', true);
}


//******************************************************************/
//Just Admin Dashboard Area
add_action('admin_enqueue_scripts', 'wp_languages_scripts_and_styles_admin');

//Just Frontend
add_action('wp_enqueue_scripts', 'wp_languages_scripts_and_styles_frontend');

//Frontend & Editor
add_action('enqueue_block_assets', 'wp_languages_scripts_and_styles');

//Just Editor
add_action('enqueue_block_editor_assets', 'wp_languages_scripts_and_styles_editor');
?>

