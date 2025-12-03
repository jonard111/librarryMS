<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DNSC LMS - Login</title>
  @vite(['resources/css/style.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="auth-hero-bg d-flex justify-content-center align-items-center vh-100">

<div class="container">
  <div class="row justify-content-center align-items-center">

    <!-- Left Side -->
    <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
      <div class="d-flex flex-column align-items-center align-items-md-start">
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" class="img-fluid mb-3" style="max-height: 160px;">
        <h1 class="text-gradient">Davao del Norte State College</h1>
        <h2 class="text-gradient">Library Management System</h2>
        <p class="mt-3 text-muted">
          Login to access your personalized dashboard, manage books, and 
          track your library activity.
        </p>
      </div>
    </div>

    <!-- Right Side -->
    <div class="col-md-5">
      <div class="auth-card card p-5 shadow-lg">
        <h2 class="text-gradient text-center mb-4">Login</h2>

        <!-- Display validation errors -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Display success message -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
          @csrf
          <!-- Role -->
          <div class="mb-3">
            <select class="form-select" id="role" name="role" required>
              <option selected disabled>Select Role</option>
              <option value="student">Student</option>
              <option value="faculty">Faculty</option>
              <option value="headlibrarian">Headlibrarian</option>
              <option value="assistant">Assistant</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required value="{{ old('email') }}">
          </div>

          <!-- Password -->
          <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
          </div>

          <button type="submit" class="btn btn-gradient-green w-100 text-white">Login</button>
        </form>

        <p class="mt-3 text-center">Don't have an account? <a href="{{ route('register.form') }}">Register</a></p>
      </div>
    </div>

  </div>
</div>

</body>
</html>
