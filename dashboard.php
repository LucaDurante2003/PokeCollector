<?php
session_start();
include_once 'includes/header.php';
require_once 'includes/config.php';

// Recupera lo slug e il mapping delle espansioni
$slug = $_GET['slug'] ?? '';
$mappingFile = 'json/espansioni.json';
$mappingEspansioni = json_decode(file_get_contents($mappingFile), true);
$apiKey = '24b29428-bc99-42f0-b4c8-d126c079bc33';
$cards = [];
$ownedCount = 0;
if ($slug) {
  $setId = urlencode($mappingEspansioni[$slug]['id'] ?? $slug);
  $cardsUrl = "https://api.pokemontcg.io/v2/cards"
    . "?q=set.id:$setId"
    . "&orderBy=number"
    . "&select=id,name,images,rarity,types,number,artist,supertype";
               
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => 'X-Api-Key: ' . $apiKey
        ]
    ];
    $json = @file_get_contents($cardsUrl, false, stream_context_create($opts));
    $cards = json_decode($json, true)['data'] ?? [];
    // Calcola quante carte possiede l'utente per questa espansione
    if (!empty($cards) && isset($_SESSION['user_id'])) {
      $cardIds = array_column($cards, 'id');
      $placeholders = implode(',', array_fill(0, count($cardIds), '?'));
      $types = str_repeat('s', count($cardIds));
      
      try{
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
          throw new Exception("Connessione fallita");
        }
        $query = "SELECT COUNT(*) AS count FROM collezioni WHERE id_utente = ? AND id_carta IN ($placeholders)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
          $params = array_merge([$_SESSION['user_id']], $cardIds);
          $stmt->bind_param('i' . $types, ...$params);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($row = $result->fetch_assoc()) {
            $ownedCount = $row['count'];
          }
          $stmt->close();
        }
        $conn->close();
      } catch (Exception $e) {
        header('Location: error.php');
        exit;
      } catch (mysqli_sql_exception $e) {
        header('Location: error.php');
        exit;
      }
    }
}
// Trova l'espansione
$expansionName = $mappingEspansioni[$slug]['name'] 
               ?? ($mappingEspansioni[$slug] ?? 'Espansione sconosciuta');

// Render
?>

<script>
  window.APP_CONFIG = {
    slug: <?= json_encode($slug) ?>,
    expansions: <?= json_encode($mappingEspansioni) ?>,
    apiKey: <?= json_encode($apiKey) ?>,
    initialCards: <?= json_encode($cards) ?>,   // qui iniettiamo le carte già pronte
    totalCards: <?= count($cards) ?>,
    ownedCount: <?= $ownedCount ?>
  };
</script>

<body class="d-flex flex-column">
    <div class="main-content mt-4">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md-auto mb-3 mb-md-0">
                <a href="homepage.php">
                    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img" style="height: 300px;">
                </a>
            </div>
        </div>
    </div>

<div id="app" class="d-flex flex-column">
<!-- Bottone impostazioni -->
<div class="settings-wrapper" v-if="!showFullscreen">
    <button id="settingsBtn" class="round-btn"><i class="fas fa-cog"></i></button>
    <div id="settingsMenu" class="dropdown-menu">
        <a href="impostazioni.php" class="dropdown-item">Gestione Account</a>
    </div>
</div>
<!-- Barra di ricerca funzionante con Vue -->
<div class="search-wrapper" v-if="!showFullscreen">
  <div class="search-container d-flex align-items-center gap-2">
    <!-- Pulsante lente identico a quello delle impostazioni -->
    <button class="round-btn" @click="applySearch" title="Cerca">
      <i class="fas fa-search"></i>
    </button>
    <!-- Pulsante "solo possedute" -->
    <button class="round-btn"
        :class="{ 'btn-success': filterOwned }"
        @click="toggleFilterOwned"
        title="Mostra solo carte possedute">
      <i class="fas fa-check"></i>
    </button>

    <input type="text"
           class="search-input flex-grow-1"
           v-model="searchQuery"
           placeholder="Cerca per nome, rarità, tipo o illustratore...">

    <button class="btn btn-outline-secondary" @click="clearSearch" v-if="searchQuery.length">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

  <!-- Messaggio operazioni -->
  <div v-if="messageText" :class="['alert', messageClass, 'mx-auto', 'w-75', 'text-center']" role="alert">
    {{ messageText }}
  </div>
  <!-- Titolo espansione -->
  <div class="container text-center mt-4">
    <h1 class="main-title display-6">{{ expansionName }}</h1>
  </div>
  <!-- Barra di progresso collezione -->
  <div class="progress-container my-3 text-center">
  <p class="mb-2 text-orange">Carte collezionate: {{ ownedCount }} / {{ totalCards }}</p>
  <div class="progress w-75 mx-auto custom-progress-bg" style="height: 20px;">
    <div class="progress-bar bg-orange" role="progressbar"
         :style="{ width: progressPercent + '%' }"
         :aria-valuenow="ownedCount" :aria-valuemin="0" :aria-valuemax="totalCards">
      {{ progressPercent }}%
    </div>
  </div>
</div>

  <!-- Contenuto principale -->
  <div class="container pokedex-container mt-4 mb-2">
    <div class="row gy-4 justify-content-center align-items-start">
      <!-- Sinistra: lista carte -->
      <div class="col-12 col-md-6 d-flex justify-content-center">
        <div class="pokedex-sidebar card-body w-100" ref="sidebar">
          <h5 class="text-orange-title-dashboard text-center mb-3">Carte disponibili</h5>
          <div class="labels-wrapper text-center">
            <div class="pokedex-cursor" :style="{ top: cursorTop + 'px' }"></div>

            <button
              v-for="(card, index) in filteredCards"
              
              class="label pokedex-label btn btn-outline-secondary w-100 mb-2 text-start"
              :class="{ hovered: currentIndex === index, active: selectedCard?.id === card.id, 'label-owned': ownedCards.has(card.id)}"
              @click="selectCard(card, index)"
              ref="labels"
            >
              {{ card.name }}
            </button>
            <div v-if="!filteredCards.length" class="alert alert-warning">
              Nessuna carta trovata.
            </div>
          </div>
        </div>
      </div>
      <!-- Destra: preview carta -->
    
<div class="col-12 col-md-6 d-flex justify-content-center">
  <div v-if="selectedCard" class="card-body text-center w-100" id="cardDisplay">
    <h5 class="text-orange-title-dashboard display-6 mb-3">{{ selectedCard.name }}</h5>

    <!-- Immagine + Pulsanti affiancati -->
    <div class="d-flex justify-content-center align-items-start gap-4" style="margin-right: -70px;">
      
      <!-- Immagine -->
      <div class="card-image-wrapper">
        <img :src="selectedCard.img"
             :class="{ grayscale: !possessed }"
             class="card-img mb-3"
             alt="Carta Pokémon" />
      </div>

      <!-- Colonna dei pulsanti -->
      <div class="d-flex flex-column align-items-center mt-2">
        <div class="round-btn mb-3 card-count">{{ copies }}</div>
        <button class="round-btn mb-3 card-add-btn" @click="openAddModal">
          <i class="fa fa-plus"></i>
        </button>
        <button class="round-btn card-remove-btn"
                :class="{ disabled: copies === 0 }"
                @click="openRemoveModal">
          <i class="fa fa-minus"></i>
        </button>
        <button class="round-btn mt-3" @click="openFullscreen">
          <i class="fas fa-expand"></i>
        </button>
      </div>
    </div>

    <!-- Dettagli carta -->
    <div id="cardDetails" class="mt-4 text-orange">
      <strong>Rarità:</strong> {{ selectedCard.rarity }}<br>
      <strong>Tipo:</strong> {{ selectedCard.type }}<br>
      <strong>Illustratore:</strong> {{ selectedCard.artist }}
    </div>
  </div>
</div>
    </div>
  </div>
  <!-- Modale Aggiunta Carte -->
  <div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="addCardModalLabel">Quante copie vuoi aggiungere?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div class="d-flex justify-content-center align-items-center">
          <button type="button" class="btn round-btn me-3" @click="decrementAdd">
            <i class="fas fa-minus"></i>
          </button>
          <input type="number"
                 class="form-control text-center"
                 v-model.number="addQuantity"
                 min="1"
                 style="width: 80px;" />
          <button type="button" class="btn round-btn ms-3" @click="incrementAdd">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-orange" @click="confirmAdd">Conferma</button>
      </div>
    </div>
  </div>
</div>

<!-- Modale Rimozione Carte -->
<div class="modal fade" id="removeCardModal" tabindex="-1" aria-labelledby="removeCardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="removeCardModalLabel">Quante copie vuoi rimuovere?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div class="d-flex justify-content-center align-items-center">
          <button type="button" class="btn round-btn me-3" @click="decrementRemove">
            <i class="fas fa-minus"></i>
          </button>
          <input type="number"
                 class="form-control text-center"
                 v-model.number="removeQuantity"
                 :max="copies"
                 min="1"
                 style="width: 80px;" />
          <button type="button" class="btn round-btn ms-3" @click="incrementRemove">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-orange" @click="confirmRemove">Conferma</button>
      </div>
    </div>
  </div>
  </div>
  <!-- Modale Carta Ingrandita -->
  <div class="modal fade" id="fullscreenModal"
     tabindex="-1" aria-hidden="true"
     :class="{ show: showFullscreen }"
     :style="{ display: showFullscreen ? 'block' : 'none', backgroundColor: 'rgba(0,0,0,0.8)' }"
     @click.self="closeFullscreen">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body d-flex justify-content-center align-items-center position-relative">
      <!-- Pulsante di chiusura -->
      <button class="btn-close position-absolute top-0 end-0 m-3" @click="closeFullscreen" aria-label="Chiudi"></button>

      <!-- Immagine ingrandita -->
      <img v-if="selectedCard" :src="selectedCard.img"
        alt="Carta ingrandita"
        class="img-fluid" style="max-height: 90vh;" />
      </div>
      </div>
    </div>
  </div>
</div>


  <?php include_once 'includes/footer.php'; ?>
</div>

<!-- Vue + script -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="assets/js/vue-dashboard.js"></script>