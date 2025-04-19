<?php
    session_start();
    include_once 'includes/header.php';
?>

<body class="d-flex flex-column min-vh-100">
    <div class="flex-column main-content">
        <a href="homepage.php">
            <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img" style="height: 300px;">
        </a>

        <h2 class="text-orange-title mb-3">Gestione Account</h2>

        <!--Form per cambiare la password-->
        <form action="php/change_pw_logic.php" method="POST" class="w-100 mb-4" style="max-width: 400px;">
            <div class="mb-3">
                <label for="old_password" class="form-label text-orange">Vecchia Password</label>
                <small id="old-password-error" class="text-danger d-none">La password attuale non è corretta.</small>
                <div class="input-group">
                    <input type="password" class="form-control" id="old_password" name="old_password" required placeholder="Inserisci la vecchia password">
                    <span class="input-group-text">
                        <i class="fa-solid fa-eye show-password" data-target="old_password"></i>
                    </span>	
                </div>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label text-orange">Nuova Password</label>
                <small id="same-as-old-error" class="text-danger d-none">La nuova password deve essere diversa da quella vecchia.</small>
                <div class="input-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Crea una nuova password">
                    <span class="input-group-text">
                        <i class="fa-solid fa-eye show-password" data-target="new_password"></i>
                    </span>	
                </div>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label text-orange">Conferma Nuova Password</label>
                <small id="password-error" class="text-danger d-none">Le password non coincidono.</small>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Ripeti la nuova password">
                    <span class="input-group-text">
                        <i class="fa-solid fa-eye show-password" data-target="confirm_password"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-orange w-100">Conferma Modifiche</button>
        </form>

        <!--Bottone per uscire dall'account -> apre una finestra modale; messo dentro
         un form in modo che abbia la stessa lunghezza degli altri e mantenga la distanza come gli altri-->
        <form action="" method="POST" class="w-100 mb-4" style="max-width: 400px;">
            <div class="mb-4 w-100">
                <button type="button" class="btn btn-orange w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>
        </form>

        <!-- Finestra modale per chiedere conferma del logout -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Conferma Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">Vuoi davvero uscire dal tuo account?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Annulla</button>
                        <form action="php/logout_logic.php" method="POST" style="margin: 0;">
                            <button type="submit" class="btn btn-orange">Conferma Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--Bottone per eliminare l'account -> apre una finestra modale; messo dentro
         un form in modo che abbia la stessa lunghezza degli altri e mantenga la distanza come gli altri-->
        <form action="" method="POST" class="w-100 mb-4" style="max-width: 400px;">
            <div class="mb-4 w-100">
                <button type="button" class="btn delete-account-btn w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Elimina Account</button>
            </div>
        </form>

        <!--Finestra modale per chiedere conferma dell'eliminazione dell'account-->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAccountModalLabel">Conferma Eliminazione</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        Sei sicuro di voler eliminare il tuo account? Questa azione è <strong>irreversibile</strong>.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Annulla</button>
                        <form action="php/delete_account_logic.php" method="POST" style="margin: 0;">
                            <button type="submit" class="btn delete-account-btn">Conferma</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Se l'eliminazione dell'account ha avuto successo si apre una finestra modale di conferma e si viene
    reinderizzati alla pagina iniziale di benvenuto. Ho scritto così l'if perchè non mi permetteva di mischiare php e
    HTML all'interno dell'if-->
    <?php if (isset($_SESSION['account_deleted'])): ?>
        <!-- Modale per confermare eliminazione -->
        <div class="modal fade" id="accountDeletedModal" tabindex="-1" aria-labelledby="accountDeletedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accountDeletedModalLabel">Account Eliminato</h5>
                    </div>
                    <div class="modal-body">
                        Il tuo account è stato eliminato con successo. Verrai reindirizzato alla pagina di benvenuto.
                    </div>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['account_deleted']); ?>
    <?php endif; ?>

<?php
    include_once 'includes/footer.php';
?>
