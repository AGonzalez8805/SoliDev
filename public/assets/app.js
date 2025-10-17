import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { CreateBlog } from "./Modules/CreateBlog.js";
import { Preview } from "./Modules/Preview.js";
import { DashboardUser } from "./Modules/DashboardUser.js";
import { DashboardDrafts } from "./Modules/DashboardDrafts.js";
import { CreateProject } from "./Modules/CreateProject.js";
import { MyCharts } from "./Modules/MyCharts.js";

document.addEventListener("DOMContentLoaded", () => {
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
    new DashboardUser();
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
});
