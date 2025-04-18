/*Gestisce l'occhio per nascondere e visualizzare la password inserita*/
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

/*Controlla se 'password' e 'conferma password' sono uguali*/
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

    password.addEventListener("input", checkPasswordsMatch);
    confirmPassword.addEventListener("input", checkPasswordsMatch);

    form.addEventListener("submit", function (e) {
        if (!checkPasswordsMatch()) {
            e.preventDefault();
        }
    });
});

/*Gestione del bottone delle impostazioni e del menù a tendina*/
const settingsBtn = document.getElementById("settingsBtn");
const settingsMenu = document.getElementById("settingsMenu");
//Quando clicchi sul bottone delle impostazioni, mostra o nascondi il menù
settingsBtn.addEventListener("click", function() { 
    settingsMenu.style.display = (settingsMenu.style.display === "block") ? "none" : "block";
});

/*Gestione freccetta per tornare su*/
const scrollTopBtn = document.getElementById("scrollTopBtn");
window.onscroll = function () {
    //Mostra il bottone scrollTop quando scorre giù
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
        scrollTopBtn.style.display = "flex";
    } 
    else{
        scrollTopBtn.style.display = "none";
    }
};
//Se ci clicchi sopra torna su
scrollTopBtn.addEventListener("click", function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
