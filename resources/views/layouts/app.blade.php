<!doctype html>
<html lang="en" data-layout="vertical" data-layout-style="default" data-layout-position="fixed" data-topbar="light" 
    data-sidebar-size="lg" data-layout-mode="light" data-layout-width="fluid" data-preloader="disable">


{{-- Include Head --}}
@include('common.head')

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Topbar -->
        @include('common.header')
        <!-- End of Topbar -->

        <!-- Sidebar -->
        @include('common.sidebar')
        <!-- End of Sidebar -->

        @yield('content')

        @include('common.footer')

    </div>

    @include('common.footerjs')

    @yield('scripts')



</body>

</html>
