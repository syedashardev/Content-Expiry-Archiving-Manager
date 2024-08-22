<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CEAM_Expiry_Manager {

    private static $instance = null;

    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_expiry_meta_box'));
        add_action('save_post', array($this, 'save_expiry_date'));
        add_action('ceam_check_expired_posts', array($this, 'check_expired_posts'));

        // Schedule the event if not already scheduled
        if (!wp_next_scheduled('ceam_check_expired_posts')) {
            wp_schedule_event(time(), 'hourly', 'ceam_check_expired_posts');
        }
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_expiry_meta_box() {
        add_meta_box(
            'ceam_expiry_meta_box',
            __('Expiry Date', 'content-expiry-archiving-manager'),
            array($this, 'render_expiry_meta_box'),
            null,
            'side',
            'default'
        );
    }

    public function render_expiry_meta_box($post) {
        $expiry_date = get_post_meta($post->ID, '_ceam_expiry_date', true);
        wp_nonce_field('ceam_save_expiry_date', 'ceam_expiry_nonce');

        echo '<input type="date" name="ceam_expiry_date" value="' . esc_attr($expiry_date) . '" />';
    }

    public function save_expiry_date($post_id) {
        if (!isset($_POST['ceam_expiry_nonce']) || !wp_verify_nonce($_POST['ceam_expiry_nonce'], 'ceam_save_expiry_date')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['ceam_expiry_date'])) {
            update_post_meta($post_id, '_ceam_expiry_date', sanitize_text_field($_POST['ceam_expiry_date']));
        }
    }

    public function check_expired_posts() {
        $args = array(
            'post_type' => 'any',
            'meta_key' => '_ceam_expiry_date',
            'meta_value' => date('Y-m-d'),
            'meta_compare' => '<=',
            'post_status' => 'publish'
        );

        $expired_posts = get_posts($args);

        foreach ($expired_posts as $post) {
            // Perform the action (e.g., unpublish, archive, etc.)
            $this->handle_expiry_action($post->ID);
        }
    }

    private function handle_expiry_action($post_id) {
        // Example: Unpublish the post
        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'draft'
        ));

        // Notify admin or perform other actions...
    }
}
