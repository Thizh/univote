import React from 'react';
import '../css/styles.css';
import DP from "../assets/imgs/dp.png";
import { useSelector } from 'react-redux';

const Header = () => {

  const isElection = useSelector((state) => state.user.isElectionStarted);

  return (
    <header className="header">
      <div className="header-container">
        <nav>
          <a href="/">Home</a>
          {!isElection && (
            <a href="/candidateapply">Apply Candidate</a>
          )}
          <a href="/statistics">Statistics</a>
          <a href="#">About us</a>
          <a href="/profile">
          <img src={DP} alt="User Icon" className="user-icon" style={{padding: '5px'}} />
          </a>
        </nav>
        
      </div>
    </header>
  );
};

export default Header;
