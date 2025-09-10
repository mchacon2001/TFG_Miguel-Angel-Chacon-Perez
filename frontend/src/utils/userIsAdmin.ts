import { loadUserFromLocalStorage } from "./jwt";

export const userIsAdmin = function () {
  const user = loadUserFromLocalStorage();

  if (user && user.roles && user.roles.includes("Administrador")) {
    return true;
  } else {
    return false;
  }
}