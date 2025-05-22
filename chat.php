<?php
    // Inizia la sessione
    session_start();
    include_once 'includes/header.php';

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
?>

<body class="d-flex flex-column min-vh-100">

<div id="chatApp" class="chat-fullscreen d-flex flex-column">

    <!-- Barra superiore con due pulsanti e titolo -->
    <div class="chat-title-wrapper d-flex align-items-center justify-content-between px-3 py-2">
        <!-- Pulsante indietro -->
        <a href="homepage.php" class="round-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Titolo centrato -->
        <h2 class="chat-title m-0 text-center flex-grow-1">
            Chatta con il Professor Oak
        </h2>

        <!-- Bottone svuota chat -->
        <button @click="clearChat" class="round-btn">
            <i class="fas fa-trash-alt me-1" style="transform:translateX(2px)"></i>
        </button>
    </div>

    <!-- Log della chat (scrollabile) -->
    <div class="chat-log flex-grow-1 overflow-auto p-4">
        <div
            class="d-flex w-100 mb-3"
            v-for="(msg, index) in messages"
            :key="index"
        >
            <!-- Messaggio dell'assistente -->
            <template v-if="msg.role === 'assistant'">
                <div class="d-flex align-items-start">
                    <img
                        src="assets/img/assistant.png"
                        alt="Assistant Avatar"
                        class="chat-avatar me-2"
                    />
                    <div class="message-bubble assistant-msg">
                        {{ msg.content }}
                    </div>
                </div>
            </template>

            <!-- Messaggio dell'utente -->
            <template v-else>
                <div class="d-flex justify-content-end w-100">
                    <div class="message-bubble user-msg ms-auto">
                        {{ msg.content }}
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Campo input in basso -->
    <div class="chat-input-container p-3 border-top d-flex">
        <input
            type="text"
            v-model="userInput"
            class="form-control me-2"
            id="chat-input"
            placeholder="Scrivi una domanda..."
            :disabled="isLoading"
            @keyup.enter="sendMessage"
        />
        <button @click="sendMessage" class="round-btn" :disabled="isLoading"><i class="fa fa-paper-plane" style="transform:translateX(-1.5px)" aria-hidden="true"></i></button>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
    window.APP_USER_NAME = <?= json_encode($_SESSION['user_name'] ?? 'Allenatore') ?>;
</script>
<script>
const app = Vue.createApp({
    data() {
        return {
            userInput: '',
            messages: JSON.parse(localStorage.getItem('chatMessages')) || [],
            isLoading: false,
            userName: window.APP_USER_NAME
        };
    },
    methods: {
        async sendMessage() {
            const input = this.userInput.trim();
            if (!input) return;

            this.isLoading = true; 
            // Aggiunge messaggio utente
            this.messages.push({ role: 'user', content: input });
            this.saveMessages(); // Salva subito dopo
            this.scrollToBottom();

            this.userInput = '';

            // Placeholder di caricamento
            this.messages.push({ role: 'assistant', content: 'Sto scrivendo...' });
            this.saveMessages();
            this.scrollToBottom();

            try {
                const res = await fetch('php/ask_to_LLM.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: input,user_name: this.userName })
                });
                const data = await res.json();

                // Rimuove il placeholder
                this.messages.pop();

                // Aggiunge risposta effettiva
                this.messages.push({ role: 'assistant', content: data.reply });
                this.saveMessages(); // Salva cronologia aggiornata
                this.scrollToBottom();
            } catch (err) {
                this.messages.pop();
                this.messages.push({ role: 'assistant', content: '⚠️ Errore nella risposta.' });
                this.saveMessages();
                this.scrollToBottom();
            } finally {
                this.isLoading = false;
            }
        },

        saveMessages() {
            localStorage.setItem('chatMessages', JSON.stringify(this.messages));
        },

        loadMessages() {
            const saved = localStorage.getItem('chatMessages');
            if (saved) {
                this.messages = JSON.parse(saved);
            }
        },

        clearChat() {
            this.messages = [];
            localStorage.removeItem('chatMessages');
            this.scrollToBottom();
        },

        scrollToBottom(){
            this.$nextTick(() => {
                const log = document.querySelector('.chat-log');
                if (log) log.scrollTop = log.scrollHeight;
            });   
        }
    },
    mounted() {
        this.loadMessages();
        this.scrollToBottom();
    }
});
app.mount('#chatApp');
</script>

<?php include_once 'includes/footer.php'; ?>