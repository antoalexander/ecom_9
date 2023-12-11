<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsFilter;
use App\Models\ProductsAttribute;
use App\Models\Vendor;
use App\Models\Cart;
use Session;
use DB;
use Auth;


class ProductsController extends Controller
{
    
    public function listing(Request $request){
       if($request->ajax()){
       $data = $request->all();
       /*   echo "<pre>"; print_r($data); exit;*/
       $url = $data['url'];
       $_GET['sort'] = $data['sort'];
       //check valid url
       $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
       if($categoryCount>0){
          //Get Category Details
          $categoryDetails = Category::categoryDetails($url);
          //dd($categoryDetails); exit;
          
          $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->where('status',1);

          //Checking for dynamic filters
          $productFilters = ProductsFilter::productFilters();

          foreach($productFilters as $key => $filter)
          {
             // if filter is selected
             if(isset($filter['filter_column']) && isset($data[$filter['filter_column']]) && !empty($filter['filter_column']) && !empty($data[$filter['filter_column']]))
             {
                $categoryProducts->whereIn($filter['filter_column'],$data[$filter['filter_column']]);
             }
          }

          /*if(isset($data['fabric']) && !empty($data['fabric']))
          {
             $categoryProducts->whereIn('products.fabric',$data['fabric']);
          }*/
          
          //checking for sort
          if(isset($_GET['sort']) && !empty($_GET['sort']))
          {
            if($_GET['sort']=="product_latest")
            {
               $categoryProducts->orderby('products.id','Desc');
            }
            else if($_GET['sort']=="price_lowest")
            {
                $categoryProducts->orderBy('products.product_price','Asc');
            }
            else if($_GET['sort']=="price_highest")
            {
                $categoryProducts->orderBy('products.product_price','Desc');
            }
            else if($_GET['sort']=="name_a_z")
            {
                $categoryProducts->orderBy('products.product_name','Asc');
            }
            else if($_GET['sort']=="name_z_a")
            {
                $categoryProducts->orderBy('products.product_name','Desc');
            }
          }
          
          
          // checking for size
          if(isset($data['size']) && !empty($data['size']))
          { 
      
             $productIds = ProductsAttribute::select('product_id')->whereIn('size',$data['size'])->pluck('product_id')->toArray();
             //echo "<pre>"; print_r($productIds); exit;
             $categoryProducts->whereIn('products.id',$productIds);
          }


          //echo "<pre>"; print_r($productIds); exit;
          // checking for color
          if(isset($data['color']) && !empty($data['color'])){ 
             $productIds = Product::select('id')->whereIn('product_color',$data['color'])->pluck('id')->toArray();
             $categoryProducts->whereIn('products.id',$productIds);
          }

          // checking price
          if(isset($data['price']) && !empty($data['price']))
          { 

              foreach($data['price'] as $key => $price){
                  $priceArr = explode("-",$price);
                  $productIds[] = Product::select('id')->whereBetween('product_price',[$priceArr[0],$priceArr[1]])->pluck('id')->toArray(); 
                 
              } 
               $productIds = call_user_func_array('array_merge',$productIds);
               $categoryProducts->whereIn('products.id',$productIds);
               //echo "<pre>"; print_r($productIds); exit;
          }
          
          // checking for brand
          if(isset($data['brand']) && !empty($data['brand'])){ 
             $productIds = Product::select('id')->whereIn('brand_id',$data['brand'])->pluck('id')->toArray();
             $categoryProducts->whereIn('products.id',$productIds);
          }
      

          $categoryProducts = $categoryProducts->paginate(30);
          return view('front.products.ajax_products_listing')->with(compact('categoryDetails','categoryProducts','url'));
       }
       else
        { 
           abort(404);
        }
       }
       else
       {
         $url = Route::getFacadeRoot()->current()->uri();
         //check valid url
          $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
          if($categoryCount>0){
             //Get Category Details
             $categoryDetails = Category::categoryDetails($url);
             //dd($categoryDetails); exit;
          
          $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->where('status',1);
          
          //checking for sort
          if(isset($_GET['sort']) && !empty($_GET['sort']))
          {
            if($_GET['sort']=="product_latest"){
               $categoryProducts->orderby('products.id','Desc');
            }
            else if($_GET['sort']=="price_lowest")
            {
                $categoryProducts->orderBy('products.product_price','Asc');
            }
            else if($_GET['sort']=="price_highest")
            {
                $categoryProducts->orderBy('products.product_price','Desc');
            }
             else if($_GET['sort']=="name_a_z")
            {
                $categoryProducts->orderBy('products.product_name','Asc');
            }
             else if($_GET['sort']=="name_z_a")
            {
                $categoryProducts->orderBy('products.product_name','Desc');
            }
          }

          $categoryProducts = $categoryProducts->paginate(3);
          return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','url'));
          }
          else
           { 
              abort(404);
           }

       }
    }
    
   public function vendorListing($vendorid){
      //get vendor shop name
        $getVendorShop = Vendor::getVendorDetails($vendorid); 

      //get vendor products
      $vendorProducts = Product::with('brand')->where('vendor_id',$vendorid)->where('status',1);
      $vendorProducts = $vendorProducts->paginate(30);
      //dd($vendorProducts);
      return view('front.products.vendor_listing')->with(compact('getVendorShop','vendorProducts'));
   }

    public function detail($id){
       $productDetails = Product::with(['section','category','brand','attributes'=>function($query){
         $query->where('stock','>',0)->where('status',1);
       },'images','vendor'])->find($id)->toArray();
       $categoryDetails = Category::categoryDetails($productDetails['category']['url']);
       //dd($productDetails); exit;
    
       //get similar products
       $similarProducts = Product::with('brand')->where('category_id',$productDetails['category']['id'])->where('id','!=',$id)->limit(4)->inRandomOrder()->get()->toArray();
       //dd($similarProducts);

       // set session for recently viewed products
       if(empty(Session::get('session_id'))){
          $session_id = md5(uniqid(rand(),true));
       }else{
         $session_id = Session::get('session_id');
       }

       Session::put('session_id',$session_id);

       //insert product in table if not already exists
       $countRecentlyViewedProducts = DB::table('recently_viewed_products')->where(['product_id'=>$id,'session_id'=>$session_id])->count();
       if($countRecentlyViewedProducts==0){
         DB::table('recently_viewed_products')->insert(['product_id'=>$id,'session_id'=>$session_id]);
       }

       //get recently viewed products ids
       $recentProductIds = DB::table('recently_viewed_products')->select('product_id')->where('product_id','!=',$id)->where('session_id',$session_id)->inRandomOrder()->get()->take(4)->pluck('product_id');

       //dd($recentProductIds);
       //get recently viewed products 
       $recentlyViewedProducts = Product::with('brand')->whereIn('id',$recentProductIds)->get()->toArray();
       //dd($recentlyViewedProducts);

       //get group products (product colors)
       $groupProducts = array();
       if(!empty($productDetails['group_code'])){
          $groupProducts = Product::select('id','product_image')->where('id','!=',$id)->where(['group_code'=>$productDetails['group_code'],'status'=>1])->get()->toArray();
          //dd($groupProducts);
       }

       $totalStock = ProductsAttribute::where('product_id',$id)->sum('stock');
       
       return view('front.products.detail')->with(compact('productDetails','categoryDetails','totalStock','similarProducts','recentlyViewedProducts','groupProducts'));
    }

    public function getProductPrice(Request $request){
        if($request->ajax()){
            $data = $request->all();
            //echo "<pre>"; print_r($data);die;
            $getDiscountAttributePrice = Product::getDiscountAttributePrice($data['product_id'],$data['size']);
            return $getDiscountAttributePrice;
        }
    }

    public function cartAdd(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            //check product stock is available or not
            $getProductStock = ProductsAttribute::getProductStock($data['product_id'],$data['size']);
            if($getProductStock<$data['quantity']){
                 return redirect()->back()->with('error_message','Required quantity is not available!');
            }

            // generate session id if not exists
            $session_id = Session::get('session_id');
            if(empty($session_id)){
                $session_id = Session::getId();
                Session::put('session_id',$session_id);
           } 
           
           // check product if already exists in the user cart
           if(Auth::check()){
              // user is logged in
              $user_id = Auth::user()->id;
              $countProducts = Cart::where(['product_id'=>$data['product_id'],'size'=>$data['size'],'user_id'=>$user_id])->count();
           }
           else{
              // user is not logged in 
             $user_id = 0;
             $countProducts = Cart::where(['product_id'=>$data['product_id'],'size'=>$data['size'],'session_id'=>$session_id])->count();
           }
            
            if($countProducts){
               return redirect()->back()->with('error_message','Product already exists in Cart!');
            }


           //save product in carts table
            $item = new Cart;
            $item->session_id = $session_id;
            $item->user_id = $user_id;
            $item->product_id = $data['product_id'];
            $item->size = $data['size'];
            $item->quantity = $data['quantity'];
            $item->save();
            return redirect()->back()->with('success_message','Product has been added in Cart! <a style="text-decoration:underline" href="/cart">View Cart</a>');
           

        }
    }

    public function cart(){
        $getCartItems = Cart::getCartItems();
        //dd($getCartItems);
        return view('front.products.cart')->with(compact('getCartItems'));
    }


    public function cartUpdate(Request $request){
        if($request->ajax()){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            //get cart details
            $cartDetails = Cart::find($data['cartid']);

            //get available product stock
            $availableStock = ProductsAttribute::select('stock')->where(['product_id'=>$cartDetails['product_id'],'size'=>$cartDetails['size']])->first()->toArray();

            //echo "<pre>"; print_r($availableStock); die;

            if($data['qty']>$availableStock['stock']){
                $getCartItems = Cart::getCartItems();
                return response()->json([
                'status'=>false,
                'message'=>'Product stock is not available', 
                'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))
                ]);
            }

            //check if product size is available
            $availableSize = ProductsAttribute::where(['product_id'=>$cartDetails['product_id'],'size'=>$cartDetails['size'],'status'=>1])->count();

            if($availableSize==0){
               $getCartItems = Cart::getCartItems();
                return response()->json([
                'status'=>false,
                'message'=>'Product Size is not available. Please this product and choose another one', 
                'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))
                ]);
            }

            Cart::where('id',$data['cartid'])->update(['quantity'=>$data['qty']]);
            $getCartItems = Cart::getCartItems();
            return response()->json(
                ['status'=>true,
                'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))
                ]);
        }
    }
    
    public function cartDelete(Request $request){
        if($request->ajax()){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Cart::where('id',$data['cartid'])->delete();
            $getCartItems = Cart::getCartItems();
            return response()->json(
                [
                'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))
                ]);
        }
    }

}
