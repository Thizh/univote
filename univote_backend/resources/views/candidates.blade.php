@extends('sidebar')

@section('content')
    <h1 style="padding: 5%;">Candidate Details</h1>
    <div style="display: flex; flex-direction: row; gap: 2rem;">
        <!-- Candidate Creation Form -->
        <div class="card1" style="flex: 1; padding-left: 5%">
            <form action="/addcandidate" method="POST" name="userForm">
                @csrf
                <div class="text-field">
                    <label for="nic">*NIC</label>
                    <input id="nic" type="text" name="nic" required>
                </div>
                <div class="text-field">
                    <label for="emp_id">*Contact Number</label>
                    <input id="contact" type="text" name="contact" required>
                </div>
                <button class="create-user-btn" type="submit">Add Candidate</button>
            </form>
        </div>

        <!-- Candidate Table -->
        <div style="flex: 2;">
            <table id="candidateTable" border="1" style="width: 65vw; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Name</th>
                        <th>NIC</th>
                        <th>Email</th>
                        <th>Reg  No</th>
                        <th>Faculty</th>
                        <th>Level</th>
                        <th>Eligiblity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($candidates as $candidate)
                        <tr>
                            <td>{{ $candidate->id }}</td>
                            <td>{{ $candidate->name }}</td>
                            <td>{{ $candidate->nic }}</td>
                            <td>{{ $candidate->email }}</td>
                            <td>{{ $candidate->reg_no }}</td>
                            <td>{{ $candidate->faculty }}</td>
                            <td>{{ $candidate->level }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                <input type="checkbox" name="eligible" style="height: 15px; width: 15px;" data-id="{{ $candidate->id }}" {{ $candidate->eligible ? 'checked' : '' }} class="eligible-checkbox"/>
                            </td>
                            <!-- <td>
                                <form action="{{ route('candidates.delete', $candidate->id) }}" method="POST" style="display: inline;">
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
    </div>

    <script>

        $(document).ready(function() {
            $('#candidateTable').DataTable();
            $('.js-example-basic-single').select2();
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

    const canId = checkbox.data('id');
    const isChecked = checkbox.is(':checked') ? true : false;

    $.ajax({
        url: '/candidate-eligible_checked',
        method: 'POST',
        data: {
            can_id: canId,
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