const { createApp } = Vue;

createApp({
  data() {
    return {
      slug: window.APP_CONFIG.slug,
      expansions: window.APP_CONFIG.expansions,
      apiKey: window.APP_CONFIG.apiKey,
      cards: [],
      ownedCards: new Set(),
      filteredCards: [],
      selectedCard: null,
      possessed: false,
      copies: 0,
      searchVisible: false,
      searchQuery: '',
      settingsOpen: false,
      cursorTop: 0,
      currentIndex: 0,
      ownedCount: window.APP_CONFIG.ownedCount || 0,
      totalCards: window.APP_CONFIG.totalCards || 0,
      filterOwned: false,
      showFullscreen: false,



      // Quantità per le modali
      addQuantity: 1,
      removeQuantity: 1,

      // Messaggi user-friendly
      messageText: '',
      messageClass: '' // 
    };
  },
  computed: {
    expansionName() {
      return this.expansions[this.slug]?.name || 'Espansione sconosciuta';
    },
    progressPercent() {
      if (this.totalCards === 0) return 0;
      return Math.round((this.ownedCount / this.totalCards) * 100);
    }
    
  },
  created() {
    // Carichiamo subito le carte già presenti in window.APP_CONFIG.initialCards
    this.cards = window.APP_CONFIG.initialCards || [];
    this.filteredCards = this.cards;
    // Ottieni carte possedute
    fetch('php/carte_possedute.php')
    .then(res => res.json())
    .then(ids => {
      ids.forEach(id => this.ownedCards.add(id));
      if (this.cards.length) {
        this.selectCard(this.cards[0]);
      }
    });
  },
  mounted() {
    window.addEventListener('keydown', this.handleArrowKeys);
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.handleArrowKeys);
  },
  methods: {
    async selectCard(card,index) {
      this.selectedCard = {
        id:     card.id,
        name:   card.name,
        img:    card.images.large || card.images.small,
        rarity: card.rarity || 'Common',
        type:   card.supertype,
        artist: card.artist || 'Unknown'
      };
      this.currentIndex = index;
      this.$nextTick(() => this.updateCursorPosition());
      // Verifica possesso su server
      const resp = await fetch(`php/verifica_possesso.php?card_id=${card.id}`);
      const data = await resp.json();
      this.possessed = !!data.possessed;
      this.copies    = data.copies || 0; 
      // Reset quantità modali
      this.addQuantity = 1;
      this.removeQuantity = 1;
    },
    toggleFilterOwned() {
      this.filterOwned = !this.filterOwned;
      this.applySearch(); // ricalcola i risultati
    },
    openFullscreen() {
      this.showFullscreen = true;
    },
    closeFullscreen() {
      this.showFullscreen = false;
    },
    applySearch() {
      const lower = this.searchQuery.toLowerCase();
      this.filteredCards = this.cards.filter(c => {
        const matchesQuery =
          c.name.toLowerCase().includes(lower) ||
          (c.rarity || '').toLowerCase().includes(lower) ||
          (c.supertype || '').toLowerCase().includes(lower) ||
          (c.artist || '').toLowerCase().includes(lower);

      const isOwned = this.ownedCards.has(c.id);
      return this.filterOwned ? matchesQuery && isOwned : matchesQuery;
  });
      this.currentIndex = 0;
      this.$nextTick(() => {
        if (this.filteredCards.length > 0) {
          this.selectCard(this.filteredCards[0], 0);
        } else {
          this.currentIndex = -1;
          this.selectedCard = null;
        }
      });
    },
    
    clearSearch() {
      this.searchQuery = '';
      this.filteredCards = this.cards;
      this.currentIndex = 0;
      this.$nextTick(() => {
        if (this.filteredCards.length > 0) {
          this.selectCard(this.filteredCards[0], 0);
        } else {
          this.currentIndex = -1;
          this.selectedCard = null;
        }
      });
    },
    handleArrowKeys(e) {
      const isInput = e.target.tagName === "INPUT" || e.target.tagName === "TEXTAREA";
      if (isInput) return;
    
      if (["ArrowUp", "ArrowDown"].includes(e.key)) {
        e.preventDefault();
    
        if (e.key === "ArrowDown" && this.currentIndex < this.filteredCards.length - 1) {
          this.currentIndex++;
        } else if (e.key === "ArrowUp" && this.currentIndex > 0) {
          this.currentIndex--;
        } else {
          return;
        }

        const selected = this.filteredCards[this.currentIndex];
        const labels = Array.isArray(this.$refs.labels) ? this.$refs.labels : [];

        if (selected && labels[this.currentIndex]) {
          this.selectCard(selected, this.currentIndex);
        }
      }
    },
    
    hoverCard(index) {
      this.currentIndex = index;
      this.updateCursorPosition();
    },
    updateCursorPosition() {
      this.$nextTick(() => {
        const labels = this.$refs.labels;
        const label = Array.isArray(labels) ? labels[this.currentIndex] : null;
        if (label) {
          const offset = label.offsetTop + label.offsetHeight / 2 - 16;
          this.cursorTop = offset;
    
          const container = this.$refs.sidebar;
          if (container) {
            const containerTop = container.scrollTop;
            const containerHeight = container.clientHeight;
            const labelTop = label.offsetTop;
            const labelHeight = label.offsetHeight;
    
            const scrollPosition = labelTop - containerHeight / 2 + labelHeight / 2;
            container.scrollTo({ top: scrollPosition, behavior: 'smooth' });
          }
        }
      });
    },
    openAddModal() {
      new bootstrap.Modal(document.getElementById('addCardModal')).show();
    },
    openRemoveModal() {
      new bootstrap.Modal(document.getElementById('removeCardModal')).show();
    },

    // Increment / decrement
    incrementAdd()   { this.addQuantity++; },
    decrementAdd()   { if (this.addQuantity > 1) this.addQuantity--; },
    incrementRemove(){ if (this.removeQuantity < this.copies) this.removeQuantity++; },
    decrementRemove(){ if (this.removeQuantity > 1) this.removeQuantity--; },

    // Conferme modali
    async confirmAdd() {
      // Esegui l’update
      await this.updateCollection('add', this.addQuantity);
      // Chiudi modale
      const modalEl = document.getElementById('addCardModal');
      bootstrap.Modal.getInstance(modalEl).hide();
      this.addQuantity = 1;
    },
    async confirmRemove() {
      if (this.removeQuantity > this.copies) {
        this.showMessage('Numero di copie da rimuovere superiore al numero di copie possedute', 'alert-danger');
        return;
      }
      await this.updateCollection('remove', this.removeQuantity);
      const modalEl = document.getElementById('removeCardModal');
      bootstrap.Modal.getInstance(modalEl).hide();
      this.removeQuantity = 1;
    },

    // Funzione generica per mostrare messaggi
    showMessage(text, cssClass) {
      this.messageText  = text;
      this.messageClass = cssClass;
      // dopo 3 secondi sparisce
      setTimeout(() => {
        this.messageText = '';
      }, 3000);
    },

    async updateCollection(action, quantity) {
      try {
        const res = await fetch('php/gestisci_collezione.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify({
            action,
            card_id: this.selectedCard.id,
            quantity
          })
        });
        const r = await res.json();
        if (r.success) {
          // Messaggio di successo
          if (action === 'add') {
            this.ownedCards.add(this.selectedCard.id);
          } else if (action === 'remove' && this.copies - quantity <= 0) {
            this.ownedCards.delete(this.selectedCard.id);
          }
          this.ownedCount = this.ownedCards.size;
          this.showMessage(
            action === 'add'
              ? `Hai aggiunto ${quantity} copi${quantity>1?'e':'a'} con successo!`
              : `Hai rimosso ${quantity} copi${quantity>1?'e':'a'} con successo!`,
            'alert-success'
          );
          // Aggiorna lo stato attuale della carta selezionata
          if (action === 'add') {
            this.possessed = true;
            this.copies += quantity;
          } else {
            this.copies -= quantity;
            if (this.copies <= 0) {
              this.possessed = false;
              this.copies = 0;
            }
          }
        } else {
          // Messaggio di errore da server
          this.showMessage(r.error || 'Errore durante l\'operazione.', 'alert-danger');
        }
      } catch (err) {
        console.error('Errore rete:', err);
        this.showMessage('Errore di rete. Riprova più tardi.', 'alert-danger');
      }
    }
  }

}).mount('#app');