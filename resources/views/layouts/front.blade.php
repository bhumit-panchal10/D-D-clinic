<!DOCTYPE html>
<html lang="en">


@include('common.front.fronthead')

<body id="page-top">

    @include('common.front.frontheader')

    @yield('content')

    @include('common.front.frontfooter')

    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

    @include('common.front.frontfooterjs')

    @yield('scripts')


</body>



</html>
