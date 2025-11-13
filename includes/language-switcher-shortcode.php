<?php
/**
 * Language Switcher Shortcode
 * 
 * Displays a language switcher that shows all available languages
 * and links to the connected posts in other languages.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the shortcode
 */
add_shortcode('wp_languages_switcher', 'wp_languages_switcher_shortcode');

/**
 * Shortcode callback function
 */
function wp_languages_switcher_shortcode($atts) {
    if (!isset($GLOBALS['languages']) || !isset($GLOBALS['all_cpt'])) {
        return '';
    }

    // Get current post
    global $post;
    if (!$post) {
        return '';
    }

    $post_id = $post->ID;
    $post_type = $post->post_type;

    // Find the group and current language for this post type
    $context = wp_languages_get_group_for_post_type($post_type);
    if (!$context) {
        return '';
    }

    $current_lang = $context['lang'];
    $group = $context['group'];

    // Get all connected posts
    $connected_posts = array();
    foreach ($group as $lang_key => $cpt) {
        if ($lang_key === $current_lang) {
            // Current language - use current post
            $connected_posts[$lang_key] = array(
                'id' => $post_id,
                'url' => get_permalink($post_id),
                'title' => get_the_title($post_id),
            );
        } else {
            // Other languages - get connected post
            $meta_key = wp_languages_meta_key($lang_key);
            $connected_id = absint(get_post_meta($post_id, $meta_key, true));
            
            if ($connected_id) {
                $connected_post = get_post($connected_id);
                if ($connected_post && $connected_post->post_status === 'publish') {
                    $connected_posts[$lang_key] = array(
                        'id' => $connected_id,
                        'url' => get_permalink($connected_id),
                        'title' => get_the_title($connected_id),
                    );
                }
            }
        }
    }

    // Build HTML
    ob_start();
    ?>
    <div class="wp-languages-switcher" data-post-id="<?php echo esc_attr($post_id); ?>" data-current-lang="<?php echo esc_attr($current_lang); ?>">
        <ul class="wp-languages-switcher-list">
            <?php foreach ($GLOBALS['languages'] as $lang_key => $lang_label): 
                $is_active = ($lang_key === $current_lang);
                $has_connection = isset($connected_posts[$lang_key]);
                $item_class = 'wp-languages-switcher-item';
                if ($is_active) {
                    $item_class .= ' active';
                }
                if (!$has_connection) {
                    $item_class .= ' no-connection';
                }
            ?>
                <li class="<?php echo esc_attr($item_class); ?>" data-lang="<?php echo esc_attr($lang_key); ?>">
                    <?php if ($has_connection): ?>
                        <a href="<?php echo esc_url($connected_posts[$lang_key]['url']); ?>" 
                           class="wp-languages-switcher-link"
                           data-lang="<?php echo esc_attr($lang_key); ?>"
                           data-post-id="<?php echo esc_attr($connected_posts[$lang_key]['id']); ?>">
                            <?php echo esc_html($lang_label); ?>
                        </a>
                    <?php else: ?>
                        <span class="wp-languages-switcher-link disabled">
                            <?php echo esc_html($lang_label); ?>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Helper function to get group and language for a post type
 * (Reuses function from post-connections.php if available, otherwise defines it)
 */
if (!function_exists('wp_languages_get_group_for_post_type')) {
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
}

/**
 * Helper function to build meta key for language connections
 * (Reuses function from post-connections.php if available, otherwise defines it)
 */
if (!function_exists('wp_languages_meta_key')) {
    function wp_languages_meta_key($lang_key) {
        return 'wp_languages_connected_' . sanitize_key($lang_key);
    }
}

