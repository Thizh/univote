import React, { useEffect, useState } from 'react';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';
import { Bar } from 'react-chartjs-2';
import Header from '../components/Header';
import Footer from '../components/Footer';
import '../css/stats.css';
import { useSelector } from 'react-redux';

const Statistics = () => {
  const baseurl = import.meta.env.VITE_BASE_URL;
  const [voteData, setVoteData] = useState([]);
  const election = useSelector((state) => state.user.isElectionStarted);

  useEffect(() => {
    getStats();
  }, []);

  const getStats = async () => {
    let res = await fetch(`${baseurl}/api/getstats`);
    const data = await res.json();
    if (data[0]) {
      console.log("stats", data.stats);
      setVoteData(data.stats);
    } else {
      console.log("err");
    }
  };

  // Find the leading icon based on the highest count, handling ties
  let leadingVote = 'no data';
  if (voteData.length > 0) {
    const maxCount = Math.max(...voteData.map(vote => vote.can_count));
    const topCandidates = voteData.filter(vote => vote.can_count === maxCount);

    if (topCandidates.length === 1) {
      leadingVote = topCandidates[0].can_name;
    } else {
      leadingVote = `<span style="font-size: 14px; color: black; fontWeight: 600 ">No one is leading. Tied candidates are: ${topCandidates.map(c => c.can_name).join(', ')}</span>`;
    }
  }

  return (
    <div className='maindiv'>
      <Header />
      <div className="statistics-container">
        {/* Vote Counts Section */}
        {election ? (
          <>
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
              <div className="leading-icon" dangerouslySetInnerHTML={{ __html: leadingVote }}></div>
            </div>
          </>
        ) : (
          <div style={{ width: '100%', height: '100%' }}>
            <div style={{ textAlign: 'center', fontSize: 35, fontWeight: 600 }}>Election is not Started yet.</div>
          </div>
        )}
      </div>
      <Footer />
    </div>
  );
};

export default Statistics;
