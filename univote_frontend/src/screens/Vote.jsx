import React, { useState } from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import { FaSpinner } from 'react-icons/fa'; // Import spinner icon from react-icons
import QRCode from 'react-qr-code'; // Import QR code component
import '../css/Vote.css';



function Vote() {
  const [isLoading, setIsLoading] = useState(false);
  const [selectedCandidate, setSelectedCandidate] = useState(null); // Single candidate state

  const candidates = [
    "Student Union 1", "Sports Council 1", "Student Union 2", "Sports Council 2",
    "Student Union 3", "Sports Council 3", "Student Union 4", "Sports Council 4"
  ];

  const handleCandidateClick = (candidate) => {
    setSelectedCandidate(candidate); // Select the clicked candidate
    setIsLoading(false); // Reset loading state when a new candidate is selected
  };



  return (
    <div className="vote-container">
  
      <div className="voting-section">
        <h2 className="heading">Select Candidate</h2>
        
        <div className="candidates">
          {candidates.map((candidate, index) => (
            <div
              key={index}
              className={`election-card ${selectedCandidate === candidate ? "selected" : ""}`}
              onClick={() => handleCandidateClick(candidate)} // Set the clicked candidate as selected
            >
              <div className="icon-placeholder"></div>
              <p>{candidate}</p>
            </div>
          ))}
        </div>

        <div className="buttons-container">
          <button 
             className="vote-button"
            onClick={setIsLoading} // Handle vote button click
            disabled={!selectedCandidate} // Disable button if no candidate is selected
          >
            {isLoading ? <FaSpinner className="spinner" size={20} /> : "Vote"}
          </button>
         
        </div>       
      </div>

      {/* Conditionally render the QR code when isLoading is true */}
      {isLoading && selectedCandidate && (
        <div className="qr-container">
          <QRCode value={`https://example.com/vote/${selectedCandidate}`} size={100} />
        </div>
      )}

    
    </div>
  );
}

export default Vote;
