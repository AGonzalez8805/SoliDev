export class Login {
  constructor() {
    console.log("Login initialisé");

    this.form = document.getElementById("loginForm");
    if (!this.form) return;

    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.rememberMe = document.getElementById("rememberMe");

    this.init();
  }

  init() {
    this.inputMail.addEventListener("input", () => this.toggleValidation(this.inputMail, this.validateEmail));
    this.inputPassword.addEventListener("input", () => this.toggleValidation(this.inputPassword, this.validatePassword));

    this.form.addEventListener("submit", (e) => {
      e.preventDefault();
      this.handleLogin();
    });
  }

  toggleValidation(field, validator) {
    const isValid = validator(field.value);
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  validateEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  validatePassword(value) {
    return value.trim().length > 0;
  }

  async handleLogin() {
    const isMailValid = this.toggleValidation(this.inputMail, this.validateEmail);
    const isPasswordValid = this.toggleValidation(this.inputPassword, this.validatePassword);

    if (!isMailValid || !isPasswordValid) {
      alert("Merci de corriger les champs avant de vous connecter.");
      return;
    }

    const data = {
      email: this.inputMail.value,
      password: this.inputPassword.value,
      remember: this.rememberMe?.checked || false,
    };

    try {
      const response = await fetch("index.php?controller=auth&action=handleLogin", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const text = await response.text();

      let result;
      try {
        result = JSON.parse(text);
      } catch (err) {
        console.error("Réponse du serveur invalide :", text);
        alert("Erreur serveur : réponse non valide.");
        return;
      }

      if (result.success) {
        window.location.href = result.redirect || "/";
      } else {
        alert(result.message || "Email ou mot de passe incorrect.");
      }
    } catch (err) {
      console.error("Erreur réseau ou serveur :", err);
      alert("Une erreur technique est survenue.");
    }
  }
}
