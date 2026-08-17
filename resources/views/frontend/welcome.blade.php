<!DOCTYPE html>
<html>
<head>
    <title>Multi Auth Example</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">MyApp</a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">

                    {{-- Guest (not logged in) --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">User Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.login') }}">Admin Login</a>
                        </li>
                    @endguest

                    {{-- Authenticated User --}}
                    @auth
                        @if(Auth::user()->role === 'user')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">User Dashboard</a>
                            </li>
                        @elseif(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>






