import React, { useState, useEffect } from "react";
import { Text, View, StyleSheet, Button } from "react-native";
import { CameraView } from "expo-camera";
import baseurl from '../baseurl';

export default function Scanner() {
  const [scanned, setScanned] = useState(false);
  const [nic, setNic] = useState('');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [faculty, setFaculty] = useState('');
  const [level, setLevel] = useState('');
  const [error, setError] = useState('');
  const [qrData, setQrData] = useState('');

  const handleBarcodeScanned = async ({ type, data }) => {
    // alert(`Bar code with type ${type} and data ${data} has been scanned!`);
    setQrData(data)
    const res = await fetch(`${baseurl}/api/qrsend`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ data }),
    });
    const resdata = await res.json();
    console.log("resdata", resdata);
    if (resdata[0]) {
      const details = resdata['details'];
      setScanned(true);
      setNic(details['nic']);
      setName(details['name']);
      setEmail(details['email']);
      setFaculty(details['faculty']);
      setLevel(details['level']);
    }
  };

  const setVote = async () => {
    const res = await fetch(`${baseurl}/api/acceptv`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ data: qrData }),
    });
    const resdata = await res.json();
    console.log("qr", qrData);
    console.log("resdata", resdata);
    if (resdata[0]) {
      setQrData('');
      setScanned(false);
      setNic('');
      setName('');
      setEmail('');
      setFaculty('');
      setLevel('');
    } else {
      setError('Already Scanned');
    }
  }

  return (
    <View style={styles.container}>
      <CameraView
        onBarcodeScanned={scanned ? undefined : handleBarcodeScanned}
        barcodeScannerSettings={{
          barcodeTypes: ["qr", "pdf417"],
        }}
        style={StyleSheet.absoluteFillObject}
      />
      {scanned && (
        <View style={styles.detailsContainer}>
        <Text>NIC: {nic}</Text>
        <Text>Name: {name}</Text>
        <Text>Email: {email}</Text>
        <Text>Faculty: {faculty}</Text>
        <Text>Level: {level}</Text>
        <View style={{height: 30}}>
          
        </View>
        <Button title={"Accept Vote"} onPress={setVote} style={styles.button}/>
        <View style={{height: 30}}>
          
        </View>
        <Button title={"Tap to Scan Again"} onPress={() => setScanned(false)} style={styles.button}/>
        </View>
     )}
     {/* {error && (
     <Text style={styles.error}>{error}</Text>
     )} */}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    flexDirection: "column",
    justifyContent: "center",
  },
  error: {
    backgroundColor: "white",
    padding: 16,
    borderRadius: 8,
    position: "absolute",
    justifyContent: 'center',
    marginLeft: 'auto',
    marginRight: 'auto',
    width: "100%",
  },
  detailsContainer: {
    backgroundColor: "white",
    padding: 16,
    borderRadius: 8,
    position: "absolute",
    justifyContent: 'center',
    marginLeft: 'auto',
    marginRight: 'auto',
    width: "100%",
  },
  button: {
    flex: 1,
    width: '40vw',
  },
});