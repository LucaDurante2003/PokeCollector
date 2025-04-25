// ------------ GESTIONE PASSWORD ------------ 
/* Gestisce l'occhio per nascondere e visualizzare la password inserita */
document.addEventListener("DOMContentLoaded", function () {
    const showPasswordElements = document.querySelectorAll(".show-password");
    showPasswordElements.forEach(function(showPassword) {
        const passwordField = document.querySelector(`#${showPassword.dataset.target}`);

        showPassword.addEventListener("click", function() {
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash", !this.classList.contains("fa-eye"));

            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
        });
    });
});

/*Controlla se 'password' e 'conferma password' sono uguali*/
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const password = document.querySelector("#password");
    const confirmPassword = document.querySelector("#confirm_password");
    const errorMsg = document.querySelector("#password-error");

    function checkPasswordsMatch() {
        const match = password.value === confirmPassword.value;

        if (match || confirmPassword.value === "") {
            errorMsg.classList.add("d-none");
            password.classList.remove("is-invalid");
            confirmPassword.classList.remove("is-invalid");
            if (confirmPassword.value !== "") {
                password.classList.add("is-valid");
                confirmPassword.classList.add("is-valid");
            } else {
                password.classList.remove("is-valid");
                confirmPassword.classList.remove("is-valid");
            }
        } else {
            errorMsg.classList.remove("d-none");
            password.classList.remove("is-valid");
            confirmPassword.classList.remove("is-valid");
            password.classList.add("is-invalid");
            confirmPassword.classList.add("is-invalid");
        }

        return match;
    }

    password.addEventListener("input", checkPasswordsMatch);
    confirmPassword.addEventListener("input", checkPasswordsMatch);

    form.addEventListener("submit", function (e) {
        if (!checkPasswordsMatch()) {
            e.preventDefault();
        }
    });
});

/* Gestisce i controlli di password per la pagina delle impostazioni */
document.addEventListener("DOMContentLoaded", function (){
    const form = document.querySelector("form");
    const oldPassword = document.querySelector("#old_password");
    const newPassword = document.querySelector("#new_password");
    const confirmPassword = document.querySelector("#confirm_password");

    const errorOldPassword = document.querySelector("#old-password-error");
    const errorSameAsOld = document.querySelector("#same-as-old-error");
    const errorPasswordMatch = document.querySelector("#password-error");

    let oldPasswordIsValid = false;
    let currentRequestId = 0; //serve per gestire le richieste asincrone e evitare conflitti tra le risposte

    if (form && oldPassword && newPassword && confirmPassword){
        
        //Funzione che verifica se la nuova password e la conferma password corrispondono
        function checkPasswordsMatch(){
            const match = newPassword.value === confirmPassword.value && confirmPassword.value !== "";
            const bothEmpty = newPassword.value === "" && confirmPassword.value === "";

            //Reset classi e messaggio di errore
            confirmPassword.classList.remove("is-valid", "is-invalid");
            errorPasswordMatch.classList.add("d-none");

            //Mostra errore solo quando si scrive nel campo di conferma password
            if (confirmPassword.value !== ""){
                if (match){
                    confirmPassword.classList.add("is-valid");
                } 
                else{
                    confirmPassword.classList.add("is-invalid");
                    errorPasswordMatch.classList.remove("d-none");
                }
            }

            return match;
        }

        //Funzione per confrontare la vecchia password con quella inserita
        function checkOldPassword() {
            const value = oldPassword.value.trim(); //rimuove gli spazi vuoti all'inizio e alla fine

            //Se la vecchia password è vuota, resetta tutto
            if (value === "") {
                oldPassword.classList.remove("is-invalid");
                errorOldPassword.classList.add("d-none");
                oldPasswordIsValid = false;
                return;
            }

            //Incrementa l'ID della richiesta per evitare che risposte precedenti interferiscano con la logica
            const requestId = ++currentRequestId;

            //Esegue una richiesta asincrona per verificare la vecchia password nel database
            fetch("php/check_old_pw_logic.php?ts=" + Date.now(), {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `old_password=${encodeURIComponent(value)}`
            })
            .then(response => response.json()) //elabora la risposta come json
            .then(data => {
                if (requestId !== currentRequestId) return;

                if (data.success){
                    oldPassword.classList.add("is-valid");
                    oldPassword.classList.remove("is-invalid");
                    errorOldPassword.classList.add("d-none");
                    oldPasswordIsValid = true;
                } 
                else{
                    oldPassword.classList.remove("is-valid");
                    oldPassword.classList.add("is-invalid");
                    errorOldPassword.classList.remove("d-none");
                    oldPasswordIsValid = false;
                }
            })
            .catch(error => {
                console.error("Errore nella verifica della vecchia password:", error);
            });
        }

        //Funzione per controllare che la nuova password non sia la stessa della vecchia
        function checkNewVsOld() {
            if (newPassword.value === oldPassword.value){
                newPassword.classList.add("is-invalid");
                errorSameAsOld.classList.remove("d-none");
            } 
            else{
                newPassword.classList.remove("is-invalid");
                errorSameAsOld.classList.add("d-none");
            }
        }

        // Controllo della vecchia password
        oldPassword.addEventListener("input", function () {
            oldPassword.classList.remove("is-invalid", "is-valid");
            errorOldPassword.classList.add("d-none");
            oldPasswordIsValid = false;
        });

        //Quando l'utente esce dal campo di input, controlla la vecchia password
        oldPassword.addEventListener("blur", function () {
            checkOldPassword(); //Verifica la vecchia password quando esce dal campo
        });

        //Controllo della nuova password
        newPassword.addEventListener("input", function () {
            checkNewVsOld(); //Verifica che la nuova password non sia uguale a quella vecchia
        });

        //Controllo della conferma della password
        confirmPassword.addEventListener("input", checkPasswordsMatch);

        //Impedire il submit del form se c'è qualche errore
        form.addEventListener("submit", function (e) {
            checkOldPassword();
            checkPasswordsMatch();
            checkNewVsOld();

            if (!oldPasswordIsValid || newPassword.classList.contains("is-invalid") || confirmPassword.classList.contains("is-invalid")) {
                e.preventDefault(); // Se c'è un errore, impediamo il submit
            }
        });
    }
});


// ------------ GESTIONE ELIMINAZIONE ACCOUNT ------------ 
window.addEventListener('DOMContentLoaded', function () {
    const accountDeletedModal = document.getElementById('accountDeletedModal');

    if (accountDeletedModal) {
        const myModal = new bootstrap.Modal(accountDeletedModal);
        myModal.show();

        setTimeout(function () {
            window.location.href = 'index.php';
        }, 3000);
    }
});


// ------------ GESTIONE ICONETTA IMPOSTAZIONI ------------ 
document.addEventListener("DOMContentLoaded", function () {
    const settingsBtn = document.getElementById("settingsBtn");
    const settingsMenu = document.getElementById("settingsMenu");

    if (settingsBtn && settingsMenu) {
        settingsBtn.addEventListener("click", function () {
            settingsMenu.style.display = (settingsMenu.style.display === "block") ? "none" : "block";
        });
    }
});

// ------------ GESTIONE FRECCETTA PER TORNARE SU ------------ 
document.addEventListener("DOMContentLoaded", function () {
    const scrollTopBtn = document.getElementById("scrollTopBtn");

    if (scrollTopBtn) {
        window.onscroll = function () {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                scrollTopBtn.style.display = "flex";
            } else {
                scrollTopBtn.style.display = "none";
            }
        };

        scrollTopBtn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});


// ------------ GESTIONE RICERCA NELLA HOMEPAGE ------------ 
document.addEventListener("DOMContentLoaded", function (){ //aspetta che il DOM sia completamente caricato prima di eseguire la funzione
    const searchToggle = document.getElementById("searchToggle"); //icona lente d'ingrandimento
    const searchInput = document.querySelector(".search-input"); //campo input della barra di ricerca
    const clearButton = document.getElementById("clearSearch"); //bottone X per cancellare l'input
    const cards = document.querySelectorAll(".card"); //celle che contengono le espansioni

    let searchActive = false;
    let firstMatchCard = null; //memorizza la prima cella ch ha un'espansione che matcha la ricerca

    searchToggle.addEventListener("click", function (){
        //Se la ricerca è attiva ma la barra di ricerca è vuota (quindi ho cliccato sulla barra di ricerca ma non ho scritto nulla)
        if (searchInput.classList.contains("active") && searchInput.value.trim() === "") {
            searchInput.classList.remove("active");
            searchActive = false;
            clearHighlights();
            window.scrollTo(0, 0); //riporta la pagina in alto
        }
        else{
            searchInput.classList.add("active");
            searchInput.focus(); //ottiene il focus per iniziare a scrivere
            searchActive = true;
        }
    });
    //Quando l'utente scrive qualcosa...
    searchInput.addEventListener("input", function (){
        const searchValue = searchInput.value.toLowerCase(); //convertito tutto in minuscolo in modo che la ricerca sia case insensitive
        firstMatchCard = null;
        //fa vedere il bottone della X solo quando inizi a scrivere qualcosa
        if (searchValue !== ""){
            clearButton.style.display = "inline";
        } 
        else{
            clearButton.style.display = "none";
        }
        //Itera su ogni cella
        cards.forEach(card => {
            const expansionButtons = card.querySelectorAll(".expansion-buttons a"); //prende tutti i bottoni delle espansioni di quella cella
            let hasMatch = false;
            //itera sui bottoni della cella
            expansionButtons.forEach(btn => {
                const text = btn.textContent.toLowerCase(); //prende il testo scritto sul bottone e lo converte in minuscolo sempre per rendere la ricerca case insensitive
                if (text.includes(searchValue)) {
                    hasMatch = true;
                }
            });

            if (hasMatch){
                card.classList.add("highlight");
                if (!firstMatchCard) firstMatchCard = card; //se è il primo match che trova, lo memorizza
            } 
            else{
                card.classList.remove("highlight");
            }
        });

        if (searchValue === ""){
            clearHighlights(); //toglie tutti gli highlight
            window.scrollTo({ top: 0, behavior: "smooth" }); //torna in cima alla pagina in modo smooth
        } 
        else if (firstMatchCard){
            //se c’è almeno una cella che matcha, scrolla fino alla prima trovata.
            firstMatchCard.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
    //Quando clicchi sulla x
    clearButton.addEventListener("click", function (){
        searchInput.value = ""; //si svuota il campo
        searchInput.dispatchEvent(new Event("input")); /*manda manualmente l'evento input in modo che il codice si accorga che l'input
        è cambiato e che quindi deve fare certe cose a livello visivo (es togliere la X e gli highlight)*/
        clearButton.style.display = "none"; //nasconde la x
        searchInput.focus(); //riporta il focus sull’input
    });
    //se premi invio (anche mentre scrive)
    searchInput.addEventListener("keydown", function (event){
        if (event.key === "Enter" && firstMatchCard) {
            //va alla prima cella che ha un match
            firstMatchCard.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
    //quando scrolli, se c'è del testo la barra di ricerca rimane aperta
    window.addEventListener("scroll", function (){
        if (searchInput.value.trim() !== ""){
            searchInput.classList.add("active");
        }
    });
    //Rimuove tutti gli highlight delle celle ( = "pulisce" i risulati)
    function clearHighlights() {
        cards.forEach(card => {
            card.classList.remove("highlight");
        });
    }
});


// ------------ GESTIONE CURSORE IN DASHBOARD ------------ 
document.addEventListener("DOMContentLoaded", function(){
    const labels = document.querySelectorAll(".pokedex-label");
    const cursor = document.getElementById("pokedexCursor");
    let currentIndex = 0; //Posizione iniziale del cursore
  
    /*Funzione che aggiorna il box dei dettagli a destra con i dati della carta selezionata -> i dati
    provengono dagli attributi HTML (data-*) della label selezionata (vedi anche il tag div di esempio
    che avevo messo in dashboard.php*/
    function aggiornaDettagliCarta(nome, immagine, rarita, tipo, numero){
        document.getElementById("cardName").textContent = nome;
        document.getElementById("cardImage").src = immagine;
        document.getElementById("cardDetails").innerHTML = `
        <strong>Rarità:</strong> ${rarita}<br>
        <strong>Tipo:</strong> ${tipo}<br>
        <strong>N. carta:</strong> ${numero}
      `;
    }
  
    //Funzione per spostare il cursore sulla label scelta
    function spostaCursore(label){
        const offset = label.offsetTop + label.offsetHeight / 2 - 10;
        cursor.style.top = `${offset}px`;
    
        //Gestisce l'animazione carina quando il cursore punta alla label
        document.querySelectorAll('.label.pokedex-label').forEach(label => { //Rimuove la classe "hovered" da tutte le label
            label.classList.remove('hovered');
        });
        label.classList.add('hovered'); //Aggiunge la classe "hovered" alla label su cui il cursore è puntato
    
        // Aggiorna i dettagli della carta
        const nome = label.getAttribute("data-nome");
        const img = label.getAttribute("data-img");
        const rarita = label.getAttribute("data-rarita");
        const tipo = label.getAttribute("data-tipo");
        const numero = label.getAttribute("data-numero");
    
        aggiornaDettagliCarta(nome, img, rarita, tipo, numero);
        //Fa in modo che se il cursore va molto giù scrolla il container
        if (currentIndex === 0){
            //Se faccio puntare il cursore al primo blocco, torno alla posizione iniziale....
            document.querySelector('.pokedex-sidebar').scrollTo({ top: 0, behavior: 'smooth' });
        } 
        else{
            //Altrimenti sto all'altezza del blocco che punto
            label.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }        
    }
  
    //Seleziona automaticamente la prima all'avvio della pagina
    if (labels.length > 0){
      const prima = labels[0];
      spostaCursore(prima);
    }
  
   //Permette la navigazione tra le carte usando le frecce su e giù della tastiera
   document.addEventListener("keydown", (e) => {
    const isInput = e.target.tagName === "INPUT" || e.target.tagName === "TEXTAREA";
    if (isInput) return;

    // Impedisce che la pagina scrolli quando uso le freccette
    if (["ArrowDown", "ArrowUp"].includes(e.key)) {
        e.preventDefault();
    }

    if (e.key === "ArrowDown"){ // Muove il cursore verso il basso
        if (currentIndex < labels.length - 1){
            currentIndex++;
            spostaCursore(labels[currentIndex]);
        }
    } 
    else if (e.key === "ArrowUp"){ // Muove il cursore verso l'alto (precedente)
        if (currentIndex > 0){
            currentIndex--;
            spostaCursore(labels[currentIndex]);
        }
    }
    });
  
    //Puoi scegliere il pokemon anche cliccando su una label.
    labels.forEach((label, index) => {
      label.addEventListener("click", () => {
        currentIndex = index;
        spostaCursore(label);
      });
    });

    //Fa in modo che il cursore segua il puntatore del mouse
    labels.forEach((label, index) => {
        label.addEventListener("mouseenter", () => {
            currentIndex = index;
            spostaCursore(label);
        });
    });

    // ------------ GESTIONE RICERCA NELLA DASHBOARD (POKÉMON) ------------
    const searchToggle = document.getElementById("searchToggle"); // Icona lente
    const searchInput = document.querySelector(".search-input");  // Campo input
    const clearButton = document.getElementById("clearSearch");   // Bottone X

    let searchActive = false;
    let firstMatch = null;

    //Mostra o nasconde la barra di ricerca
    searchToggle.addEventListener("click", function (){
        if (searchInput.classList.contains("active") && searchInput.value.trim() === ""){
            searchInput.classList.remove("active");
            searchActive = false;
            clearHighlights();
            window.scrollTo(0, 0);
        } 
        else{
            searchInput.classList.add("active");
            searchInput.focus();
            searchActive = true;
        }
    });

    //Quando l'utente scrive nella barra
    searchInput.addEventListener("input", function () {
        const searchValue = searchInput.value.toLowerCase();
        firstMatch = null;

        //Mostra o nasconde la X
        clearButton.style.display = searchValue !== "" ? "inline" : "none";

        //Cerca nei nomi dei Pokémon
        labels.forEach(label => {
            const nomePokemon = label.textContent.toLowerCase();
            if (nomePokemon.includes(searchValue)){
                label.classList.add("highlight");
                if (!firstMatch) firstMatch = label;
            } 
            else{
                label.classList.remove("highlight");
            }
        });

        if (searchValue === ""){
            clearHighlights();
            window.scrollTo({ top: 0, behavior: "smooth" });
        } 
        else if (firstMatch){
            firstMatch.scrollIntoView({ behavior: "smooth", block: "center" });
            spostaCursore(firstMatch); // Usa la funzione esistente
        }
    });

    //Quando clicchi sulla X per cancellare
    clearButton.addEventListener("click", function (){
        searchInput.value = "";
        searchInput.dispatchEvent(new Event("input"));
        clearButton.style.display = "none";
        searchInput.focus();
    });

    //Premi Invio per andare al primo match
    searchInput.addEventListener("keydown", function (event){
        if (event.key === "Enter" && firstMatch) {
            firstMatch.scrollIntoView({ behavior: "smooth", block: "center" });
            spostaCursore(firstMatch);
        }
    });

    //Mantiene la barra espansa se c'è testo durante lo scroll
    window.addEventListener("scroll", function () {
        if (searchInput.value.trim() !== "") {
            searchInput.classList.add("active");
        }
    });

    //Rimuove tutti gli highlight
    function clearHighlights(){
        labels.forEach(label => {
            label.classList.remove("highlight");
        });
    }
});


// ------------ GESTIONE MODALE AGGIUNTA COPIE ------------
window.addEventListener('DOMContentLoaded', function () {
    const addCardButton = document.getElementById('addButton');
    const addCardModal = document.getElementById('addCardModal');

    if (addCardButton && addCardModal) {
        addCardButton.addEventListener('click', function () {
            const myModal = new bootstrap.Modal(addCardModal);
            myModal.show();
        });
    }
});

// ------------ GESTIONE MODALE RIMOZIONE COPIE ------------
window.addEventListener('DOMContentLoaded', function () {
    const removeCardButton = document.getElementById('removeButton');
    const removeCardModal = document.getElementById('removeCardModal');

    if (removeCardButton && removeCardModal) {
        removeCardButton.addEventListener('click', function () {
            const myModal = new bootstrap.Modal(removeCardModal);
            myModal.show();
        });
    }
});


// ------------ GESTIONE BOTTONI + E - NELLE MODALI DI AGGIUNTA/RIMOZIONE COPIE ------------
document.addEventListener("DOMContentLoaded", function () {

    function setupCounter(modalId, displayId, inputId) {
        const modal = document.getElementById(modalId);
        const decrementBtn = modal.querySelector('.fa-minus').closest('button');
        const incrementBtn = modal.querySelector('.fa-plus').closest('button');
        const display = modal.querySelector(`#${displayId}`);
        const input = modal.querySelector(`#${inputId}`);

        let count = 1;

        //Se clicchi il bottone - si riduce il numero di copie (ma non va sotto 1)
        decrementBtn.addEventListener('click', () => {
            if (count > 1) {
                count--;
                display.textContent = count;
                input.value = count;
            }
        });

        //Se clicchi il bottone + aumenta il numero di copie
        incrementBtn.addEventListener('click', () => {
            count++;
            display.textContent = count;
            input.value = count;
        });

        //Reset del contatore quando si apre la modale
        modal.addEventListener('show.bs.modal', () => {
            count = 1;
            display.textContent = count;
            input.value = count;
        });
    }

    //Inizializzazione dei contatori per entrambe le modali
    setupCounter('addCardModal', 'removeCardDisplay', 'removeCardInput');
    setupCounter('removeCardModal', 'removeCardDisplay', 'removeCardInput');
});
