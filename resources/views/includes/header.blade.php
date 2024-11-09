<!-- resources/views/includes/header.blade.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <!-- Add header content like user info, logout, etc. -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('auth.logout') }}">Logout</a>
        </li>
    </ul>
</nav>
