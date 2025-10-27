import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { CreateBlog } from "./Modules/CreateBlog.js";
import { Preview } from "./Modules/Preview.js";
import { DashboardUser } from "./Modules/DashboardUser.js";
import { DashboardDrafts } from "./Modules/DashboardDrafts.js";
import { CreateProject } from "./Modules/CreateProject.js";
import { MyCharts } from "./Modules/MyCharts.js";
import { DashboardAdmin } from "./Modules/DashboardAdmin.js";
import { ThemeToggle } from "./Modules/ThemeToggle.js";
import { CreateSnippet } from "./Modules/CreateSnippet.js";
import { SnippetFavorites } from "./Modules/SnippetFavorites.js";
import { HomePage } from "./Modules/HomePage.js";

document.addEventListener("DOMContentLoaded", () => {
  // Initialiser le thème toggle (disponible sur toutes les pages)
  new ThemeToggle();

  if (document.getElementById("registrationForm")) {
    new Registration();
  }

  if (document.getElementById("loginForm")) {
    new Login();
  }

  if (document.getElementById("articleForm")) {
    new CreateBlog();
  }

  if (document.getElementById("previewContainer")) {
    new Preview();
  }

  if (document.getElementById("photoForm")) {
    const dashboard = new DashboardUser();

    // Appliquer le thème sauvegardé au chargement
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      dashboard.applyTheme(savedTheme);
      const darkModeToggle = document.getElementById('darkMode');
      if (darkModeToggle) {
        darkModeToggle.checked = savedTheme === 'dark';
      }
    }
  }

  if (document.querySelector('#drafts-tab .form-card')) {
    new DashboardDrafts();
  }

  if (document.querySelector('.project-form')) {
    new CreateProject();
  }

  if (document.getElementById('distributionChart')) {
    new MyCharts();
  }

  if (document.getElementById("usersTableBody")) {
    new DashboardAdmin();
  }

  if (document.querySelector(".snippet-form")) {
    new CreateSnippet();
  }

  const favButtons = document.querySelectorAll(".favorite-btn");
  if (favButtons.length > 0) {
    new SnippetFavorites(favButtons);
  }

  if (document.querySelector(".hero-section")) {
    new HomePage();
  }
});