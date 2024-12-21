@extends('sidebar')
@if(!session()->has('staff_logged_in'))
@section('content')
    <h1 style="padding: 5%;">Users Details</h1>
    <div style="display: flex; flex-direction: row; gap: 2rem;">
        <!-- User Creation Form -->
        <div class="card1" style="flex: 1; padding-left: 5%">
            <form action="/addstaff" method="POST" name="userForm">
                @csrf
                <div class="text-field">
                    <label for="username">*Username</label>
                    <input id="username" type="text" name="username" required>
                </div>
                <div class="text-field">
                    <label for="password">*Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div class="text-field">
                    <label for="utype">*User Type</label><br>
                    <select id="utype" name="utype" required>
                        <option disabled value="" selected>Select one user type</option>
                        <option value="admin" >Admin</option>
                        <option value="staff" >Staff</option>
                    </select>
                </div>
                <button class="create-user-btn" type="submit" style="margin-top: 10%;">Add User</button>
            </form>
        </div>

        <div style="flex: 2;">
            <table id="usersTable" border="1" style="width: 65vw; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Name</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->user_type }}</td>
                            <td>
                                <form action="{{ route('user.delete', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" style="background: none; color: blue; border: none; cursor: pointer; text-decoration: underline;">
                                        <ion-icon name="trash-outline"></ion-icon>
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
            $('#usersTable').DataTable();
            $('.js-example-basic-single').select2();
        });

    </script>
@endsection
@endif