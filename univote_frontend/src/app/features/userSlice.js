import { createSlice } from '@reduxjs/toolkit';

export const userSlice = createSlice({
    name: 'user',
    initialState: {
        isElectionStarted: null,
    },
    reducers: {
        toggleElectionStatus: (state, action) => {
            state.isElectionStarted = action.payload;
        }
    },
});

export const { toggleElectionStatus } = userSlice.actions;

export default userSlice.reducer;
