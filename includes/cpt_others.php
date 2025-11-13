<?php
// Hook para registrar todos los CPTs dinámicamente
add_action('init', 'register_cpts_from_globals');

function register_cpts_from_globals() {
    if (!isset($GLOBALS['cpt_creator']) || !isset($GLOBALS['languages'])) return;

    foreach ($GLOBALS['cpt_creator'] as $section) {
        foreach ($GLOBALS['languages'] as $lang_key => $lang_code) {
            if (!isset($section[$lang_key])) continue;

            $plural = $section[$lang_key][0];
            $singular = $section[$lang_key][1];
            $real_name = $section[$lang_key][2];

            // Nombre del CPT: plural + _ + código de idioma (DE, EN)
            $cpt_name = strtolower($plural);

            // Labels del CPT
            $labels = [
                'name' => ucfirst($real_name),
                'singular_name' => ucfirst($singular),
                'menu_name' => ucfirst($real_name) . ' ' . ucfirst($lang_code),
                'add_new' => 'Add New',
                'add_new_item' => 'Add New ' . ucfirst($singular),
                'edit_item' => 'Edit ' . ucfirst($singular),
                'new_item' => 'New ' . ucfirst($singular),
                'view_item' => 'View ' . ucfirst($singular),
                'search_items' => 'Search ' . ucfirst($real_name),
                'not_found' => 'No ' . ucfirst($real_name) . ' found',
                'not_found_in_trash' => 'No ' . ucfirst($real_name) . ' found in Trash',
            ];

            // Argumentos del CPT
            $args = [
                'labels' => $labels,
                'public' => true,
                'has_archive' => true,
                'show_in_rest' => true,
                'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
                'menu_position' => 5,
                'menu_icon' => 'dashicons-admin-post',  
            ];

            // Registrar CPT
            register_post_type($cpt_name, $args);
        }
    }
}
?>
