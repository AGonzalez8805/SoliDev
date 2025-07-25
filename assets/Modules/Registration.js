const passwordRegex =
  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{9,}$/;

export class Registration {
  constructor() {
    this.form = document.getElementById("registrationForm");
    this.inputName = document.getElementById("name");
    this.inputFirstName = document.getElementById("firstName");
    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.inputValidatePassword = document.getElementById("validatePassword");
    this.btnValidation = document.getElementById("register");

    this.init();
  }

  init() {
    // Événement sur chaque champ pour valider en temps réel
    this.inputName.addEventListener("input", () =>
      this.validateField(this.inputName)
    );
    this.inputFirstName.addEventListener("input", () =>
      this.validateField(this.inputFirstName)
    );
    this.inputMail.addEventListener("input", () =>
      this.validateEmail(this.inputMail)
    );
    this.inputPassword.addEventListener("input", () => {
      this.updatePasswordCriteria(this.inputPassword.value);
      this.validatePasswordMatch();
    });
    this.inputValidatePassword.addEventListener("input", () =>
      this.validatePasswordMatch()
    );

    // Soumission du formulaire
    this.form.addEventListener("submit", (e) => {
      e.preventDefault();
      this.handleRegister();
    });
  }

  validateField(field) {
    if (field.value.trim() === "") {
      field.classList.remove("is-valid");
      field.classList.add("is-invalid");
      return false;
    } else {
      field.classList.remove("is-invalid");
      field.classList.add("is-valid");
      return true;
    }
  }

  validateEmail(field) {
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  validatePasswordMatch() {
    const password = this.inputPassword.value;
    const confirmPassword = this.inputValidatePassword.value;

    const match = password === confirmPassword && password !== "";

    if (match) {
      this.inputValidatePassword.classList.add("is-valid");
      this.inputValidatePassword.classList.remove("is-invalid");
    } else {
      this.inputValidatePassword.classList.remove("is-valid");
      this.inputValidatePassword.classList.add("is-invalid");
    }

    return match;
  }

  async handleRegister() {
    const allValid =
      this.validateField(this.inputName) &
      this.validateField(this.inputFirstName) &
      this.validateEmail(this.inputMail) &
      this.validatePasswordMatch();

    if (!allValid) {
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
      const response = await fetch(
        "index.php?controller=auth&action=handleRegister",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const text = await response.text();

      try {
        const result = JSON.parse(text);
        if (result.success) {
          alert("Inscription réussie !");
          window.location.href = "index.php?controller=auth&action=login";
        } else {
          alert(result.message || "Erreur lors de l'inscription.");
        }
      } catch (e) {
        console.error("Réponse invalide :", text);
      }
    } catch (err) {
      console.error("Erreur lors de l'envoi de la requête :", err);
    }
  }

  updatePasswordCriteria(password) {
    const criteria = {
      length: password.length >= 9,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /[0-9]/.test(password),
      special: /[\W_]/.test(password),
    };

    for (const [key, isValid] of Object.entries(criteria)) {
      const element = document.getElementById(key);
      element.classList.toggle("text-success", isValid);
      element.classList.toggle("text-danger", !isValid);
    }
  }
}
