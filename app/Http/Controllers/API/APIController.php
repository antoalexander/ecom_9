<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CmsPage;
use App\Models\Section;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsFilter;
use App\Models\ProductsAttribute;
use Validator;

class APIController extends Controller
{
    
 public function registerUser(Request $request){
     if($request->isMethod('post')){
         $data = $request->input();
         // echo "<pre>"; print_r($data); die;
         
         $rules = [
           "name" => "required",
           "email" => "required|email|unique:users",
           "password" => "required",
         ];

         $customMessages = [
            "name.required" => "Name is required",
            "email.required" => "Email is required",
            "email.unique" => "Email Already exists",
            "password.required" => "Password is required"
         ];

         $validator = Validator::make($data,$rules,$customMessages);
         if($validator->fails()){
            return response()->json($validator->errors(),422);
         }

         $user = new User;
         $user->name = $data['name'];
         $user->mobile = $data['mobile'];
         $user->email = $data['email'];
         $user->password = bcrypt($data['password']);
         $user->status =1;
         $user->save();
         return response()->json(['status'=>true,'message'=>'User Registered successfully!'],201);
     }
 }

 public function loginUser(Request $request){
      if($request->isMethod("post")){
         $data = $request->input();
         //echo "<pre>"; print_r($data); die;

         $rules = [
          "email" => "required|email|exists:users",
          "password" => "required",
        ];

        $customMessages = [
           "email.required" => "Email is required",
           "email.exists" => "Email does not exists",
           "password.required" => "Password is required"
        ];
       
          $validator = Validator::make($data,$rules,$customMessages);
          if($validator->fails()){
            return response()->json($validator->errors(),422);
          }
         
          //verify user 
          $userCount = User::where('email',$data['email'])->count();
          if($userCount>0){
               // Fetch user details
           $userDetails = User::where('email',$data['email'])->first();
         
        // verify the password 
        if(password_verify($data['password'],$userDetails->password)){
           return response()->json([
             "userDetails" => $userDetails,
             "status" => true,
             "message" => "User Login Succesfully"
           ],201);
        }else{
            $message = "Password is Incorrect";
            return response()->json(['status'=>false,"message"=>$message],422);
        }
          }
        else{
          $message = "Email is Incorrect";
          return response()->json(['status'=>false,"message"=>$message],422);
        }
       

      }
 }

 public function updateUser(Request $request){
    if($request->isMethod('post')){
        $data = $request->input();
        
        //  echo "<pre>"; print_r($data); die;
         $rules = [
          "name" => "required",
        ];

        $customMessages = [
           "name.required" => "Name is required",
        ];

        $validator = Validator::make($data,$rules,$customMessages);
        if($validator->fails()){
           return response()->json($validator->errors(),422);
        }

         //verify user  ID
         $userCount = User::where('id',$data['id'])->count();
        
         if($userCount>0){
          
          if(empty($data['address'])){
            $data['address'] = "";
          }
          if(empty($data['city'])){
            $data['city'] = "";
          }
          if(empty($data['state'])){
            $data['state'] = "";
          }
          if(empty($data['country'])){
            $data['country'] = "";
          }
          if(empty($data['pincode'])){
            $data['pincode'] = "";
          }

          // update user details
          User::where('id',$data['id'])->update(['name'=>$data['name'],
          'address'=>$data['address'],'city'=>$data['city'],
          'state'=>$data['state'],'country'=>$data['country'],
          'pincode'=>$data['pincode']]);
          // Fetch user details
          $userDetails = User::where('id',$data['id'])->first();
          return response()->json([
            "userDetails" => $userDetails,
            "status" => true,
            "message" => "User Updated Succesfully"
          ],201);
         }
         else{
          $message = "User does not exists";
          return response()->json(['status'=>false,"message"=>$message],422);
         }

    }
 }


 public function cmsPage(){
  // echo  $currentRoute = url()->current();  exit;
  $currentRoute = url()->current();
  $currentRoute = str_replace("http://127.0.0.1:8000/api/","",$currentRoute);
  $cmsRoutes = CmsPage::select('url')->where('status',1)->get()->pluck('url')->toArray();
  if(in_array($currentRoute,$cmsRoutes)){
      //echo "Page will come";
      $cmsPageDetails = CmsPage::where('url',$currentRoute)->get();
      return response()->json(['cmsPageDetails'=>$cmsPageDetails,'status'=>true,"message"=>"Pge details fetched succesfully "],200);
  }
  else{
    $message = "Page does not exists!";
    return response()->json(['status'=>false,"message"=>$message],422);
   }
}

 public function menu(){
    $categories = Section::with('categories')->get();
    return response()->json(["categories" => $categories],200);
 }

 public function listing($url){

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
 
     $categoryProducts = $categoryProducts->get();  
     foreach($categoryProducts as $key =>$value){
         $getDiscountPrice = Product::getDiscountPrice($categoryProducts[$key]['id']);
         if($getDiscountPrice>0){
           $categoryProducts[$key]['final_price'] = "Rs.".$getDiscountPrice;
         }
         else{
          $categoryProducts[$key]['final_price'] = "Rs.".$$categoryProducts[$key]['product_price'];
         }
         $categoryProducts[$key]['product_image'] = url("/front/images/product_iamges/small/".$categoryProducts[$key]['product_image']);
     }
     return response()->json(["products"=>$categoryProducts],200);
  }
  else{
    $message = "Category URL is Incorrect!";
    return response()->json(['status'=>false,"message"=>$message],422);
  }
 }

}
   