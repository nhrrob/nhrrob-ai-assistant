<?php
namespace NHR\AIAssistant\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin {

    public function init() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
    }

    public function add_admin_menu() {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M11.5 2.5L13.8 8.2L19.5 10.5L13.8 12.8L11.5 18.5L9.2 12.8L3.5 10.5L9.2 8.2L11.5 2.5Z"/>
            <path d="M18.5 15.5L19.4 18L22 18.9L19.4 19.8L18.5 22.5L17.6 19.8L15 18.9L17.6 18L18.5 15.5Z"/>
        </svg>';
        $icon = 'data:image/svg+xml;base64,' . base64_encode( $svg );

        add_menu_page(
            __( 'AI Developer', 'nhrrob-ai-assistant' ),
            __( 'AI Developer', 'nhrrob-ai-assistant' ),
            'manage_options',
            'nhraa-settings',
            array( $this, 'render_app' ),
            $icon,
            30
        );
    }

    public function enqueue_scripts( $hook ) {
        if ( 'toplevel_page_nhraa-settings' !== $hook ) {
            return;
        }

        $asset_file = NHRAA_PLUGIN_DIR . 'admin/build/index.asset.php';
        if ( ! file_exists( $asset_file ) ) {
            return;
        }

        $assets = require $asset_file;

        wp_enqueue_script(
            'nhraa-app',
            NHRAA_PLUGIN_URL . 'admin/build/index.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );

        wp_enqueue_style(
            'nhraa-app-css',
            NHRAA_PLUGIN_URL . 'admin/build/style-index.css',
            array(),
            $assets['version']
        );
    }

    public function render_app() {
        echo '<div id="nhraa-admin-app" style="margin:0 -20px -10px;"></div>';
    }

    public function plugin_action_links( $links, $file ) {
        if ( plugin_basename( NHRAA_PLUGIN_DIR . 'nhrrob-ai-assistant.php' ) === $file ) {
            $settings_link = sprintf(
                '<a href="%s">%s</a>',
                admin_url( 'admin.php?page=nhraa-settings' ),
                __( 'Open', 'nhrrob-ai-assistant' )
            );
            array_unshift( $links, $settings_link );
        }
        return $links;
    }
}
