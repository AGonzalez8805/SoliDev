// Expression régulière pour valider un mot de passe sécurisé :
// - au moins 9 caractères
// - une majuscule
// - une minuscule
// - un chiffre
// - un caractère spécial
const passwordRegex =
  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{9,}$/;

export class Registration {
  constructor() {
    this.form = document.getElementById("registrationForm");
    if (!this.form) return;

    this.inputName = document.getElementById("name");
    this.inputFirstName = document.getElementById("firstName");
    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.inputValidatePassword = document.getElementById("validatePassword");

    this.init();
  }

  init() {
    const fields = [this.inputName, this.inputFirstName];
    fields.forEach((f) => f.addEventListener("input", () => this.validateField(f)));

    this.inputMail.addEventListener("input", () => this.validateField(this.inputMail, this.validateEmail));
    this.inputPassword.addEventListener("input", () => {
      this.updatePasswordCriteria(this.inputPassword.value);
      this.validatePasswordMatch();
    });
    this.inputValidatePassword.addEventListener("input", () => this.validatePasswordMatch());

    this.form.addEventListener("submit", (e) => {
      e.preventDefault();
      this.handleRegister();
    });
  }

  validateField(field, customValidator) {
    const value = field.value.trim();
    const isValid = customValidator ? customValidator(value) : value !== "";
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  validateEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  validatePasswordMatch() {
    const match = this.inputPassword.value === this.inputValidatePassword.value && this.inputPassword.value !== "";
    this.inputValidatePassword.classList.toggle("is-valid", match);
    this.inputValidatePassword.classList.toggle("is-invalid", !match);
    return match;
  }

  updatePasswordCriteria(password) {
    const criteria = {
      length: password.length >= 9,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /\d/.test(password),
      special: /[\W_]/.test(password),
    };

    for (const [key, valid] of Object.entries(criteria)) {
      const el = document.getElementById(key);
      if (!el) continue;
      el.classList.toggle("text-success", valid);
      el.classList.toggle("text-danger", !valid);
    }
  }

  async handleRegister() {
    const isNameValid = this.validateField(this.inputName);
    const isFirstNameValid = this.validateField(this.inputFirstName);
    const isEmailValid = this.validateField(this.inputMail, this.validateEmail);
    const isPasswordMatch = this.validatePasswordMatch();
    const isPasswordStrong = passwordRegex.test(this.inputPassword.value);

    if (!isPasswordStrong) {
      alert("Le mot de passe ne respecte pas les critères de sécurité.");
      return;
    }

    if (!isNameValid || !isFirstNameValid || !isEmailValid || !isPasswordMatch) {
      alert("Merci de corriger les erreurs avant de soumettre.");
      return;
    }

    const data = {
      name: this.inputName.value,
      firstName: this.inputFirstName.value,
      email: this.inputMail.value,
      password: this.inputPassword.value,
      validatePassword: this.inputValidatePassword.value,
    };

    try {
      const response = await fetch("index.php?controller=auth&action=handleRegister", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const text = await response.text();

      let result;
      try {
        result = JSON.parse(text);
      } catch (err) {
        console.error("Réponse serveur invalide :", text);
        alert("Erreur serveur : réponse non valide.");
        return;
      }

      if (result.success) {
        const messageDiv = document.getElementById("registrationMessage");
        if (messageDiv) {
          messageDiv.innerHTML = result.message;
          messageDiv.classList.remove("d-none"); // Bootstrap pour afficher
        } else {
          alert(result.message); // fallback
        }
        // Optionnel : désactiver le formulaire pour éviter plusieurs soumissions
        this.form.querySelector("button[type='submit']").disabled = true;
      } else {
        alert(result.message || "Erreur lors de l'inscription.");
      }

    } catch (err) {
      console.error("Erreur requête :", err);
      alert("Une erreur technique est survenue.");
    }
  }
}

