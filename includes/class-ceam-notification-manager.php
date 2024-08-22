<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CEAM_Notification_Manager {

    private static $instance = null;

    private function __construct() {
        // Add hooks for notifications if necessary
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function send_expiry_notification($post_id) {
        $post = get_post($post_id);
        $admin_email = get_option('admin_email');
        $subject = __('Content Expiry Notification', 'content-expiry-archiving-manager');
        $message = sprintf(__('The content "%s" has expired.', 'content-expiry-archiving-manager'), $post->post_title);

        wp_mail($admin_email, $subject, $message);
    }
}
