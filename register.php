<?php
session_start();
include_once 'includes/header.php';
?>

<body class="d-flex flex-column min-vh-100">
<div class="flex-column main-content">
    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img mb-4">

    <h2 class="text-orange-title mb-3">Crea un nuovo account</h2>

    <?php
    if (isset($_SESSION['error_register'])) {
        echo '<div class="error-message">' . $_SESSION['error_register'] . '</div>';
        unset($_SESSION['error_register']);
    }
    ?>

    <form action="php/register_logic.php" method="POST" class="w-100" style="max-width: 400px;">
        <div class="mb-3">
            <label for="username" class="form-label text-orange">Username</label>
            <input type="text" class="form-control" id="username" name="username" required placeholder="Scegli un username">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label text-orange">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Inserisci la tua email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-orange">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required placeholder="Crea una password">
                <span class="input-group-text">
                    <i class="fa-solid fa-eye show-password" data-target="password"></i>
                </span>	
    	    </div>
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="form-label text-orange">Conferma Password</label>
            <small id="password-error" class="text-danger d-none">Le password non coincidono.</small>
            <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Ripeti la password">
                <span class="input-group-text">
                    <i class="fa-solid fa-eye show-password" data-target="confirm_password"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-orange w-100">Registrati</button>
    </form>

    <p class="mt-3 text-white">Hai già un account? <a href="login.php" class="text-orange text-decoration-none fw-bold">Accedi</a></p>
</div>

<?php
include_once 'includes/footer.php';
?>