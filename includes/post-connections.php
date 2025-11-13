<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'wp_languages_register_connection_meta');
add_action('rest_api_init', 'wp_languages_register_rest_routes');

/**
 * Registers the post meta keys used to store language connections.
 */
function wp_languages_register_connection_meta() {
    if (empty($GLOBALS['languages']) || !is_array($GLOBALS['languages'])) {
        return;
    }

    foreach ($GLOBALS['languages'] as $lang_key => $label) {
        register_meta(
            'post',
            wp_languages_meta_key($lang_key),
            array(
                'type'              => 'integer',
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => 'absint',
                'auth_callback'     => function ($allowed, $meta_key, $post_id) {
                    return current_user_can('edit_post', $post_id);
                },
            )
        );
    }
}

/**
 * Registers the REST API routes used by the Gutenberg panel.
 */
function wp_languages_register_rest_routes() {
    register_rest_route(
        'wp-languages/v1',
        '/connections',
        array(
            'methods'             => 'GET',
            'callback'            => 'wp_languages_rest_get_connections',
            'permission_callback' => 'wp_languages_rest_can_edit_post',
            'args'                => array(
                'post_id' => array(
                    'required' => true,
                    'type'     => 'integer',
                ),
            ),
        )
    );

    register_rest_route(
        'wp-languages/v1',
        '/available',
        array(
            'methods'             => 'GET',
            'callback'            => 'wp_languages_rest_get_available_posts',
            'permission_callback' => 'wp_languages_rest_can_edit_post',
            'args'                => array(
                'post_id'    => array(
                    'required' => true,
                    'type'     => 'integer',
                ),
                'target_lang' => array(
                    'required' => true,
                    'type'     => 'string',
                ),
            ),
        )
    );

    register_rest_route(
        'wp-languages/v1',
        '/connect',
        array(
            'methods'             => 'POST',
            'callback'            => 'wp_languages_rest_connect_posts',
            'permission_callback' => 'wp_languages_rest_can_edit_post',
            'args'                => array(
                'post_id'        => array(
                    'required' => true,
                    'type'     => 'integer',
                ),
                'target_lang'    => array(
                    'required' => true,
                    'type'     => 'string',
                ),
                'target_post_id' => array(
                    'required' => false,
                    'type'     => 'integer',
                ),
            ),
        )
    );
}

/**
 * Permission callback shared across the REST endpoints.
 */
function wp_languages_rest_can_edit_post($request) {
    $post_id = isset($request['post_id']) ? absint($request['post_id']) : 0;
    if (!$post_id) {
        return false;
    }

    return current_user_can('edit_post', $post_id);
}

/**
 * Returns the post context (group and language) from $GLOBALS['all_cpt'].
 */
function wp_languages_get_post_context($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return null;
    }

    $post_type = $post->post_type;
    $group = wp_languages_get_group_for_post_type($post_type);
    if (!$group) {
        return null;
    }

    return array(
        'post_type'   => $post_type,
        'source_lang' => $group['lang'],
        'group'       => $group['group'],
    );
}

/**
 * Finds the CPT group entry for a given post type.
 */
function wp_languages_get_group_for_post_type($post_type) {
    if (empty($GLOBALS['all_cpt']) || !is_array($GLOBALS['all_cpt'])) {
        return null;
    }

    foreach ($GLOBALS['all_cpt'] as $group) {
        if (!is_array($group)) {
            continue;
        }

        foreach ($group as $lang_key => $cpt) {
            if ($cpt === $post_type) {
                return array(
                    'group' => $group,
                    'lang'  => $lang_key,
                );
            }
        }
    }

    return null;
}

/**
 * Builds the meta key used to store a connection for a language.
 */
function wp_languages_meta_key($lang_key) {
    return 'wp_languages_connected_' . sanitize_key($lang_key);
}

/**
 * REST callback: returns the current connections for a post.
 */
function wp_languages_rest_get_connections(WP_REST_Request $request) {
    $post_id = absint($request->get_param('post_id'));
    $context = wp_languages_get_post_context($post_id);

    if (!$context) {
        return new WP_Error('wp_languages_invalid_post', __('No se pudo determinar el idioma del post.', 'wp-languages'), array('status' => 400));
    }

    $connections = array();
    foreach ($context['group'] as $lang_key => $cpt) {
        if ($lang_key === $context['source_lang']) {
            continue;
        }

        $meta_key   = wp_languages_meta_key($lang_key);
        $target_id  = absint(get_post_meta($post_id, $meta_key, true));

        if ($target_id) {
            $target_post = get_post($target_id);
            if ($target_post) {
                $connections[$lang_key] = array(
                    'id'        => $target_id,
                    'title'     => get_the_title($target_post),
                    'status'    => get_post_status($target_post),
                    'edit_link' => get_edit_post_link($target_id, 'raw'),
                );
            } else {
                delete_post_meta($post_id, $meta_key);
            }
        }
    }

    return array(
        'post_id'     => $post_id,
        'post_type'   => $context['post_type'],
        'source_lang' => $context['source_lang'],
        'group'       => $context['group'],
        'connections' => $connections,
    );
}

/**
 * REST callback: returns the available posts for a target language.
 */
function wp_languages_rest_get_available_posts(WP_REST_Request $request) {
    $post_id    = absint($request->get_param('post_id'));
    $target_lang = sanitize_key($request->get_param('target_lang'));
    $context    = wp_languages_get_post_context($post_id);

    if (!$context) {
        return new WP_Error('wp_languages_invalid_post', __('No se pudo determinar el idioma del post.', 'wp-languages'), array('status' => 400));
    }

    if (!isset($context['group'][$target_lang])) {
        return new WP_Error('wp_languages_invalid_lang', __('El idioma seleccionado no está disponible para este tipo de contenido.', 'wp-languages'), array('status' => 400));
    }

    if ($target_lang === $context['source_lang']) {
        return new WP_Error('wp_languages_same_lang', __('No es posible conectar el post con el mismo idioma.', 'wp-languages'), array('status' => 400));
    }

    $target_cpt = $context['group'][$target_lang];
    $meta_key   = wp_languages_meta_key($context['source_lang']);

    $query = new WP_Query(
        array(
            'post_type'      => $target_cpt,
            'post_status'    => array('publish', 'draft', 'pending', 'future'),
            'posts_per_page' => 100,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => $meta_key,
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => $meta_key,
                    'value'   => $post_id,
                    'compare' => '=',
                    'type'    => 'NUMERIC',
                ),
            ),
        )
    );

    $posts = array();
    foreach ($query->posts as $target_post) {
        $posts[] = array(
            'id'     => $target_post->ID,
            'title'  => get_the_title($target_post),
            'status' => $target_post->post_status,
        );
    }

    wp_reset_postdata();

    return array(
        'post_id'     => $post_id,
        'target_lang' => $target_lang,
        'posts'       => $posts,
    );
}

/**
 * REST callback: connects (or disconnects) a post with another language.
 */
function wp_languages_rest_connect_posts(WP_REST_Request $request) {
    $post_id        = absint($request->get_param('post_id'));
    $target_lang    = sanitize_key($request->get_param('target_lang'));
    $target_post_id = absint($request->get_param('target_post_id'));

    $context = wp_languages_get_post_context($post_id);
    if (!$context) {
        return new WP_Error('wp_languages_invalid_post', __('No se pudo determinar el idioma del post.', 'wp-languages'), array('status' => 400));
    }

    if (!isset($context['group'][$target_lang])) {
        return new WP_Error('wp_languages_invalid_lang', __('El idioma seleccionado no está disponible para este tipo de contenido.', 'wp-languages'), array('status' => 400));
    }

    if ($target_lang === $context['source_lang']) {
        return new WP_Error('wp_languages_same_lang', __('No es posible conectar el post con el mismo idioma.', 'wp-languages'), array('status' => 400));
    }

    $meta_key_source = wp_languages_meta_key($target_lang);
    $meta_key_target = wp_languages_meta_key($context['source_lang']);
    $current_target  = absint(get_post_meta($post_id, $meta_key_source, true));

    // Disconnect if no target_post_id provided.
    if (!$target_post_id) {
        if ($current_target) {
            delete_post_meta($post_id, $meta_key_source);
            delete_post_meta($current_target, $meta_key_target);
        }
        return wp_languages_rest_get_connections($request);
    }

    $target_post = get_post($target_post_id);
    if (!$target_post || $target_post->post_type !== $context['group'][$target_lang]) {
        return new WP_Error('wp_languages_invalid_target', __('El post seleccionado no pertenece al idioma correspondiente.', 'wp-languages'), array('status' => 400));
    }

    if (!current_user_can('edit_post', $target_post_id)) {
        return new WP_Error('wp_languages_forbidden', __('No tienes permisos para editar el post destino.', 'wp-languages'), array('status' => 403));
    }

    $existing_source = absint(get_post_meta($target_post_id, $meta_key_target, true));
    if ($existing_source && $existing_source !== $post_id) {
        return new WP_Error('wp_languages_already_connected', __('El post seleccionado ya está emparejado con otro post.', 'wp-languages'), array('status' => 400));
    }

    if ($current_target && $current_target !== $target_post_id) {
        delete_post_meta($current_target, $meta_key_target);
    }

    update_post_meta($post_id, $meta_key_source, $target_post_id);
    update_post_meta($target_post_id, $meta_key_target, $post_id);

    return wp_languages_rest_get_connections($request);
}

?>

