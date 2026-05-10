<?php
namespace NHR\AIAssistant\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin {

    public function init() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_footer', array( $this, 'render_chat_widget' ) );
    }

    public function add_admin_menu() {
        $svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="black" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 2.5L13.8 8.2L19.5 10.5L13.8 12.8L11.5 18.5L9.2 12.8L3.5 10.5L9.2 8.2L11.5 2.5Z"/><path d="M18.5 16.5L19.5 19L22 20L19.5 21L18.5 23.5L17.5 21L15 20L17.5 19L18.5 16.5Z"/></svg>';
        $icon_url = 'data:image/svg+xml;base64,' . base64_encode( $svg );

        // Single main menu, no submenus since it's an SPA
        add_menu_page(
            __( 'AI Developer', 'wpad' ),
            __( 'AI Developer', 'wpad' ),
            'manage_options',
            'wpad-settings',
            array( $this, 'render_react_app' ),
            $icon_url,
            30
        );
    }

    public function enqueue_scripts( $hook ) {
        // Always enqueue chat widget scripts globally
        wp_enqueue_style( 'wpad-admin-css', WPAD_PLUGIN_URL . 'admin/css/admin.css', array(), WPAD_VERSION );
        wp_enqueue_script( 'wpad-chat-js', WPAD_PLUGIN_URL . 'admin/js/chat.js', array(), WPAD_VERSION, true );

        wp_localize_script( 'wpad-chat-js', 'wpadChatData', array(
            'apiUrl'  => esc_url_raw( rest_url( 'wpad/v1/chat' ) ),
            'undoUrl' => esc_url_raw( rest_url( 'wpad/v1/undo' ) ),
            'nonce'   => wp_create_nonce( 'wp_rest' ),
        ) );

        // Enqueue React SPA only on our pages
        if ( 'toplevel_page_wpad-settings' === $hook || 'ai-developer_page_wpad-history' === $hook ) {
            $asset_file = WPAD_PLUGIN_DIR . 'admin/build/index.asset.php';
            
            if ( file_exists( $asset_file ) ) {
                $assets = require $asset_file;
                wp_enqueue_script(
                    'wpad-react-app',
                    WPAD_PLUGIN_URL . 'admin/build/index.js',
                    $assets['dependencies'],
                    $assets['version'],
                    true
                );

                wp_enqueue_style(
                    'wpad-react-app-css',
                    WPAD_PLUGIN_URL . 'admin/build/style-index.css',
                    array(),
                    $assets['version']
                );

                // Setup api-fetch
                wp_set_script_translations( 'wpad-react-app', 'wpad' );
                wp_enqueue_script( 'wp-api-fetch' );
            }
        }
    }

    public function render_react_app() {
        echo '<div id="wpad-admin-app"></div>';
    }

    public function render_chat_widget() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        include WPAD_PLUGIN_DIR . 'admin/views/chat.php';
    }
}
