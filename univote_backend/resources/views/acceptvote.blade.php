@extends('sidebar')
@section('content')


@if($voter)
<div style="font-family: Arial, sans-serif; max-width: 400px; margin: 20px auto; padding: 15px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div style="margin-bottom: 20px;">
        <input type="hidden" value="{{ $voter->vote_id }}" id="vote_id"/>
        <span style="display: block; font-size: 14px; color: #555;">NIC:</span>
        <span style="font-weight: bold; color: #333;">{{$voter->nic}}</span><br />
        <span style="display: block; font-size: 14px; color: #555;">Name:</span>
        <span style="font-weight: bold; color: #333;">{{$voter->name}}</span><br />
        <span style="display: block; font-size: 14px; color: #555;">Email:</span>
        <span style="font-weight: bold; color: #333;">{{$voter->email}}</span><br />
        <span style="display: block; font-size: 14px; color: #555;">Faculty:</span>
        <span style="font-weight: bold; color: #333;">{{$voter->faculty}}</span><br />
        <span style="display: block; font-size: 14px; color: #555;">Level:</span>
        <span style="font-weight: bold; color: #333;">{{$voter->level}}</span>
    </div>
    <div style="display: flex; gap: 10px; justify-content: center;">
        <button style="flex: 1; padding: 10px; font-size: 14px; border: none; border-radius: 5px; background-color: #28a745; color: white; cursor: pointer; transition: background-color 0.3s;" >
            Accept
        </button>
        <button style="flex: 1; padding: 10px; font-size: 14px; border: none; border-radius: 5px; background-color: #dc3545; color: white; cursor: pointer; transition: background-color 0.3s;">
            Reject
        </button>
    </div>
</div>
@else
<div>
    <span>Scan the QR from the mobile app</span>
</div>
@endif

<script>

    const endpoint = '/api/accept-or-reject';

    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', () => {
            // Get the action from the button text
            const action = button.textContent.trim().toLowerCase();
            const vote_id = document.getElementById('vote_id').value;

            // Make an AJAX POST request
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action, vote_id}),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

</script>



    

@endsection