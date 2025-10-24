import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { CreateBlog } from "./Modules/CreateBlog.js";
import { Preview } from "./Modules/Preview.js";
import { DashboardUser } from "./Modules/DashboardUser.js";
import { DashboardDrafts } from "./Modules/DashboardDrafts.js";
import { CreateProject } from "./Modules/CreateProject.js";
import { MyCharts } from "./Modules/MyCharts.js";
import { DashboardAdmin } from "./Modules/DashboardAdmin.js";

document.addEventListener("DOMContentLoaded", () => {
  const moduleMap = [
    { selector: "#registrationForm", module: Registration },
    { selector: "#loginForm", module: Login },
    { selector: "#articleForm", module: CreateBlog },
    { selector: "#previewContainer", module: Preview },
    { selector: "#photoForm", module: DashboardUser },
    { selector: "#drafts-tab .form-card", module: DashboardDrafts },
    { selector: ".project-form", module: CreateProject },
    { selector: "#distributionChart", module: MyCharts },
    { selector: "#usersTableBody", module: DashboardAdmin },
  ];

  moduleMap.forEach(({ selector, module }) => {
    if (document.querySelector(selector)) new module();
  });
});
