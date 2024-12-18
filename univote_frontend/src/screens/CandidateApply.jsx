import React, { useEffect, useState } from 'react';
import QRCode from 'react-qr-code';
import '../css/CandidateApply.css';
import Header from '../components/Header';
import Footer from '../components/Footer';
import Cookies from 'js-cookie';

function CandidateApply() {
  const [name, setName] = useState('');
  const [registrationNo, setRegistrationNo] = useState('');
  const [contactNo, setContactNo] = useState('');
  const [faculty, setFaculty] = useState('');
  const [level, setLevel] = useState('');
  const [showQRCode, setShowQRCode] = useState(false);
  const user_id = Cookies.get('user_id');
  const [isLoading, setIsLoading] = useState(false);
  const baseurl = import.meta.env.VITE_BASE_URL;
  const [applied, setApplied] = useState(null);
  const [refNo, setRefNo] = useState('');

  useEffect(() => {
    isApplied();
  }, []);

  const isApplied = async () => {
    setIsLoading(true);
    try {
      let res = await fetch(`${baseurl}/api/isApplied/${user_id}`);
      const data = await res.json();
      if (!data[0]) {
        setApplied(false);
      } else {
        setApplied(true);
        setRefNo(data.ref);
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setIsLoading(false);
    }
  }

  const handleApply = async (event) => {
    if (contactNo) {
      event.preventDefault();
      setIsLoading(true);
      let res = await fetch(`${baseurl}/api/assign-qr`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({ contact : contactNo, user : user_id }),
      });
      const data = await res.json();
      if (data[0]) {
        setApplied(true);
        setRefNo(data.ref);
      } else {
        setError('error occured');
      }
      setIsLoading(false);
    } else {
      alert('All fields are required.');
      setShowQRCode(false);
    }
  };

  return (
    <div style={{height: '100vh', width: '100vw', color: '#000'}}>
    <Header />

    <div className="candidate-apply-container">
      {applied == false ? (
        <>
      <h2>Candidate Application</h2>

      <div style={{ display: 'flex', flexDirection: 'row' }}>
        <a href={`${baseurl}/storage/pdfs/by_law.pdf`} target="_blank" rel="noopener noreferrer">
          by_law.pdf
        </a>
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
      </>
      ) : (
        <div style={{display: 'flex', flexDirection: 'column', alignItems: 'center', marginTop: 60}}>
          <div style={{marginBottom: '10vh', fontSize: 20}}>You have applied to be a Candidate</div>
          <div style={{display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: '10vh'}}>
            <span style={{fontWeight: '500', fontSize: 25}}>Reference Number:</span>
            <span style={{fontWeight: '700', fontSize: 26}}>{refNo}</span>
          </div>
          <div style={{fontSize: 16, color: '#808080', margin: '5vh 30vw', textAlign: 'center'}}>You are required to bring your OUSL record book to the nearest OUSL Regional or Study Centre to verify your eligibility as a candidate.  
            Please ensure to complete this process before the election takes place, as this step is essential for the verification process.</div>
        </div>
      )}
    </div>
    <Footer />
    </div>
  );
}

export default CandidateApply;
