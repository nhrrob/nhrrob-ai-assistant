<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="wpad-chat-widget-container" class="wpad-chat-widget-container">
    <!-- Chat Bubble Toggle -->
    <button id="wpad-chat-toggle" class="wpad-chat-toggle" aria-label="Toggle AI Assistant">
        <span class="wpad-ai-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.5 2.5L13.8 8.2L19.5 10.5L13.8 12.8L11.5 18.5L9.2 12.8L3.5 10.5L9.2 8.2L11.5 2.5Z" fill="white"/>
                <path d="M18.5 16.5L19.5 19L22 20L19.5 21L18.5 23.5L17.5 21L15 20L17.5 19L18.5 16.5Z" fill="white"/>
            </svg>
        </span>
    </button>

    <!-- Chat Window -->
    <div id="wpad-chat-window" class="wpad-chat-window wpad-hidden">
        <div class="wpad-chat-header">
            <div class="wpad-header-title">
                <span class="wpad-header-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.5 2.5L13.8 8.2L19.5 10.5L13.8 12.8L11.5 18.5L9.2 12.8L3.5 10.5L9.2 8.2L11.5 2.5Z" fill="white"/>
                        <path d="M18.5 16.5L19.5 19L22 20L19.5 21L18.5 23.5L17.5 21L15 20L17.5 19L18.5 16.5Z" fill="white"/>
                    </svg>
                </span>
                <h3><?php esc_html_e( 'AI Developer Assistant', 'wpad' ); ?></h3>
            </div>
            <button id="wpad-chat-close" class="wpad-chat-close" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <div class="wpad-chat-history" id="wpad-chat-history">
            <div class="wpad-message wpad-assistant">
                <div class="wpad-message-content">
                    <p><?php esc_html_e( 'Hello! I am your AI developer. How can I help you improve your site today?', 'wpad' ); ?></p>
                </div>
            </div>
            <!-- Dynamic messages will be appended here -->
            <div id="wpad-loading" class="wpad-loading" style="display: none;">
                <span class="wpad-spin"></span> <?php esc_html_e( 'Thinking...', 'wpad' ); ?>
            </div>
        </div>

        <div class="wpad-chat-input-area">
            <textarea id="wpad-chat-input" placeholder="<?php esc_attr_e( 'E.g., Make my header sticky', 'wpad' ); ?>" rows="1"></textarea>
            <div class="wpad-chat-controls">
                <span class="wpad-char-count" id="wpad-char-count">0 / 500</span>
                <button type="button" id="wpad-chat-send" class="button button-primary">
                    <?php esc_html_e( 'Send', 'wpad' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>
