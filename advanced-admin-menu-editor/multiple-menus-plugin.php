// Extract settings
        $menu_bg = isset($theme_settings['menu_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_bg_color']) : '#ffffff';
        $menu_text = isset($theme_settings['menu_text_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_text_color']) : '#000000';
        $menu_hover_bg = isset($theme_settings['menu_hover_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_hover_bg_color']) : '#000000';
        $menu_hover_text = isset($theme_settings['menu_hover_text_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_hover_text_color']) : '#ffffff';
        $submenu_bg = isset($theme_settings['submenu_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_bg_color']) : '#f8f8f8';
        $submenu_text = isset($theme_settings['submenu_text_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_text_color']) : '#000000';
        $submenu_hover_bg = isset($theme_settings['submenu_hover_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_hover_bg_color']) : '#000000';
        $submenu_hover_text = isset($theme_settings['submenu_hover_text_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_hover_text_color']) : '#ffffff';
        $border_radius = isset($theme_settings['border_radius']) ? intval($theme_settings['border_radius']) : 4;
        $transition_speed = isset($theme_settings['transition_speed']) ? floatval($theme_settings['transition_speed']) : 0.3;
        $spacing = isset($theme_settings['spacing']) ? intval($theme_settings['spacing']) : 15;
        $custom_css = isset($theme_settings['custom_css']) ? wp_strip_all_tags($theme_settings['custom_css']) : '';
        
        // Typography options
        $enable_custom_fonts = isset($theme_settings['enable_custom_fonts']) ? (bool) $theme_settings['enable_custom_fonts'] : false;
        $primary_font = isset($theme_settings['primary_font']) ? sanitize_text_field($theme_settings['primary_font']) : 'default';
        $secondary_font = isset($theme_settings['secondary_font']) ? sanitize_text_field($theme_settings['secondary_font']) : 'default';
        $font_size = isset($theme_settings['font_size']) ? intval($theme_settings['font_size']) : 14;
        $font_weight = isset($theme_settings['font_weight']) ? sanitize_text_field($theme_settings['font_weight']) : 'normal';
        <?php

/**
 * Plugin Name: Advanced Admin Menu Editor
 * Plugin URI: https://example.com/advanced-admin-menu-editor
 * Description: Customize your WordPress admin menu with support for multiple configurations and theme customization.
 * Version: 2.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: multiple-menus-admin-editor
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('CAME_VERSION', '2.0.0');
define('CAME_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CAME_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CAME_DEBUG', false); // Toggle for debug mode

/**
 * Main plugin class
 */
class Advanced_Admin_Menu_Editor {
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * All menu configurations
     *
     * @var array
     */
    private $all_menu_configs = array();
    
    /**
     * Active configuration ID
     *
     * @var string
     */
    private $active_config_id = '';
    
    /**
     * Flag to prevent duplicate processing
     * 
     * @var bool
     */
    private $menu_processed = false;

    /**
     * Initialize the plugin.
     */
    private function __construct() {
        // Load plugin textdomain
        add_action('init', array($this, 'came_load_textdomain'));
        
        // Load saved menu configurations
        $this->all_menu_configs = get_option('came_menu_configs', array());
        
        // Set the active configuration ID from user meta (if exists)
        $user_id = get_current_user_id();
        if ($user_id) {
            $user_active_config = get_user_meta($user_id, 'came_active_config', true);
            if (!empty($user_active_config) && isset($this->all_menu_configs[$user_active_config])) {
                $this->active_config_id = $user_active_config;
            }
        }
        
        // Setup default configuration if none exist
        if (empty($this->all_menu_configs)) {
            $this->came_setup_default_config();
        }
        
        // Add admin menu item
        add_action('admin_menu', array($this, 'came_add_admin_menu'));

        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'came_enqueue_admin_assets'));

        // Filter admin menu
        add_action('admin_menu', array($this, 'came_modify_admin_menu'), 999);

        // Register AJAX handlers
        add_action('wp_ajax_came_save_menu', array($this, 'came_ajax_save_menu'));
        add_action('wp_ajax_came_reset_menu', array($this, 'came_ajax_reset_menu'));
        add_action('wp_ajax_came_add_config', array($this, 'came_ajax_add_config'));
        add_action('wp_ajax_came_delete_config', array($this, 'came_ajax_delete_config'));
        add_action('wp_ajax_came_load_config', array($this, 'came_ajax_load_config'));

        // Add settings link to plugins page
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'came_add_settings_link'));
        
        // Add debug info
        if (CAME_DEBUG) {
            add_action('admin_notices', array($this, 'came_debug_info'));
        }
        
        // Theme customizer 
        add_action('admin_head', array($this, 'came_inject_theme_css'));
        add_action('wp_ajax_came_save_theme', array($this, 'came_ajax_save_theme'));
    }
    
    /**
     * Load plugin textdomain
     */
    public function came_load_textdomain() {
        load_plugin_textdomain('multiple-menus-admin-editor', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Setup default configuration
     */
    private function came_setup_default_config() {
        $this->all_menu_configs = array(
            'default' => array(
                'name' => esc_html__('Default Configuration', 'multiple-menus-admin-editor'),
                'menu_items' => array(),
                'allowed_roles' => array('administrator'),
                'is_active' => true
            )
        );
        
        // Save default configuration
        update_option('came_menu_configs', $this->all_menu_configs);
        
        // Set active configuration
        $this->active_config_id = 'default';
    }
    
    /**
     * Get active configuration
     * 
     * @return array Active configuration array or empty array
     */
    public function get_active_config() {
        // If no active config set, find the first active one or use the first available
        if (empty($this->active_config_id)) {
            foreach ($this->all_menu_configs as $config_id => $config) {
                if (isset($config['is_active']) && $config['is_active']) {
                    $this->active_config_id = $config_id;
                    break;
                }
            }
            
            // If still no active config, use the first one
            if (empty($this->active_config_id) && !empty($this->all_menu_configs)) {
                reset($this->all_menu_configs);
                $this->active_config_id = key($this->all_menu_configs);
            }
        }
        
        // Return the active configuration or empty array if none found
        return isset($this->all_menu_configs[$this->active_config_id]) 
               ? $this->all_menu_configs[$this->active_config_id] 
               : array();
    }

    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Add the admin menu item as a top-level menu
     */
    public function came_add_admin_menu() {
        // Add top-level menu
        add_menu_page(
            esc_html__('Menu Editor', 'multiple-menus-admin-editor'),
            esc_html__('Menu Editor', 'multiple-menus-admin-editor'),
            'manage_options',
            'advanced-admin-menu-editor',
            array($this, 'came_display_plugin_admin_page'),
            'dashicons-layout', // You can change this icon as desired
            30 // Position in the menu
        );
        
        // Add submenu items
        add_submenu_page(
            'advanced-admin-menu-editor',
            esc_html__('Menu Structure', 'multiple-menus-admin-editor'),
            esc_html__('Menu Structure', 'multiple-menus-admin-editor'),
            'manage_options',
            'advanced-admin-menu-editor',
            array($this, 'came_display_plugin_admin_page')
        );
        
        // Add Theme Customizer submenu
        add_submenu_page(
            'advanced-admin-menu-editor',
            esc_html__('Theme Customizer', 'multiple-menus-admin-editor'),
            esc_html__('Theme Customizer', 'multiple-menus-admin-editor'),
            'manage_options',
            'admin-menu-theme-customizer',
            array($this, 'came_display_theme_customizer_page')
        );
    }

    /**
     * Add settings link to plugin list
     *
     * @param array $links Array of plugin action links
     * @return array Modified array of plugin action links
     */
    public function came_add_settings_link($links) {
        $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=advanced-admin-menu-editor')) . '">' . esc_html__('Settings', 'multiple-menus-admin-editor') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Enqueue admin scripts and styles
     * 
     * @param string $hook Current admin page hook
     */
    public function came_enqueue_admin_assets($hook) {
        // Check if we're on the plugin's admin pages
        if (!in_array($hook, array(
            'toplevel_page_advanced-admin-menu-editor',
            'menu-editor_page_admin-menu-theme-customizer'
        ))) {
            return;
        }

        // Check if we're on the theme customizer page
        if ('menu-editor_page_admin-menu-theme-customizer' === $hook) {
            // Check if CSS file exists before enqueueing
            $theme_css_file = CAME_PLUGIN_DIR . 'assets/css/theme-customizer.css';
            if (file_exists($theme_css_file)) {
                wp_enqueue_style(
                    'came-theme-customizer-css',
                    CAME_PLUGIN_URL . 'assets/css/theme-customizer.css',
                    array(),
                    CAME_VERSION
                );
            } else {
                // Add admin notice if CSS file is missing
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-warning"><p>' . 
                        sprintf(esc_html__('Theme Customizer: CSS file not found at %s', 'multiple-menus-admin-editor'), '<code>' . esc_html(CAME_PLUGIN_DIR) . 'assets/css/theme-customizer.css</code>') . 
                        '</p></div>';
                });
            }
        }

        // Enqueue jQuery UI and Sortable for menu editor
        if ('toplevel_page_advanced-admin-menu-editor' === $hook) {
            // Get jQuery UI version from WordPress
            global $wp_scripts;
            $ui_version = isset($wp_scripts->registered['jquery-ui-core']->ver) ? $wp_scripts->registered['jquery-ui-core']->ver : false;
            
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-droppable');
            
            if (CAME_DEBUG && $ui_version) {
                add_action('admin_notices', function() use ($ui_version) {
                    echo '<div class="notice notice-info is-dismissible"><p>' . 
                         esc_html__('jQuery UI Version: ', 'multiple-menus-admin-editor') . esc_html($ui_version) . 
                         '</p></div>';
                });
            }
        }

        // Check if main CSS file exists before enqueueing
        $css_file = CAME_PLUGIN_DIR . 'assets/css/admin.css';
        if (file_exists($css_file)) {
            wp_enqueue_style(
                'came-admin-css',
                CAME_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                CAME_VERSION
            );
        } else {
            // Add admin notice if CSS file is missing
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>' . 
                     sprintf(esc_html__('Admin Menu Editor: CSS file not found at %s', 'multiple-menus-admin-editor'), '<code>' . esc_html(CAME_PLUGIN_DIR) . 'assets/css/admin.css</code>') . 
                     '</p></div>';
            });
        }

        // Check if JS file exists before enqueueing
        $js_file = CAME_PLUGIN_DIR . 'assets/js/admin.js';
        if (file_exists($js_file)) {
            wp_enqueue_script(
                'came-admin-js',
                CAME_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                CAME_VERSION,
                true
            );
            
            // Get active configuration
            $active_config = $this->get_active_config();
            
            // Get admin menu structure with unique IDs - use transient for caching
            $admin_menu = get_transient('came_admin_menu_structure');
            if (false === $admin_menu) {
                $admin_menu = $this->came_get_admin_menu_structure();
                set_transient('came_admin_menu_structure', $admin_menu, HOUR_IN_SECONDS); // Cache for 1 hour
            }
            
            // Pass data to script
            wp_localize_script('came-admin-js', 'came_data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('came_nonce'),
                'current_user_roles' => $this->came_get_current_user_roles(),
                'dashicons' => $this->came_get_available_dashicons(),
                'admin_menu' => $admin_menu,
                'saved_menu' => isset($active_config['menu_items']) ? $active_config['menu_items'] : array(),
                'active_config_id' => $this->active_config_id,
                'all_configs' => $this->all_menu_configs,
                'text_confirm_reset' => esc_html__('Are you sure you want to reset the menu to default?', 'multiple-menus-admin-editor'),
                'text_confirm_delete' => esc_html__('Are you sure you want to delete this configuration?', 'multiple-menus-admin-editor')
            ));
        } else {
            // Add admin notice if JS file is missing
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>' . 
                     sprintf(esc_html__('Admin Menu Editor: JavaScript file not found at %s', 'multiple-menus-admin-editor'), '<code>' . esc_html(CAME_PLUGIN_DIR) . 'assets/js/admin.js</code>') . 
                     '</p></div>';
            });
        }

        // Enqueue dashicons
        wp_enqueue_style('dashicons');
    }

    /**
     * Display the admin page
     */
    public function came_display_plugin_admin_page() {
        // Verify user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'multiple-menus-admin-editor'));
        }
        
        // Check if admin page file exists
        $admin_page_file = CAME_PLUGIN_DIR . 'includes/admin-page.php';
        
        if (file_exists($admin_page_file)) {
            include_once $admin_page_file;
        } else {
            // Fallback if admin page file doesn't exist with debugging info
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('Admin Menu Editor', 'multiple-menus-admin-editor') . '</h1>';
            echo '<div class="notice notice-error"><p>' . 
                 esc_html__('Error: Admin page template file not found. Please reinstall the plugin.', 'multiple-menus-admin-editor') . 
                 '</p></div>';
            echo '<p>' . esc_html__('Looking for file at:', 'multiple-menus-admin-editor') . ' ' . esc_html($admin_page_file) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Display the theme customizer page
     */
    public function came_display_theme_customizer_page() {
        // Verify user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'multiple-menus-admin-editor'));
        }
        
        // Include the theme customizer template
        $customizer_page_file = CAME_PLUGIN_DIR . 'includes/theme-customizer.php';
        
        if (file_exists($customizer_page_file)) {
            include_once $customizer_page_file;
        } else {
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('Theme Customizer', 'multiple-menus-admin-editor') . '</h1>';
            echo '<div class="notice notice-error"><p>' . 
                 esc_html__('Error: Theme customizer template file not found.', 'multiple-menus-admin-editor') . 
                 '</p></div>';
            echo '<p>' . esc_html__('Looking for file at:', 'multiple-menus-admin-editor') . ' ' . esc_html($customizer_page_file) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Debug information display
     */
    public function came_debug_info() {
        // Only show to admins and only in debug mode
        if (!current_user_can('manage_options') || !CAME_DEBUG) {
            return;
        }
        
        // Only show on plugin's admin page
        $screen = get_current_screen();
        if (!$screen || !in_array($screen->id, array(
            'toplevel_page_advanced-admin-menu-editor',
            'menu-editor_page_admin-menu-theme-customizer'
        ))) {
            return;
        }
        
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p><strong>' . esc_html__('Debug Info:', 'multiple-menus-admin-editor') . '</strong></p>';
        echo '<ul>';
        echo '<li>' . esc_html__('Plugin Version:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_VERSION) . '</li>';
        echo '<li>' . esc_html__('Plugin Directory:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_DIR) . '</li>';
        echo '<li>' . esc_html__('Plugin URL:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_URL) . '</li>';
        echo '<li>' . esc_html__('Admin Page File:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_DIR . 'includes/admin-page.php') . ' (' . esc_html__('Exists:', 'multiple-menus-admin-editor') . ' ' . (file_exists(CAME_PLUGIN_DIR . 'includes/admin-page.php') ? esc_html__('Yes', 'multiple-menus-admin-editor') : esc_html__('No', 'multiple-menus-admin-editor')) . ')</li>';
        echo '<li>' . esc_html__('Theme Customizer File:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_DIR . 'includes/theme-customizer.php') . ' (' . esc_html__('Exists:', 'multiple-menus-admin-editor') . ' ' . (file_exists(CAME_PLUGIN_DIR . 'includes/theme-customizer.php') ? esc_html__('Yes', 'multiple-menus-admin-editor') : esc_html__('No', 'multiple-menus-admin-editor')) . ')</li>';
        echo '<li>' . esc_html__('CSS File:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_DIR . 'assets/css/admin.css') . ' (' . esc_html__('Exists:', 'multiple-menus-admin-editor') . ' ' . (file_exists(CAME_PLUGIN_DIR . 'assets/css/admin.css') ? esc_html__('Yes', 'multiple-menus-admin-editor') : esc_html__('No', 'multiple-menus-admin-editor')) . ')</li>';
        echo '<li>' . esc_html__('Theme CSS File:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_DIR . 'assets/css/theme-customizer.css') . ' (' . esc_html__('Exists:', 'multiple-menus-admin-editor') . ' ' . (file_exists(CAME_PLUGIN_DIR . 'assets/css/theme-customizer.css') ? esc_html__('Yes', 'multiple-menus-admin-editor') : esc_html__('No', 'multiple-menus-admin-editor')) . ')</li>';
        echo '<li>' . esc_html__('JS File:', 'multiple-menus-admin-editor') . ' ' . esc_html(CAME_PLUGIN_DIR . 'assets/js/admin.js') . ' (' . esc_html__('Exists:', 'multiple-menus-admin-editor') . ' ' . (file_exists(CAME_PLUGIN_DIR . 'assets/js/admin.js') ? esc_html__('Yes', 'multiple-menus-admin-editor') : esc_html__('No', 'multiple-menus-admin-editor')) . ')</li>';
        echo '<li>' . esc_html__('Configs:', 'multiple-menus-admin-editor') . ' ' . esc_html(count($this->all_menu_configs)) . '</li>';
        echo '<li>' . esc_html__('Active Config:', 'multiple-menus-admin-editor') . ' ' . esc_html($this->active_config_id) . '</li>';
        echo '</ul>';
        echo '<p>' . esc_html__('Please check browser console for any JavaScript errors.', 'multiple-menus-admin-editor') . '</p>';
        echo '</div>';
    }

    /**
     * Generate and inject custom theme CSS
     */
    public function came_inject_theme_css() {
        // Get theme settings
        $theme_settings = get_option('came_theme_settings', array());
        
        // If theme is not enabled, return
        if (empty($theme_settings['enabled'])) {
            return;
        }
        
        // Extract settings
        $menu_bg = isset($theme_settings['menu_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_bg_color']) : '#ffffff';
        $menu_text = isset($theme_settings['menu_text_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_text_color']) : '#000000';
        $menu_hover_bg = isset($theme_settings['menu_hover_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_hover_bg_color']) : '#000000';
        $menu_hover_text = isset($theme_settings['menu_hover_text_color']) ? $this->came_sanitize_hex_color($theme_settings['menu_hover_text_color']) : '#ffffff';
        $submenu_bg = isset($theme_settings['submenu_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_bg_color']) : '#f8f8f8';
        $submenu_text = isset($theme_settings['submenu_text_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_text_color']) : '#000000';
        $submenu_hover_bg = isset($theme_settings['submenu_hover_bg_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_hover_bg_color']) : '#000000';
        $submenu_hover_text = isset($theme_settings['submenu_hover_text_color']) ? $this->came_sanitize_hex_color($theme_settings['submenu_hover_text_color']) : '#ffffff';
        $border_radius = isset($theme_settings['border_radius']) ? intval($theme_settings['border_radius']) : 4;
        $transition_speed = isset($theme_settings['transition_speed']) ? floatval($theme_settings['transition_speed']) : 0.3;
        $spacing = isset($theme_settings['spacing']) ? intval($theme_settings['spacing']) : 15;
        $custom_css = isset($theme_settings['custom_css']) ? wp_strip_all_tags($theme_settings['custom_css']) : '';
        
        // Generate CSS
        $css = "
        /* Custom Admin Menu Theme by Advanced Admin Menu Editor */";
        
        // If custom fonts are enabled, enqueue Google Fonts
        if ($enable_custom_fonts) {
            // Build the Google Fonts URL
            $google_fonts = array();
            
            if ($primary_font !== 'default') {
                $google_fonts[] = str_replace(' ', '+', $primary_font) . ':300,400,500,600,700';
            }
            
            if ($secondary_font !== 'default' && $secondary_font !== $primary_font) {
                $google_fonts[] = str_replace(' ', '+', $secondary_font) . ':300,400,500,600,700';
            }
            
            if (!empty($google_fonts)) {
                $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $google_fonts) . '&display=swap';
                echo '<link rel="stylesheet" href="' . esc_url($fonts_url) . '" type="text/css" media="all" />';
            }
            
            // Add typography styles
            $css .= "
        /* Typography Settings */";
            
            if ($primary_font !== 'default') {
                $css .= "
        #adminmenu,
        #adminmenu .wp-submenu-head,
        #adminmenu a.menu-top {
            font-family: '{$primary_font}', sans-serif;
            font-weight: {$font_weight};
        }";
            }
            
            if ($secondary_font !== 'default') {
                $css .= "
        #adminmenu .wp-submenu a {
            font-family: '{$secondary_font}', sans-serif;
        }";
            }
            
            $css .= "
        #adminmenu,
        #adminmenu .wp-submenu {
            font-size: {$font_size}px;
        }";
        }
        
        $css .= "
        #adminmenu {
            background-color: {$menu_bg};
            margin: 0;
        }
        
        #adminmenu li {
            margin-bottom: " . ($spacing/2) . "px;
        }
        
        /* Main Menu Items */
        #adminmenu a {
            color: {$menu_text};
            transition: all {$transition_speed}s ease;
            border-radius: {$border_radius}px;
            padding: " . ($spacing/2) . "px {$spacing}px;
        }
        
        #adminmenu a:hover,
        #adminmenu a:focus,
        #adminmenu li.menu-top:hover,
        #adminmenu li.opensub > a.menu-top,
        #adminmenu li > a.menu-top:focus {
            background-color: {$menu_hover_bg};
            color: {$menu_hover_text};
        }
        
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
        #adminmenu li.current a.menu-top,
        .folded #adminmenu li.wp-has-current-submenu,
        .folded #adminmenu li.current.menu-top,
        #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head {
            background-color: {$menu_hover_bg};
            color: {$menu_hover_text};
            font-weight: bold;
        }
        
        /* Submenu container */
        #adminmenu .wp-submenu {
            background-color: {$submenu_bg};
            border-radius: {$border_radius}px;
            padding: " . ($spacing/3) . "px 0;
        }
        
        /* Submenu items */
        #adminmenu .wp-submenu a {
            color: {$submenu_text};
        }
        
        #adminmenu .wp-submenu a:hover,
        #adminmenu .wp-submenu a:focus,
        #adminmenu .wp-submenu li.current a,
        #adminmenu .wp-submenu li.current a:hover {
            background-color: {$submenu_hover_bg};
            color: {$submenu_hover_text};
        }
        
        /* Collapse button */
        #collapse-button {
            color: {$menu_text};
        }
        
        #collapse-button:hover, 
        #collapse-button:focus {
            color: {$menu_hover_text};
        }
        
        /* Admin bar (optional) */
        #wpadminbar {
            background-color: {$menu_hover_bg};
            color: {$menu_hover_text};
        }
        
        /* Menu icons */
        #adminmenu div.wp-menu-image:before {
            color: {$menu_text};
        }
        
        #adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
        #adminmenu li.current div.wp-menu-image:before,
        #adminmenu a:hover div.wp-menu-image:before,
        #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
        #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before {
            color: {$menu_hover_text};
        }
        
        /* Remove default WordPress admin menu styling */
        #adminmenu, 
        #adminmenu .wp-submenu, 
        #adminmenuback, 
        #adminmenuwrap {
            background-color: transparent;
        }
        
        #adminmenu .wp-has-current-submenu .wp-submenu, 
        #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, 
        #adminmenu .wp-has-current-submenu.opensub .wp-submenu, 
        #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, 
        .no-js li.wp-has-current-submenu:hover .wp-submenu,
        #adminmenu .wp-not-current-submenu .wp-submenu {
            background-color: {$submenu_bg};
        }
        
        /* Menu separators */
        #adminmenu li.wp-menu-separator {
            height: 1px;
            margin: {$spacing}px 0;
            background: rgba(0,0,0,0.1);
            border: none;
        }
        
        /* Update bubble */
        #adminmenu .awaiting-mod, 
        #adminmenu .update-plugins {
            background-color: {$menu_hover_bg};
            color: {$menu_hover_text};
        }
        
        /* Clean up menu widths and transitions */
        #adminmenuwrap {
            transition: all {$transition_speed}s ease;
        }
        
        #adminmenu .wp-submenu {
            left: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        #adminmenu .wp-has-current-submenu .wp-submenu {
            box-shadow: none;
        }
        
        /* Custom CSS */
        {$custom_css}
        ";
        
        // Output CSS
        echo '<style id="came-custom-admin-theme">' . wp_strip_all_tags($css) . '</style>';
    }

    /**
     * AJAX handler for saving theme settings
     */
    public function came_ajax_save_theme() {
        // Check nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'came_theme_nonce', false)) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'multiple-menus-admin-editor')));
            return;
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => esc_html__('You do not have sufficient permissions.', 'multiple-menus-admin-editor')));
            return;
        }
        
        // Get and sanitize data
        $theme_settings = isset($_POST['theme_settings']) ? $_POST['theme_settings'] : array();