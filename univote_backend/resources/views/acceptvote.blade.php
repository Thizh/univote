@extends('sidebar')
@section('content')


@if($voter)
<div style="font-family: Arial, sans-serif; max-width: 400px; margin: 20px auto; padding: 15px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
  <div style="margin-bottom: 20px;">
    <input type="hidden" value="{{ $voter->vote_id }}" id="vote_id" />
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
    <button style="flex: 1; padding: 10px; font-size: 14px; border: none; border-radius: 5px; background-color: #28a745; color: white; cursor: pointer; transition: background-color 0.3s;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#acceptModal">
      Accept
    </button>
    <button style="flex: 1; padding: 10px; font-size: 14px; border: none; border-radius: 5px; background-color: #dc3545; color: white; cursor: pointer; transition: background-color 0.3s;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rejectModal">
      Reject
    </button>
  </div>
</div>
@else
<div>
  <span>Scan the QR from the mobile app</span>
</div>
@endif

<div class="modal fade" id="acceptModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Accept the vote</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to accept this vote?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary vote-action" data-action="accepted">Accept</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rejectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Reject the vote</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to reject this vote?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger vote-action" data-action="rejected">Reject</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $(".vote-action").click(function () {
      var vote_id = $("#vote_id").val();
      var action = $(this).data("action");

      $.ajax({
        url: "/votestat",
        type: "POST",
        data: {
          vote_id: vote_id,
          action: action,
          _token: "{{ csrf_token() }}"
        },
        success: function (response) {
          // alert(response.message); 
          location.reload(); 
        },
        error: function (xhr) {
          // alert("An error occurred: " + xhr.responseText);
        }
      });
    });
  });
</script>




@endsection