<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternatif Coffee - @yield('title')</title>
    <style>
        /* [Existing styles from your files can be moved here] */
        .drawer { /* Sidebar styles */ }
        .header { /* Header styles */ }
        /* Add other necessary styles */
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="menu-icon" id="menu-icon" onclick="toggleDrawer()">
            <div class="menu-line"></div>
            <div class="menu-line"></div>
            <div class="menu-line"></div>
        </div>
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 30px; height: 30px;">
        </div>
        <div class="brand-info">
            <h1>Alternatif Coffee</h1>
            <p>Lukmin Tajinan</p>
        </div>
    </div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Overlay -->
    <div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

    <!-- Content -->
    <div class="container" id="container">
        @yield('content')
    </div>

    <script>
        let isDrawerOpen = false;

        function toggleDrawer() {
            const drawer = document.getElementById('drawer');
            const overlay = document.getElementById('drawer-overlay');
            const menuIcon = document.getElementById('menu-icon');
            const container = document.getElementById('container');

            isDrawerOpen = !isDrawerOpen;
            drawer.classList.toggle('active');
            overlay.classList.toggle('active');
            menuIcon.classList.toggle('active');
            container.classList.toggle('drawer-open');
            document.body.style.overflow = isDrawerOpen ? 'hidden' : 'auto';
        }

        function closeDrawer() {
            if (isDrawerOpen) toggleDrawer();
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && !isDrawerOpen) {
                document.getElementById('container').classList.add('drawer-open');
            } else if (window.innerWidth <= 768) {
                document.getElementById('container').classList.remove('drawer-open');
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && isDrawerOpen) {
                closeDrawer();
            }
        });
    </script>
</body>
</html>