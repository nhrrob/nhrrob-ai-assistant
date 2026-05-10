document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('wpad-chat-toggle');
    const closeBtn = document.getElementById('wpad-chat-close');
    const chatWindow = document.getElementById('wpad-chat-window');

    const input = document.getElementById('wpad-chat-input');
    const sendBtn = document.getElementById('wpad-chat-send');
    const history = document.getElementById('wpad-chat-history');
    const counter = document.getElementById('wpad-char-count');
    const MAX_CHARS = 1000;

    if (!input || !sendBtn || !history || !counter) return;

    // Toggle logic
    if (toggleBtn && chatWindow) {
        toggleBtn.addEventListener('click', function() {
            chatWindow.classList.toggle('wpad-hidden');
            if (!chatWindow.classList.contains('wpad-hidden')) {
                input.focus();
                history.scrollTop = history.scrollHeight;
            }
        });
    }

    if (closeBtn && chatWindow) {
        closeBtn.addEventListener('click', function() {
            chatWindow.classList.add('wpad-hidden');
        });
    }

    // Create loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'wpad-loading-indicator';
    loadingIndicator.textContent = 'Thinking...';
    history.appendChild(loadingIndicator);

    // Character counter
    input.addEventListener('input', function() {
        const len = input.value.length;
        counter.textContent = `${len} / ${MAX_CHARS}`;
        if (len > MAX_CHARS) {
            counter.style.color = '#d63638';
            sendBtn.disabled = true;
        } else {
            counter.style.color = '#646970';
            sendBtn.disabled = false;
        }
    });

    // Enter to send
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    sendBtn.addEventListener('click', function(e) {
        e.preventDefault();
        sendMessage();
    });

    function appendMessage(role, text, changeId = null) {
        const wrapper = document.createElement('div');
        wrapper.className = `wpad-message wpad-${role}`;
        
        const content = document.createElement('div');
        content.className = 'wpad-message-content';
        
        const p = document.createElement('p');
        p.textContent = text;
        
        content.appendChild(p);

        if (changeId) {
            const undoBtn = document.createElement('button');
            undoBtn.className = 'button button-small wpad-undo-btn';
            undoBtn.style.marginTop = '10px';
            undoBtn.textContent = 'Undo';
            undoBtn.dataset.changeId = changeId;
            
            undoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                handleUndo(changeId, undoBtn, wrapper);
            });
            content.appendChild(undoBtn);
        }

        wrapper.appendChild(content);
        
        // Insert before loading indicator
        history.insertBefore(wrapper, loadingIndicator);
        history.scrollTop = history.scrollHeight;
    }

    function handleUndo(changeId, btn, wrapper) {
        btn.disabled = true;
        btn.textContent = 'Undoing...';

        fetch(wpadChatData.undoUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': wpadChatData.nonce
            },
            body: JSON.stringify({ change_id: changeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btn.textContent = 'Undone';
                btn.classList.add('disabled');
                appendMessage('assistant', data.message);
            } else {
                btn.disabled = false;
                btn.textContent = 'Undo';
                alert('Undo failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.textContent = 'Undo';
            alert('Network error. Please try again.');
        });
    }

    function sendMessage() {
        const message = input.value.trim();
        if (!message || message.length > MAX_CHARS) return;

        // Disable input
        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;
        
        // Append user message
        appendMessage('user', message);
        
        // Show loading
        loadingIndicator.style.display = 'block';
        history.scrollTop = history.scrollHeight;

        // Send API request
        fetch(wpadChatData.apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': wpadChatData.nonce
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.style.display = 'none';
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
            
            if (data.confirmation_message) {
                appendMessage('assistant', data.confirmation_message, data.change_id);
            } else if (data.message) {
                appendMessage('assistant', 'Error: ' + data.message);
            } else {
                appendMessage('assistant', 'An unknown error occurred.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.style.display = 'none';
            input.disabled = false;
            sendBtn.disabled = false;
            appendMessage('assistant', 'Network error. Please try again.');
        });
    }
});
