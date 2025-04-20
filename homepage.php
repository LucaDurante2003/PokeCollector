<?php
    session_start();
    include_once 'includes/header.php';
?>

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
        <input type="text" class="search-input" placeholder="Cerca espansione...">
        <button id="clearSearch" class="clear-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<body class="d-flex flex-column min-vh-100">
    <div class="main-content mt-4">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md-auto mb-3 mb-md-0">
                <a href="#top">
                    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img" style="height: 300px;">
                </a>
            </div>
            <div class="col text-center text-md-start">
                <?php $username = $_SESSION['user_name']; ?>
                <h1 class="main-title display-6"><?php echo "Ciao $username! Seleziona un'espansione"; ?></h1>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row g-4 justify-content-center">
            <!-- Serie Base -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/serie_base.png" alt="serie_base" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Base</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Set base</a>
                            <a href="dashboard.php" class="btn btn-orange">Jungle</a>
                            <a href="dashboard.php" class="btn btn-orange">Fossil</a>
                            <a href="dashboard.php" class="btn btn-orange">Team Rocket</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie Neo Genesis -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/neo_genesis.png" alt="serie_neo_genesis" style="height: 55px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Neo</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Neo Discovery</a>
                            <a href="dashboard.php" class="btn btn-orange">Neo Revelation</a>
                            <a href="dashboard.php" class="btn btn-orange">Neo Destiny</a>
                            <a href="dashboard.php" class="btn btn-orange">Neo Genesis</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie e-card-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/e_series.png" alt="serie_e_card" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie e-Card</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Expedition Set Base</a>
                            <a href="dashboard.php" class="btn btn-orange">Aquapolis</a>
                            <a href="dashboard.php" class="btn btn-orange">Skyridge</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie EX Rubino e Zaffiro-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/ex_rubino_zaffiro.png" alt="ex_rubino_zaffiro" style="height: 30px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie EX Rubino e Zaffiro</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">EX Rubino e Zaffiro</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Tempesta di sabbia</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Drago</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Team Magma vs Team Idro</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Leggende Nascoste</a>
                            <a href="dashboard.php" class="btn btn-orange">EX RossoFuoco e VerdeFoglia</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Deoxys</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Smeraldo</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Forze Segrete</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Specie Delta</a>
                            <a href="dashboard.php" class="btn btn-orange">EX La Leggenda di Mew</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Fantasmi di Holon</a>
                            <a href="dashboard.php" class="btn btn-orange">EX Guardiani dei Cristalli</a>
                            <a href="dashboard.php" class="btn btn-orange">EX L'isola dei Draghi</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie Diamante e Perla-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/diamante_perla.png" alt="serie_diamante_perla" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Diamante e Perla</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Diamante e Perla</a>
                            <a href="dashboard.php" class="btn btn-orange">Tesori Misteriosi</a>
                            <a href="dashboard.php" class="btn btn-orange">Prodigi Segreti</a>
                            <a href="dashboard.php" class="btn btn-orange">Incontri leggendari</a>
                            <a href="dashboard.php" class="btn btn-orange">Alba Suprema</a>
                            <a href="dashboard.php" class="btn btn-orange">Il Risveglio dei Miti</a>
                            <a href="dashboard.php" class="btn btn-orange">Fronte di tempesta</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie POP-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/pop.png" alt="serie_pop" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie POP</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Serie 1</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 2</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 3</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 4</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 5</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 6</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 7</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 8</a>
                            <a href="dashboard.php" class="btn btn-orange">Serie 9</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie Platino-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/platino.png" alt="serie_platino" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Platino</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Platino</a>
                            <a href="dashboard.php" class="btn btn-orange">L'ascesa dei Rivali</a>
                            <a href="dashboard.php" class="btn btn-orange">Arceus</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Serie HeartGold and SoulSilver-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/hg_ss.png" alt="serie_hg_ss" style="height: 50px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie HeartGold & SoulSilver</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">HeartGold & SoulSilver</a>
                            <a href="dashboard.php" class="btn btn-orange">Forze scatenate</a>
                            <a href="dashboard.php" class="btn btn-orange">Senza Paura</a>
                            <a href="dashboard.php" class="btn btn-orange">Battaglie trionfali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Serie Richiamo delle Leggende-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/richiamo_leggende.png" alt="serie_richiamo_leggende" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Richiamo delle Leggende</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Richiamo delle Leggende</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Serie Nero e Bianco-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/nero_bianco.png" alt="serie_bianco_nero" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Nero e Bianco</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Nero e Bianco</a>
                            <a href="dashboard.php" class="btn btn-orange">Nuove Forze</a>
                            <a href="dashboard.php" class="btn btn-orange">Vittorie Regali</a>
                            <a href="dashboard.php" class="btn btn-orange">Destini Futuri</a>
                            <a href="dashboard.php" class="btn btn-orange">Esploratori delle Tenebre</a>
                            <a href="dashboard.php" class="btn btn-orange">Stirpe dei Draghi</a>
                            <a href="dashboard.php" class="btn btn-orange">Tesoro dei Draghi</a>
                            <a href="dashboard.php" class="btn btn-orange">Confini Varcati</a>
                            <a href="dashboard.php" class="btn btn-orange">Uragano Plasma</a>
                            <a href="dashboard.php" class="btn btn-orange">Glaciazione Plasma</a>
                            <a href="dashboard.php" class="btn btn-orange">Esplosione Plasma</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Serie XY-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/xy.png" alt="serie_xy" style="height: 60px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie XY</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">XY</a>
                            <a href="dashboard.php" class="btn btn-orange">XY - Benvenuti a Kalos</a>
                            <a href="dashboard.php" class="btn btn-orange">Fuoco Infernale</a>
                            <a href="dashboard.php" class="btn btn-orange">Colpi Furiosi</a>
                            <a href="dashboard.php" class="btn btn-orange">Forze Spettrali</a>
                            <a href="dashboard.php" class="btn btn-orange">Scontro Primordiale</a>
                            <a href="dashboard.php" class="btn btn-orange">Furie Volanti</a>
                            <a href="dashboard.php" class="btn btn-orange">Antiche origini</a>
                            <a href="dashboard.php" class="btn btn-orange">Turboblitz</a>
                            <a href="dashboard.php" class="btn btn-orange">Turbocrash</a>
                            <a href="dashboard.php" class="btn btn-orange">Generazioni</a>
                            <a href="dashboard.php" class="btn btn-orange">Destini Incrociati</a>
                            <a href="dashboard.php" class="btn btn-orange">Vapori Accesi</a>
                            <a href="dashboard.php" class="btn btn-orange">Evoluzioni</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serie Sole e Luna-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/sole_luna.png" alt="serie_sole_luna" style="height: 80px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Sole e Luna</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Sole e Luna</a>
                            <a href="dashboard.php" class="btn btn-orange">Guardiani Nascenti</a>
                            <a href="dashboard.php" class="btn btn-orange">Ombre Infuocate</a>
                            <a href="dashboard.php" class="btn btn-orange">Leggende Iridescenti</a>
                            <a href="dashboard.php" class="btn btn-orange">Invasione Scarlatta</a>
                            <a href="dashboard.php" class="btn btn-orange">Ultraprisma</a>
                            <a href="dashboard.php" class="btn btn-orange">Apocalisse di Luce</a>
                            <a href="dashboard.php" class="btn btn-orange">Tempesta Astrale</a>
                            <a href="dashboard.php" class="btn btn-orange">Trionfo dei Draghi</a>
                            <a href="dashboard.php" class="btn btn-orange">Tuoni Perduti</a>
                            <a href="dashboard.php" class="btn btn-orange">Gioco di Squadra</a>
                            <a href="dashboard.php" class="btn btn-orange">Detective Pikachu</a>
                            <a href="dashboard.php" class="btn btn-orange">Legami Inossidabili</a>
                            <a href="dashboard.php" class="btn btn-orange">Sintonia Mentale</a>
                            <a href="dashboard.php" class="btn btn-orange">Destino Sfuggente</a>
                            <a href="dashboard.php" class="btn btn-orange">Eclissi Cosmica</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Serie Spada e Scudo-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/spada_scudo.png" alt="serie_spda_scudo" style="height: 90px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Spada e Scudo</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Spada e Scudo</a>
                            <a href="dashboard.php" class="btn btn-orange">Fragore Ribelle</a>
                            <a href="dashboard.php" class="btn btn-orange">Fiamme Oscure</a>
                            <a href="dashboard.php" class="btn btn-orange">Futuri Campioni</a>
                            <a href="dashboard.php" class="btn btn-orange">Voltaggio Sfolgorante</a>
                            <a href="dashboard.php" class="btn btn-orange">Destino Splendente</a>
                            <a href="dashboard.php" class="btn btn-orange">Stili di Lotta</a>
                            <a href="dashboard.php" class="btn btn-orange">Regno Glaciale</a>
                            <a href="dashboard.php" class="btn btn-orange">Evoluzioni Eteree</a>
                            <a href="dashboard.php" class="btn btn-orange">Gran Festa</a>
                            <a href="dashboard.php" class="btn btn-orange">Colpo Fusione</a>
                            <a href="dashboard.php" class="btn btn-orange">Astri Lucenti</a>
                            <a href="dashboard.php" class="btn btn-orange">Lucentezza Siderale</a>
                            <a href="dashboard.php" class="btn btn-orange">Pokémon Go</a>
                            <a href="dashboard.php" class="btn btn-orange">Origine Perduta</a>
                            <a href="dashboard.php" class="btn btn-orange">Tempesta Argentata</a>
                            <a href="dashboard.php" class="btn btn-orange">Zenit Regale</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Serie Scarlatto e Violetto-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="card-header d-flex align-items-center mb-3">
                            <img src="assets/img/scarlatto_violetto.png" alt="serie_scarlatto_violetto" style="height: 35px;" class="me-3 rounded">
                            <h5 class="text-orange-title-homepage m-0">Serie Scarlatto e Violetto</h5>
                        </div>
                        <div class="expansion-buttons">
                            <a href="dashboard.php" class="btn btn-orange">Scarlatto e Violetto</a>
                            <a href="dashboard.php" class="btn btn-orange">Evoluzioni a Paldea</a>
                            <a href="dashboard.php" class="btn btn-orange">Ossidiana Infuocata</a>
                            <a href="dashboard.php" class="btn btn-orange">151</a>
                            <a href="dashboard.php" class="btn btn-orange">Paradosso Temporale</a>
                            <a href="dashboard.php" class="btn btn-orange">Destino di Paldea</a>
                            <a href="dashboard.php" class="btn btn-orange">Cronoforze</a>
                            <a href="dashboard.php" class="btn btn-orange">Crepuscolo Mascherato</a>
                            <a href="dashboard.php" class="btn btn-orange">Segreto Fiabesco</a>
                            <a href="dashboard.php" class="btn btn-orange">Corona Astrale</a>
                            <a href="dashboard.php" class="btn btn-orange">Scintille Folgoranti</a>
                            <a href="dashboard.php" class="btn btn-orange">Evoluzioni Prismatiche</a>
                            <a href="dashboard.php" class="btn btn-orange">Avventure Insieme</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<!-- Freccetta per tornare su-->
<button id="scrollTopBtn" class="round-btn">
  <i class="fas fa-arrow-up"></i>
</button>

<?php
    include_once 'includes/footer.php';
?>