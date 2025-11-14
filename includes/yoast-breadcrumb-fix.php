<?php
/**
 * Yoast SEO Breadcrumb Fix for Multi-Language
 * 
 * Modifies Yoast breadcrumbs to use the correct home page for each language
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the current language based on post type
 */
function wp_languages_get_current_language() {
    global $post;
    
    if (!$post) {
        // Try to detect from URL or default to lang1
        return 'lang1';
    }
    
    $post_type = $post->post_type;
    $context = wp_languages_get_group_for_post_type($post_type);
    
    if ($context) {
        return $context['lang'];
    }
    
    // Default to lang1 if we can't determine
    return 'lang1';
}

/**
 * Get home page ID for a specific language
 */
function wp_languages_get_home_page_id($lang_key) {
    $option_name = 'wp_languages_home_page_' . sanitize_key($lang_key);
    $home_page_id = get_option($option_name, 0);
    
    // If no custom home page is set, return 0 (will use default home)
    return absint($home_page_id);
}

/**
 * Set home page ID for a specific language
 */
function wp_languages_set_home_page_id($lang_key, $page_id) {
    $option_name = 'wp_languages_home_page_' . sanitize_key($lang_key);
    update_option($option_name, absint($page_id));
}

/**
 * Get alternative breadcrumb text for a specific language
 */
function wp_languages_get_home_breadcrumb_text($lang_key) {
    $option_name = 'wp_languages_home_breadcrumb_text_' . sanitize_key($lang_key);
    return get_option($option_name, '');
}

/**
 * Set alternative breadcrumb text for a specific language
 */
function wp_languages_set_home_breadcrumb_text($lang_key, $text) {
    $option_name = 'wp_languages_home_breadcrumb_text_' . sanitize_key($lang_key);
    update_option($option_name, sanitize_text_field($text));
}

/**
 * Filter Yoast breadcrumb links to replace home URL
 */
add_filter('wpseo_breadcrumb_links', 'wp_languages_filter_yoast_breadcrumb_links');
function wp_languages_filter_yoast_breadcrumb_links($links) {
    if (empty($links) || !is_array($links)) {
        return $links;
    }
    
    $current_lang = wp_languages_get_current_language();
    $home_page_id = wp_languages_get_home_page_id($current_lang);
    
    // If no custom home page is set for this language, return original links
    if (!$home_page_id) {
        return $links;
    }
    
    $home_page = get_post($home_page_id);
    if (!$home_page || $home_page->post_status !== 'publish') {
        return $links;
    }
    
    // Replace the first link (home) with the language-specific home page
    if (isset($links[0]) && isset($links[0]['url'])) {
        $home_url = get_permalink($home_page_id);
        if ($home_url) {
            $links[0]['url'] = $home_url;
            // Use alternative text if set, otherwise use page title
            if (isset($links[0]['text'])) {
                $alternative_text = wp_languages_get_home_breadcrumb_text($current_lang);
                if (!empty($alternative_text)) {
                    $links[0]['text'] = $alternative_text;
                } else {
                    $links[0]['text'] = get_the_title($home_page_id);
                }
            }
        }
    }
    
    return $links;
}

/**
 * Add admin settings page to configure home pages for each language
 */
add_action('admin_menu', 'wp_languages_add_home_pages_menu');
function wp_languages_add_home_pages_menu() {
    add_submenu_page(
        'options-general.php',
        __('WP Languages Settings', 'wp-languages'),
        __('WP Languages Settings', 'wp-languages'),
        'manage_options',
        'wp-languages-home-pages',
        'wp_languages_home_pages_page'
    );
}

/**
 * Render the home pages configuration page
 */
function wp_languages_home_pages_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle form submission
    if (isset($_POST['wp_languages_save_home_pages']) && check_admin_referer('wp_languages_home_pages')) {
        if (isset($GLOBALS['languages'])) {
            foreach ($GLOBALS['languages'] as $lang_key => $lang_name) {
                $page_id = isset($_POST['home_page_' . $lang_key]) ? absint($_POST['home_page_' . $lang_key]) : 0;
                wp_languages_set_home_page_id($lang_key, $page_id);
                
                $breadcrumb_text = isset($_POST['breadcrumb_text_' . $lang_key]) ? sanitize_text_field($_POST['breadcrumb_text_' . $lang_key]) : '';
                wp_languages_set_home_breadcrumb_text($lang_key, $breadcrumb_text);
            }
            echo '<div class="notice notice-success"><p>' . __('Settings saved successfully.', 'wp-languages') . '</p></div>';
        }
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php _e('Configure the home page for each language. This page will be used in the Yoast SEO breadcrumb.', 'wp-languages'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('wp_languages_home_pages'); ?>
            
            <table class="form-table">
                <?php if (isset($GLOBALS['languages'])): ?>
                    <?php foreach ($GLOBALS['languages'] as $lang_key => $lang_name): ?>
                        <?php 
                        $current_home_id = wp_languages_get_home_page_id($lang_key);
                        $current_breadcrumb_text = wp_languages_get_home_breadcrumb_text($lang_key);
                        
                        // Get all pages for this language's post type
                        $post_type = 'page';
                        if ($lang_key === 'lang2') {
                            $post_type = 'pages_en';
                        } elseif ($lang_key === 'lang3') {
                            $post_type = 'paginas';
                        }
                        
                        // Get all pages of this post type
                        $pages = get_posts(array(
                            'post_type' => $post_type,
                            'post_status' => 'publish',
                            'numberposts' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                        ));
                        ?>
                        <tr>
                            <th scope="row">
                                <label for="home_page_<?php echo esc_attr($lang_key); ?>">
                                    <?php echo esc_html($lang_name); ?>
                                </label>
                            </th>
                            <td>
                                <select name="home_page_<?php echo esc_attr($lang_key); ?>" id="home_page_<?php echo esc_attr($lang_key); ?>" style="width: 100%; max-width: 400px;">
                                    <option value="0"><?php _e('— Select —', 'wp-languages'); ?></option>
                                    <?php foreach ($pages as $page): ?>
                                        <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($current_home_id, $page->ID); ?>>
                                            <?php echo esc_html($page->post_title); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description">
                                    <?php printf(__('Home page for %s. Will be used in the Yoast SEO breadcrumb.', 'wp-languages'), esc_html($lang_name)); ?>
                                </p>
                                
                                <p style="margin-top: 10px;">
                                    <label for="breadcrumb_text_<?php echo esc_attr($lang_key); ?>" style="display: block; margin-bottom: 5px; font-weight: 600;">
                                        <?php _e('Alternative Breadcrumb Text:', 'wp-languages'); ?>
                                    </label>
                                    <input type="text" 
                                           name="breadcrumb_text_<?php echo esc_attr($lang_key); ?>" 
                                           id="breadcrumb_text_<?php echo esc_attr($lang_key); ?>" 
                                           value="<?php echo esc_attr($current_breadcrumb_text); ?>" 
                                           class="regular-text"
                                           placeholder="<?php _e('e.g., Home, Start, etc.', 'wp-languages'); ?>" />
                                    <p class="description">
                                        <?php _e('Optional: Enter an alternative text to display in the breadcrumb instead of the page title. The link will remain the same, only the visible text will change.', 'wp-languages'); ?>
                                    </p>
                                </p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
            
            <?php submit_button(__('Save Changes', 'wp-languages'), 'primary', 'wp_languages_save_home_pages'); ?>
        </form>
    </div>
    <?php
}

/**
 * Helper function to get group for post type (reuse from other files)
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

