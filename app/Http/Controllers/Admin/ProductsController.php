<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
use App\Models\ProductsFilter;
use Auth;
use Image;
use Session;

class ProductsController extends Controller
{
    
  public function products(){
      Session::put('page','products');
       $adminType = Auth::guard('admin')->user()->type;
       $vendor_id = Auth::guard('admin')->user()->vendor_id;
       if($adminType=="vendor"){
          $vendorStatus = Auth::guard('admin')->user()->status;
          if($vendorStatus==0){
            return redirect("admin/update-vendor-details/personal")->with("error_message","Your Vendor account is not approved yet. Please make sure to fill your valid personal, business and bank details");
          }
       }
       $products = Product::with(['section'=>function($query){
         $query->select('id','name');
       },'category'=>function($query){
         $query->select('id','category_name');
       }]);
       
       if($adminType=="vendor")
       {
         $products = $products->where('vendor_id',$vendor_id);
       }

       //dd($products);
       $products = $products->get()->toArray();
       return view('admin.products.products')->with(compact('products'));
  }

  public function updateProductStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
           // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
            //Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
            //return response()->json(['status'=>$status,'admin_id'=>$data['admin_id']]);
            Product::where('id',$data['product_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'product_id'=>$data['product_id']]);
        }
    }

    public function deleteProduct($id){
        //delete product
        Product::where('id',$id)->delete();
        $message = "Product has been deleted successfully!";
        return redirect()->back()->with('success_message',$message);
    }

    public function addEditProduct(Request $request,$id=null){
      Session::put('page','products');
         if($id==""){
            $title = "Add Product";
            $product = new Product;
            $message = "Product added successfully!";
         }else{
            $title = "Edit Product";
            $product = Product::find($id);
            //dd($product);
            // echo "<pre>"; print_r($product); exit;
            $message = "Product Updated successfully!";
         }
  
         if($request->isMethod('post')){
            $data  = $request->all();
            //echo "<pre>"; print_r($data); exit;

            $rules = [
               'category_id'=>'required',
               'product_name'=>'required|regex:/^[\pL\s\-]+$/u',
               'product_code'=>'required',
               'product_price'=>'required|numeric',
               'product_color'=>'required|regex:/^[\pL\s\-]+$/u',
              
            ];
            
            $customMessages = [
                'category_id.required' =>'Category is required',
                'product_name.required' => 'Product Name is required',
                'product_name.regex' => 'Valid Product Name is required',
                'product_code.required' => 'Product code is required',
                'product_price.required' => 'Product price is required',
                'product_price.regex' => 'Valid Product price is required',
                'product_color.required' => 'Product color is required',
                'product_color.regex' => 'Valid Product color is required',

            ];

         $this->validate($request,$rules,$customMessages);

         // upload product image after resize
         if($request->hasFile('product_image')){
            $image_tmp = $request->file('product_image');
            if($image_tmp->isValid())
            {
                // upload image after resize
                //Get Image Extension
                $extension = $image_tmp->getClientOriginalExtension();
                //Generate New Image Name
                $imageName = rand(111,999999).'.'.$extension;
                $largeImagePath = 'front/images/product_images/large/'.$imageName;
                $mediumImagePath = 'front/images/product_images/medium/'.$imageName;
                $smallImagePath = 'front/images/product_images/small/'.$imageName;
                // Upload the large, medium small image after resize
                Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);
                Image::make($image_tmp)->resize(500,500)->save($mediumImagePath);
                Image::make($image_tmp)->resize(250,250)->save($smallImagePath);
                // Insert image name in product table
                $product->product_image = $imageName;
            }

         }

         //Upload product video
         if($request->hasFile('product_video')){
            $video_tmp = $request->file('product_video');
            
            if($video_tmp->isValid()){
                //upload video in videos folder
                //$video_name = $video_tmp->getClientOriginalName();
                $extension = $video_tmp->getClientOriginalExtension();
                $videoName = rand(111,999999).'.'.$extension;
                $videoPath = 'front/videos/product_videos';
                $video_tmp->move($videoPath,$videoName);
                // Insert video name in products table
                $product->product_video = $videoName;
            }
         }

         //save product details in products table
         $categoryDetails = Category::find($data['category_id']);
         $product->section_id = $categoryDetails['section_id'];
         $product->category_id = $data['category_id'];
         $product->brand_id = $data['brand_id'];
         $product->group_code = $data['group_code'];

         $productFilters = ProductsFilter::productFilters();
         
         foreach($productFilters as $filter){
             // echo $data[$filter['filter_column']]; die;
            $filterAvailable = ProductsFilter::filterAvailable($filter['id'],$data['category_id']);
            if($filterAvailable=="Yes")
            {
                if(isset($filter['filter_column'])  && $data[$filter['filter_column']])
                {
                    $product->{$filter['filter_column']} = $data[$filter['filter_column']];
                }
            }
         }

         $adminType = Auth::guard('admin')->user()->type;
         $vendor_id = Auth::guard('admin')->user()->vendor_id;
         $admin_id = Auth::guard('admin')->user()->id;

         $product->admin_type = $adminType;
         $product->admin_id = $admin_id;
         
         if($adminType=="vendor")
         {
            $product->vendor_id = $vendor_id;
         }
         else
         {
            $product->vendor_id = 0;
         }

         if(empty($data['product_discount']))
         {
            $data['product_discount'] = 0;
         }
         if(empty($data['product_weight']))
         {
            $data['product_weight'] = 0;
         }
           
         $product->product_name = $data['product_name'];
         $product->product_code = $data['product_code'];
         $product->product_color = $data['product_color'];
         $product->product_price = $data['product_price'];
         $product->product_discount = $data['product_discount'];
         $product->product_weight = $data['product_weight'];
         $product->description = $data['discription'];
         $product->meta_title = $data['meta_title'];
         $product->meta_description = $data['meta_description'];
         $product->meta_keywords = $data['meta_keywords'];
         

         if(!empty($data['is_featured']))
         { 
            $product->is_featured = $data['is_featured'];
         }
         else
         {
            $product->is_featured = 'No';
         }

         if(!empty($data['is_bestseller']))
         { 
            $product->is_bestseller = $data['is_bestseller'];
         }
         else
         {
            $product->is_bestseller = 'No';
         }

         $product->status = 1;
         $product->save();

         return redirect('admin/products')->with('success_message',$message);

       }



         //get sections with categoris and subcategories
         $categories = Section::with('categories')->get()->toArray();
         //dd($categories);

         //get all brands
         $brands = Brand::where('status',1)->get()->toArray();
         
         return view('admin.products.add_edit_product')->with(compact('title','categories','brands','product'));

    }

    public function deleteProductImage($id)
    {
      //get product image
      $productImage = Product::select('product_image')->where('id',$id)->first();

      //get product image paths
      $small_image_path = 'front/images/product_images/small/';
      $medium_image_path = 'front/images/product_images/medium/';
      $large_image_path = 'front/images/product_images/large/';

      // delete product small image if exists in small folder
      if(file_exists($small_image_path.$productImage->product_image)){
          unlink($small_image_path.$productImage->product_image);
      }

      // delete product medium image if exists in small folder
      if(file_exists($medium_image_path.$productImage->product_image)){
          unlink($medium_image_path.$productImage->product_image);
      }

      // delete product large image if exists in small folder
      if(file_exists($large_image_path.$productImage->product_image)){
          unlink($large_image_path.$productImage->product_image);
      }

      //delete product image from products table
      Product::where('id',$id)->update(['product_image'=>'']);

      $message = "Product Images has been deleted successfully!";
      return redirect()->back()->with('success_message',$message);


    }

    public function deleteProductVideo($id)
    {
      //get product image
      $productVideo = Product::select('product_video')->where('id',$id)->first();

      //get product image paths
      $product_video_path = 'front/videos/product_videos/';
      

      // delete product small image if exists in small folder
      if(file_exists($product_video_path.$productVideo->product_video)){
          unlink($product_video_path.$productVideo->product_video);
      }


      //delete product image from products table
      Product::where('id',$id)->update(['product_video'=>'']);

      $message = "Product Videos has been deleted successfully!";
      return redirect()->back()->with('success_message',$message);


    }

    public function addAttributes(Request $request,$id){
        Session::put('page','products');
        $product = Product::select('id','product_name','product_code','product_color','product_price','product_image')->with('attributes')->find($id);
        $product = json_decode(json_encode($product),true);
      
      // $product = json_decode(json_encode($product),true);
      // dd($product);
      // exit;
        
        if($request->isMethod('post'))
        {
            $data = $request->all();
            //echo "<pre>"; print_r($data); exit;
            
            foreach($data['sku'] as $key => $value)
            {
                if(!empty($value))
                {
                    //SKU duplicate check
                    $skuCount = ProductsAttribute::where('sku',$value)->count();
                     
                    if($skuCount > 0){
                        return redirect()->back()->with('error_message','Sku already Exists Please add another SKU!');
                    }

                    //Size duplicate check
                    /* need to check 
                     $sizeCount = ProductsAttribute::where(['product_id'=>$id,'size',$data['size'][$key]])->count();

                     
                    if($sizeCount > 0){
                        return redirect()->back()->with('error_message','Size already exists. Please select another size!.');
                    }
                    */

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->status = 1;
                    $attribute->save();
                }
            }

            return redirect()->back()->with('success_message','Product Attributes has been added successfully!');

        }

        return view('admin.attributes.add_edit_attributes')->with(compact('product'));
    }

   public function updateAttrStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
           // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
            //Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
            //return response()->json(['status'=>$status,'admin_id'=>$data['admin_id']]);
            ProductsAttribute::where('id',$data['attr_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'attr_id'=>$data['attr_id']]);
        }
    }
   
   public function editAttributes(Request $request)
   {
      
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach($data['attributeId'] as $key => $attribute)
            {
            if(!empty($attribute))
                {
                    ProductsAttribute::where(['id'=>$data['attributeId'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
                }
            }
            return redirect()->back()->with('success_message','Product Attributes has been Updated successfully!');
        }
   }

   public function addImages($id,Request $request){

       Session::put('page','products');
       $product = Product::select('id','product_name','product_code','product_color','product_price','product_image')->with('images')->find($id);

       if($request->isMethod('post'))
       {
          
          if($request->hasFile('images'))
          {
             $images = $request->file('images');
             foreach($images as $key => $image){
                // Generate temp image
                $image_tmp = Image::make($image);
                //get image name
                $image_name =  $image->getClientOriginalName();
                //Generate New Image Name
                $extension = $image->getClientOriginalExtension();
                $imageName = $image_name.rand(111,999999).'.'.$extension;
                $largeImagePath = 'front/images/product_images/large/'.$imageName;
                $mediumImagePath = 'front/images/product_images/medium/'.$imageName;
                $smallImagePath = 'front/images/product_images/small/'.$imageName;
                // Upload the large, medium small image after resize
                Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);
                Image::make($image_tmp)->resize(500,500)->save($mediumImagePath);
                Image::make($image_tmp)->resize(250,250)->save($smallImagePath);
                // Insert image name in product table
                $image = new ProductsImage;
                $image->image = $imageName;
                $image->product_id = $id ;
                $image->status = 1;
                $image->save();
             }
          }
          return redirect()->back()->with('success_message','Product Image have been Added successfully!');
       }

       return view('admin.images.add_images')->with(compact('product'));
   }

   public function updateImageStatus(Request $request){
       if($request->ajax()){
            $data = $request->all();
           //echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
           
            ProductsImage::where('id',$data['image_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'image_id'=>$data['image_id']]);
        }
   }


   public function deleteImage($id){
          //delete product
        ProductsImage::where('id',$id)->delete();
        $message = "Image has been deleted successfully!";
        return redirect()->back()->with('success_message',$message);
   }

}
