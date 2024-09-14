from flask import Flask, request, jsonify
from bs4 import BeautifulSoup
import requests

app = Flask(__name__)

@app.route('/scrape', methods=['POST'])
def scrape_data():
    nic_number = request.json.get('nic')

    url = 'https://reginfo.ou.ac.lk/redirect.php'
    
    payload = {'nic': nic_number}
    response = requests.post(url, data=payload)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')
        
        name = soup.find(text="Name :").find_next('b').text
        row = soup.find('tr', {'class': 'evenrowcolor'})
        reg_no = row.find_all('td')[0].text.strip()
        
        return jsonify({
            'name': name,
            'reg_no': reg_no
        })
    else:
        return jsonify({'error': 'Failed to fetch data'}), 500


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
