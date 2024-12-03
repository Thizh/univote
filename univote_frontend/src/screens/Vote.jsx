import React, { useState } from 'react';
import QRCode from 'react-qr-code';
import Header from '../components/Header';
import Footer from '../components/Footer';
import forge from 'node-forge';
import '../css/Vote.css';  // Ensure the CSS file is included

function Vote() {
  const [selectedCandidate, setSelectedCandidate] = useState(null);
  const [qrData, setQrData] = useState(null);
  const [qrClicked, setQrClicked] = useState(false);  // Track QR click state

  const candidates = [
    "Student Union 1", "Student Union 2", "Student Union 3", "Student Union 4","Student Union 5","Student Union 6","Student Union 7","Student Union 8"
  ];

  const handleCandidateClick = (candidate) => {
    setSelectedCandidate(candidate);
    setQrData(null); // Reset QR data when a new candidate is selected
  };

  const handleVoteClick = () => {
    if (!selectedCandidate) return;

    const user = { name: "John Doe", id: "12345" };  // User details
    const voteDetails = { candidate: selectedCandidate };  // Vote details
    const combinedData = JSON.stringify({ user, voteDetails });

    setQrData(voteDetails); // Set the encrypted data to QR code
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
            className={`election-card ${selectedCandidate === candidate ? "selected" : ""}`}
            onClick={() => handleCandidateClick(candidate)}
          >
            <div className="icon-placeholder"></div>
            <p>{candidate}</p>
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
