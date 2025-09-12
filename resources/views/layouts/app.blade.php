<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Alternatif Coffee - @yield('title')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />

    {{-- ✅ Tambahkan baris ini untuk CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1001;
            backdrop-filter: blur(10px);
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin-right: 20px;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 8px;
        }

        .menu-icon:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-icon.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .menu-line {
            width: 20px;
            height: 2px;
            background-color: white;
            margin: 2px 0;
            transition: all 0.3s ease;
            border-radius: 1px;
        }

        .menu-icon.active .menu-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px) !important;
        }

        .menu-icon.active .menu-line:nth-child(2) {
            opacity: 0 !important;
        }

        .menu-icon.active .menu-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px) !important;
        }

        .logo {
            width: 37px;
            height: 37px;
            margin-right: 15px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 43px;
            height: 43px;
            margin-top: -2px;
            object-fit: contain;
        }

        .brand-info {
            flex: 1;
            color: white;
        }

        .brand-info h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        .brand-info p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
            line-height: 1.2;
        }

        /* Sidebar Styles */
        .drawer {
            width: 280px;
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: -280px !important; /* Pastikan sidebar tersembunyi secara default */
            top: 0;
            z-index: 1000;
            transition: left 0.3s ease;
            margin-top: 70px;
            /* Memastikan drawer tidak menghalangi konten */
            pointer-events: auto;
        }

        .drawer.active {
            left: 0 !important; /* Pastikan sidebar muncul ketika active */
        }

        /* Memastikan konten drawer tetap bisa diklik */
        .drawer * {
            pointer-events: auto;
            z-index: 1001;
        }

        /* Overlay */
        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: transparent;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none; /* Memungkinkan klik melalui overlay */
        }

        .drawer-overlay.active {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto; /* Hanya aktif ketika drawer terbuka di mobile */
        }

        /* Container */
        .container {
            margin-top: 70px;
            padding: 20px;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
            position: relative;
            z-index: 1; /* Memastikan konten tetap di atas overlay */
        }

        .container.drawer-open {
            margin-left: 280px !important;
        }

        /* Memastikan semua elemen interaktif tetap bisa diklik */
        .container * {
            position: relative;
            z-index: 2;
        }

        /* Memastikan form, button, dan elemen interaktif lainnya tetap berfungsi */
        .container button,
        .container input,
        .container select,
        .container textarea,
        .container a,
        .container .clickable {
            position: relative;
            z-index: 3;
            pointer-events: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .drawer {
                left: -280px !important;
            }
            
            .container.drawer-open {
                margin-left: 0 !important;
            }
            
            .drawer.active {
                left: 0 !important;
            }

            /* Di mobile, overlay harus bisa diklik untuk menutup drawer */
            .drawer-overlay.active {
                pointer-events: auto;
            }
        }

        @media (min-width: 769px) {
            .drawer {
                left: -280px !important; /* Sidebar tersembunyi secara default di desktop */
            }
            
            .drawer.active {
                left: 0 !important; /* Sidebar muncul hanya ketika active */
            }
            
            .container.drawer-open {
                margin-left: 365px !important;
            }

            /* Di desktop, overlay tidak menghalangi interaksi */
            .drawer-overlay.active {
                pointer-events: none;
            }
        }
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
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <div class="brand-info">
            <h1>Alternatif Coffee</h1>
            <p>
                @auth
                    {{ Auth::user()->name }}
                @else
                    Guest
                @endauth
            </p>
        </div>
    </div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Overlay -->
    <div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

    <!-- Content -->
    <div class="container main-content" id="container">
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
            
            // Update menu grid columns based on sidebar state
            updateMenuGrid();
            
            // Update interactive elements setelah drawer dibuka/ditutup
            updateInteractiveElements();
            
            // Hapus overflow hidden agar fitur tetap bisa diklik
            // document.body.style.overflow = isDrawerOpen ? 'hidden' : 'auto';
        }

        function closeDrawer() {
            if (isDrawerOpen) toggleDrawer();
        }

        function updateMenuGrid() {
            const menuGrids = document.querySelectorAll('.menu-grid');
            menuGrids.forEach(grid => {
                if (isDrawerOpen) {
                    grid.style.gridTemplateColumns = 'repeat(3, 1fr)';
                } else {
                    grid.style.gridTemplateColumns = 'repeat(4, 1fr)';
                }
            });
        }

        // Initialize menu grid on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateMenuGrid();
            
            // Memastikan semua elemen interaktif tetap berfungsi
            ensureInteractiveElements();
        });

        function ensureInteractiveElements() {
            // Memastikan semua elemen interaktif tetap bisa diklik
            const interactiveElements = document.querySelectorAll('button, input, select, textarea, a, .clickable');
            interactiveElements.forEach(element => {
                element.style.pointerEvents = 'auto';
                element.style.position = 'relative';
                element.style.zIndex = '3';
            });
        }

        // Memastikan elemen interaktif tetap berfungsi setelah drawer dibuka/ditutup
        function updateInteractiveElements() {
            setTimeout(() => {
                ensureInteractiveElements();
            }, 100);
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                document.getElementById('container').classList.remove('drawer-open');
                updateMenuGrid();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && isDrawerOpen) {
                closeDrawer();
            }
        });

        // Prevent back button access after logout
        window.addEventListener('load', function() {
            // Clear any cached data and prevent back navigation
            if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                window.location.href = '{{ route("login") }}';
            }
        });

        // Disable browser back button for authenticated pages
        window.addEventListener('popstate', function(event) {
            // If user tries to go back, redirect to appropriate page based on user type
            @auth
                @if(Auth::user()->usertype === 'admin')
                    window.location.href = '{{ route("dashboard") }}';
                @else
                    window.location.href = '{{ route("kasir") }}';
                @endif
            @else
                window.location.href = '{{ route("login") }}';
            @endauth
        });
    </script>
</body>
</html>
