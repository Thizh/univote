import React, { useState } from 'react';
import QrReader from 'react-qr-scanner';

const AcceptVote = () => {
  const [scannedData, setScannedData] = useState('');
  const [decryptedData, setDecryptedData] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleScan = (data) => {
    if (data) {
      setScannedData(data.text);  // Access scanned data with .text property

      setLoading(true);

      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      
      // Send encrypted data to the backend (Laravel)
      fetch('http://192.168.1.107:8000/api/decrypt-vote', {
        method: 'get',
        headers: {
          'X-CSRF-Token': csrfToken,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          encrypted_data: data.text, // QR code data (Base64 encoded)
        }),
      })
        .then((response) => response.json())
        .then((responseData) => {
          setLoading(false);
          if (responseData.status === 'success') {
            setDecryptedData(responseData.data);
          } else {
            console.error('Decryption failed');
          }
        })
        .catch((error) => {
          setLoading(false);
          console.error('Error:', error);
        });
    }
  };

  return (
    <div>
      <h1>Admin QR Code Scanner</h1>
      <QrReader
        delay={300}
        style={{ width: '100%' }}
        onScan={handleScan}
        onError={(err) => console.error(err)}
      />
      
      {scannedData && <div><h3>Scanned Data:</h3><pre>{scannedData}</pre></div>}
      {loading && <p>Decrypting...</p>}
      
      {decryptedData && (
        <div className="decryptedDataContainer">
          <h3>Decrypted Data:</h3>
          <p>User Name: {decryptedData.user.userName}</p>
          <p>User ID: {decryptedData.user.userId}</p>
          <p>Vote: {decryptedData.voteDetails.candidate}</p>
        </div>
      )}
    </div>
  );
};

export default AcceptVote;
