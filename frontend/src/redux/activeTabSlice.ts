import { createSlice, createAsyncThunk, Action, PayloadAction } from '@reduxjs/toolkit';

export interface ActiveTab {
  section: string;
  activeTab: number;
}

const initialState: [] = []

const activeTabSlice = createSlice({
  name: 'active_tab',
  initialState,
  reducers: {
    setActiveTab: (state: [], action: PayloadAction<ActiveTab>) => {
      let found = state.findIndex((item: ActiveTab) => item.section === action.payload.section);
      if (found !== -1) {
        // @ts-ignore
        state[found].activeTab = action.payload.activeTab;
      } else {
        // @ts-ignore
        state.push(action.payload);
      }

    }
  }
});

export const { setActiveTab } = activeTabSlice.actions;

export default activeTabSlice.reducer;