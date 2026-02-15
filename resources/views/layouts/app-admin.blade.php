<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Koleksi Buku')</title>
    
    <!-- Header -->
    @include('layouts.partials.header')
    
    <!-- Style Global -->
    @include('layouts.partials.styles')
    
    <!-- Style Page -->
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        <!-- Navbar -->
        @include('layouts.partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            
            <div class="main-panel">
                <!-- Content -->
                <div class="content-wrapper">
                    @yield('content')
                </div>
                
                <!-- Footer -->
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>
    
    <!-- Javascript Global -->
    @include('layouts.partials.scripts')
    
    <!-- Javascript Page -->
    @stack('scripts')
</body>
</html>