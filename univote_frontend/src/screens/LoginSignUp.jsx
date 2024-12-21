import React, { useEffect, useState } from 'react';
import Logo from '../assets/imgs/logo.png'
import '../css/login.css';
import { BrowserRouter as Router, Routes, Route, Link, useNavigate } from 'react-router-dom';
import { FourSquare } from 'react-loading-indicators';
import Cookies from 'js-cookie';
import OTPInput from 'react-otp-input';


function LoginSignUp() {
  const [nic, setNic] = useState('');
  const [name, setName] = useState('');
  const [email, setEmail] = useState(null);
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();
  const [secBtnClicked, setSecBtnClicked] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const baseurl = import.meta.env.VITE_BASE_URL;
  const [pwSubmitted, setPwSubmitted] = useState(false);
  const [otp, setOtp] = useState(null);

  useEffect(() => {
    console.log(otp);

  }, [otp]);

  const fetchStu = async (event) => {
    event.preventDefault();
    setIsLoading(true);
    let res = await fetch(`${baseurl}/api/student-details`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ nic }),
    });
    const data = await res.json();
    console.log(nic);
    if (data[0]) {
      setName(data.name);
      setEmail(data.email);
      console.log(data.email);
    } else {
      setError('You are not an OUSL student');
    }
    setIsLoading(false);
  }

  const checkPass = async (event) => {
    event.preventDefault();
    setIsLoading(true);
    let check_pw = await fetch(`${baseurl}/api/checkuser`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ nic, reg_no: password, email }),
    });
    const pw_data = await check_pw.json();
    if (pw_data[0]) {
      setPwSubmitted(true);
      if (email == null) {
        Cookies.set("isLoggedIn", "true", { expires: 7, path: "/" });
        Cookies.set("user_id", JSON.stringify(pw_data.user), { expires: 7, path: "/" });
        navigate('/');
      }
    } else {
      setError('Your Password is wrong');
    }
    setIsLoading(false);
  }

  const checkOtp = async (event) => {
    event.preventDefault();
    setIsLoading(true);
    let check_otp = await fetch(`${baseurl}/api/checkotp`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ nic, otp }),
    });
    const otp_data = await check_otp.json();
    console.log(otp_data);
    if (otp_data[0]) {
      Cookies.set("isLoggedIn", "true", { expires: 7, path: "/" });
      Cookies.set("user_id", JSON.stringify(otp_data.voter), { expires: 7, path: "/" });
      navigate('/');
    } else {
      setError('Your otp is wrong');
    }
    setIsLoading(false);
  }

  return (
    <div style={{ height: '100%', width: '100vw' }}>
      <div className="overlay-container-mobile">
          <div className='overlay-mobile'>
            <h1>Hello Voter!</h1>
            {name ? (
              <p>If you are a first timer login password is your OUSL Registration Number</p>
            ) : (
              <p>Your vote is awaiting.</p>
            )}
            </div>
      </div>
      <div className="container" id="container">
        {!pwSubmitted ? (
          <>
            <div className="form-container sign-in-container">
              <form onSubmit={name ? checkPass : fetchStu}>
                <img src={Logo} alt="A beautiful scenery" width="30" height="40" />
                <h1 className="signin-h1">Sign in or Create an Account</h1>
                {name ? (
                  <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                    <div style={{ display: 'flex', flexDirection: 'row', alignItems: 'center', marginBottom: 10 }}>
                      <img src={Logo} alt="A beautiful scenery" width="30" height="40" />
                      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start', marginLeft: 10 }}>
                        <p style={{ color: '#000', fontSize: 15, margin: 0 }}>{name}</p>
                        <p style={{ color: '#000', fontSize: 10, margin: 0 }}>{nic}</p>
                      </div>
                    </div>
                    <input type="password" placeholder="Password" style={{ padding: '8px', fontSize: '14px', width: '100%' }} onChange={event => setPassword(event.target.value)} />
                  </div>

                ) : (
                  <input type="text" placeholder="NIC Number" onChange={event => { setNic(event.target.value), setError('') }} />
                )}
                <p style={{ color: 'red' }}>{error}</p>

                <button style={{ marginTop: '10%' }}> {isLoading ? <FourSquare color="#ff3d00" size="small" text="" textColor="" /> : "Next"}</button>
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
          </>
        ) : email && (
          <div style={{ display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
            <div style={{ color: '#000', fontSize: 25, fontWeight: '700', margin: '6%' }}>Enter Otp</div>
            <div style={{ color: '#000', marginTop: '5%' }}>we sent an OTP to your OUSL mail <span style={{ fontWeight: '700' }}>{email}</span> Please enter the OTP below. </div>
            <OTPInput
              value={otp}
              onChange={setOtp}
              numInputs={6}
              inputStyle={{
                width: '3rem',
                height: '3rem',
                margin: '20px 1rem',
                fontSize: '1rem',
                borderRadius: 4,
                border: '2px solid rgba(0,0,0,0.3)',
              }}
              renderSeparator={<span>-</span>}
              renderInput={(props) => <input {...props} />}
            />
            <p style={{ color: 'red' }}>{error}</p>
            <button style={{ marginTop: '5%' }} onClick={checkOtp}> {isLoading ? <FourSquare color="#ff3d00" size="small" text="" textColor="" /> : "Register"}</button>
          </div>
        )};
      </div>
    </div>
  );
}

export default LoginSignUp;