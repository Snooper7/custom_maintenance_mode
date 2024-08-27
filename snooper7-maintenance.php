<?php
/*
 * Plugin Name: Custom Maintenance Mode
 * Plugin URI:  https://ya.ru
 * Description: Позволяет включать/выключать режим обслуживания с кастомной страницей-заглушкой
 * Version: 0.9
 * Author: Snooper7
 * Author URI: https://github.com/Snooper7
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: snooper7
 * Domain Path: /languages
 *
 * Network: true
 */

define('PMG_MAINT_PATH', plugin_dir_path(__FILE__));
define('PMG_MAINT_URL', plugin_dir_url(__FILE__));


add_action('init', 'pmg_maint_init');
/**
 * Hooked into `init`.  Adds other actions if there isn't an admin viewing the
 * site.
 *
 * @uses add_filter
 */
function pmg_maint_init()
{
    if (current_user_can('manage_options')) {
        return;
    }

    add_filter('status_header', 'pmg_maint_change_status', 10, 4);
    add_filter('wp_headers', 'pmg_maint_headers');
    add_filter('template_include', 'pmg_maint_template');
}

/**
 * Changes the status header to 503.
 *
 * @uses get_status_header_desc
 * @return string The status header
 */
function pmg_maint_change_status($header, $status_code, $text, $proto)
{
    $text = get_status_header_desc(503);
    return "{$proto} 503 {$text}";
}


/**
 * Hooked into `wp_headers`.  Adds the `Retry-After` header
 *
 * @return array The array of HTTP headers
 */
function pmg_maint_headers($headers)
{
    $headers['Retry-After'] = 3600;
    return $headers;
}


/**
 * Hooked into `template_include`, returns a new template for all the pages
 *
 * @return string The full path to the template file
 */
function pmg_maint_template($t)
{
    return PMG_MAINT_PATH . 'inc/template.php';
}
