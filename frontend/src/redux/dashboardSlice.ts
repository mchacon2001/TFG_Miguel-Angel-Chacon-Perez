import { createSlice } from "@reduxjs/toolkit";

export interface CalendarState {
    viewMode: "years" | "months" | "weeks" | "days";
}

export const initialState: CalendarState = {
    viewMode: 'days',
};

const dashboardSlice = createSlice({
    name: 'dashboard',
    initialState,
    reducers: {
        changeStorageViewMode: (state, action) => {
            state.viewMode = action.payload;
        },
    },
});

export const { changeStorageViewMode } = dashboardSlice.actions;

export default dashboardSlice.reducer;