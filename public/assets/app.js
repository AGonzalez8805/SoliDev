import { Registration } from "/assets/modules/Registration.js";
import { Login } from "/assets/modules/Login.js";

document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("registrationForm")) {
    new Registration();
  }

  if (document.getElementById("loginForm")) {
    new Login();
  }
});
