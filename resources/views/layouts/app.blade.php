<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Include AdminLTE CSS and dependencies from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OverlayScrollbars/1.13.1/css/OverlayScrollbars.min.css">

    <!-- Custom styles can go here -->
    @stack('styles') <!-- Push additional styles in specific views -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('includes.header') <!-- Include header -->

        <!-- Sidebar -->
        @include('includes.sidebar') <!-- Include sidebar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper p-2">
            <!-- Content Header -->
            @yield('content-header') <!-- Section for the content header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content') <!-- Section for the main content -->
                </div>
            </section>
        </div>

        <!-- Main Footer -->
        @include('includes.footer') <!-- Include footer -->

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlayScrollbars/1.13.1/js/jquery.overlayScrollbars.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts') <!-- Push additional scripts in specific views -->
</body>
</html>
