@extends('sidebar')
@section('content')
<style>
    .box {
        height: 15vh;
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
    <div style="display: flex; gap: 15px">
        @if(!session()->has('staff_logged_in'))
        <div class="box" id="toggle-election" style="height: 30vh; width: 30vw; font-size: 25px;">
            {{ Session::get('election_started', true) ? 'Election Started' : 'Start Election' }}
        </div>
        @endif
        <div class="box" style="display: flex; flex-direction: column; background-color: #82A69C">
            <span>Registered Voters</span><br>
            <span style="font-size: 20px;">{{$voters}}</span>
        </div>
        <div class="box" style="display: flex; flex-direction: column; background-color: #3C3A3D">
            <span>Registered Candidates</span><br>
            <span style="font-size: 20px;">{{$candidates}}</span>
        </div>
        <div class="box" style="display: flex; flex-direction: column; background-color: #A69C7D">
            <span>Eligible Candidates</span><br>
            <span style="font-size: 20px;">{{$eligibleCandidates}}</span>
        </div>
    </div>
    <div style="border: 1px solid #000; margin: 5% 0; padding: 5%; width: 30%">
        <span style="font-weight: 700">"By Law" Document</span>
        <span>Current PDF</span>
        <a href="/storage/pdfs/by_law.pdf" target="_blank">
            <div style="display: flex; flex-direction: row; justify-content:space-between; border: 1px solid #333; margin-bottom: 30px;" onclick="">
                by_law.pdf
                <img src="/assets/image/pdf_icon.png" style="height: 50px; width: 50px;" />
            </div>
        </a>
        <span style="font-weight: 700;">Upload "By Law" Document</span>
        <form id="uploadPDF">
            @csrf
            <input type="file" id="pdfFile" accept="application/pdf" name="bylaw" required>
            <button type="submit" style="margin-top: 10px">Upload</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const box = document.getElementById('toggle-election');

    box.addEventListener('click', function() {
        axios.post('/toggle-election')
            .then(response => {
                const data = response.data;

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

    document.getElementById('uploadPDF').addEventListener('submit', async function(event) {
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