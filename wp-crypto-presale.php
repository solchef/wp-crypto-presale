<?php
/**
 * Plugin Name: WP Crypto Presale
 * Description: A WordPress plugin for handling crypto presales.
 * Version: 1.0.0
 * Author: Solchef
 * Author URI: https://wpcrypto.com
 * Text Domain: wp-crypto-presale
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu.
function wp_crypto_presale_menu() {
    add_menu_page(
        'WP Crypto Presale',
        'Crypto Presale',
        'manage_options',
        'wp-crypto-presale',
        'wp_crypto_presale_settings_page',
        'dashicons-chart-area',
        30
    );
}
add_action('admin_menu', 'wp_crypto_presale_menu');

// Enqueue admin scripts and styles.
function wp_crypto_presale_admin_enqueue_scripts($hook) {
    if ('toplevel_page_wp-crypto-presale' !== $hook) {
        return;
    }

    // Enqueue styles.
    wp_enqueue_style('wp-crypto-presale-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', [], '1.0.0');

    // Enqueue scripts.
    wp_enqueue_script('wp-crypto-presale-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin.js', ['jquery'], '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'wp_crypto_presale_admin_enqueue_scripts');

// Settings page content.
function wp_crypto_presale_settings_page() {
    // Check user capabilities.
    if (!current_user_can('manage_options')) {
        return;
    }

    // Variables for the form fields and sections.
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_settings';

    // Save settings.
    if (isset($_POST['submit'])) {
        // Handle form submission and save settings.
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    ?>

    <div class="wrap">
        <h2>WP Crypto Presale Settings</h2>

        <!-- Tabs -->
        <h2 class="nav-tab-wrapper">
            <a href="?page=wp-crypto-presale&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">General Settings</a>
            <a href="?page=wp-crypto-presale&tab=presale_details" class="nav-tab <?php echo $active_tab == 'presale_details' ? 'nav-tab-active' : ''; ?>">Presale Details</a>
            <a href="?page=wp-crypto-presale&tab=nextjs_widget" class="nav-tab <?php echo $active_tab == 'nextjs_widget' ? 'nav-tab-active' : ''; ?>">Next.js Widget</a>
        </h2>

        <!-- Form -->
        <form method="post" action="">
            <?php
            if ($active_tab == 'general_settings') {
                settings_fields('wp_crypto_presale_general_settings');
                do_settings_sections('wp_crypto_presale_general_settings');
            } elseif ($active_tab == 'presale_details') {
                settings_fields('wp_crypto_presale_presale_details');
                do_settings_sections('wp_crypto_presale_presale_details');
            } elseif ($active_tab == 'nextjs_widget') {
                settings_fields('wp_crypto_presale_nextjs_settings');
                do_settings_sections('wp_crypto_presale_nextjs_settings');
            }

            submit_button('Save Settings', 'primary', 'submit', true);
            ?>
        </form>
    </div>

    <?php
}

function enqueue_nextjs_scripts($hook) {
    if ('toplevel_page_wp-crypto-presale' !== $hook) {
        return;
    }
}
add_action('admin_enqueue_scripts', 'enqueue_nextjs_scripts');

// Register settings.
function wp_crypto_presale_register_settings() {
    // General Settings
    register_setting('wp_crypto_presale_general_settings', 'wp_crypto_presale_project_name');
    register_setting('wp_crypto_presale_general_settings', 'wp_crypto_presale_presale_token');
    register_setting('wp_crypto_presale_general_settings', 'wp_crypto_presale_presale_receiving_address');

    // Presale Details
    register_setting('wp_crypto_presale_presale_details', 'wp_crypto_presale_presale_details');

    // Next.js Widget Settings
    register_setting('wp_crypto_presale_nextjs_settings', 'wp_crypto_presale_button_color');
    register_setting('wp_crypto_presale_nextjs_settings', 'wp_crypto_presale_button_text_color');

    // Sections
    add_settings_section('wp_crypto_presale_general_settings_section', 'General Settings', 'wp_crypto_presale_general_settings_section_callback', 'wp_crypto_presale_general_settings');
    add_settings_section('wp_crypto_presale_presale_details_section', 'Presale Details', 'wp_crypto_presale_presale_details_section_callback', 'wp_crypto_presale_presale_details');
    add_settings_section('wp_crypto_presale_nextjs_settings_section', 'Next.js Widget Settings', 'wp_crypto_presale_nextjs_settings_section_callback', 'wp_crypto_presale_nextjs_settings');

    // Fields
    // General Settings Fields
    add_settings_field('wp_crypto_presale_project_name', 'Project Name', 'wp_crypto_presale_project_name_callback', 'wp_crypto_presale_general_settings', 'wp_crypto_presale_general_settings_section');
    add_settings_field('wp_crypto_presale_presale_token', 'Presale Token Used', 'wp_crypto_presale_presale_token_callback', 'wp_crypto_presale_general_settings', 'wp_crypto_presale_general_settings_section');
    add_settings_field('wp_crypto_presale_presale_receiving_address', 'Presale Receiving Address', 'wp_crypto_presale_presale_receiving_address_callback', 'wp_crypto_presale_general_settings', 'wp_crypto_presale_general_settings_section');

    // Presale Details Fields
    add_settings_field('wp_crypto_presale_presale_details', 'Presale Details', 'wp_crypto_presale_presale_details_callback', 'wp_crypto_presale_presale_details', 'wp_crypto_presale_presale_details_section');

    // Next.js Widget Settings Fields
    add_settings_field('wp_crypto_presale_button_color', 'Button Color', 'wp_crypto_presale_button_color_callback', 'wp_crypto_presale_nextjs_settings', 'wp_crypto_presale_nextjs_settings_section');
    add_settings_field('wp_crypto_presale_button_text_color', 'Button Text Color', 'wp_crypto_presale_button_text_color_callback', 'wp_crypto_presale_nextjs_settings', 'wp_crypto_presale_nextjs_settings_section');
}
add_action('admin_init', 'wp_crypto_presale_register_settings');

// Section Callbacks
function wp_crypto_presale_general_settings_section_callback() {
    echo '<p>Enter your general settings here.</p>';
}

function wp_crypto_presale_presale_details_section_callback() {
    echo '<p>Enter your presale details here.</p>';
}

function wp_crypto_presale_nextjs_settings_section_callback() {
    echo '<p>Customize the appearance of the Next.js widget.</p>';
}

// Field Callbacks
function wp_crypto_presale_project_name_callback() {
    $project_name = get_option('wp_crypto_presale_project_name');
    echo "<input type='text' name='wp_crypto_presale_project_name' value='$project_name' />";
}

function wp_crypto_presale_presale_token_callback() {
    $presale_token = get_option('wp_crypto_presale_presale_token');
    echo "<input type='text' name='wp_crypto_presale_presale_token' value='$presale_token' />";
}

function wp_crypto_presale_presale_receiving_address_callback() {
    $presale_receiving_address = get_option('wp_crypto_presale_presale_receiving_address');
    echo "<input type='text' name='wp_crypto_presale_presale_receiving_address' value='$presale_receiving_address' />";
}

function wp_crypto_presale_presale_details_callback() {
    $presale_details = get_option('wp_crypto_presale_presale_details');
    echo "<textarea name='wp_crypto_presale_presale_details' rows='5' cols='50'>$presale_details</textarea>";
}

function wp_crypto_presale_button_color_callback() {
    $button_color = get_option('wp_crypto_presale_button_color', '#4CAF50'); // Default color
    echo "<input type='color' name='wp_crypto_presale_button_color' value='$button_color' />";
}

function wp_crypto_presale_button_text_color_callback() {
    $button_text_color = get_option('wp_crypto_presale_button_text_color', '#FFFFFF'); // Default color
    echo "<input type='color' name='wp_crypto_presale_button_text_color' value='$button_text_color' />";
}

// Shortcode
function nextjs_presale_shortcode() {
    $button_color = get_option('wp_crypto_presale_button_color', '#4CAF50');
    $button_text_color = get_option('wp_crypto_presale_button_text_color', '#FFFFFF');

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <meta name="next-head-count" content="2" />
        <?php
        wp_enqueue_style('nextjs-style', plugin_dir_url(__FILE__) . 'out/_next/static/css/418449e9d0434868.css', array(), '1.0.0');

        wp_enqueue_script('nextjs-polyfills', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/polyfills-78c92fac7aa8fdd8.js', array(), '1.0.0', true);
        wp_enqueue_script('nextjs-webpack', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/webpack-8d9aa3767d4a4207.js', array('nextjs-polyfills'), '1.0.0', true);
        wp_enqueue_script('nextjs-framework', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/framework-ff7f418116f76b2d.js', array('nextjs-webpack'), '1.0.0', true);
        wp_enqueue_script('nextjs-main', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/main-b5fef5a603b72c4e.js', array('nextjs-framework'), '1.0.0', true);
        wp_enqueue_script('nextjs-app', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/pages/_app-62225f762e3da357.js', array('nextjs-main'), '1.0.0', true);
        wp_enqueue_script('nextjs-338', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/338-e2e31d80007310d1.js', array('nextjs-app'), '1.0.0', true);
        wp_enqueue_script('nextjs-939', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/939-71a2352314ae5926.js', array('nextjs-338'), '1.0.0', true);
        wp_enqueue_script('nextjs-presale', plugin_dir_url(__FILE__) . 'out/_next/static/chunks/pages/presale-33e9a2858fc2a5f2.js', array('nextjs-main'), '1.0.0', true);
        wp_enqueue_script('nextjs-buildManifest', plugin_dir_url(__FILE__) . 'out/_next/static/RNu86c65ZR20nd7hqo_e-/_buildManifest.js', array('nextjs-presale'), '1.0.0', true);
        wp_enqueue_script('nextjs-ssgManifest', plugin_dir_url(__FILE__) . 'out/_next/static/RNu86c65ZR20nd7hqo_e-/_ssgManifest.js', array('nextjs-buildManifest'), '1.0.0', true);
        ?>
    </head>
    <body>
        <div id="__next">
            <div data-is-root-theme="true" data-accent-color="indigo" data-gray-color="slate" data-has-background="true"
                data-panel-background="translucent" data-radius="medium" data-scaling="100%" class="radix-themes">
                <div class="rt-Container rt-r-size-4">
                    <div class="rt-ContainerInner">
                        <div style="--max-width:450px" class="rt-Flex rt-r-fd-column rt-r-gap-3 rt-r-max-w">
                            <div class="rt-reset rt-BaseCard rt-Card rt-r-size-3 rt-variant-surface">
                                <div class="rt-Flex rt-r-fd-column rt-r-gap-3"><button data-accent-color="" type="button"
                                        aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-:Rm:"
                                        data-state="closed"
                                        class="rt-reset rt-BaseButton rt-r-size-2 rt-variant-solid rt-Button Button violet">Connect
                                        Wallet</button>
                                    <form>
                                        <div class="FormField">
                                            <div style="display:flex;align-items:baseline;justify-content:space-between">
                                                <label class="FormLabel" for="radix-:R36:">Email</label></div><input
                                                class="Input" name="value" type="number" required="" title=""
                                                id="radix-:R36:" />
                                        </div><button data-accent-color="" type="submit"
                                            class="rt-reset rt-BaseButton rt-r-size-4 rt-variant-soft rt-Button Button">Send</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script id="__NEXT_DATA__"
            type="application/json">{"props":{"pageProps":{}},"page":"/presale","query":{},"buildId":"RNu86c65ZR20nd7hqo_e-","nextExport":true,"autoExport":true,"isFallback":false,"scriptLoader":[]}</script>
    </body>
    </html>
    <?php
    return ob_get_clean();
}
add_shortcode('nextjs_presale', 'nextjs_presale_shortcode');
