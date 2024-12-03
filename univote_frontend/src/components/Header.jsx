import React from 'react';
import '../css/styles.css';
import DP from "../assets/imgs/dp.png";

const Header = () => {
  return (
    <header className="header">
      <div className="header-container">
        <nav>
          <a href="/">Home</a>
          <a href="/candidateApply">Apply Candidate</a>
          <a href="#">Statistics</a>
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
