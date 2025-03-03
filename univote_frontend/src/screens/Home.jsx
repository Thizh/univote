import React, { useEffect, useState } from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import '../css/styles.css';
import Cookies from 'js-cookie';
import { useNavigate } from 'react-router-dom';
import { useSelector, useDispatch } from 'react-redux';

function Home() {

  const navigation = useNavigate();
  const election = useSelector((state) => state.user.isElectionStarted)
  
  const userId = Cookies.get('user_id');
  const [firstTime, isFirstTime] = useState(false);
  // const [election, setElection] = useState(false);

  const [password, setPassword] = useState('');
  const [firstPage, isFirstPage] = useState(true);
  const [error, setError] = useState('');
  const [faculty, setFaculty] = useState('');
  const [level, setLevel] = useState('');
  const baseurl = import.meta.env.VITE_BASE_URL;

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
    console.log(data);
    if (data[0]) {
      isFirstTime(data.firstTime == 1);
    }
  }

  const checkPassword = (confirmPw) => {

    if (password != confirmPw) {
      setError('Passwords are not equal');
    }
    setError('');

  }

  const sendData = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`${baseurl}/api/setdata`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({id: userId, password, faculty, level}),
      });
      const result = await response.json();
      if (result[0]) {
        window.location.reload();
      } else {
        console.log(`Error: ${result.message}`);
      }
    } catch (error) {
      console.log("An error occurred while submitting the form.");
    }
  }

  return (
    <div className="Home">
      <Header />
      <div className="elections">
        <h2>Available Elections</h2>
        <div className="election-cards">
            <div className="election-card">
            <p style={{color: '#000'}}>News Feed</p>
            </div>
            {(
              <div className="election-card" style={{backgroundColor: '#B8DFAE'}} onClick={() => navigation('/vote')}>
                <p style={{color: '#000'}}>Vote</p>
              </div>
            )}

        </div>
        </div>
        {firstTime && (
          <div className="modal-overlay">
            <div className="modal-content">
              <h2>Let's Get Started</h2>
              <form onSubmit={sendData}>
                {firstPage ? (
                  <>
                    <label htmlFor="pw">Enter your new Password</label>
                    <input type='password' name='password' id="pw" onChange={(e) => setPassword(e.target.value)}/>

                    <label htmlFor="pw">Confirm your new Password</label>
                    <input type='password' id="pw" onChange={(e) => checkPassword(e.target.value)}/>

                    <div>{error}</div>

                    <div onClick={() => isFirstPage(false)} style={{backgroundColor: '#d3d3d3', padding: '3%', margin: '9%', width: '100%'}}>Next</div>
                  </>
                ) : (
                  <>
                    <label htmlFor="faculty">What is your faculty?</label>
                    <select id="faculty" name="faculty" value={faculty} onChange={(e) => setFaculty(e.target.value)}>
                      <option disabled value="">Select an option</option>
                      <option value="eng">Engineering</option>
                      <option value="sci">Science</option>
                      <option value="edu">Education</option>
                    </select>

                    <label htmlFor="level">What is your level?</label>
                    <select id="level" name="level" value={level} onChange={(e) => setLevel(e.target.value)}>
                      <option disabled value="">Select an option</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>  
                    </select>

                    <div style={{display: 'flex', flexDirection: 'row', flex: 1}}>
                      <div onClick={() => isFirstPage(true)} style={{flex: 1, backgroundColor: '#d3d3d3', padding: '3%', margin: '9%', width: '100%'}}>back</div>
                      <input type="submit" value="Start" style={{flex: 1}} />
                    </div>
                  </>
                )}
              </form>

            </div>
          </div>
        )}
      <Footer />
    </div>
  );
}

export default Home;
