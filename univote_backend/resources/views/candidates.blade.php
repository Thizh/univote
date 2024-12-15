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
                        <th>Faculty</th>
                        <th>Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($candidates as $candidate)
                        <tr>
                            <td>{{ $candidate->id }}</td>
                            <td>{{ $candidate->name }}</td>
                            <td>{{ $candidate->nic }}</td>
                            <td>{{ $candidate->email }}</td>
                            <td>{{ $candidate->faculty }}</td>
                            <td>{{ $candidate->level }}</td>
                            <td>
                                <form action="{{ route('candidates.delete', $candidate->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" style="background: none; color: blue; border: none; cursor: pointer; text-decoration: underline;">
                                        Delete
                                    </button>
                                </form>
                            </td>
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
    </script>
@endsection