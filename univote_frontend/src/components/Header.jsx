import React from 'react';
import { Link } from 'react-router-dom';
import '../css/styles.css';
import { User, Home, BarChart, Info, UserPlus } from 'lucide-react';
import { useSelector } from 'react-redux';

const Header = () => {
  const isElection = useSelector((state) => state.user.isElectionStarted);

  return (
    <header className="header">
      <h2>UNIVOTE</h2>
      <div className="header-container">
        <nav>
          <Link to="/" className="nav-item">
            <Home size={20} /> <span><h3>Home</h3></span>
          </Link>
          {!isElection && (
            <Link to="/candidateapply" className="nav-item">
              <UserPlus size={20} /> <span><h3>Apply Candidate</h3></span>
            </Link>
          )}
          <Link to="/statistics" className="nav-item">
            <BarChart size={20} /> <span><h3>Statistics</h3></span>
          </Link>
          <Link to="/about" className="nav-item">
            <Info size={20} /> <span><h3>About Us</h3></span>
          </Link>
          <Link to="/profile" className="user-icon">
            <User size={20} />
          </Link>
        </nav>
      </div>
    </header>
  );
};

export default Header;
