<?php
// Widget Amil Virtual AI BAZNAS Kabupaten Sumbawa
$iden_ai = $this->model_utama->view_where('identitas', array('id_identitas' => 1))->row_array();
$nama_lembaga_ai = !empty($iden_ai['nama_website']) ? $iden_ai['nama_website'] : 'BAZNAS Kabupaten Sumbawa';
$telp_ai = !empty($iden_ai['no_telp']) ? $iden_ai['no_telp'] : '081936955747';
$clean_wa_ai = preg_replace('/[^0-9]/', '', $telp_ai);
if (substr($clean_wa_ai, 0, 1) === '0') {
    $clean_wa_ai = '62' . substr($clean_wa_ai, 1);
}
?>
<!-- STYLES WIDGET AMIL VIRTUAL AI -->
<style>
    /* Scope khusus Widget AI BAZNAS */
    #baznas-ai-widget {
        position: fixed;
        bottom: 100px; /* Tepat di atas tombol Bayar Zakat & ZIS di Desktop */
        right: 30px;
        z-index: 999998;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    #baznas-ai-widget * {
        box-sizing: border-box;
        border-radius: 0px !important; /* Standar BAZNAS Sumbawa: Sudut Kotak Biasa */
    }

    #baznas-ai-widget i.fa,
    #baznas-ai-widget .fa {
        font-family: 'FontAwesome' !important;
        font-style: normal;
        font-weight: normal;
    }

    /* Floating Launcher Button - Icon Only (Kotak Biasa BAZNAS) */
    .ai-launcher-btn {
        width: 52px;
        height: 52px;
        background: #006937;
        color: #ffffff;
        border: 2px solid #F4C10F; /* Aksen Emas BAZNAS */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 105, 55, 0.4);
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .ai-launcher-btn:hover {
        background: #00522b;
        transform: scale(1.08) translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 105, 55, 0.55);
        border-color: #ffffff;
    }

    .ai-launcher-svg {
        display: block;
        transition: transform 0.25s ease;
    }

    .ai-launcher-btn:hover .ai-launcher-svg {
        transform: scale(1.1);
    }

    .ai-launcher-btn i.ai-main-icon {
        font-size: 24px;
        color: #ffffff;
        transition: transform 0.25s;
    }

    .ai-launcher-btn:hover i.ai-main-icon {
        transform: scale(1.1);
    }

    .ai-pulse-dot {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 11px;
        height: 11px;
        background: #2ecc71;
        border: 2px solid #ffffff;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
        animation: ai-pulse 2s infinite;
    }

    @keyframes ai-pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(46, 204, 113, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
    }

    /* Modal / Jendela Chat */
    .ai-chat-window {
        position: fixed;
        bottom: 165px; /* Nyaman di atas launcher icon dan tombol Bayar Zakat */
        right: 30px;
        width: 380px;
        max-width: calc(100vw - 40px);
        height: 520px;
        max-height: calc(100vh - 190px);
        background: #ffffff;
        border: 1px solid #b8b8b8;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.18);
        display: none;
        flex-direction: column;
        z-index: 1000000;
        overflow: hidden;
    }

    .ai-chat-window.open {
        display: flex;
        animation: ai-fade-up 0.25s ease-out;
    }

    @keyframes ai-fade-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Chat */
    .ai-chat-header {
        background: #006937;
        color: #ffffff;
        border-top: 3px solid #F4C10F;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ai-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-header-avatar {
        width: 32px;
        height: 32px;
        background: #ffffff;
        color: #006937;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        border: 1px solid #F4C10F;
    }

    .ai-header-title {
        font-size: 13.5px;
        font-weight: 800;
        line-height: 1.2;
    }

    .ai-header-status {
        font-size: 11px;
        color: #d1fae5;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ai-header-actions {
        display: flex;
        gap: 6px;
    }

    .ai-btn-action {
        background: transparent;
        color: #ffffff;
        border: none;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.2s;
    }

    .ai-btn-action:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Message Body */
    .ai-chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        background: #fdfdfd;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ai-msg-row {
        display: flex;
        flex-direction: column;
        max-width: 88%;
    }

    .ai-msg-row.bot {
        align-self: flex-start;
    }

    .ai-msg-row.user {
        align-self: flex-end;
    }

    .ai-bubble {
        padding: 10px 14px;
        font-size: 13px;
        line-height: 1.55;
        word-break: break-word;
    }

    .ai-bubble.bot {
        background: #ffffff;
        color: #222222;
        border: 1px solid #dcdcdc;
        border-left: 3px solid #006937;
    }

    .ai-bubble.user {
        background: #006937;
        color: #ffffff;
        border: 1px solid #004d25;
    }

    .ai-bubble p {
        margin: 0 0 6px 0;
    }

    .ai-bubble p:last-child {
        margin-bottom: 0;
    }

    .ai-bubble strong {
        color: inherit;
    }

    .ai-bubble a.ai-chat-link {
        color: #006937;
        text-decoration: underline;
        font-weight: 700;
        word-break: break-word;
        transition: color 0.2s;
    }

    .ai-bubble a.ai-chat-link:hover {
        color: #0b7c44;
        text-decoration: none;
    }

    .ai-bubble.user a.ai-chat-link {
        color: #F4C10F;
    }

    .ai-msg-time {
        font-size: 10px;
        color: #999;
        margin-top: 3px;
    }

    .ai-msg-row.user .ai-msg-time {
        align-self: flex-end;
    }

    /* Quick Chips */
    .ai-chips-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .ai-chip {
        background: #ffffff;
        color: #006937;
        border: 1px solid #006937;
        padding: 5px 9px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ai-chip:hover {
        background: #006937;
        color: #ffffff;
    }

    /* Typing Indicator */
    .ai-typing {
        display: none;
        align-self: flex-start;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-left: 3px solid #F4C10F;
        padding: 8px 12px;
        gap: 4px;
        align-items: center;
    }

    .ai-typing.active {
        display: inline-flex;
    }

    .ai-typing-dot {
        width: 6px;
        height: 6px;
        background: #006937;
        animation: ai-bounce 1.2s infinite ease-in-out;
    }

    .ai-typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .ai-typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes ai-bounce {
        0%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-5px); }
    }

    /* Footer Input Area */
    .ai-chat-footer {
        padding: 10px;
        background: #ffffff;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 8px;
    }

    .ai-input-text {
        flex: 1;
        padding: 9px 12px;
        font-size: 13px;
        border: 1px solid #b8b8b8;
        outline: none;
        background: #ffffff;
        color: #111111;
        resize: none;
        height: 38px;
    }

    .ai-input-text:focus {
        border-color: #006937;
    }

    .ai-btn-send {
        background: #006937;
        color: #ffffff;
        border: 1px solid #004d25;
        padding: 0 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.2s;
    }

    .ai-btn-send:hover {
        background: #00522b;
    }

    .ai-btn-send:disabled {
        background: #a0aec0;
        border-color: #cbd5e0;
        cursor: not-allowed;
    }

    /* Cursor Efek Mengetik (Typewriter) */
    .ai-typing-cursor {
        display: inline-block;
        color: #006937;
        font-weight: 900;
        margin-left: 2px;
        animation: ai-cursor-blink 0.7s infinite;
    }

    @keyframes ai-cursor-blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }

    /* ========================================================
       TAMPILAN KHUSUS MOBILE (< 768px) - ZERO OVERLAP & FULL EXPERIENCE
       ======================================================== */
    @media (max-width: 768px) {
        /* Saat tertutup: Posisi launcher tombol AI pas di atas tombol WhatsApp */
        #baznas-ai-widget {
            bottom: 136px;
            right: 14px;
            z-index: 999998;
        }

        .ai-launcher-btn {
            width: 48px;
            height: 48px;
            box-shadow: 0 4px 15px rgba(0, 105, 55, 0.45);
        }

        .ai-launcher-btn .ai-launcher-svg {
            width: 24px;
            height: 24px;
        }

        /* Saat Chat Terbuka di Layar HP: 
           Sembunyikan pop up lain (WhatsApp, Bottom Nav, Sticky Pay, Cookie Banner) agar TIDAK TUMPANG TINDIH */
        body.ai-chat-active {
            overflow: hidden !important; /* Kunci scroll latar belakang */
        }

        body.ai-chat-active .mobile-wa-btn,
        body.ai-chat-active .bottom-nav,
        body.ai-chat-active .sticky-payment-button,
        body.ai-chat-active .desktop-only-btn,
        body.ai-chat-active #baznasCookieConsent {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* Container Widget mengambil layer teratas mutlak di HP */
        #baznas-ai-widget.ai-chat-open {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100% !important;
            height: 100dvh !important;
            z-index: 99999999 !important; /* Di atas seluruh elemen mobile tanpa tumpang tindih */
            background: transparent !important;
        }

        /* Jendela Chat menjadi Full Mobile Sheet */
        #baznas-ai-widget.ai-chat-open .ai-chat-window {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            max-width: 100vw !important;
            height: 100% !important;
            height: 100dvh !important;
            max-height: 100dvh !important;
            border: none !important;
            border-radius: 0px !important;
            display: flex !important;
            flex-direction: column !important;
            z-index: 99999999 !important;
            box-shadow: none !important;
            animation: ai-slide-up-mobile 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        #baznas-ai-widget.ai-chat-open .ai-launcher-btn {
            display: none !important; /* Sembunyikan launcher saat mode chat aktif di HP */
        }

        /* Header Chat Mobile */
        .ai-chat-header {
            padding: 12px 14px;
            min-height: 56px;
            flex-shrink: 0;
            border-top: none;
            border-bottom: 2px solid #F4C10F;
        }

        .ai-header-title {
            font-size: 14px;
        }

        .ai-btn-action {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ai-btn-action:hover, .ai-btn-action:active {
            background: rgba(255, 255, 255, 0.35);
        }

        /* Body Chat Mobile */
        .ai-chat-body {
            padding: 14px 12px;
            gap: 14px;
            -webkit-overflow-scrolling: touch;
        }

        .ai-msg-row {
            max-width: 92%;
        }

        .ai-bubble {
            font-size: 13.5px;
            line-height: 1.5;
            padding: 11px 13px;
        }

        .ai-chips-container {
            gap: 6px;
            margin-top: 10px;
        }

        .ai-chip {
            font-size: 11.5px;
            padding: 6px 11px;
        }

        /* Footer Chat Mobile - Nyaman untuk Jempol & Bebas Safe Area */
        .ai-chat-footer {
            padding: 10px 12px;
            padding-bottom: calc(10px + env(safe-area-inset-bottom, 0px));
            background: #ffffff;
            border-top: 1px solid #dcdcdc;
            gap: 8px;
            flex-shrink: 0;
        }

        .ai-input-text {
            font-size: 16px !important; /* Mencegah auto-zoom liar pada browser iOS / Safari */
            height: 44px;
            padding: 10px 14px;
            border: 1px solid #b8b8b8;
        }

        .ai-btn-send {
            width: 44px;
            height: 44px;
            padding: 0;
            flex-shrink: 0;
        }
    }

    @keyframes ai-slide-up-mobile {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<!-- WIDGET DOM -->
<div id="baznas-ai-widget">
    <!-- Floating Launcher (Icon Saja - Bebas Tumpang Tindih & Anti-Rusak SVG) -->
    <div class="ai-launcher-btn" id="ai-launcher" onclick="toggleBaznasAiChat()" title="Tanya Amil Virtual AI BAZNAS Sumbawa">
        <svg class="ai-launcher-svg" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            <circle cx="8.5" cy="10" r="1.3" fill="#F4C10F" stroke="#F4C10F"></circle>
            <circle cx="12" cy="10" r="1.3" fill="#F4C10F" stroke="#F4C10F"></circle>
            <circle cx="15.5" cy="10" r="1.3" fill="#F4C10F" stroke="#F4C10F"></circle>
        </svg>
        <span class="ai-pulse-dot" title="Aktif 24 Jam"></span>
    </div>

    <!-- Jendela Chat -->
    <div class="ai-chat-window" id="ai-chat-window">
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="ai-header-left">
                <div class="ai-header-avatar">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#006937" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <div class="ai-header-title">Amil Virtual BAZNAS Sumbawa</div>
                    <div class="ai-header-status">
                        <span class="ai-pulse-dot" style="width:6px; height:6px;"></span>
                        <span>Online (WITA) — Layanan ZIS</span>
                    </div>
                </div>
            </div>
            <div class="ai-header-actions">
                <button class="ai-btn-action" onclick="toggleBaznasAiChat()" title="Tutup Chat">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body Percakapan -->
        <div class="ai-chat-body" id="ai-chat-body">
            <!-- Pesan Sambutan Otomatis -->
            <div class="ai-msg-row bot">
                <div class="ai-bubble bot">
                    <p><em>Assalamu’alaikum Warahmatullahi Wabarakatuh.</em></p>
                    <p>Saya <strong>Amil Virtual BAZNAS Kabupaten Sumbawa</strong>. Ada yang bisa kami bantu seputar Zakat, Infak, Sedekah, atau Program Bantuan hari ini?</p>
                    
                    <div class="ai-chips-container">
                        <button class="ai-chip" onclick="sendAiQuickPrompt('Bagaimana cara menghitung zakat penghasilan?')">🕌 Hitung Zakat</button>
                        <button class="ai-chip" onclick="sendAiQuickPrompt('Berapa nomor rekening resmi BAZNAS Sumbawa?')">💳 Rekening Resmi</button>
                        <button class="ai-chip" onclick="sendAiQuickPrompt('Apa saja 5 program bantuan BAZNAS Sumbawa?')">📋 5 Program BAZNAS</button>
                        <button class="ai-chip" onclick="sendAiQuickPrompt('Apa syarat mengajukan bantuan mustahik?')">🩺 Syarat Bantuan</button>
                        <button class="ai-chip" onclick="sendAiQuickPrompt('Di mana alamat kantor dan jam layanan BAZNAS Sumbawa?')">📍 Alamat Kantor</button>
                        <a href="https://wa.me/<?php echo $clean_wa_ai; ?>?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa%2C%20saya%20ingin%20konsultasi" target="_blank" rel="noopener" class="ai-chip" style="border-color: #25D366; color: #1ebe5b; display: inline-flex; align-items: center; gap: 5px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#25D366">
                                <path d="M20.52 3.48A11.9 11.9 0 0012.06 0C5.46 0 .09 5.37.09 11.97c0 2.11.55 4.17 1.6 6L0 24l6.23-1.63a11.94 11.94 0 005.83 1.51h.01c6.6 0 11.97-5.37 11.97-11.97 0-3.2-1.25-6.21-3.52-8.43zM12.06 21.87h-.01a9.92 9.92 0 01-5.06-1.39l-.36-.21-3.76.99 1-3.66-.23-.38a9.9 9.9 0 01-1.52-5.25c0-5.48 4.46-9.94 9.95-9.94 2.66 0 5.15 1.03 7.03 2.91a9.88 9.88 0 012.91 7.03c0 5.48-4.46 9.94-9.94 9.94zm5.45-7.44c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.87 1.22 3.07c.15.2 2.1 3.2 5.09 4.49.71.31 1.27.49 1.7.63.71.23 1.36.2 1.88.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"/>
                            </svg>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
                <span class="ai-msg-time">Amil AI • WITA</span>
            </div>

            <!-- Loading Indicator -->
            <div class="ai-typing" id="ai-typing-indicator">
                <span class="ai-typing-dot"></span>
                <span class="ai-typing-dot"></span>
                <span class="ai-typing-dot"></span>
                <span style="font-size:11px; color:#666; margin-left:5px;">Amil AI sedang mengetik...</span>
            </div>
        </div>

        <!-- Footer Input -->
        <div class="ai-chat-footer">
            <input type="text" id="ai-input-field" class="ai-input-text" placeholder="Ketik pertanyaan seputar ZIS & program..." onkeypress="handleAiKeyPress(event)">
            <button class="ai-btn-send" id="ai-send-btn" onclick="sendBaznasAiMessage()" title="Kirim Pesan">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2" fill="#ffffff"></polygon>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- JAVASCRIPT WIDGET LOGIC -->
<script>
    const BAZNAS_AI_API = '<?php echo base_url("ai-asisten/chat"); ?>';
    const AI_SVG_LAUNCHER_CHAT = `<svg class="ai-launcher-svg" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><circle cx="8.5" cy="10" r="1.3" fill="#F4C10F" stroke="#F4C10F"></circle><circle cx="12" cy="10" r="1.3" fill="#F4C10F" stroke="#F4C10F"></circle><circle cx="15.5" cy="10" r="1.3" fill="#F4C10F" stroke="#F4C10F"></circle></svg><span class="ai-pulse-dot" title="Aktif 24 Jam"></span>`;
    const AI_SVG_LAUNCHER_CLOSE = `<svg class="ai-launcher-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;

    function toggleBaznasAiChat() {
        const widget = document.getElementById('baznas-ai-widget');
        const win = document.getElementById('ai-chat-window');
        const launcher = document.getElementById('ai-launcher');
        if (win) {
            win.classList.toggle('open');
            const isOpen = win.classList.contains('open');

            if (widget) {
                widget.classList.toggle('ai-chat-open', isOpen);
            }
            document.body.classList.toggle('ai-chat-active', isOpen);

            if (isOpen) {
                if (launcher) launcher.innerHTML = AI_SVG_LAUNCHER_CLOSE;
                setTimeout(() => {
                    const input = document.getElementById('ai-input-field');
                    if (input) input.focus();
                    scrollAiChatToBottom();
                }, 150);
            } else {
                if (launcher) launcher.innerHTML = AI_SVG_LAUNCHER_CHAT;
            }
        }
    }

    // Kemudahan menutup chat dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const win = document.getElementById('ai-chat-window');
            if (win && win.classList.contains('open')) {
                toggleBaznasAiChat();
            }
        }
    });

    function handleAiKeyPress(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendBaznasAiMessage();
        }
    }

    function sendAiQuickPrompt(text) {
        const input = document.getElementById('ai-input-field');
        if (input) {
            input.value = text;
            sendBaznasAiMessage();
        }
    }

    function scrollAiChatToBottom() {
        const body = document.getElementById('ai-chat-body');
        if (body) {
            body.scrollTop = body.scrollHeight;
        }
    }

    function formatAiText(text) {
        if (!text) return '';
        // Escape HTML
        let escaped = text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");

        // Format **tebal**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Format *miring*
        escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Format bullet points
        escaped = escaped.replace(/^\s*[•\-\*]\s+(.*)$/gm, '• $1');
        // Format markdown links [Judul Tautan](URL)
        escaped = escaped.replace(/\[([^\]]+)\]\((https?:\/\/[^\s\)<>]+)\)/g, '<a href="$2" target="_blank" rel="noopener" class="ai-chat-link"><i class="fa fa-external-link" style="font-size:11px; margin-right:3px;"></i>$1</a>');

        // Newlines ke <br>
        escaped = escaped.replace(/\n/g, '<br>');

        return escaped;
    }

    function sendBaznasAiMessage() {
        const input = document.getElementById('ai-input-field');
        const sendBtn = document.getElementById('ai-send-btn');
        const body = document.getElementById('ai-chat-body');
        const typing = document.getElementById('ai-typing-indicator');

        if (!input || !body) return;
        const msg = input.value.trim();
        if (!msg) return;

        // 1. Tampilkan pesan pengguna di chat
        const now = new Date();
        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

        const userRow = document.createElement('div');
        userRow.className = 'ai-msg-row user';
        userRow.innerHTML = `
            <div class="ai-bubble user">${formatAiText(msg)}</div>
            <span class="ai-msg-time">${timeStr}</span>
        `;
        body.insertBefore(userRow, typing);

        // Reset input & lock send button
        input.value = '';
        input.disabled = true;
        if (sendBtn) sendBtn.disabled = true;
        if (typing) typing.classList.add('active');
        scrollAiChatToBottom();

        // 2. Kirim AJAX POST ke controller Ai_asisten
        const formData = new FormData();
        formData.append('message', msg);

        fetch(BAZNAS_AI_API, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (typing) typing.classList.remove('active');

            const botReply = data.reply || "Afwan, sistem sedang mengalami kendala. Silakan coba kembali atau hubungi layanan WhatsApp resmi kami.";
            const botRow = document.createElement('div');
            botRow.className = 'ai-msg-row bot';

            let chipsHtml = '';
            if (data.quick_actions && Array.isArray(data.quick_actions) && data.quick_actions.length > 0) {
                chipsHtml = '<div class="ai-chips-container" style="display:none; opacity:0; transition:opacity 0.35s ease;">';
                data.quick_actions.forEach(action => {
                    if (action.url) {
                        chipsHtml += `<a href="${action.url}" target="_blank" rel="noopener" class="ai-chip">${action.label}</a>`;
                    } else if (action.action === 'kalkulator') {
                        chipsHtml += `<a href="<?php echo base_url('kalkulator-zakat'); ?>" class="ai-chip">${action.label}</a>`;
                    } else if (action.action === 'rekening') {
                        chipsHtml += `<button class="ai-chip" onclick="sendAiQuickPrompt('Berapa nomor rekening zakat dan infaq BAZNAS?')">${action.label}</button>`;
                    } else if (action.action === 'program') {
                        chipsHtml += `<button class="ai-chip" onclick="sendAiQuickPrompt('Jelaskan 5 program unggulan BAZNAS Sumbawa')">${action.label}</button>`;
                    } else {
                        chipsHtml += `<button class="ai-chip" onclick="sendAiQuickPrompt('${action.label}')">${action.label}</button>`;
                    }
                });
                chipsHtml += '</div>';
            }

            botRow.innerHTML = `
                <div class="ai-bubble bot">
                    <div class="ai-bubble-text"></div>
                    ${chipsHtml}
                </div>
                <span class="ai-msg-time">Amil AI • ${timeStr}</span>
            `;
            body.insertBefore(botRow, typing);
            scrollAiChatToBottom();

            // Jalankan animasi penulisan mulus (Typewriter Streaming)
            const bubbleTextEl = botRow.querySelector('.ai-bubble-text');
            const chipsEl = botRow.querySelector('.ai-chips-container');

            streamTypewriterAiText(bubbleTextEl, botReply, function() {
                // Saat selesai mengetik:
                input.disabled = false;
                if (sendBtn) sendBtn.disabled = false;
                input.focus();

                if (chipsEl) {
                    chipsEl.style.display = 'flex';
                    setTimeout(() => {
                        chipsEl.style.opacity = '1';
                        scrollAiChatToBottom();
                    }, 50);
                }
            });
        })
        .catch(err => {
            if (typing) typing.classList.remove('active');
            input.disabled = false;
            if (sendBtn) sendBtn.disabled = false;

            const errRow = document.createElement('div');
            errRow.className = 'ai-msg-row bot';
            errRow.innerHTML = `
                <div class="ai-bubble bot">
                    <p>Mohon maaf, koneksi ke server sedang mengalami gangguan. Anda dapat langsung berkonsultasi melalui kanal resmi kami:</p>
                    <div class="ai-chips-container">
                        <a href="https://wa.me/<?php echo $clean_wa_ai; ?>" target="_blank" rel="noopener" class="ai-chip" style="border-color:#25D366; color:#1ebe5b; display: inline-flex; align-items: center; gap: 5px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#25D366">
                                <path d="M20.52 3.48A11.9 11.9 0 0012.06 0C5.46 0 .09 5.37.09 11.97c0 2.11.55 4.17 1.6 6L0 24l6.23-1.63a11.94 11.94 0 005.83 1.51h.01c6.6 0 11.97-5.37 11.97-11.97 0-3.2-1.25-6.21-3.52-8.43zM12.06 21.87h-.01a9.92 9.92 0 01-5.06-1.39l-.36-.21-3.76.99 1-3.66-.23-.38a9.9 9.9 0 01-1.52-5.25c0-5.48 4.46-9.94 9.95-9.94 2.66 0 5.15 1.03 7.03 2.91a9.88 9.88 0 012.91 7.03c0 5.48-4.46 9.94-9.94 9.94zm5.45-7.44c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.87 1.22 3.07c.15.2 2.1 3.2 5.09 4.49.71.31 1.27.49 1.7.63.71.23 1.36.2 1.88.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"/>
                            </svg>
                            Hubungi WhatsApp Petugas
                        </a>
                        <a href="<?php echo base_url('kalkulator-zakat'); ?>" class="ai-chip">🧮 Kalkulator Zakat</a>
                    </div>
                </div>
                <span class="ai-msg-time">Amil AI • ${timeStr}</span>
            `;
            body.insertBefore(errRow, typing);
            scrollAiChatToBottom();
        });
    }

    /**
     * Efek Mengetik Mengalir Mulus (Smooth Typewriter Streaming)
     * Menuliskan teks kata per kata dari atas ke bawah secara elegan dan auto-scroll
     */
    function streamTypewriterAiText(element, fullText, onDone) {
        if (!element) return;
        // Pisahkan teks menjadi token kata + spasi/newline
        const tokens = fullText.split(/(\s+)/);
        let currentText = '';
        let i = 0;
        // Kecepatan adaptif: cepat untuk teks panjang, halus untuk teks pendek
        const speed = (tokens.length > 250) ? 7 : (tokens.length > 100 ? 11 : 16);

        function step() {
            if (i < tokens.length) {
                currentText += tokens[i];
                i++;
                element.innerHTML = formatAiText(currentText) + '<span class="ai-typing-cursor">▌</span>';
                scrollAiChatToBottom();
                setTimeout(step, speed);
            } else {
                element.innerHTML = formatAiText(fullText);
                scrollAiChatToBottom();
                if (typeof onDone === 'function') {
                    onDone();
                }
            }
        }
        step();
    }
</script>
