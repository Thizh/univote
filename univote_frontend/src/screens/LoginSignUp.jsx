import React, { useState } from 'react';
import Logo from '../assets/imgs/logo.png'
import '../css/login.css';
import { BrowserRouter as Router, Routes, Route, Link, useNavigate } from 'react-router-dom';

function LoginSignUp() {
  let csrfToken = $('meta[name="csrf-token"]').attr('content');
  const [nic, setNic] = useState('');
  const [name, setName] = useState('');
  const [password, setPassword] = useState('');
  const baseurl = 'http://univoteadmin.sytes.net/';
  const [error, setError] = useState('');
  const navigate = useNavigate();
  const [secBtnClicked, setSecBtnClicked] = useState(false);

  const fetchStu = async (event) => {
    event.preventDefault();
    let res = await fetch(`${baseurl}/api/student-details`, {
      method: 'POST',
      headers: {
        "X-CSRF-Token": csrfToken
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ nic }),
    });
    const data = await res.json();
    if (data[0]) {
      setName(data.name);
    } else {
      setError('You are not an OUSL student');
    }

    console.log(data);
  }

  const checkPass = async (event) => {
    event.preventDefault();
    let check_pw = await fetch(`${baseurl}/api/checkuser`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ nic, reg_no: password }),
    });
    const pw_data = await check_pw.json();
    if (pw_data[0]) {
      localStorage.setItem('isLoggedIn', 'true');
      window.location.reload();
    } else {
      setError('Your Password is wrong');
    }
  }

  return (
    <div className="container" id="container">
      <div className="form-container sign-in-container">
        <form onSubmit={name ? checkPass : fetchStu}>
          <img src={Logo} alt="A beautiful scenery" width="30" height="40" />
          <h1 className="signin-h1">Sign in</h1>
            {name ? (
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <div style={{ display: 'flex', flexDirection: 'row', alignItems: 'center', marginBottom: 10 }}>
                  <img src={Logo} alt="A beautiful scenery" width="30" height="40" />
                  <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start', marginLeft: 10 }}>
                    <p style={{ color: '#000', fontSize: 15, margin: 0 }}>{name}</p>
                    <p style={{ color: '#000', fontSize: 10, margin: 0 }}>{nic}</p>
                  </div>
                </div>
                <input type="password" placeholder="Password" style={{ padding: '8px', fontSize: '14px', width: '100%' }} onChange={event => setPassword(event.target.value)}/>
              </div>

            ) : (
              <input type="text" placeholder="NIC Number" onChange={event => {setNic(event.target.value), setError('')}}/>
            )}
            <p style={{color: 'red'}}>{error}</p>
            
            <button style={{marginTop: '10%'}}>Sign In</button>
        </form>
      </div>
      
      <div className="overlay-container">
        <div className="overlay">
          <div className="overlay-panel overlay-right">
            <h1>Hello Voter!</h1>
            {name ? (
              <p>If you are a first timer login password is your OUSL Registration Number</p>
            ) : (
              <p>Your vote is awaiting.</p>
            )}
            
          </div>
        </div>
      </div>
    </div>
  );
}

export default LoginSignUp;
