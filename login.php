<?php
include_once 'includes/header.php';
?>

<body class="d-flex flex-column min-vh-100">
<div class="flex-column main-content">
    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img mb-4">
    <h2 class="text-orange-title mb-3">Accedi al tuo account</h2>
    <form action="process_login.php" method="POST" class="w-100" style="max-width: 400px;">
        <div class="mb-3">
            <label for="email" class="form-label text-orange">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Inserisci la tua email">
        </div>
        <div class="mb-4">
            <label for="password" class="form-label text-orange">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required placeholder="Inserisci la password">
                <span class="input-group-text">
                    <i class="fa-solid fa-eye show-password" data-target="password"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-orange w-100">Accedi</button>
    </form>
    <p class="mt-3 text-white">Non hai un account? <a href="register.php" class="text-orange text-decoration-none fw-bold">Registrati</a></p>
</div>

<?php
include_once 'includes/footer.php';
?>