import React, { useEffect, useState } from 'react';
import QRCode from 'react-qr-code';
import Header from '../components/Header';
import Footer from '../components/Footer';
import forge from 'node-forge';
import '../css/Vote.css';  // Ensure the CSS file is included
import Cookies from 'js-cookie';

function Vote() {
  const [selectedCandidate, setSelectedCandidate] = useState(null);
  const [qrData, setQrData] = useState(null);
  const [qrClicked, setQrClicked] = useState(false);  // Track QR click state
  const [candidates, setCandidates] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const baseurl = import.meta.env.VITE_BASE_URL;
  const user_id = Cookies.get('user_id');

  useEffect(() => {
    getCandidates();
  }, []);

  const getCandidates = async () => {
    try {
      let res = await fetch(`${baseurl}/api/getCandidate`);
      const data = await res.json();
      if (data[0]) { 
        setCandidates(data.candidates);
        console.log("candidate", data.candidates[0].canid);
      } else {
        console.error('Error: No valid data found.');
      }
    } catch (error) {
      console.error('Error fetching candidates:', error);
    } finally {
      setIsLoading(false);
    }
  };
  
  // const candidates = [
  //   "Student Union 1", "Student Union 2", "Student Union 3", "Student Union 4","Student Union 5","Student Union 6","Student Union 7","Student Union 8"
  // ];

  const handleCandidateClick = (candidate) => {
    setSelectedCandidate(candidate.canid);
    setQrData(null); // Reset QR data when a new candidate is selected
  };

  const handleVoteClick = async (event) => {
    if (!selectedCandidate) return;

    event.preventDefault();
    setIsLoading(true);
    let res = await fetch(`${baseurl}/api/placevote`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ voter : user_id, candidate : selectedCandidate }),
    });
    const data = await res.json();
    if (data[0]) {
      console.log(data.voted);
      setQrData(JSON.stringify(data.voted));
    } else {
      console.log('error occured');
    }
    setIsLoading(false);

 // Set the encrypted data to QR code
  };

  const handleQrClick = () => {
    setQrClicked(!qrClicked);  // Toggle the enlarged state
  };

  return (
    <div className={`main_container ${qrClicked ? 'background-blur' : ''}`}>
      <Header />
      <h2 className="heading">Select Candidate</h2>
      <div className="candidates">
        {candidates.map((candidate, index) => (
          <div
            key={index}
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
      {qrData && (
        <div className="qr-container" onClick={() => setQrData(false)}>
          <QRCode value={qrData} size={400} />
          <div style={{backgroundColor: 'green', padding: 5, width:300,  alignItems: 'center', justifyContent: 'center', display: 'flex', color: '#fff', marginTop: '5%', borderRadius: 55, cursor: 'pointer'}}>
            <p style={{fontWeight: 700, fontSize: 20}}>Download</p>
          </div>
        </div>
      )}
     <Footer />
    </div>
  );
}

export default Vote;
