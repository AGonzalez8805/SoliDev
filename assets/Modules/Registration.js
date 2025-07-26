// Expression régulière pour valider un mot de passe sécurisé :
// - au moins 9 caractères
// - une majuscule
// - une minuscule
// - un chiffre
// - un caractère spécial
const passwordRegex =
  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{9,}$/;

// Classe de gestion du formulaire d'inscription
export class Registration {
  constructor() {
    // Récupération des champs du formulaire
    this.form = document.getElementById("registrationForm");
    this.inputName = document.getElementById("name");
    this.inputFirstName = document.getElementById("firstName");
    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.inputValidatePassword = document.getElementById("validatePassword");

    // Initialisation des événements
    this.init();
  }

  init() {
    // Événements de validation en temps réel sur les champs texte
    this.inputName.addEventListener("input", () =>
      this.validateField(this.inputName)
    );
    this.inputFirstName.addEventListener("input", () =>
      this.validateField(this.inputFirstName)
    );

    // Validation de l'email au format
    this.inputMail.addEventListener("input", () =>
      this.validateEmail(this.inputMail)
    );

    // Validation dynamique du mot de passe et des critères
    this.inputPassword.addEventListener("input", () => {
      this.updatePasswordCriteria(this.inputPassword.value);
      this.validatePasswordMatch(); // Mise à jour de la confirmation
    });

    // Vérifie la correspondance des mots de passe
    this.inputValidatePassword.addEventListener("input", () =>
      this.validatePasswordMatch()
    );

    // Soumission du formulaire
    this.form.addEventListener("submit", (e) => {
      e.preventDefault(); // Empêche la soumission classique
      this.handleRegister(); // Lance le traitement JS
    });
  }

  // Vérifie que le champ n'est pas vide
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

  // Vérifie que l'email respecte un format standard
  validateEmail(field) {
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  // Vérifie que les deux mots de passe sont identiques et non vides
  validatePasswordMatch() {
    const password = this.inputPassword.value;
    const confirmPassword = this.inputValidatePassword.value;

    const match = password === confirmPassword && password !== "";

    this.inputValidatePassword.classList.toggle("is-valid", match);
    this.inputValidatePassword.classList.toggle("is-invalid", !match);

    return match;
  }

  // Met à jour visuellement les critères du mot de passe
  updatePasswordCriteria(password) {
    const criteria = {
      length: password.length >= 9,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /[0-9]/.test(password),
      special: /[\W_]/.test(password),
    };

    // Pour chaque critère, on met à jour la couleur selon qu'il est respecté
    for (const [key, isValid] of Object.entries(criteria)) {
      const element = document.getElementById(key);
      element.classList.toggle("text-success", isValid);
      element.classList.toggle("text-danger", !isValid);
    }
  }

  // Gestion complète de la soumission du formulaire
  async handleRegister() {
    const isNameValid = this.validateField(this.inputName);
    const isFirstNameValid = this.validateField(this.inputFirstName);
    const isEmailValid = this.validateEmail(this.inputMail);
    const isPasswordMatch = this.validatePasswordMatch();
    const isPasswordStrong = passwordRegex.test(this.inputPassword.value);

    // Affiche une alerte si le mot de passe ne respecte pas les critères
    if (!isPasswordStrong) {
      alert("Le mot de passe ne respecte pas les critères de sécurité.");
      return;
    }

    // Vérifie que tous les champs sont valides
    if (
      !isNameValid ||
      !isFirstNameValid ||
      !isEmailValid ||
      !isPasswordMatch
    ) {
      alert("Merci de corriger les erreurs avant de soumettre.");
      return;
    }

    // Préparation des données à envoyer
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

      // Tentative de décodage JSON
      try {
        const result = JSON.parse(text);
        if (result.success) {
          alert("Inscription réussie !");
          window.location.href = "index.php?controller=auth&action=login";
        } else {
          alert(result.message || "Erreur lors de l'inscription.");
        }
      } catch (e) {
        console.error("Réponse invalide du serveur :", text);
      }
    } catch (err) {
      console.error("Erreur lors de la requête :", err);
    }
  }
}
