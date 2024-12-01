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

  const publicKeyPem = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqkqGX5nXiYLbArfTk/pw
qMS83vScQakMFIP8haVgk70lLZ++mCnbsZy/IlhCjADih7CS6NE1vz9FDi3ZVp5k
Sn0JImhFPbl4xByKpgvBoIeb1BWuzg6g2d2Zq0vynaDJc9WWeukg6/q9ZGg5diYy
wja1psiXBc8T/OxBLtYa+YGpsvMYBoi+JRjqo3teU3cw0SP/1qAz3xs025+2Fu8Y
1zOM111jrV9YjTnMltl8DCXISDkifguqjLZfWEHxz7TysRC3bjNPMjZDLcv1dGHu
3p75G2yU5RqnH7s2pEyuZGJSCe54YoWybPRE8ovpnx+brSMTtNKZTIyscoKDkE66
4QIDAQAB
  -----END PUBLIC KEY-----`;  // Replace with your actual public key

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

    // Use forge to create an RSA public key object from PEM format
    const publicKey = forge.pki.publicKeyFromPem(publicKeyPem);

    // Encrypt the combined data with the public key
    const encryptedData = publicKey.encrypt(combinedData, 'RSA-OAEP');

    // Encode encrypted data in Base64 to store in the QR code
    const encryptedString = forge.util.encode64(encryptedData);

    setQrData(encryptedString); // Set the encrypted data to QR code
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
        <div className={`qr-container ${qrClicked ? 'enlarged' : ''}`} onClick={handleQrClick}>
          <QRCode value={qrData} size={128} />
        </div>
      )}
     
    </div>
  );
}

export default Vote;
