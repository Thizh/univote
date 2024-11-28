import React from 'react';
import '../css/styles.css';

const Header = () => {
  return (
    <header className="header">
      <div className="header-container">
        <img src="user-icon-placeholder.png" alt="User Icon" className="user-icon" />
        <nav>
          <a href="#">Home</a>
          <a href="#">Voting</a>
          <a href="#">Statistics</a>
          <a href="#">About us</a>
          <a href="/profile">
            <img src="user-icon-placeholder.png" alt="User Icon" className="user-icon" />
          </a>
        </nav>
      </div>
    </header>
  );
};

export default Header;
