<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/adminlogin.css') }}">
</head>
<body>
<div class="login-container">
    <div class="text-center mb-4 d-flex align-items-center justify-content-center">
        <img src="{{ asset('assets/image/logo.png') }}" alt="Logo" class="logo">
        <h2 class="mb-0">Admin Login</h2>
    </div>
    <form action="{{ route('admin.handleLogin') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        @if(session('error'))
            <div class="alert" style="color: red">{{ session('error') }}</div>
        @endif
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>
