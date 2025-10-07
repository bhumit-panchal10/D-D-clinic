<!-- footer -->
<section class="footer">
    <div class="container-fluid fotr">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="sec1">
                    <div class="sec1-img">
                        <a href="{{ route('frontindex') }}">
                            <img src="{{ asset('assets/front/img/logo.jpg') }}">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="sec2">
                    <div class="sec2-head">
                        <h2>Quick Links</h2>
                    </div>
                    <div class="sec2-info">
                        <ul class="icon-list" type="none">
                            <li><i class="fa-solid fa-caret-right"></i>
                                <a href="{{ route('frontindex') }}">
                                    Home
                                </a>
                            </li>
                            <li><i class="fa-solid fa-caret-right"></i><a href="{{ route('frontabout') }}">Company</a>
                            </li>
                            <li><i class="fa-solid fa-caret-right"></i><a href="{{ route('allproducts') }}">Products</a>
                            </li>
                            <li><i class="fa-solid fa-caret-right"></i><a href="{{ route('frontphotogallery') }}">Photo Gallery</a>
                            </li>
                            <li><i class="fa-solid fa-caret-right"></i><a href="{{ route('frontvideogallery') }}">Video Gallery</a>
                            </li>
                            <li><i class="fa-solid fa-caret-right"></i><a href="{{ route('frontcontact') }}">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="sec3">
                    <div class="sec3-head">
                        <h3>Popular Products</h3>
                    </div>
                    <div class="sec3-info">
                        <ul type="none">
                            <?php
                            $Product = App\Models\Product::select('product.productId', 'product.productname', 'product.description', 'product.slugname', DB::raw('(SELECT slugname FROM category where category.categoryId=product.categoryId ORDER BY product.productId  LIMIT 1) as categoryslug'))
                                ->where(['iStatus' => 1, 'isDelete' => 0,'showOnFooter'=>1 ])
                                ->orderBy('productname', 'asc')
                                ->take(6)
                                ->get();
                            ?>
                            @foreach ($Product as $product)
                                <li><i class="fa-solid fa-caret-right"></i>
                                    <a
                                        href="{{ route('productdetail', [$product->categoryslug, $product->slugname]) }}">
                                        {{ $product->productname }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="sec4">
                    <div class="sec4-head">
                        <h3>Get In Touch</h3>
                    </div>
                    <div class="sec4-info">
                        <ul type="none">
                            <li><a target="_blank" href = "https://maps.app.goo.gl/ifXqBUQP89pWzgxt9"><i class="fa-solid fa-location-dot"></i></a>Plot No : 21, Survey No : 338, </br>
                                Balda Industrial Park - Phase - II, </br>
                                B/h Pardi G.I.D.C., </br>
                                Killa Pardi : 396 125, Dist : Valsad </br>
                                Gujarat, India.
                            </li>
                            <li><a class="text-white" href="tel:+91 8905799279"> <i class="fa-solid fa-phone"></i>+91 89057 99279 </a></li>
                            <li> <a class="text-white" href = "mailto:navdeepproducts111@gmail.com"><i class="fa-solid fa-envelope"></i>navdeepproducts111@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- footer -->

<!-- whatsapp icon -->
<a target="_blank" href="https://wa.me/+918905799279" class="wp-icon">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<!-- whatsapp icon -->
