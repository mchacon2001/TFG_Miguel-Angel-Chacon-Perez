import { User } from "../redux/authSlice";

const USER_KEY = "user";

export const saveUserToLocalStorage = (user: User) => {
  try {
    const serializedUser = JSON.stringify(user);
    localStorage.setItem(USER_KEY, serializedUser);
  } catch (error) {
    console.error(
      "Error al guardar el usuario en el almacenamiento local:",
      error
    );
  }
};

export const removeUserFromLocalStorage = () => {
  try {
    localStorage.removeItem(USER_KEY);
    window.location.reload();
  } catch (error) {
    console.error(
      "Error al eliminar el usuario del almacenamiento local:",
      error
    );
  }
};

export const loadUserFromLocalStorage = (): User | null => {
  try {
    //const serializedUser = localStorage.getItem(USER_KEY);
    const brainygymAppState = localStorage.getItem('BrainyGymAppState');
    const serializedUser = brainygymAppState ? JSON.parse(brainygymAppState).auth.user : null;
    if (serializedUser === null) {
      return null;
    }
    return serializedUser;
  } catch (error) {
    console.error(
      "Error al cargar el usuario desde el almacenamiento local:",
      error
    );
    return null;
  }
};
