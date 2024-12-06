import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Cookies from "js-cookie";
import Home from './screens/Home';
import Statistics from './screens/statistics.jsx';
import LoginSignUp from './screens/LoginSignUp';
import Profile from './screens/Profile';
import CandidateApply from './screens/CandidateApply';
import AcceptVote from './screens/AcceptVote';
import Middleware from './Middleware';
import Vote from './screens/Vote.jsx';

function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    const isLoggedIn = Cookies.get('isLoggedIn') === 'true';
    setIsLoggedIn(isLoggedIn);
  }, []);

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
        <Route
          path="/vote"
          element={
            <Middleware>
              <Vote />
            </Middleware>
          }
        />
        <Route
          path="/candidateApply"
          element={
            <Middleware>
              <CandidateApply />
            </Middleware>
          }
        />
        <Route
          path="/acceptVote"
          element={
            <Middleware>
              <AcceptVote />
            </Middleware>
          }
        />
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
              <Statistics/>
            </Middleware>
          }
        />
       
      </Routes>
    </Router>
  );
}

export default App;
