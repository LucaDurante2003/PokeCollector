// ------------ GESTIONE PASSWORD ------------ 
/* Gestisce l'occhio per nascondere e visualizzare la password inserita */
document.addEventListener("DOMContentLoaded", function () {
    const showPasswordElements = document.querySelectorAll(".show-password");
    showPasswordElements.forEach(function(showPassword) {
        const passwordField = document.querySelector(`#${showPassword.dataset.target}`);

        showPassword.addEventListener("click", function() {
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash", !this.classList.contains("fa-eye"));

            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
        });
    });
});

/* Controlla se 'password' e 'conferma password' sono uguali nella pagina di registrazione */
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const password = document.querySelector("#password");
    const confirmPassword = document.querySelector("#confirm_password");
    const errorMsg = document.querySelector("#password-error");

    function checkPasswordsMatch() {
        const match = password.value === confirmPassword.value;

        if (match || confirmPassword.value === "") {
            errorMsg.classList.add("d-none");
            password.classList.remove("is-invalid");
            confirmPassword.classList.remove("is-invalid");
            if (confirmPassword.value !== "") {
                password.classList.add("is-valid");
                confirmPassword.classList.add("is-valid");
            } else {
                password.classList.remove("is-valid");
                confirmPassword.classList.remove("is-valid");
            }
        } else {
            errorMsg.classList.remove("d-none");
            password.classList.remove("is-valid");
            confirmPassword.classList.remove("is-valid");
            password.classList.add("is-invalid");
            confirmPassword.classList.add("is-invalid");
        }

        return match;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const password = document.getElementById('password');
        if (password) {
            password.addEventListener("input", checkPasswordsMatch);
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        const confirmpassword = document.getElementById('confirm_password');
        if (confirmpassword) {
            confirmpassword.addEventListener("input", checkPasswordsMatch);
        }
    });

    if (form) {
    form.addEventListener("submit", function (e) {
        if (!checkPasswordsMatch()) {
            e.preventDefault();
        }
    });
}

});

/* Gestisce i controlli di password per la pagina delle impostazioni */
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const oldPassword = document.querySelector("#old_password");
    const newPassword = document.querySelector("#new_password");
    const confirmPassword = document.querySelector("#confirm_password");

    const errorOldPassword = document.querySelector("#old-password-error");
    const errorSameAsOld = document.querySelector("#same-as-old-error");
    const errorPasswordMatch = document.querySelector("#password-error");

    let oldPasswordIsValid = false;
    let currentRequestId = 0;

    if (form && newPassword && confirmPassword) {

        function checkPasswordsMatch() {
            const match = newPassword.value === confirmPassword.value && confirmPassword.value !== "";

            confirmPassword.classList.remove("is-valid", "is-invalid");
            errorPasswordMatch.classList.add("d-none");

            if (confirmPassword.value !== "") {
                if (match) {
                    confirmPassword.classList.add("is-valid");
                } else {
                    confirmPassword.classList.add("is-invalid");
                    errorPasswordMatch.classList.remove("d-none");
                }
            }

            return match;
        }

        function checkNewVsOld() {
            if (oldPassword && newPassword.value === oldPassword.value) {
                newPassword.classList.add("is-invalid");
                errorSameAsOld.classList.remove("d-none");
            } else {
                newPassword.classList.remove("is-invalid");
                errorSameAsOld.classList.add("d-none");
            }
        }

        function checkOldPassword() {
            if (!oldPassword) {
                oldPasswordIsValid = true; // Se non c'è il campo, consideriamo la validazione superata
                return;
            }

            const value = oldPassword.value.trim();
            if (value === "") {
                oldPassword.classList.remove("is-invalid");
                errorOldPassword.classList.add("d-none");
                oldPasswordIsValid = false;
                return;
            }

            const requestId = ++currentRequestId;

            fetch("php/check_old_pw_logic.php?ts=" + Date.now(), {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `old_password=${encodeURIComponent(value)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (requestId !== currentRequestId) return;

                    if (data.success) {
                        oldPassword.classList.add("is-valid");
                        oldPassword.classList.remove("is-invalid");
                        errorOldPassword.classList.add("d-none");
                        oldPasswordIsValid = true;
                    } else {
                        oldPassword.classList.remove("is-valid");
                        oldPassword.classList.add("is-invalid");
                        errorOldPassword.classList.remove("d-none");
                        oldPasswordIsValid = false;
                    }
                })
                .catch(error => {
                    console.error("Errore nella verifica della vecchia password:", error);
                });
        }

        // EVENTI

        if (oldPassword) {
            oldPassword.addEventListener("input", function () {
                oldPassword.classList.remove("is-invalid", "is-valid");
                errorOldPassword.classList.add("d-none");
                oldPasswordIsValid = false;
            });

            oldPassword.addEventListener("blur", checkOldPassword);
        }

        newPassword.addEventListener("input", checkNewVsOld);
        confirmPassword.addEventListener("input", checkPasswordsMatch);

        form.addEventListener("submit", function (e) {
            checkPasswordsMatch();
            checkNewVsOld();
            checkOldPassword();

            const newAndConfirmValid = !newPassword.classList.contains("is-invalid") &&
                                       !confirmPassword.classList.contains("is-invalid");

            if (!oldPassword || oldPasswordIsValid) {
                if (!newAndConfirmValid) {
                    e.preventDefault();
                }
            } else {
                e.preventDefault();
            }
        });
    }
});


// ------------ GESTIONE MODALE PER ELIMINAZIONE ACCOUNT ------------ 
window.addEventListener('DOMContentLoaded', function () {
    const accountDeletedModal = document.getElementById('accountDeletedModal');

    if (accountDeletedModal) {
        const myModal = new bootstrap.Modal(accountDeletedModal);
        myModal.show();

        setTimeout(function () {
            window.location.href = 'index.php';
        }, 3000);
    }
});


// ------------ GESTIONE ICONETTA IMPOSTAZIONI ------------ 
document.addEventListener("DOMContentLoaded", function () {
    const settingsBtn = document.getElementById("settingsBtn");
    const settingsMenu = document.getElementById("settingsMenu");

    if(settingsBtn && settingsMenu) {  
        // Chiudi il menu se clicchi fuori
        document.addEventListener("click", function (e) {
            if (!settingsBtn.contains(e.target) && !settingsMenu.contains(e.target)) {
                settingsMenu.style.display = "none";
            }
        });
    }

    if (settingsBtn && settingsMenu) {
        settingsBtn.addEventListener("click", function (e) {
            e.stopPropagation(); // Impedisce il bubbling
            const visible = settingsMenu.style.display === "block";
            settingsMenu.style.display = visible ? "none" : "block";
        });
    }
});

// ------------ GESTIONE FRECCETTA PER TORNARE SU ------------ 
document.addEventListener("DOMContentLoaded", function () {
    const scrollTopBtn = document.getElementById("scrollTopBtn");

    if (scrollTopBtn) {
        window.onscroll = function () {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                scrollTopBtn.style.display = "flex";
            } else {
                scrollTopBtn.style.display = "none";
            }
        };

        scrollTopBtn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});


// ------------ GESTIONE RICERCA NELLA HOMEPAGE ------------ 
document.addEventListener("DOMContentLoaded", function (){ //aspetta che il DOM sia completamente caricato prima di eseguire la funzione
    const searchToggle = document.getElementById("searchToggle"); //icona lente d'ingrandimento
    const searchInput = document.querySelector(".search-input"); //campo input della barra di ricerca
    const clearButton = document.getElementById("clearSearch"); //bottone X per cancellare l'input
    const cards = document.querySelectorAll(".card"); //celle che contengono le espansioni

    let searchActive = false;
    let firstMatchCard = null; //memorizza la prima cella ch ha un'espansione che matcha la ricerca
    
    if(searchToggle){
        searchToggle.addEventListener("click", function (){
            //Se la ricerca è attiva ma la barra di ricerca è vuota (quindi ho cliccato sulla barra di ricerca ma non ho scritto nulla)
            if (searchInput.classList.contains("active") && searchInput.value.trim() === "") {
                searchInput.classList.remove("active");
                searchActive = false;
                clearHighlights();
                window.scrollTo(0, 0); //riporta la pagina in alto
            }
            else{
                searchInput.classList.add("active");
                searchInput.focus(); //ottiene il focus per iniziare a scrivere
                searchActive = true;
            }
        });
    }

    if(searchInput){
        //Quando l'utente scrive qualcosa...
        searchInput.addEventListener("input", function (){
            const searchValue = searchInput.value.toLowerCase(); //convertito tutto in minuscolo in modo che la ricerca sia case insensitive
            firstMatchCard = null;
            if(clearButton){
                //fa vedere il bottone della X solo quando inizi a scrivere qualcosa
                if (searchValue !== ""){
                    clearButton.style.display = "inline";
                } 
                else{
                    clearButton.style.display = "none";
                }
            }
            //Itera su ogni cella
            cards.forEach(card => {
                const expansionButtons = card.querySelectorAll(".expansion-buttons a"); //prende tutti i bottoni delle espansioni di quella cella
                let hasMatch = false;
                //itera sui bottoni della cella
                expansionButtons.forEach(btn => {
                    const text = btn.textContent.toLowerCase(); //prende il testo scritto sul bottone e lo converte in minuscolo sempre per rendere la ricerca case insensitive
                    if (text.includes(searchValue)) {
                        hasMatch = true;
                    }
                });

                if (hasMatch){
                    card.classList.add("highlight");
                    if (!firstMatchCard) firstMatchCard = card; //se è il primo match che trova, lo memorizza
                } 
                else{
                    card.classList.remove("highlight");
                }
            });

            if (searchValue === ""){
                clearHighlights(); //toglie tutti gli highlight
                window.scrollTo({ top: 0, behavior: "smooth" }); //torna in cima alla pagina in modo smooth
            } 
            else if (firstMatchCard){
                //se c’è almeno una cella che matcha, scrolla fino alla prima trovata.
                firstMatchCard.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    }
    
    if(clearButton){
        //Quando clicchi sulla x
        clearButton.addEventListener("click", function (){
            searchInput.value = ""; //si svuota il campo
            searchInput.dispatchEvent(new Event("input")); /*manda manualmente l'evento input in modo che il codice si accorga che l'input
            è cambiato e che quindi deve fare certe cose a livello visivo (es togliere la X e gli highlight)*/
            clearButton.style.display = "none"; //nasconde la x
            searchInput.focus(); //riporta il focus sull’input
        });
    }
    if(searchInput){
        //se premi invio (anche mentre scrive)
        searchInput.addEventListener("keydown", function (event){
            if (event.key === "Enter" && firstMatchCard) {
                //va alla prima cella che ha un match
                firstMatchCard.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    }
    if(searchInput){
        //quando scrolli, se c'è del testo la barra di ricerca rimane aperta
        window.addEventListener("scroll", function (){
            if (searchInput.value.trim() !== ""){
                searchInput.classList.add("active");
            }
        });
    }
    //Rimuove tutti gli highlight delle celle ( = "pulisce" i risulati)
    function clearHighlights() {
        cards.forEach(card => {
            card.classList.remove("highlight");
        });
    }
});