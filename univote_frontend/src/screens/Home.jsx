import React, { useEffect, useState } from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import '../css/styles.css';
import Cookies from 'js-cookie';
import { useNavigate } from 'react-router-dom';

function Home() {

  const navigation = useNavigate();

  const [userId, setUserId] = useState(Cookies.get('user_id'));
  const [firstTime, isFirstTime] = useState(false);

  useEffect(() => {
    checkUser();
  }, []);

  const checkUser = async () => {
    let res = await fetch(`${import.meta.env.VITE_BASE_URL}/api/first-time`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        id: userId,
      }),
    });
    const data = await res.json();
    if (data[0]) {
      isFirstTime(data.firstTime == 1);
    }
  }

  return (
    <div className="Home">
      <Header />
      <div className="elections">
        <img src="university-placeholder.jpg" alt="University" className="university-image" />
        <h2>Available Elections</h2>
        <div className="election-cards">
            <div className="election-card">
            <p style={{color: '#000'}}>News Feed</p>
            </div>
            <div className="election-card" style={{backgroundColor: '#B8DFAE'}} onClick={() => navigation('/vote')}>
            <p style={{color: '#000'}}>Vote</p>
            </div>
        </div>
        </div>
        {firstTime && (
          <div className="modal-overlay">
            <div className="modal-content">
              <h2>Let's Get Started</h2>
              <form method="post" action={`${import.meta.env.VITE_BASE_URL}/api/setdata`}>
                <input type='hidden' name="id" value={userId} />
                <label htmlFor="faculty">What is your faculty?</label>
                <select id="faculty" name="faculty" defaultValue="">
                  <option disabled value="">Select an option</option>
                  <option value="eng">Engineering</option>
                  <option value="sci">Science</option>
                  <option value="edu">Education</option>
                </select>

                <label htmlFor="level">What is your level?</label>
                <select id="level" name="level" defaultValue="">
                  <option disabled value="">Select an option</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>  
                </select>

                <input type="submit" value="Start" />
              </form>

            </div>
          </div>
        )}
      <Footer />
    </div>
  );
}

export default Home;
