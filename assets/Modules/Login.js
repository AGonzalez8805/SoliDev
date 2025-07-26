export class Login {
  constructor() {
    // Récupération du formulaire
    this.form = document.getElementById("loginForm");

    // Si le formulaire n'existe pas (par exemple sur une autre page), on stoppe
    if (!this.form) return;

    // Récupération des champs nécessaires
    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");

    // Lancement des écouteurs d'événements
    this.init();
  }

  init() {
    // Événement sur l'email : validation en temps réel
    this.inputMail.addEventListener("input", () =>
      this.validateEmail(this.inputMail)
    );

    // Événement sur le mot de passe : vérifie juste que le champ n'est pas vide
    this.inputPassword.addEventListener("input", () =>
      this.validatePassword(this.inputPassword)
    );

    // Gestion de la soumission du formulaire
    this.form.addEventListener("submit", (e) => {
      e.preventDefault(); // Empêche l'envoi classique
      this.handleLogin(); // Déclenche la logique de connexion
    });
  }

  // Vérifie que l'email est au bon format
  validateEmail(field) {
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  // Vérifie simplement que le champ n'est pas vide
  validatePassword(field) {
    const isValid = field.value.trim().length > 0;
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  // Gère l'envoi des données au backend pour connexion
  async handleLogin() {
    // Re-valide les champs avant soumission
    const isMailValid = this.validateEmail(this.inputMail);
    const isPasswordValid = this.validatePassword(this.inputPassword);

    // Affiche une alerte si un champ est invalide
    if (!isMailValid || !isPasswordValid) {
      alert("Merci de corriger les champs avant de vous connecter.");
      return;
    }

    // Prépare les données du formulaire
    const data = {
      email: this.inputMail.value,
      password: this.inputPassword.value,
      remember: document.getElementById("rememberMe").checked,
    };

    try {
      // Envoie les données au contrôleur backend
      const response = await fetch(
        "index.php?controller=auth&action=handleLogin",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        }
      );

      const text = await response.text();

      let result;
      try {
        result = JSON.parse(text); // Essaie de parser la réponse JSON
      } catch (err) {
        console.error("Erreur de parsing JSON :", err);
        alert("La réponse du serveur n'est pas du JSON valide.");
        return;
      }

      // Si la connexion est un succès, on redirige
      if (result.success) {
        window.location.href = result.redirect || "";
      } else {
        // Affiche le message d'erreur personnalisé ou un message par défaut
        alert(result.message || "Erreur d'identifiants.");
      }
    } catch (error) {
      console.error("Erreur lors de la connexion :", error);
      alert("Une erreur technique est survenue.");
    }
  }
}
