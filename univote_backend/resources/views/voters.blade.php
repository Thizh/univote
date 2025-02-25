@extends('sidebar')
@section('content')
<h1 style="padding: 3%;">Voter Details</h1>
<div style="display: flex; flex-direction: row;">
    <table id="votersTable" style="width: 85vw; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px;">Ref</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Name</th>
                <th style="border: 1px solid #ddd; padding: 8px;">NIC</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Email</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Reg No.</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Faculty</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Level</th>
                <!-- <th style="border: 1px solid #ddd; padding: 8px;">Eligible</th> -->
                <!-- <th style="border: 1px solid #ddd; padding: 8px;">Action</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($voters as $voter)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->id }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->name }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->nic }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->email }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->reg_no }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->faculty }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $voter->level }}</td>
                <!-- <td style="border: 1px solid #ddd; padding: 8px;">
                    <input type="checkbox" name="eligible" style="height: 15px; width: 15px;" data-id="{{ $voter->id }}" {{ $voter->eligible ? 'checked' : '' }} class="eligible-checkbox"/>
                </td> -->
                <!-- <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                    <form action="{{ route('candidates.delete', $voter->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" style="background: none; color: blue; border: none; cursor: pointer; text-decoration: underline;">
                            Delete
                        </button>
                    </form>
                </td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#votersTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
        });
    });

    window.onload = function() {
        document.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
            if (!checkbox.hasAttribute('checked')) {
                checkbox.checked = false;
            } else {
                checkbox.checked = true;
            }
        });
    };

    $(document).on('change', '.eligible-checkbox', function() {
    const checkbox = $(this);

    const voterId = checkbox.data('id');
    const isChecked = checkbox.is(':checked') ? 1 : 0;

    $.ajax({
        url: '/voter-eligible_checked',
        method: 'POST',
        data: {
            voter_id: voterId,
            eligible_checked: isChecked,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr) {
            alert('Failed to update status.');
        }
    });
});

</script>
@endsection
