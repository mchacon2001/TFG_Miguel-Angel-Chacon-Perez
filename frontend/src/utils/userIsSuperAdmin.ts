import { loadUserFromLocalStorage } from "./jwt";

export const userIsSuperAdmin = function () {
  const user = loadUserFromLocalStorage();

  if (user && user.roles && user.roles.includes("Superadministrador")) {
    return true;
  } else {
    return false;
  }
}