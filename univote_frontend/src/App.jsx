import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Cookies from "js-cookie";
import Home from './screens/Home';
import Statistics from './screens/statistics.jsx';
import LoginSignUp from './screens/LoginSignUp';
import Profile from './screens/Profile';
import CandidateApply from './screens/CandidateApply';
import Middleware from './Middleware';
import Vote from './screens/Vote.jsx';
import { useDispatch, useSelector } from 'react-redux';
import { toggleElectionStatus } from './app/features/userSlice.js';

function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const isElection = useSelector((state) => state.user.isElectionStarted)
  const dispatch = useDispatch();

  useEffect(() => {
    const isLoggedIn = Cookies.get('isLoggedIn') === 'true';
    setIsLoggedIn(isLoggedIn);
    isStarted();
  }, []);

  const isStarted = async () => {
    let res = await fetch(`${import.meta.env.VITE_BASE_URL}/api/is-started`);
    const data = await res.json();
    console.log('isStarted', data);
    dispatch(toggleElectionStatus(data[0]));
  }

  // const isElection = useSelector((state) => {
  //   console.log('Redux State:', state.user.isElectionStarted);
  //   return state.user.isElectionStarted;
  // });

  return (
    <Router>
      <Routes>
        <Route path="/login" element={<LoginSignUp />} />
        <Route
          path="/"
          element={
            <Middleware>
              <Home />
            </Middleware>
          }
        />
        {isElection ? (
          <Route
            path="/vote"
            element={
              <Middleware>
                <Vote />
              </Middleware>
            }
          />
        ) : <Route
          path="/candidateapply"
          element={
            <Middleware>
              <CandidateApply />
            </Middleware>
          }
        />}

        <Route
          path="/profile"
          element={
            <Middleware>
              <Profile />
            </Middleware>
          }
        />
        <Route
          path="/statistics"
          element={
            <Middleware>
              <Statistics />
            </Middleware>
          }
        />

      </Routes>
    </Router>
  );
}

export default App;
