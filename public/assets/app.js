import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { CreateBlog } from "./Modules/CreateBlog.js";
import { Preview } from "./Modules/Preview.js";

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
});
