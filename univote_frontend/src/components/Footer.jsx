import React from 'react';
import '../css/styles.css';

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-content">
        <div className="contact">
          <h3>Contact us</h3>
          <p>0242221543</p>
          <p>SWD@ousl.lk</p>
        </div>
        <div className="location">
          <h3>Location</h3>
          <p>Student Welfare Division</p>
          <p>The Open University of Sri Lanka</p>
          <p>Brown Road, Nugegoda</p>
        </div>
        <div className="quick-links">
          <h3>Quick Links</h3>
          <a href="#">About Us</a>
          <a href="#">Voting Page</a>
          <a href="#">Statistics</a>
          <a href="#">Log out</a>
        </div>
        <div className="map">
          <h3>Map</h3>
          <div className="map-placeholder"></div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
