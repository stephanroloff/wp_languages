<?php
if (!isset($GLOBALS['languages'])) {
    return;
}

add_action('admin_bar_menu', function($admin_bar) {
    if (!is_admin()) {
        // Only within wp-admin
        return;
    }

    if (!isset($GLOBALS['languages'])) return;

    $currentLang = isset($_GET['lang']) ? $_GET['lang'] : key($GLOBALS['languages']);
    $menu_id = 'language-switcher';

    $admin_bar->add_menu(array(
        'id'    => $menu_id,
        'title' => $GLOBALS['languages'][$currentLang],
        'href'  => false,
        'meta'  => array('class' => 'language-switcher-' . $currentLang,),
    ));
    

    foreach ($GLOBALS['languages'] as $langCode => $langName) {
        if ($langCode === $currentLang) continue;

        $url = add_query_arg('lang', $langCode);
        $admin_bar->add_menu(array(
            'id'     => $langCode,
            'parent' => $menu_id,
            'title'  => $langName,
            'href'   => '/wp-admin/index.php?lang=' . $langCode,
            'meta'   => array('class' => 'language-switcher-option')
        ));
    }
}, 100);




add_action('admin_menu', 'hide_cpt_menu_item', 999);

function hide_cpt_menu_item() {
    
    foreach ($GLOBALS['cpt_creator'] as $cpt) {
        foreach ($cpt as $lang => $cpt_name) {
            if(isset($_GET['lang']) && $_GET['lang'] !== $lang) {
                remove_menu_page('edit.php?post_type=' . strtolower($cpt_name[0]));
            }
            if(isset($_GET['lang']) && $_GET['lang'] !== 'lang1') {
                foreach ($GLOBALS['cpt_existing'] as $cpt) {
                remove_menu_page('edit.php?post_type=stellen');
                    remove_menu_page('edit.php?post_type=' . $cpt);
                }
                remove_menu_page('edit.php?post_type=page');
                remove_menu_page('edit.php');
            }
        }
    }
}







// Guardar la opción elegida de idioma en una cookie

add_action('admin_init', function() {
    if (isset($_GET['lang']) && isset($GLOBALS['languages'][$_GET['lang']])) {
        // Guardar en una cookie por 30 días
        setcookie('wp_languages_current_language', $_GET['lang'], time() + 60 * 60 * 24 * 30, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true);
        // Opcional: guardar también como user meta para persistencia a nivel de usuario
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'wp_languages_current_language', $_GET['lang']);
        }
    }
});

// Recuperar la opción elegida si no está en GET
add_filter('init', function() {
    if (!isset($_GET['lang'])) {
        // 1. Intentar user meta si el usuario está logueado
        if (is_user_logged_in()) {
            $lang = get_user_meta(get_current_user_id(), 'wp_languages_current_language', true);
            if ($lang && isset($GLOBALS['languages'][$lang])) {
                $_GET['lang'] = $lang;
                return;
            }
        }
        // 2. Si no, intentar cookie
        if (isset($_COOKIE['wp_languages_current_language']) && isset($GLOBALS['languages'][$_COOKIE['wp_languages_current_language']])) {
            $_GET['lang'] = $_COOKIE['wp_languages_current_language'];
        }
    }
});






