import React, { useState } from 'react';
import QRCode from 'react-qr-code';
import '../css/CandidateApply.css';

function CandidateApply() {
  const [name, setName] = useState('');
  const [registrationNo, setRegistrationNo] = useState('');
  const [contactNo, setContactNo] = useState('');
  const [faculty, setFaculty] = useState('');
  const [level, setLevel] = useState('');
  const [showQRCode, setShowQRCode] = useState(false);

  const handleApply = () => {
    if (name && registrationNo && contactNo && faculty && level) {
      setShowQRCode(true); // Show QR code after successful form submission
    } else {
      alert('All fields are required.');
      setShowQRCode(false);
    }
  };

  return (
    <div className="candidate-apply-container">
      <h2>Candidate Application</h2>
      
      <div className="form">
        <label>Name:</label>
        <input type="text" value={name} onChange={(e) => setName(e.target.value)} required />

        <label>Registration No:</label>
        <input type="text" value={registrationNo} onChange={(e) => setRegistrationNo(e.target.value)} required />

        <label>Contact No:</label>
        <input type="text" value={contactNo} onChange={(e) => setContactNo(e.target.value)} required />

        <label>Faculty:</label>
        <input type="text" value={faculty} onChange={(e) => setFaculty(e.target.value)} required />

        <label>Level:</label>
        <input type="text" value={level} onChange={(e) => setLevel(e.target.value)} required />

        <button onClick={handleApply} className="apply-button">Apply</button>
      </div>

      {/* Conditionally render the QR code */}
      {showQRCode && (
        <div className="qr-container">
          <QRCode
            value={`Name: ${name}, Reg. No: ${registrationNo}, Contact: ${contactNo}, Faculty: ${faculty}, Level: ${level}`}
            size={100}
          />
        </div>
      )}
    </div>
  );
}

export default CandidateApply;
