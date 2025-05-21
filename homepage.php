<?php
    // Avvia la sessione
    session_start();
    include_once 'includes/header.php';

    // Carica e decodifica il file JSON con la lista delle espansioni
    $mappingFile = 'json/espansioni.json';
    $expansions = json_decode(file_get_contents($mappingFile), true); // Legge il contenuto del file JSON e lo converte in array associativo PHP

    // Mappa i loghi per ogni serie
    $serieImages = [
        'base' => 'assets/img/serie_base.png',
        'neo' => 'assets/img/neo_genesis.png',
        'ecard' => 'assets/img/e_series.png',
        'ex' => 'assets/img/ex_rubino_zaffiro.png',
        'dp' => 'assets/img/diamante_perla.png',
        'pop' => 'assets/img/pop.png',
        'pl' => 'assets/img/platino.png',
        'hgss' => 'assets/img/hg_ss.png',
        'col' => 'assets/img/richiamo_leggende.png',
        'bw' => 'assets/img/nero_bianco.png',
        'xy' => 'assets/img/xy.png',
        'sm' => 'assets/img/sole_luna.png',
        'swsh' => 'assets/img/spada_scudo.png',
        'sv' => 'assets/img/scarlatto_violetto.png'
    ];

    // Nomi delle serie (usati per il titolo della card)
    $serieNames = [
        'base' => 'Serie Base',
        'neo' => 'Serie Neo',
        'ecard' => 'Serie e-Card',
        'ex' => 'Serie EX Rubino e Zaffiro',
        'dp' => 'Serie Diamante e Perla',
        'pop' => 'Serie POP',
        'pl' => 'Serie Platino',
        'hgss' => 'Serie HeartGold & SoulSilver',
        'col' => 'Serie Richiamo delle Leggende',
        'bw' => 'Serie Nero e Bianco',
        'xy' => 'Serie XY',
        'sm' => 'Serie Sole e Luna',
        'swsh' => 'Serie Spada e Scudo',
        'sv' => 'Serie Scarlatto e Violetto'
    ];

    // Mappa delle eccezioni: alcune espansioni hanno ID speciali da mappare dentro la serie a cui appartengono
    $exceptions = [
        'dv1' => 'bw', //Tesoro dei draghi
        'g1' => 'xy', //Generazioni
        'det1' => 'sm', //Detective Pikachu
        'cel25' => 'swsh', //Gran Festa
        'pgo' => 'swsh', //Pokémon Go
        'swsh12pt5' => 'swsh', //Zenit Regale
        'sv6pt5' => 'sv', //Segreto Fiabesco
        'sv8pt5' => 'sv' //Evoluzioni Prismatiche
    ];

    // Riorganizza le espansioni per serie in base al prefisso dell'ID
    $serieMap = [];

    foreach ($expansions as $slug => $data) {
        if (!isset($data['id'])) continue;

        // Verifica se l'ID dell'espansione è un'eccezione
        $serieKey = null;
        if (isset($exceptions[$data['id']])) {
            // Se l'espansione è nelle eccezioni, usa la serie definita lì
            $serieKey = $exceptions[$data['id']];
        } else {
            // Se non è un'eccezione, prende il prefisso dall'ID
            preg_match('/^[a-z]+/', $data['id'], $match);
            $serieKey = $match[0] ?? 'unknown';
        }

        // Aggiunge la serie e le espansioni se non esistono ancora
        if (!isset($serieMap[$serieKey])) {
            $serieMap[$serieKey] = [
                'name' => $serieNames[$serieKey] ?? ucfirst($serieKey),
                'image' => $serieImages[$serieKey] ?? '',
                'expansions' => []
            ];
        }

        // Aggiunge l'espansione alla serie corrispondente
        $serieMap[$serieKey]['expansions'][] = [
            'slug' => $slug,
            'name' => $data['name']
        ];
    }
    // Converte la mappa in array indicizzato per Json
    $serieArray = array_values($serieMap);
?>

<script>
  window.APP_CONFIG = {
    series: <?= json_encode($serieArray) ?>
  };
</script>

<!--Bottone impostazioni e menù a tendina-->
<div class="settings-wrapper">
    <button id="settingsBtn" class="round-btn">
        <i class="fas fa-cog"></i>
    </button>
    <div id="settingsMenu" class="dropdown-menu">
        <a href="impostazioni.php" class="dropdown-item">Gestione Account</a>
    </div>
</div>

<!--Barra di ricerca-->
<div class="search-wrapper">
    <div class="search-container">
        <button id="searchToggle" class="round-btn">
            <i class="fas fa-search"></i>
        </button>
        <input type="text" id="search" class="search-input" placeholder="Cerca espansione...">
        <button id="clearSearch" class="clear-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<body class="d-flex flex-column min-vh-100">
    <div class="main-content mt-4">
        <div class="row align-items-center justify-content-center mb-4">
            <div class="col-12 col-md-auto mb-3 mb-md-0">
                <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img" style="height: 300px;">
            </div>
            <div class="col text-center text-md-center">
                <?php $username = $_SESSION['user_name']; ?>
                <h1 class="main-title display-6"><?php echo "Ciao $username! Seleziona un'espansione"; ?></h1>
            </div>
        </div>
    </div>

    <div id="app" class="container mb-5">
        <div class="row g-4 justify-content-center">
            <!-- Ciclo Vue che genera una card per ogni serie -->
            <div class="col-12 col-md-6 col-lg-4" v-for="serie in series" :key="serie.name">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img :src="serie.image" :alt="serie.name" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">{{ serie.name }}</h5>
                        </div>
                        <div class="expansion-buttons">
                            <!-- Ciclo Vue che genera un bottone per ogni espansione della serie corrente -->
                            <a class="btn btn-orange"
                            v-for="exp in serie.expansions"
                            :key="exp.slug"
                            :href="`dashboard.php?slug=${exp.slug}`">
                            {{ exp.name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/vue@3"></script>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    series: window.APP_CONFIG.series
                };
            }
        });

        app.mount('#app');
    </script>

    <!-- Freccetta per tornare su-->
    <button id="scrollTopBtn" class="round-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

<?php
    include_once 'includes/footer.php';
?>