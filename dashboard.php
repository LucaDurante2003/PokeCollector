<?php
    session_start();
    include_once 'includes/header.php';
    /*Qui dovrai mettere la parte per la connesione al database delle 
    espansioni, delle carte e della collezione dell'utente*/
?>

<!--Barra di ricerca-->
<div class="search-wrapper">
    <div class="search-container">
        <button id="searchToggle" class="round-btn">
            <i class="fas fa-search"></i>
        </button>
        <input type="text" class="search-input" placeholder="Cerca Pokémon...">
        <button id="clearSearch" class="clear-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Bottone impostazioni -->
<div class="settings-wrapper">
    <button id="settingsBtn" class="round-btn"><i class="fas fa-cog"></i></button>
    <div id="settingsMenu" class="dropdown-menu">
        <a href="impostazioni.php" class="dropdown-item">Gestione Account</a>
    </div>
</div>

<body class="d-flex flex-column min-vh-100">
    <div class="main-content mt-4">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md-auto mb-3 mb-md-0">
                <a href="homepage.php">
                    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img" style="height: 300px;">
                </a>
            </div>
            <div class="col text-center text-md-start">
                <!--Qua dovrai sotituirlo con la riga php che prende l'espansione cliccata dall'utente in homepage.php-->
                <h1 class="main-title display-6">Nome espansione - Dashboard</h1>
            </div>
        </div>
    </div>

    <div class="container pokedex-container mt-4 mb-5">
        <div class="row gy-4 justify-content-center align-items-start">
            <!-- Colonna sinistra: elenco carte -->
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <div class="pokedex-sidebar card-body">
                    <div class="pokedex-sidebar-header d-flex align-items-center justify-content-center mb-3 mt-2">
                        <h5 class="text-orange-title-dashboard text-center m-0">Carte disponibili</h5>
                    </div>
                    <div class="labels-wrapper position-relative">
                        <div class="pokedex-cursor" id="pokedexCursor"></div>
                        <!--Qua devi mettere le varie label, credo devi fare un ciclo for in cui fai ripetere l'elemento
                        <div class "label pokedex.label" .....>Nome del pokemon</div>
                        Avevo fatto una versione statica di prova e avevo scritto questa cosa:-->
                        <div class="label pokedex-label" data-nome="Alakazam" data-img="https://media.pokemoncentral.it/wiki/thumb/1/12/AlakazamSetBase1.jpg/270px-AlakazamSetBase1.jpg" data-rarita="Comune" data-tipo="Psiche" data-numero="01">Alakazam</div>
                        <div class="label pokedex-label" data-nome="Blastoise" data-img="https://media.pokemoncentral.it/wiki/3/39/BlastoiseSetBase2.jpg" data-rarita="Comune" data-tipo="Acqua" data-numero="02">Blastoise</div>
                        <div class="label pokedex-label" data-nome="Chansey" data-img="https://media.pokemoncentral.it/wiki/8/80/ChanseySetBase3.jpg" data-rarita="Comune" data-tipo="Incolore" data-numero="03">Chansey</div>
                    </div>
                </div>
            </div>

            <!-- Colonna destra: mostra carta selezionata -->
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <div class="card-body text-center" id="cardDisplay">
                    <div class="card-body-header d-flex align-items-center justify-content-center mb-3">
                        <h5 class="text-orange-title-dashboard text-center m-0" id="cardName">Nome del pokemon</h5>
                    </div>
                    <!--Nel campo src ci mettere l'url dell'immagine della carta. La carta la vedi in bianco e nero se l'utente non
                    ce l'ha nella connessione. Devi però effettivamente controllare se l'utente ha la carta o no. ChatGPT consigliava
                    di scrivere una funzione Javascript:
                    document.querySelectorAll('.label.pokedex-label').forEach(function(label) {
                        label.addEventListener('click', function() {
                        const cartaId = label.getAttribute('data-carta-id');
                        const imgSrc = label.getAttribute('data-img');
                        const cardImage = document.getElementById('cardImage');
        
                        // Verifica se l'utente possiede la carta tramite una richiesta AJAX
                        fetch(`verifica_possessone.php?carta_id=${cartaId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.posseduta) {
                                    cardImage.classList.remove('grayscale'); // Se posseduta, la mostri a colori
                                } else {
                                    cardImage.classList.add('grayscale'); // Se non posseduta, la mostri in bianco e nero
                                }
                                cardImage.src = imgSrc; // Imposta l'immagine della carta
                                // Mostra i dettagli della carta (aggiungi il tuo codice qui)
                            });
                        });
                    });-->
                    <div class="card-image-wrapper">
                        <!-- Pallino in alto a sinistra con il numero di copie
                         (al posto di 0 ci dovrai mettere il numero di copie che prendi dal database)-->
                        <div class="round-btn card-count" id="copiesCount">0</div>
                        <!-- Bottone + in alto a destra-->
                        <button class="round-btn card-add-btn" id="addButton"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        <!-- Immagine della carta -->
                        <img id="cardImage" src="" alt="Carta Pokémon" class="card-img" />
                        <!-- Qui ci vanno i dettagli della carta -->
                        <div id="cardDetails" class="mt-3 text-white"></div>
                        <!-- Bottone - in basso a destra; questo voglio che sia presente solo se l'utente ha almeno una 
                         copia di quella carta nella collezione. ChatGPT consigliava di scrivere questa funzione Javascript:
                         if (data.posseduta && data.copie > 0) {
                            removeButton.classList.remove('disabled');
                            copiesCount.textContent = data.copie;
                        } else {
                            removeButton.classList.add('disabled');
                            copiesCount.textContent = 0;
                        }-->
                        <button class="round-btn card-remove-btn" id="removeButton"><i class="fa fa-minus" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--DEVI GESTIRE L'AGGIUNTA/RIMOZIONE DELLE CARTE A LIVELLO DI DATABASE. Nelle modali ho messo un bottone "aggiorna collezione",
    quando lo premi fai partire un file php che gestisce sta cosa (esempio aggiorna_collezione.php). ChatGPT diceva di scrivere questo in script.js:
        // Quando clicchi su "Aggiorna Collezione" nella modale Aggiunta
        document.getElementById('confirmAddCard').addEventListener('click', function () {
            const copie = document.getElementById('removeCardInput').value;
            const cartaId = document.getElementById('cardImage').dataset.cartaId;

            fetch('aggiorna_collezione.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `azione=aggiungi&carta_id=${cartaId}&copie=${copie}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // o aggiorna dinamicamente i valori
                } else {
                    alert('Errore durante l\'aggiornamento della collezione');
                }
            });
        });

        // Quando clicchi su "Aggiorna Collezione" nella modale Rimozione
        document.getElementById('confirmRemoveCard').addEventListener('click', function () {
            const copie = document.getElementById('removeCardInput').value;
            const cartaId = document.getElementById('cardImage').dataset.cartaId;

            fetch('aggiorna_collezione.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `azione=rimuovi&carta_id=${cartaId}&copie=${copie}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // o aggiorna dinamicamente i valori
                } else {
                    alert('Errore durante l\'aggiornamento della collezione');
                }
            });
        });
    e diceva di sostituire questa riga:
    <img id="cardImage" src="" alt="Carta Pokémon" class="card-img" /> 
    con questa: 
    <img id="cardImage" src="" alt="Carta Pokémon" class="card-img" data-carta-id="" />-->

    <!-- Modale aggiunta aopie (compare quando premi su +) -->
    <div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCardModalLabel">Quante copie vuoi aggiungere alla tua collezione?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <button class="btn round-btn me-3" id="decrementBtn"><i class="fas fa-minus"></i></button>
                        <span id="removeCardDisplay" class="fs-4 px-3">1</span>
                        <input type="hidden" id="removeCardInput" value="1">
                        <button class="btn round-btn ms-3" id="incrementBtn"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-orange" id="confirmAddCard">Aggiorna Collezione</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale rimozione copie (compare quando premi su -) -->
    <div class="modal fade" id="removeCardModal" tabindex="-1" aria-labelledby="removeCardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeCardModalLabel">Quante copie vuoi rimuovere dalla collezione?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <button class="btn round-btn me-3" id="decrementBtn"><i class="fas fa-minus"></i></button>
                        <span id="removeCardDisplay" class="fs-4 px-3">1</span>
                        <input type="hidden" id="removeCardInput" value="1">
                        <button class="btn round-btn ms-3" id="incrementBtn"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-orange" id="confirmRemoveCard">Aggiorna Collezione</button>
                </div>
            </div>
        </div>
    </div>

<?php
    include_once 'includes/footer.php';
?>
