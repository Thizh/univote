<!DOCTYPE html>
<html lang="en">

<head>
    <script type="module">

        import Echo from '/js/echo.js';

        import {Pusher} from '/js/pusher.js';

        window.Pusher = Pusher

        window.Echo = new Echo({
            broadcaster: "pusher",
            key: "8e49cb8e60d21e0478dc",
            cluster: "ap2",
            forceTLS: true,
            enabledTransports: ['ws', 'wss'], // Add fallbacks
            disabledTransports: ['xhr_polling', 'xhr_streaming'],
        });

        window.Echo.channel('screen-updates')
            .listen('ScreenUpdated', (e) => {
                    console.log(e)
            })
            .subscribed(() => {
                console.log('Subscribed to channel screen-updates');
            })
            .listenToAll((event, data) => {
                console.log(event, data)
            });


        console.log("websokets in use")

    </script> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.jquery.min.js"></script>           

</head>

<body>
    <div class="d-flex" id="wrapper" style="width: 100%">
        <!-- Sidebar -->
        <div class="bg-light border-end" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4">
                <img src="{{ asset('assets/image/logo.png') }}" alt="Logo" class="logo mb-2">
                <h5>Admin Panel</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                @if (session('election_started'))
                <a href="{{ route('admin.acceptvote') }}" class="list-group-item list-group-item-action">Accept Vote</a>
                @endif
                <a href="{{ route('admin.candidates') }}" class="list-group-item list-group-item-action">Candidate Details</a>
                <a href="{{ route('admin.voters') }}" class="list-group-item list-group-item-action">Voters Details</a>
                <a href="{{ route('admin.results') }}" class="list-group-item list-group-item-action">Result</a>
                @if (!session()->has('staff_logged_in'))
                <a href="{{ route('admin.createstaff') }}" class="list-group-item list-group-item-action">Create User</a>
                @endif
            </div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <div class="navbar-collapse">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a href="{{ route('admin.logout') }}">
                                    Logout
                                </a>
                                <!-- <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile Settings</a>
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
                                </div> -->
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div>
                @yield('content')
            </div>

        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("wrapper").classList.toggle("toggled");
        });
    </script> -->

</body>

</html>