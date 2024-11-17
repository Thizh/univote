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

