# PokéCollector

**PokéCollector** è il progetto pratico del corso _Tecnologie e Sistemi Web_. Si tratta di un'applicazione web completa che consente agli utenti di visualizzare, organizzare e monitorare la propria collezione di carte Pokémon tramite un’interfaccia intuitiva e funzionale.

## Tecnologie utilizzate

- **Front-end:** HTML, CSS, JavaScript, Bootstrap, Font Awesome, Vue.js
- **Back-end:** PHP
- **Web server:** Apache
- **Database:** MySQL

---

## 📄 Struttura delle pagine

### `index.php` e `error.php`

- `index.php`: Pagina iniziale con logo, frase di benvenuto, descrizione dell’applicazione e pulsanti per accedere o registrarsi.
- `error.php`: Mostrata in caso di errore di connessione al database, con notifica di tentativo di riconnessione.

### `login.php` e `register.php`

- Entrambe contengono form per email e password.
- `login.php` include una checkbox per accesso automatico (cookie persistente) e un link per reimpostare la password.
- Le form sono gestite da `login_logic.php` e `register_logic.php`.
- Sono previsti messaggi di errore o successo.
- Lo script `script.js` gestisce:
  - L’icona "occhio" per mostrare/nascondere la password.
  - Il controllo sulla coincidenza tra password e conferma.

### `homepage.php`

- Pagina principale organizzata in una griglia di **cards**, una per ogni serie di carte.
- Ogni card contiene i pulsanti delle espansioni.
- Include:
  - Griglia responsive con Bootstrap.
  - Barra di ricerca per trovare espansioni.
  - Pulsante per accedere a `impostazioni.php`, dove si può reimpostare la password, eseguire logout o eliminare l’account.
  - Link alle pagine `dashboard.php` relative alle espansioni.

### `dashboard.php`

- Pagina dinamica che mostra tutte le carte di una specifica espansione selezionata.
- Struttura a griglia con:
  - Elenco delle carte
  - Immagine della carta selezionata
- Funzionalità:
  - Aggiunta/rimozione di copie
  - Ingrandimento immagine
  - Link diretto a Cardmarket
- Le carte possedute sono a colori e hanno un'icona Pokéball.
- Navigazione via barra di ricerca o tastiera.
- Tutta la logica dinamica è gestita in `vue-dashboard.js`.

### `chat.php`

- Pagina di chat con un **LLM** che interpreta il **Professor Oak**.
- Utilizza l'API gratuita di **Gemma 3** (modello Google con 27 miliardi di parametri).
- I messaggi sono salvati nel `localStorage` per mantenerli anche dopo ricariche o navigazioni.

---

## 📁 File di stile

Il layout e la grafica dell'intera applicazione sono definiti nel file `style.css`.

---
