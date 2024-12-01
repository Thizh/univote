import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Home from './screens/Home';
import LoginSignUp from './screens/LoginSignUp';
import Vote from './screens/Vote';
import AcceptVote from './screens/AcceptVote';

function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    const checkLoginStatus = async () => {
      try {
        const isLoggedIn = await localStorage.getItem('isLoggedIn');
        setIsLoggedIn(isLoggedIn === 'true');
      } catch (error) {
        console.error('Error retrieving login status:', error);
      }
    };

    checkLoginStatus();
  }, []);

  return (
    <Router>

      
   

      <Routes>
        <Route
          path="/"
          element={isLoggedIn ? <AcceptVote /> : <AcceptVote />}
        />
      </Routes>

    </Router>
  );
}

export default App;


import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Cookies from "js-cookie";
import Home from './screens/Home';
import Statistics from './screens/statistics.jsx';
import LoginSignUp from './screens/LoginSignUp';
import Profile from './screens/Profile';
import Middleware from './Middleware';

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
          path="/profile"
          element={
            <Middleware>
              <Profile />
            </Middleware>
          }
        />
        <Route
          path="/statistics" element={<Statistics/>}
        />
       
      </Routes>
    </Router>
  );
}

export default App;
