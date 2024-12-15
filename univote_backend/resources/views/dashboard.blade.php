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
</head>
<body>
    <div class="container-fluid mt-4">
        <div>
            <div class="box" id="toggle-election">
                {{ Session::get('election_started', false) ? 'Election Started' : 'Start Election' }}
            </div>
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
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to toggle election state. Please try again.');
                });
        });

        // Set Axios CSRF token header
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>

@endsection
