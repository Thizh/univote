import React from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import '../css/styles.css';

function Home() {
  return (
    <div className="Home">
      <Header />
      <div className="elections">
        <img src="university-placeholder.jpg" alt="University" className="university-image" />
        <h2>Available Elections</h2>
        <div className="election-cards">
            <div className="election-card">
            <div className="icon-placeholder"></div>
            <p style={{color: '#000'}}>Student Union</p>
            </div>
            <div className="election-card" style={{backgroundColor: '#B8DFAE'}}>
            <div className="icon-placeholder"></div>
            <p style={{color: '#000'}}>Sports Council</p>
            </div>
            <div className="election-card">
            <div className="icon-placeholder"></div>
            <p style={{color: '#000'}}>Student Union</p>
            </div>
        </div>
        </div>
      <Footer />
    </div>
  );
}

export default Home;
