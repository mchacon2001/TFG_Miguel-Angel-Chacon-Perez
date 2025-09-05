import { createSlice, PayloadAction } from '@reduxjs/toolkit';

export interface User {
  id: string;
  token: string;
  name: string;
  roles: string[];
  profilePictureId: string;
}

export interface AuthState {
  isAuthenticated: boolean;
  user: User | null;
  loading: boolean;
  error: string | null;
}

export const initialState: AuthState = {
  isAuthenticated: false,
  user: null,
  loading: false,
  error: null,
};


const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    login: (state: AuthState, action: PayloadAction<AuthState>) => {
      state = {
        ...action.payload
      }
      return state;
    },
    logout: (state) => {
      state.isAuthenticated = false;
      state.user = null;
      return state;
    },
    setUser: (state, action: PayloadAction<User>) => {
      state.user = action.payload;
    },
  }
});

export const { logout, login, setUser } = authSlice.actions;

export default authSlice.reducer;