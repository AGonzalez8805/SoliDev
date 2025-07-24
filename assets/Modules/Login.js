export class Login {
  constructor() {
    this.form = document.querySelector(".loginForm");
    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.btnValidation = document.getElementById("login");

    this.init();
  }

  init() {
    // Validation en temps réel
    this.inputMail.addEventListener("input", () =>
      this.validateEmail(this.inputMail)
    );
    this.inputPassword.addEventListener("input", () =>
      this.validatePassword(this.inputPassword)
    );

    // Soumission du formulaire
    this.form.addEventListener("submit", (e) => {
      e.preventDefault();
      this.handleLogin();
    });
  }

  validateEmail(field) {
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  validatePassword(field) {
    const isValid = field.value.trim().length > 0;
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  async handleLogin() {
    const isMailValid = this.validateEmail(this.inputMail);
    const isPasswordValid = this.validatePassword(this.inputPassword);

    if (!isMailValid || !isPasswordValid) {
      alert("Merci de corriger les champs avant de vous connecter.");
      return;
    }

    const data = {
      email: this.inputMail.value,
      password: this.inputPassword.value,
      remember: document.getElementById("rememberMe").checked,
    };

    try {
      const response = await fetch(
        "index.php?controller=auth&action=handleLogin",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const result = await response.json();

      if (result.success) {
        alert("Connexion réussie !");
        window.location.href = "index.php?controller=home";
      } else {
        alert(result.message || "Email ou mot de passe incorrect.");
      }
    } catch (error) {
      console.error("Erreur lors de la connexion :", error);
    }
  }
}
