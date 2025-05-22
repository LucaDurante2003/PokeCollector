<?php
// Avvia la sessione per mostrare messaggi e salvare dati temporanei
session_start();
include_once 'includes/header.php';
?>

<body class="d-flex flex-column min-vh-100">
    <div class="flex-column main-content">
        <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img mb-4">

        <h2 class="text-orange-title mb-3 text-center">Crea un nuovo account</h2>

        <?php
            // Se c'è un messaggio di errore nella registrazione, viene mostrato
            if (isset($_SESSION['error_register'])) {
                echo '<div class="error-message">' . $_SESSION['error_register'] . '</div>';
                unset($_SESSION['error_register']);
            }
        ?>
        <!-- Form di registrazione -->
        <form action="php/register_logic.php" method="POST" class="px-3 px-sm-0 w-100" style="max-width: 400px;">
            <!-- Campo username -->
            <div class="mb-3">
                <label for="username" class="form-label text-orange">Username</label>
                <input type="text" class="form-control" id="username" name="username" autocomplete="on" required placeholder="Scegli un username">
            </div>
            <!-- Campo email -->
            <div class="mb-3">
                <label for="email" class="form-label text-orange">Email</label>
                <input type="email" class="form-control" id="email" name="email" autocomplete="on" required placeholder="Inserisci la tua email">
            </div>
            <!-- Campo password -->
            <div class="mb-3">
                <label for="password" class="form-label text-orange">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Crea una password">
                    <span class="input-group-text">
                        <!-- Icona occhio per mostrare/nascondere la password (gestita via JS) -->
                        <i class="fa-solid fa-eye show-password" data-target="password"></i>
                    </span>	
                </div>
            </div>
            <!-- Campo conferma password -->
            <div class="mb-4">
                <label for="confirm_password" class="form-label text-orange">Conferma Password</label>
                <!-- Messaggio d'errore mostrato in caso di mismatch, da gestire via JS -->
                <small id="password-error" class="text-danger d-none">Le password non coincidono.</small>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Ripeti la password">
                    <span class="input-group-text">
                        <i class="fa-solid fa-eye show-password" data-target="confirm_password"></i>
                    </span>
                </div>
            </div>
            <!-- Pulsante per inviare il form -->
            <button type="submit" class="btn btn-orange w-100">Registrati</button>
        </form>
        <!-- Link alla pagina di login per utenti già registrati -->
        <p class="mt-3 text-white">Hai già un account? <a href="login.php" class="text-orange text-decoration-none fw-bold">Accedi</a></p>
    </div>

<?php
include_once 'includes/footer.php';
?>