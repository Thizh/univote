import React from 'react';
import { Navigate } from 'react-router-dom';
import Cookies from 'js-cookie';

const Middleware = ({ children }) => {
  const isLoggedIn = Cookies.get('isLoggedIn') === 'true';
  
  return isLoggedIn ? children : <Navigate to="/login" />;
};

export default Middleware;
