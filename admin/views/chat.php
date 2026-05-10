<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="nhraa-chat-widget-container" class="nhraa-chat-widget-container">
    <!-- Chat Bubble Toggle -->
    <button id="nhraa-chat-toggle" class="nhraa-chat-toggle" aria-label="Toggle AI Assistant">
        <span class="nhraa-ai-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Robotic Head Concept -->
                <rect x="4" y="10" width="16" height="10" rx="3" stroke="white" stroke-width="2"/>
                <path d="M9 14H9.01" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M15 14H15.01" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M10 17H14" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                <!-- Antenna / Connection -->
                <path d="M12 10V7" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="5" r="2" fill="white"/>
            </svg>
        </span>
    </button>

    <!-- Chat Window -->
    <div id="nhraa-chat-window" class="nhraa-chat-window nhraa-hidden">
        <div class="nhraa-chat-header">
            <div class="nhraa-header-title">
                <span class="nhraa-header-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="11" width="14" height="8" rx="2" stroke="white" stroke-width="2"/>
                        <circle cx="12" cy="7" r="2" stroke="white" stroke-width="2"/>
                    </svg>
                </span>
                <div class="nhraa-header-text">
                    <h3><?php esc_html_e( 'AI Site Assistant', 'nhrrob-ai-assistant' ); ?></h3>
                    <span class="nhraa-header-badge"><?php esc_html_e( 'AI POWERED', 'nhrrob-ai-assistant' ); ?></span>
                </div>
            </div>
            <button id="nhraa-chat-close" class="nhraa-chat-close" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <div class="nhraa-chat-history" id="nhraa-chat-history">
            <div class="nhraa-message nhraa-assistant">
                <div class="nhraa-message-content">
                    <p><?php esc_html_e( 'Hello! I am your AI assistant specialized in WordPress development and management. How can I help you improve your website today?', 'nhrrob-ai-assistant' ); ?></p>
                </div>
            </div>
            <!-- Dynamic messages will be appended here -->
            <div id="nhraa-loading" class="nhraa-loading" style="display: none;">
                <div class="nhraa-dot-flashing"></div>
            </div>
        </div>

        <div class="nhraa-chat-input-area">
            <div class="nhraa-input-wrapper">
                <textarea id="nhraa-chat-input" placeholder="<?php esc_attr_e( 'E.g., Update site colors to match brand', 'nhrrob-ai-assistant' ); ?>" rows="1"></textarea>
            </div>
            <div class="nhraa-chat-controls">
                <span class="nhraa-char-count" id="nhraa-char-count">0 / 500</span>
                <button type="button" id="nhraa-chat-send">
                    <?php esc_html_e( 'Send Request', 'nhrrob-ai-assistant' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>
