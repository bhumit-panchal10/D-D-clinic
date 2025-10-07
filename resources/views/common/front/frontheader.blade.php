<style>
    ul.dropdown-menu.cust-nav {
    height: 250px !important;
    overflow: scroll;
    overflow-x: hidden;
}
</style>


<section class="pre-head">
    <div class="container header0">
        <div class="pre-header1 d-flex">
            <p class="mb-0"><i class="fa-solid fa-phone"></i><a href="#">+91 89057 99279</a></p>
            <p class="mb-0"><i class="fa-solid fa-envelope"></i><a href="#">navdeepproducts111@gmail.com</a></p>
        </div>
        <div class="pre-header2">
            <a target="_blank" title="Facebook" href="https://www.facebook.com/navdeepgroup?mibextid=ZbWKwL"><i class="fa-brands fa-square-facebook"></i> </a>
            <!--<a targrt="_blank" title="Twitter" href="#"><i class="fa-brands fa-twitter"></i></a>-->
            <a target="_blank" title="Instagram" href="#"><i class="fa-brands fa-instagram"></i></a>
            <a target="_blank" title="Youtube" href="https://youtube.com/@navdeepproducts4863"><i class="fa-brands fa-youtube"></i></a>
        </div>
    </div>
</section>
<!-- pre header -->
<!-- navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a href="{{ route('frontindex') }}">
            <img class="logo" src="{{ asset('assets/front/img/logo.png') }}" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('frontindex')) {{ 'active' }} @endif" aria-current="page" href="{{ route('frontindex') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('frontabout')) {{ 'active' }} @endif" href="{{ route('frontabout') }}">Company</a>
                </li>

                <li class="nav-item dropdown hoverable-dropdown">
                    <a class="nav-link dropdown-toggle @if (request()->routeIs('allproducts')) {{ 'active' }} @endif || @if (request()->routeIs('categoryproducts')) {{ 'active' }} @endif" href="{{ route('allproducts') }}" role="button"
                        aria-expanded="false">
                        Products
                    </a>
                    <ul class="dropdown-menu cust-nav">
                        <?php
                        $Category = App\Models\Category::where(['iStatus' => 1, 'isDelete' => 0])
                            ->orderBy('strSequenceNo', 'asc')
                            ->get();
                        ?>
                        @foreach ($Category as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('categoryproducts', $category->slugname) }}">
                                    {{ $category->categoryname }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item  dropdown hoverable-dropdown">
                    <a class="nav-link dropdown-toggle @if (request()->routeIs('frontphotogallery')) {{ 'active' }} @endif || @if (request()->routeIs('frontvideogallery')) {{ 'active' }} @endif" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Gallery
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('frontphotogallery') }}">Photo Gallery</a></li>
                        <li><a class="dropdown-item" href="{{ route('frontvideogallery') }}">Video Gallery</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('frontcontact')) {{ 'active' }} @endif" href="{{ route('frontcontact') }}">Contact</a>
                </li>
            </ul>
            <form action="{{ route('HeaderSearch') }}" method="post" class="d-flex" role="search">
                @csrf
                <input class="form-control form-input" name="search" type="search" placeholder="Search" aria-label="Search" value="<?= isset($Search) ? $Search : '' ?>">
                <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </div>
</nav>
