<!-- HMS AI Medical Chatbot Widget -->
<div id="hms-chatbot-container">
    <!-- Floating Launcher Button -->
    <button id="hms-chatbot-launcher" type="button" class="btn shadow-lg" title="Open HMS Medical AI Assistant">
        <div class="launcher-pulse"></div>
        <div class="launcher-icon">
            <i class="bi bi-robot fs-3 text-white"></i>
        </div>
        <span class="launcher-badge">AI Online</span>
    </button>

    <!-- Chatbot Window Panel -->
    <div id="hms-chatbot-panel" class="shadow-lg border-0">
        <!-- Header -->
        <div class="chatbot-header d-flex align-items-center justify-content-between p-3">
            <div class="d-flex align-items-center gap-2">
                <div class="chatbot-avatar bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bi bi-heart-pulse-fill text-danger fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                        HMS Medical AI
                        <span class="status-indicator"></span>
                    </h6>
                    <small class="text-white-50 font-11">Hospital Records & Clinical Triage</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" id="hms-chatbot-clear" class="btn btn-sm btn-icon text-white-50 hover-white" title="Clear Conversation">
                    <i class="bi bi-arrow-counterclockwise fs-6"></i>
                </button>
                <button type="button" id="hms-chatbot-close" class="btn btn-sm btn-icon text-white hover-white" title="Minimize Chat">
                    <i class="bi bi-x-lg fs-6"></i>
                </button>
            </div>
        </div>

        <!-- Chat Message Area -->
        <div id="hms-chatbot-messages" class="p-3">
            <!-- Initial Greeting -->
            <div class="chat-msg bot-msg">
                <div class="msg-bubble">
                    <div class="msg-header d-flex align-items-center gap-1 mb-1">
                        <i class="bi bi-robot text-primary font-12"></i>
                        <span class="fw-bold font-12 text-primary">HMS AI Clinical Assistant</span>
                    </div>
                    <div class="msg-text">
                        👋 <strong>Hello! I am your HMS AI Assistant.</strong><br><br>
                        I can provide instant real-time answers about our hospital:
                        <ul class="mb-2 ps-3 mt-1 font-13">
                            <li>🏥 <strong>Doctors & Fees</strong>: PMDC, roster, consultation charges.</li>
                            <li>📅 <strong>Appointments</strong>: Today's queue, search by patient name/phone.</li>
                            <li>🩺 <strong>Symptom Guidance</strong>: Preliminary medical triage & specialist matching.</li>
                            <li>💳 <strong>Patient Records</strong>: Outstanding dues and wallet balances.</li>
                        </ul>
                        <em>How can I assist you right now?</em>
                    </div>
                </div>
            </div>

            <!-- Dynamic Quick Prompt Chips -->
            <div id="chatbot-suggestion-chips" class="d-flex flex-wrap gap-1 my-2">
                <button type="button" class="btn btn-sm suggestion-chip" data-prompt="Show available doctors and consultation fees">
                    👨‍⚕️ Doctors & Fees
                </button>
                <button type="button" class="btn btn-sm suggestion-chip" data-prompt="What are today's appointments?">
                    📅 Today's Schedule
                </button>
                <button type="button" class="btn btn-sm suggestion-chip" data-prompt="I have severe fever and headache, which doctor should I see?">
                    🩺 Check Symptoms
                </button>
                <button type="button" class="btn btn-sm suggestion-chip" data-prompt="Show patient dues and financial overview">
                    💳 Patient Dues
                </button>
                <button type="button" class="btn btn-sm suggestion-chip" data-prompt="Hospital overview, timing and contact info">
                    🏥 Hospital Info
                </button>
            </div>

            <!-- Loading Indicator (Hidden by default) -->
            <div id="chatbot-typing-indicator" class="chat-msg bot-msg d-none">
                <div class="msg-bubble typing-bubble">
                    <div class="typing-dots">
                        <span></span><span></span><span></span>
                    </div>
                    <span class="font-11 text-muted ms-2">Analyzing hospital records & medical guidance...</span>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chatbot-footer p-2 bg-light border-top">
            <form id="hms-chatbot-form" class="d-flex align-items-center gap-2 m-0">
                @csrf
                <input type="text" id="hms-chatbot-input" class="form-control form-control-sm border shadow-none" 
                       placeholder="Ask about doctors, appointments, or medical symptoms..." 
                       autocomplete="off" required>
                <button type="submit" id="hms-chatbot-send" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" title="Send Question">
                    <i class="bi bi-send-fill font-13"></i>
                </button>
            </form>
            <div class="text-center mt-1">
                <small class="font-10 text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i>HMS AI · Medical guidance is educational. In emergencies, visit Emergency OPD immediately.
                </small>
            </div>
        </div>
    </div>
</div>

<style>
/* HMS Chatbot Styles */
#hms-chatbot-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1060;
    font-family: inherit;
}

#hms-chatbot-launcher {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%);
    border: 3px solid #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

#hms-chatbot-launcher:hover {
    transform: scale(1.08);
    box-shadow: 0 10px 25px rgba(29, 78, 216, 0.45) !important;
}

.launcher-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    opacity: 0.6;
    animation: launcherPulse 2.5s infinite;
    z-index: 0;
}

@keyframes launcherPulse {
    0% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.25); opacity: 0; }
    100% { transform: scale(1); opacity: 0; }
}

.launcher-icon {
    position: relative;
    z-index: 1;
}

.launcher-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background-color: #10b981;
    color: #ffffff;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    border: 2px solid #ffffff;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Chatbot Panel */
#hms-chatbot-panel {
    display: none;
    position: fixed;
    bottom: 95px;
    right: 24px;
    width: 390px;
    max-width: calc(100vw - 32px);
    height: 560px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    flex-direction: column;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22) !important;
    animation: slideUpPanel 0.3s ease-out;
}

@keyframes slideUpPanel {
    from { opacity: 0; transform: translateY(20px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.chatbot-header {
    background: linear-gradient(135deg, #1e40af 0%, #0284c7 100%);
}

.chatbot-avatar {
    width: 36px;
    height: 36px;
}

.status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #34d399;
    box-shadow: 0 0 6px #34d399;
}

.hover-white:hover {
    color: #ffffff !important;
}

#hms-chatbot-messages {
    flex: 1;
    overflow-y: auto;
    background-color: #f8fafc;
    scroll-behavior: smooth;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.chat-msg {
    display: flex;
    flex-direction: column;
    max-width: 88%;
}

.chat-msg.bot-msg {
    align-self: flex-start;
}

.chat-msg.user-msg {
    align-self: flex-end;
}

.msg-bubble {
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
}

.bot-msg .msg-bubble {
    background-color: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 3px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.user-msg .msg-bubble {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border-bottom-right-radius: 3px;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
}

.msg-time {
    font-size: 10px;
    color: #94a3b8;
    margin-top: 3px;
    padding: 0 4px;
}

.user-msg .msg-time {
    align-self: flex-end;
}

.suggestion-chip {
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    color: #1e3a8a;
    font-size: 11px;
    font-weight: 500;
    border-radius: 20px;
    padding: 3px 10px;
    transition: all 0.2s ease;
}

.suggestion-chip:hover {
    background-color: #eff6ff;
    border-color: #3b82f6;
    color: #1d4ed8;
    transform: translateY(-1px);
}

.typing-bubble {
    display: inline-flex;
    align-items: center;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
}

.typing-dots {
    display: inline-flex;
    gap: 4px;
}

.typing-dots span {
    width: 6px;
    height: 6px;
    background-color: #3b82f6;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
}

.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
}

.font-10 { font-size: 10px; }
.font-11 { font-size: 11px; }
.font-12 { font-size: 12px; }
.font-13 { font-size: 13px; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const launcher = document.getElementById("hms-chatbot-launcher");
    const panel = document.getElementById("hms-chatbot-panel");
    const closeBtn = document.getElementById("hms-chatbot-close");
    const clearBtn = document.getElementById("hms-chatbot-clear");
    const form = document.getElementById("hms-chatbot-form");
    const input = document.getElementById("hms-chatbot-input");
    const messages = document.getElementById("hms-chatbot-messages");
    const typingIndicator = document.getElementById("chatbot-typing-indicator");
    const suggestionChipsContainer = document.getElementById("chatbot-suggestion-chips");

    if (!launcher || !panel) return;

    // Toggle Chatbot Panel
    function toggleChat(open) {
        if (open) {
            panel.style.display = "flex";
            launcher.style.display = "none";
            input.focus();
            scrollToBottom();
        } else {
            panel.style.display = "none";
            launcher.style.display = "flex";
        }
    }

    launcher.addEventListener("click", () => toggleChat(true));
    closeBtn.addEventListener("click", () => toggleChat(false));

    // Expose global helper for dashboard and external triggers
    window.toggleHMSChatbot = toggleChat;
    window.openChatWithPrompt = function (prompt) {
        toggleChat(true);
        if (prompt) {
            input.value = prompt;
            sendMessage(prompt);
        }
    };

    // Clear Chat
    clearBtn.addEventListener("click", function () {
        const firstGreeting = messages.querySelector(".chat-msg.bot-msg");
        messages.innerHTML = "";
        if (firstGreeting) messages.appendChild(firstGreeting);
        if (suggestionChipsContainer) messages.appendChild(suggestionChipsContainer);
        messages.appendChild(typingIndicator);
    });

    // Handle suggestion chips
    document.addEventListener("click", function (e) {
        const chip = e.target.closest(".suggestion-chip");
        if (chip) {
            const prompt = chip.getAttribute("data-prompt");
            if (prompt) {
                input.value = prompt;
                sendMessage(prompt);
            }
        }
    });

    // Form submit
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;
        sendMessage(text);
    });

    function sendMessage(text) {
        // Append user bubble
        appendMessage("user", text);
        input.value = "";

        // Show typing indicator
        showTyping(true);

        // Send POST request
        fetch("{{ route('chatbot.message') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message: text })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            showTyping(false);
            if (data && data.reply) {
                appendMessage("bot", data.reply, data.suggestions);
            } else {
                appendMessage("bot", "⚠️ Sorry, I could not process your query at this moment.");
            }
        })
        .catch(err => {
            console.error("Chatbot Error:", err);
            showTyping(false);
            appendMessage("bot", "⚠️ Unable to connect to hospital assistant server. Please check your connection.");
        });
    }

    function appendMessage(sender, text, suggestions = []) {
        const msgDiv = document.createElement("div");
        msgDiv.className = `chat-msg ${sender}-msg`;

        const now = new Date();
        const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        if (sender === "user") {
            msgDiv.innerHTML = `
                <div class="msg-bubble">${escapeHtml(text)}</div>
                <div class="msg-time">${timeStr}</div>
            `;
        } else {
            const formattedHTML = formatMarkdown(text);
            let suggestionsHTML = "";
            if (suggestions && suggestions.length > 0) {
                suggestionsHTML = `<div class="d-flex flex-wrap gap-1 mt-2">` +
                    suggestions.map(s => `<button type="button" class="btn btn-sm suggestion-chip" data-prompt="${escapeHtml(s)}">${escapeHtml(s)}</button>`).join('') +
                    `</div>`;
            }

            msgDiv.innerHTML = `
                <div class="msg-bubble">
                    <div class="msg-header d-flex align-items-center gap-1 mb-1">
                        <i class="bi bi-robot text-primary font-12"></i>
                        <span class="fw-bold font-12 text-primary">HMS AI</span>
                    </div>
                    <div class="msg-text">${formattedHTML}</div>
                    ${suggestionsHTML}
                </div>
                <div class="msg-time">${timeStr}</div>
            `;
        }

        // Insert before typing indicator
        messages.insertBefore(msgDiv, typingIndicator);
        scrollToBottom();
    }

    function showTyping(show) {
        if (show) {
            typingIndicator.classList.remove("d-none");
            messages.appendChild(typingIndicator);
        } else {
            typingIndicator.classList.add("d-none");
        }
        scrollToBottom();
    }

    function scrollToBottom() {
        setTimeout(() => {
            messages.scrollTop = messages.scrollHeight;
        }, 50);
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatMarkdown(str) {
        let out = escapeHtml(str);
        // Bold
        out = out.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Italic
        out = out.replace(/\*(.*?)\*/g, '<em>$1</em>');
        out = out.replace(/_(.*?)_/g, '<em>$1</em>');
        // Inline code
        out = out.replace(/`(.*?)`/g, '<code class="badge bg-light text-dark font-11 px-1">$1</code>');
        // Bullets
        out = out.replace(/• (.*?)(?:\n|$)/g, '<div class="d-flex align-items-start gap-1 my-1"><span class="text-primary font-11">•</span><span>$1</span></div>');
        // Newlines
        out = out.replace(/\n\n/g, '<div class="my-2"></div>');
        out = out.replace(/\n/g, '<br>');
        return out;
    }
});
</script>
