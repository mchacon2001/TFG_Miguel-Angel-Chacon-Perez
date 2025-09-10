import { configureStore } from '@reduxjs/toolkit';
import authReducer from './authSlice';
import activeTabReducer from './activeTabSlice';
import { loadState, saveState } from './browser-storage';
import { debounce } from "../helpers/helpers";

const store = configureStore({
  reducer: {
    auth: authReducer,
    activeTab: activeTabReducer,
  },
  preloadedState: loadState(),
});

store.subscribe(
  debounce(() => {
    saveState(store.getState());
  }, 800));

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;

export default store;