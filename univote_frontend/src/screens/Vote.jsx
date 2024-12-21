import React, { useEffect, useRef, useState } from 'react';
import QRCode from 'react-qr-code';
import Header from '../components/Header';
import Footer from '../components/Footer';
import forge from 'node-forge';
import '../css/Vote.css';
import Cookies from 'js-cookie';
import CryptoJS from 'crypto-js';
import { toPng } from "html-to-image";

function Vote() {
  const [selectedCandidate, setSelectedCandidate] = useState(null);
  const [qrData, setQrData] = useState(null);
  const [qrClicked, setQrClicked] = useState(false);
  const [candidates, setCandidates] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const baseurl = import.meta.env.VITE_BASE_URL;
  const user_id = Cookies.get('user_id');
  const secretKey = import.meta.env.VITE_SECRET_KEY;
  const [voted, setVoted] = useState(null);
  const qrCodeRef = useRef(null);

  useEffect(() => {
    checkVoted();
  }, []);

  const getCandidates = async () => {
    setIsLoading(true);
    try {
      let res = await fetch(`${baseurl}/api/getCandidate`);
      const data = await res.json();
      if (data[0]) {
        setCandidates(data.candidates);
      } else {
        console.error('Error: No valid data found.');
      }
    } catch (error) {
      console.error('Error fetching candidates:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const checkVoted = async () => {
    setIsLoading(true);
    try {
      let res = await fetch(`${baseurl}/api/isVoted/${user_id}`);
      const data = await res.json();
      if (!data[0]) {
        getCandidates();
        setVoted(false);
      } else {
        setVoted(true);
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setIsLoading(false);
    }
  }

  const handleCandidateClick = (candidate) => {
    setSelectedCandidate(candidate.canid);
    setQrData(null);
  };

  const handleVoteClick = async (event) => {
    if (!selectedCandidate) return;

    event.preventDefault();
    const iv = CryptoJS.lib.WordArray.random(16);
    setIsLoading(true);
    let res = await fetch(`${baseurl}/api/placevote`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ voter: user_id, candidate: selectedCandidate }),
    });
    const data = await res.json();
    if (data[0]) {
      // const generatedHash = CryptoJS.MD5(data.voted).toString();
      // const encrypted = CryptoJS.AES.encrypt(JSON.stringify(data.voted), secretKey).toString();
      const encrypted = CryptoJS.AES.encrypt(JSON.stringify(data.voted), CryptoJS.enc.Utf8.parse(secretKey), {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7,
      });
      const encryptedDataWithIV = {
        ciphertext: encrypted.ciphertext.toString(CryptoJS.enc.Base64),
        iv: iv.toString(CryptoJS.enc.Base64),
      };
      setQrData(JSON.stringify(encryptedDataWithIV));
      setVoted(true);
      const timer = setTimeout(() => {
        sendQRPNG();
      }, 5000);
      return () => clearTimeout(timer);
    } else {
      console.log('error occured');
    }
    setIsLoading(false);
  };

  const sendQRPNG = async () => {
    if (!qrCodeRef.current) {
      console.error('QR Code reference is null!');
      return;
    }
    // console.log(qrCodeRef.current);

    // const qrCode = document.getElementById('qr_code');
    const svgElement = qrCodeRef.current.querySelector('svg');

    try {
      const svgString = new XMLSerializer().serializeToString(svgElement);
      // const base64Data = `data:image/svg+xml;base64,${btoa(svgString)}`;
      // console.log('Base64 SVG:', base64Data);
      let res = await fetch(`${baseurl}/api/saveqr`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({ voter: user_id, code: svgString }),
      });
      const resdata = await res.json();
      console.log(resdata);
    } catch (error) {
      console.error('Error generating QR code PNG:', error);
    }
  };


  return (
    <div className={`main_container`}>
      <Header />
      {voted == false ? (
        <>
          <h2 className="heading">Select Candidate</h2>
          <div className="candidates">
            {candidates.map((candidate) => (
              <div
                key={candidate.canid}
                className={`election-card ${selectedCandidate == candidate.canid ? "selected" : ""}`}
                onClick={() => handleCandidateClick(candidate)}
              >
                <div className="icon-placeholder"></div>
                <p>{candidate.candidate_name}</p>
              </div>
            ))}
          </div>
          <div className="buttons-container">
            <button onClick={handleVoteClick} disabled={!selectedCandidate} className="vote-button">
              Vote
            </button>
          </div>
          {/* {qrData && (
            <div className="qr-container" onClick={() => setQrData(false)} >
                <QRCode ref={qrCodeRef}  value={qrData} size={400} />
              <div style={{ backgroundColor: 'green', padding: 5, width: 300, alignItems: 'center', justifyContent: 'center', display: 'flex', color: '#fff', marginTop: '5%', borderRadius: 55, cursor: 'pointer' }}>
                <p style={{ fontWeight: 700, fontSize: 20 }}>Download</p>
              </div>
            </div>
          )} */}
        </>
      ) : (
        <div style={{display: 'flex', justifyContent: 'center', flexDirection: 'column', alignItems: 'center'}}>
          <div style={{fontSize: 18, fontWeight: 700, margin: '2%'}}>Your Vote is Recorded <br /> Here is your QR Code</div>
          {qrData ? (
            <div ref={qrCodeRef}>
              <QRCode value={qrData} size={'20%'} />
            </div>
          ) : (
            <div style={{ width: '20%', height: '20%' }}>
            <img src={`${baseurl}/storage/qr_codes/${user_id}.svg`} style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
            </div>
          )}
          <a href={`${baseurl}/storage/qr_codes/${user_id}.svg`} download="QR_code.svg">Download</a>
        </div>
      )}
      <Footer />
    </div>
  );
}

export default Vote;
