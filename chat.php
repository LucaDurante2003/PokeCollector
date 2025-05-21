<?php
    // Inizia la sessione
    session_start();
    include_once 'includes/header.php';
?>

<body class="d-flex flex-column min-vh-100">

<div id="chatApp" class="chat-fullscreen d-flex flex-column">

    <!-- Barra superiore con due pulsanti e titolo -->
    <div class="chat-title-wrapper d-flex align-items-center justify-content-between px-4 py-2">
        <!-- Pulsante indietro -->
        <a href="homepage.php" class="btn btn-orange">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Titolo centrato -->
        <h2 class="chat-title m-0 text-center flex-grow-1">
            Chatta con il Professor Oak
        </h2>

        <!-- Bottone svuota chat -->
        <button @click="clearChat" class="btn btn-orange btn-sm">
            <i class="fas fa-trash-alt me-1"></i> Svuota chat
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
        <button @click="sendMessage" class="btn btn-orange" :disabled="isLoading">Invia</button>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
const app = Vue.createApp({
    data() {
        return {
            userInput: '',
            messages: JSON.parse(localStorage.getItem('chatMessages')) || [],
            isLoading: false
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

            this.userInput = '';

            // Placeholder di caricamento
            this.messages.push({ role: 'assistant', content: 'Sto scrivendo...' });
            this.saveMessages();

            try {
                const res = await fetch('php/ask_to_LLM.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: input })
                });
                const data = await res.json();

                // Rimuove il placeholder
                this.messages.pop();

                // Aggiunge risposta effettiva
                this.messages.push({ role: 'assistant', content: data.reply });
                this.saveMessages(); // Salva cronologia aggiornata

                this.$nextTick(() => {
                    const log = document.querySelector('.chat-log');
                    log.scrollTop = log.scrollHeight;
                });
            } catch (err) {
                this.messages.pop();
                this.messages.push({ role: 'assistant', content: '⚠️ Errore nella risposta.' });
                this.saveMessages();
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
        }
    },
    mounted() {
        this.loadMessages();
        this.$nextTick(() => {
            const log = document.querySelector('.chat-log');
            if (log) log.scrollTop = log.scrollHeight;
        });
    }
});
app.mount('#chatApp');
</script>

<?php include_once 'includes/footer.php'; ?>