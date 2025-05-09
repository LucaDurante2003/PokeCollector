<?php
    session_start();
    include_once 'includes/header.php';
    include_once 'includes/config.php';

    //Se non è loggato ma ha il cookie remember_token
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn->connect_error) {
            $stmt = $conn->prepare("SELECT id, nome FROM utenti WHERE remember_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'];

                // Login automatico riuscito, reindirizzo
                header('Location: homepage.php');
                exit;
            }

            $stmt->close();
            $conn->close();
        }
    }
?>

<body class="d-flex flex-column min-vh-100">
<div class="flex-column main-content">
    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img mb-4">
    <h2 class="text-orange-title mb-3">Accedi al tuo account</h2>
    <?php
        if (isset($_SESSION['success_register'])) {
            echo '<div class="success-message">' . $_SESSION['success_register'] . '</div>';
            unset($_SESSION['success_register']);
        }
        if (isset($_SESSION['error_login'])) {
            echo '<div class="error-message">' . $_SESSION['error_login'] . '</div>';
            unset($_SESSION['error_login']);
        }
        if (isset($_SESSION['success_pw_change'])) {
            echo '<div class="success-message">' .  $_SESSION['success_pw_change'] . '</div>';
            unset( $_SESSION['success_pw_change']);
        }
    ?>
    <!--px-3 = aggiungo un padding laterale sia a dx sia a sx di 3 dagli schermi molto piccoli in poi
    px-sm-0 = dagli schermi piccoli in poi, metto un padding laterale di 0 ( = tolgo il padding)
    Usati in combinazione, fanno in modo che SOLO sugli schermi molto piccoli (mobile) ci sia un padding laterale di 3, in modo che
    i rettangoli dei vari input e i bottoni non occupino tutta la larghezza della pagina (su desktop quindi non c'è questo padding e lo vedi normale)--> 
    <form action="php/login_logic.php" method="POST" class="px-3 px-sm-0 w-100" style="max-width: 400px;">
        <div class="mb-3">
            <label for="email" class="form-label text-orange">Email</label>
            <input type="email" class="form-control" id="email" name="email" autocomplete="on" required placeholder="Inserisci la tua email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-orange">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required placeholder="Inserisci la password">
                <span class="input-group-text">
                    <i class="fa-solid fa-eye show-password" data-target="password"></i>
                </span>
            </div>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center w-100" style="max-width: 400px">
            <div class="form-check m-0">
                <input class="form-check-input" type="checkbox" id="checkbox" name="checkbox">
                <label for="checkbox" class="form-label text-orange">Mantieni l'accesso</label>
            </div>
            <a href="impostazioni.php?reset_pw=1" class="text-orange text-decoration-none fw-bold small" style="position: relative; top: -2px;">Hai dimenticato la password?</a>
        </div>
        <button type="submit" class="btn btn-orange w-100">Accedi</button>
    </form>
    <p class="mt-3 text-white">Non hai un account? <a href="register.php" class="text-orange text-decoration-none fw-bold">Registrati</a></p>
</div>

<?php
    include_once 'includes/footer.php';
?>