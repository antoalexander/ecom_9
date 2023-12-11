<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Session;

class BrandController extends Controller
{
    public function brands(){
         Session::put('page','brands');
         $brands = brand::get()->toArray();
         /*dd($brands);*/
         return view('admin.brands.brand')->with(compact('brands'));
     }

     public function updateBrandStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
            // echo "<pre>"; print_r($status); die;
            //Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
            //return response()->json(['status'=>$status,'admin_id'=>$data['admin_id']]);

            Brand::where('id',$data['brand_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'brand_id'=>$data['brand_id']]);
        }
    }

    public function deleteBrand($id){
        //delete brand
        Brand::where('id',$id)->delete();
        $message = "brand has been deleted successfully!";
        return redirect()->back()->with('success_message',$message);
    }
    
    public function addEditBrand(Request $request,$id=null){
        Session::put('page','brands');
       
        if($id==""){
            $title = "Add brand";
            $brand = new Brand;
            $message = "brand added successfully!";
        }else{
            $title = "Edit brand";
            $brand = Brand::find($id);
            $message = "brand Updated successfully!";
        }

        if($request->isMethod('post')){
            $data = $request->all();
           // echo "<pre>"; print_r($data); die;

            $rules = [
               'brand_name'=>'required|regex:/^[\pL\s\-]+$/u',
            ];
            
            $customMessages = [
                'brand_name.required' => 'Brand Name is required',
                'brand_name.regex' => 'Valid Brand Name is required',
            ];

            $this->validate($request,$rules,$customMessages);

            $brand->name = $data['brand_name'];
            $brand->status = 1;
            $brand->save();

            return redirect('admin/brands')->with('success_message',$message);
        }

        return view('admin.brands.add_edit_brand')->with(compact('title','brand'));


    }
}
