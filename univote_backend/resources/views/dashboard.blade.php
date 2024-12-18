@extends('sidebar')
@section('content')
<style>
        .box {
            height: 10vh;
            width: 15vw;
            background-color: grey;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-weight: bold;
            text-align: center;
        }
    </style>
    <div class="container-fluid mt-4">
        <div>
            <div class="box" id="toggle-election">
                {{ Session::get('election_started', false) ? 'Election Started' : 'Start Election' }}
            </div>
        </div>
        <div style="border: 1px solid #000; margin: 5% 0; padding: 5%; width: 30%">
            <span style="font-weight: 700">Upload By Law Document</span>
            <span>Current PDF</span>
            <div style="display: flex; flex-direction: row; border: 1px solid #333" onclick="">
                <a href="/storage/pdfs/by_law.pdf" target="_blank">by_law.pdf</a>
            </div>
            <form id="uploadPDF">
                @csrf
                <input type="file" id="pdfFile" accept="application/pdf" name="bylaw" required>
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const box = document.getElementById('toggle-election');

        box.addEventListener('click', function () {
            axios.post('/toggle-election')
                .then(response => {
                    const data = response.data;

                    // Update the text and style based on the election state
                    if (data.election_started) {
                        box.textContent = 'Election Started';
                    } else {
                        box.textContent = 'Start Election';
                    }
                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to toggle election state. Please try again.');
                });
        });

        // Set Axios CSRF token header
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('uploadPDF').addEventListener('submit', async function (event) {
            event.preventDefault();

            const fileInput = document.getElementById('pdfFile');
            const file = fileInput.files[0];

            if (!file) {
                alert('Please select a file!');
                return;
            }

            if (file.type !== 'application/pdf') {
                alert('Please upload a valid PDF file.');
                return;
            }

            const base64String = await convertToBase64(file);

            fetch('/api/bylawupload', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fileName: file.name,
                    fileData: base64String,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        function convertToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
    </script>

@endsection
