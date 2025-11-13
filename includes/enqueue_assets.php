<?php

function wp_languages_scripts_and_styles() {
    wp_enqueue_style('wp_languages_styles', WP_LANGUAGES_PLUGIN_URL . 'build/index.css', array(), '1.0', 'all');
    wp_enqueue_script('wp_languages_scripts', WP_LANGUAGES_PLUGIN_URL . 'build/index.js', array('jquery'), '1.0.0', true);
}

function wp_languages_scripts_and_styles_editor() {
    $editor_asset_file = WP_LANGUAGES_PLUGIN_PATH . 'build/editor.asset.php';
    $editor_asset = file_exists($editor_asset_file) ? require($editor_asset_file) : array(
        'dependencies' => array('react-jsx-runtime', 'wp-api-fetch', 'wp-components', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-plugins'),
        'version' => '1.0.0'
    );

    wp_enqueue_style('wp_languages_styles_editor', WP_LANGUAGES_PLUGIN_URL . 'build/editor.css', array(), $editor_asset['version']);
    wp_enqueue_script(
        'wp_languages_scripts_editor',
        WP_LANGUAGES_PLUGIN_URL . 'build/editor.js',
        $editor_asset['dependencies'],
        $editor_asset['version'],
        true
    );

    if (isset($GLOBALS['languages'], $GLOBALS['all_cpt'])) {
        wp_localize_script(
            'wp_languages_scripts_editor',
            'wpLanguagesData',
            array(
                'languages' => $GLOBALS['languages'],
                'allCpt'    => $GLOBALS['all_cpt'],
                'nonce'     => wp_create_nonce('wp_rest'),
            )
        );
    }
}

function wp_languages_scripts_and_styles_frontend() {
    wp_enqueue_style('wp_languages_styles_frontend', WP_LANGUAGES_PLUGIN_URL . 'build/frontend.css');
    wp_enqueue_style('wp_languages_switcher_styles', WP_LANGUAGES_PLUGIN_URL . 'assets/language-switcher.css', array(), '1.0.0');
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

