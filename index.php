<?php
    include_once 'includes/header.php';
    include_once 'php/init_db.php';
?>

<body class="d-flex flex-column min-vh-100">
<div class="flex-column main-content text-center">
    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img mb-4">
    <h1 class="main-title">Benvenuto su PokéCollector</h1>
    <p class="lead mb-4">Organizza e tieni traccia della tua collezione di carte Pokémon con facilità.</p>
    <div>
        <a href="login.php" class="btn btn-orange m-2">Accedi</a>
        <a href="register.php" class="btn btn-orange m-2">Registrati</a>
    </div>
</div>

<?php
    include_once 'includes/footer.php';
?>