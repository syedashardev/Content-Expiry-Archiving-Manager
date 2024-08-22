<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CEAM_Admin {

    private static $instance = null;

    private function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_plugin_menu() {
        add_options_page(
            __('Content Expiry Manager', 'content-expiry-archiving-manager'),
            __('Content Expiry', 'content-expiry-archiving-manager'),
            'manage_options',
            'ceam-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        register_setting('ceam_settings_group', 'ceam_default_expiry_days');
        add_settings_section('ceam_general_settings', __('General Settings', 'content-expiry-archiving-manager'), null, 'ceam-settings');
        add_settings_field('ceam_default_expiry_days', __('Default Expiry Days', 'content-expiry-archiving-manager'), array($this, 'default_expiry_days_callback'), 'ceam-settings', 'ceam_general_settings');
    }

    public function default_expiry_days_callback() {
        $value = get_option('ceam_default_expiry_days', 30);
        echo '<input type="number" name="ceam_default_expiry_days" value="' . esc_attr($value) . '" />';
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Content Expiry & Archiving Manager', 'content-expiry-archiving-manager'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('ceam_settings_group');
                do_settings_sections('ceam-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
