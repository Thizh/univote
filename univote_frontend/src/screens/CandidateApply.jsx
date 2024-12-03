import React, { useState } from 'react';
import QRCode from 'react-qr-code';
import '../css/CandidateApply.css';
import Header from '../components/Header';
import Footer from '../components/Footer';

function CandidateApply() {
  const [name, setName] = useState('');
  const [registrationNo, setRegistrationNo] = useState('');
  const [contactNo, setContactNo] = useState('');
  const [faculty, setFaculty] = useState('');
  const [level, setLevel] = useState('');
  const [showQRCode, setShowQRCode] = useState(false);

  const handleApply = () => {
    if (contactNo) {
      setShowQRCode(true); // Show QR code after successful form submission
    } else {
      alert('All fields are required.');
      setShowQRCode(false);
    }
  };

  return (
    <div style={{height: '100vh', width: '100vw', color: '#000'}}>
    <Header />

    <div className="candidate-apply-container">
      <h2>Candidate Application</h2>

      <div style={{borderWidth: 1, borderColor: '#000', height: '30vh', width: '20vw', color: '#000'}}>
          <p>download by law</p>
      </div>
      
      <div className="form">

        <label>Contact No:</label>
        <input type="text" value={contactNo} onChange={(e) => setContactNo(e.target.value)} required />

        <div style={{ color: "#000", flexDirection: 'row', display: 'flex', alignItems: 'center', marginBottom: '4%' }}>
          <input type="checkbox" style={{ height: 15, width: 15 }} />
          <p style={{ margin: '0 0 0 8px' }}>I believe I am well-qualified to serve as a candidate.</p>
        </div>

        <button onClick={handleApply} className="apply-button">Apply</button>
      </div>

      {/* Conditionally render the QR code */}
      {showQRCode && (

        <div className="qr-container" onClick={() => setShowQRCode(false)}>
          <QRCode
            value={`Name: ${name}, Reg. No: ${registrationNo}, Contact: ${contactNo}, Faculty: ${faculty}, Level: ${level}`}
            size={300}
          />
          <div style={{backgroundColor: 'green', padding: 5, width:300,  alignItems: 'center', justifyContent: 'center', display: 'flex', color: '#fff', marginTop: '5%', borderRadius: 55, cursor: 'pointer'}}>
            <p style={{fontWeight: 700, fontSize: 20}}>Download</p>
          </div>
        </div>
      )}
    </div>
    <Footer />
    </div>
  );
}

export default CandidateApply;
