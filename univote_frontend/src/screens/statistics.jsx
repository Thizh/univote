import React, { useEffect, useState } from 'react';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';
import { Bar } from 'react-chartjs-2';
import Header from '../components/Header';
import Footer from '../components/Footer';
import '../css/stats.css';

const Statistics = () => {
  const baseurl = import.meta.env.VITE_BASE_URL;
  const [voteData, setVoteData] = useState([]);

  useEffect(() => {
    getStats();
    
  }, []);

  const getStats = async () => {
    let res = await fetch(`${baseurl}/api/getstats`);
    const data = await res.json();
    if (data[0]) {
      console.log("ststs", data.stats);
      setVoteData(data.stats);
    } else {
      console.log("err");
    }
  }

  // Find the leading icon based on the highest count
  const leadingVote = voteData.length > 0 
  ? voteData.reduce((max, current) => 
      current.count > max.count ? current : max
    ) 
  : "no data";


  return (
    <div className='maindiv'>
      <Header />
    <div className="statistics-container">
      {/* Vote Counts Section */}
      <div className="vote-counts">
        <h3>Vote Counts</h3>
        {voteData.map((data, index) => (
          <div key={index} className="vote-row">
            <div className="vote-icon">{data.can_name}</div>
            <div className="vote-bar" style={{ width: `${data.can_count}px` }}></div>
            <div className="vote-number">{data.can_count}</div>
          </div>
        ))}
      </div>

      {/* Leading Section */}
      <div className="leading-section">
        <h3>Leading</h3>
        <div className="leading-icon">{voteData ? leadingVote.can_name : 'no data'}</div>
      </div>
    </div>
    <Footer />
    </div>
  );
};

export default Statistics;