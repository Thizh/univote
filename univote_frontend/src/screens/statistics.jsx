import React from 'react';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';
import { Bar } from 'react-chartjs-2';
import Header from '../components/Header';
import Footer from '../components/Footer';
import '../css/stats.css';

const Statistics = () => {
  const voteData = [
    { icon: '⚓', count: 200 },
    { icon: '📷', count: 140 },
    { icon: '👁️', count: 70 },
    { icon: '⚡', count: 86 },
    { icon: '✒️', count: 32 },
  ];

  // Find the leading icon based on the highest count
  const leadingVote = voteData.reduce((max, current) =>
    current.count > max.count ? current : max
  );

  return (
    <div className='Main Div'>
      <Header />
    <div className="statistics-container">
      {/* Vote Counts Section */}
      <div className="vote-counts">
        <h3>Vote Counts</h3>
        {voteData.map((data, index) => (
          <div key={index} className="vote-row">
            <div className="vote-icon">{data.icon}</div>
            <div className="vote-bar" style={{ width: `${data.count}px` }}></div>
            <div className="vote-number">{data.count}</div>
          </div>
        ))}
      </div>

      {/* Leading Section */}
      <div className="leading-section">
        <h3>Leading</h3>
        <div className="leading-icon">{leadingVote.icon}</div>
      </div>
    </div>
    <Footer />
    </div>
  );
};

export default Statistics;