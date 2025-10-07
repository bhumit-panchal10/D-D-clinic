<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\PhotoGallery;
use App\Models\ProductInquiry;
use App\Models\VideoGallery;
use App\Models\ProductPhotos;
use App\Models\CategoryFeatures;
use App\Models\ProductFeatures;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Calculation\Token\Stack;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Redirect;


class FrontController extends Controller
{
    public function index(Request $request)
    {
        try {

            $Banner = Banner::orderBy('iSequenceNo', 'asc')
                ->where(['banner.iStatus' => 1, 'banner.isDelete' => 0])
                ->get();
            // dd($Banner);

            $Product = Product::select(
                'product.productId',
                'product.productname',
                'product.description',
                'product.slugname',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId ORDER BY product.productId  LIMIT 1) as photo'),
                DB::raw('(SELECT slugname FROM category where category.categoryId=product.categoryId ORDER BY product.productId  LIMIT 1) as categoryslug'),
            )
                ->orderBy('productId', 'desc')
                ->take(8)
                ->where(['iStatus' => 1, 'isDelete' => 0, 'ShowHomePage' => 1])
                ->get();

            return view('frontview.index', compact('Product', 'Banner'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function about(Request $request)
    {
        try {
            return view('frontview.about');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function contact(Request $request)
    {
        try {
            return view('frontview.contact');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function contactsubmit(Request $request)
    {
        $request->validate(
            [
                'strFirstName' => 'required',
                'strLastName' => 'required',
                'strMobile' => 'required|digits:10',
                'strEmail' => 'required',
                'strMessage' => 'required',
                'captcha' => 'required|captcha'
            ],
            ['captcha.captcha' => 'Invalid captcha code.']
        );

        $data = array(
            'name' => $request->strFirstName . ' ' . $request->strLastName,
            'email' => $request->strEmail,
            'mobileNumber' => $request->strMobile,
            'message' => $request->strMessage,
            "strIp" => $request->ip(),
            "created_at" => date('Y-m-d H:i:s')
        );
        DB::table('inquiry')->insert($data);

        $SendEmailDetails = DB::table('sendemaildetails')
            ->where(['id' => 4])
            ->first();

        $root = $_SERVER['DOCUMENT_ROOT'];
        $file = file_get_contents($root . '/mailers/contacteinquirymail.html', 'r');
        $file = str_replace('#name', $data['name'], $file);
        $file = str_replace('#email', $data['email'], $file);
        $file = str_replace('#mobile', $data['mobileNumber'], $file);
        $file = str_replace('#message', $data['message'], $file);

        // $setting = DB::table("setting")->select('email')->first();
        // $toMail = $setting->email; // "shahkrunal83@gmail.com";//
        $toMail = "navdeepproducts111@gmail.com";
        // $toMail = "dev2.apolloinfotech@gmail.com";
        $ccMail = "kashyap1790@gmail.com";


        $to = $toMail;
        $subject = $SendEmailDetails->strSubject;
        $message = $file;
        $header = "From:" . $SendEmailDetails->strFromMail . "\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Cc:" . $ccMail . "\r\n";
        $header .= "Content-type: text/html\r\n";

        $retval = mail($to, $subject, $message, $header);


        return redirect()->route('thankyou');
    }

    public function photogallery(Request $request)
    {
        try {
            $PhotoGallery =  PhotoGallery::orderBy('photoGalleryId', 'desc')->paginate(12);
            $Count = $PhotoGallery->count();
            return view('frontview.photogallery', compact('PhotoGallery', 'Count'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function videogallery(Request $request)
    {
        try {
            $VideoGallery =  VideoGallery::orderBy('videoGalleryId', 'desc')->paginate(12);
            $Count = $VideoGallery->count();
            return view('frontview.videogallery', compact('VideoGallery', 'Count'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function allproducts(Request $request)
    {
        try {
            $Product = Product::select(
                'product.productId',
                'product.productname',
                'product.description',
                'product.slugname',

                'product.strSequenceNo',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId ORDER BY product.productId  LIMIT 1) as photo'),
                DB::raw('(SELECT slugname FROM category where category.categoryId=product.categoryId ORDER BY product.productId  LIMIT 1) as categoryslug'),
            )
                ->orderBy('strSequenceNo', 'asc')
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->paginate(16);
            $ProductCount = $Product->count();

            return view('frontview.allproducts', compact('Product', 'ProductCount'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function categoryproducts(Request $request, $id)
    {
        try {
            $Category = Category::orderBy('categoryId', 'desc')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'slugname' => $id])
                ->first();

            $Product = Product::select(
                'product.productId',
                'product.productname',
                'product.description',
                'product.slugname',

                'product.strSequenceNo',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId ORDER BY product.productId  LIMIT 1) as photo'),
                DB::raw('(SELECT slugname FROM category where category.categoryId=product.categoryId ORDER BY product.productId  LIMIT 1) as categoryslug'),
            )
                ->orderBy('product.strSequenceNo', 'asc')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'categoryId' => $Category->categoryId])
                ->paginate(16);
            // dd($Product);

            $ProductCount = $Product->count();

            return view('frontview.categoryproducts', compact('Product', 'ProductCount', 'id', 'Category'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function productdetail(Request $request, $category = null, $product = null)
    {
        // dd($category);
        // dd($product);
        try {
            $ProductDetail = Product::select(
                'product.productId',
                'product.categoryId',
                'product.productname',
                'product.description',
                'product.slugname',

                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId  LIMIT 1) as photo'),
            )
                ->orderBy('productId', 'DESC')
                ->where(['product.iStatus' => 1, 'product.isDelete' => 0, 'product.slugname' => $product])
                ->first();
            // dd($ProductDetail);

            $Category = Category::where(['slugname' => $category])->first();

            $Photos = ProductPhotos::where(['productphotos.iStatus' => 1, 'productphotos.isDelete' => 0, 'productphotos.productid' => $ProductDetail->productId])
                ->get();

            $RelatedProduct = Product::select(
                'product.productId',
                'product.productname',
                'product.description',
                'product.slugname',
                'product.strSequenceNo',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId  LIMIT 1) as photo'),
                DB::raw('(SELECT slugname FROM category where category.categoryId=product.categoryId ORDER BY product.productId  LIMIT 1) as categoryslug'),
            )
                ->orderBy('strSequenceNo', 'asc')
                ->take(4)
                ->where(['product.iStatus' => 1, 'product.isDelete' => 0, 'categoryId' => $ProductDetail->categoryId])
                ->where('product.slugname', '!=', $product)
                ->take(12)
                ->get();
            $count = $RelatedProduct->count();

            $CategoryFeatures = CategoryFeatures::orderBy('category_featuresId', 'asc')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'iCategoryId' => $Category->categoryId])
                ->get();
            // dd($CategoryFeatures);
            $CategoryFeaturesCount = $CategoryFeatures->count();

            $ProductFeatures = ProductFeatures::orderBy('product_featuresId', 'asc')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'iProductId' => $ProductDetail->productId])
                ->get();
            // dd($ProductFeatures);
            $ProductFeaturesCount = $ProductFeatures->count();



            return view('frontview.productdetail', compact('category', 'ProductDetail', 'Photos',  'category', 'RelatedProduct', 'count', 'CategoryFeatures', 'ProductFeatures', 'ProductFeaturesCount', 'CategoryFeaturesCount'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function productinquiry(Request $request)
    {
        // dd($request);
        try {
            // $request->validate([
            //     'strName' => 'required',
            //     'strEmail' => 'required',
            //     'strMobile' => 'required',
            //     'strLocation' => 'required',
            //     'strMessage' => 'required'
            // ]);

            $data = array(
                'productid' => $request->productname,
                'name' => $request->strName,
                'email' => $request->strEmail,
                'mobileNumber' => $request->strMobile,
                'location' => $request->strLocation,
                'message' => $request->strMessage,
                'created_at' => date('Y-m-d H:i:s'),
                'strIP' => $request->ip()
            );
            // dd($data);
            $Insert = DB::table('productinquiry')->insert($data);
            // dd($Insert);

            $SendEmailDetails = DB::table('sendemaildetails')
                ->where(['id' => 8])
                ->first();
            // dd($SendEmailDetails);

            $root = $_SERVER['DOCUMENT_ROOT'];
            // dd($root);
            $file = file_get_contents($root . '/mailers/productinquirymail.html', 'r');
            $file = str_replace('#name', $data['name'], $file);
            $file = str_replace('#email', $data['email'], $file);
            $file = str_replace('#mobile', $data['mobileNumber'], $file);

            $file = str_replace('#location', $data['location'], $file);
            $file = str_replace('#message', $data['message'], $file);
            // dd($file);
            // $setting = DB::table("setting")->select('email')->first();
            // $toMail = $setting->email; // "shahkrunal83@gmail.com";//
            $toMail = "navdeepproducts111@gmail.com";
            // $toMail = "dev2.apolloinfotech@gmail.com";
            $ccMail = "kashyap1790@gmail.com";

            $to = $toMail;
            $subject = $SendEmailDetails->strSubject . " For " . $request->productname;
            $message = $file;
            $header = "From:" . $SendEmailDetails->strFromMail . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Cc:" . $ccMail . "\r\n";
            $header .= "Content-type: text/html\r\n";

            $retval = mail($to, $subject, $message, $header);

            return redirect()->route('thankyou');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function thankyou(Request $request)
    {
        try {
            return view('frontview.thankyou');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }


    public function HeaderSearch(Request $request)
    {
        try {
            $Search = $request->search;

            $Product = Product::select(
                'product.productId',
                'product.productname',
                'product.description',
                'product.slugname',

                'product.strSequenceNo',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId ORDER BY product.productId  LIMIT 1) as photo'),
                DB::raw('(SELECT slugname FROM category where category.categoryId=product.categoryId ORDER BY product.productId  LIMIT 1) as categoryslug'),
            )
                ->orderBy('strSequenceNo', 'asc')
                ->where(['product.iStatus' => 1, 'product.isDelete' => 0])
                ->when($Search, fn ($query, $Search) => $query
                    ->where('product.productname', 'LIKE', '%' . $Search . '%'))
                ->get();
            $ProductCount = $Product->count();

            $ProductFeatures = ProductFeatures::orderBy('product_featuresId', 'asc')
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->get();

            return view('frontview.Searchdata', compact('Product', 'ProductCount', 'Search', 'ProductFeatures'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
}
