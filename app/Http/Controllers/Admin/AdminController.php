<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\VendorsBusinessDetail;
use App\Models\VendorsBankDetail;
use App\Models\Country;
use Image;
use Session;

class AdminController extends Controller
{
    public function dashboard(){
        Session::put('page','dashboard');
        return view('admin.dashboard');
    }

    public function updateAdminPassword(Request $request)
    {
          /* echo "<pre>"; print_r(Auth::guard('admin')->user()); exit();*/
          Session::put('page','update_admin_password');
          if($request->isMethod('post'))
          {
             $data = $request->all();
            /* echo "<pre>"; print_r($data); exit();*/
             // Check if current password entered by admin is correct
             if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password))
             {
               // check if new password is matchng with confim password
               if($data['new_password']==$data['confirm_password'])
               {
                  Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($data['new_password'])]);
                  return redirect()->back()->with('success_message','Password has been updated successfully!');
               }
               else{
                  return redirect()->back()->with('error_message','New password is not matching with Confirm password');
               }
             }
             else
             {
                 return redirect()->back()->with('error_message','Your current password is incorrect!');
             }
          }
          $adminDetails = Admin::where('email',Auth::guard('admin')->user()->email)->first()->toArray();
          return view('admin.settings.update_admin_password')->with(compact('adminDetails'));
    } 

    public function updateAdminDetails(Request $request){
        Session::put('page','update_admin_details');
        if($request->isMethod('post'))
        {
            $data = $request->all();
             // echo "<pre>";print_r($data); exit;
            $rules = [
               'admin_name'=>'required|regex:/^[\pL\s\-]+$/u',
               'admin_mobile'=>'required|numeric'
            ];
            
            $customMessages = [
                'admin_name.required' => 'Name is required',
                'admin_name.regex' => 'Valid Name is required',
                'admin_mobile.required' => 'Mobile Number is required',
                'admin_mobile.numeric' => 'Valid Mobile Number is required',
            ];

            $this->validate($request,$rules,$customMessages);
        
            //Upload Admin Photo
            if($request->hasFile('admin_image')){
             
                $image_tmp = $request->file('admin_image');
                if($image_tmp->isValid()){
                    //Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    //Generate New Image Name
                    $imageName = rand(111,999999).'.'.$extension;
                    $imagePath = 'admin/images/photos/'.$imageName;
                    //Upload the Image
                    Image::make($image_tmp)->save($imagePath);
                }
               
            }
             else if(!empty($data['current_admin_image']))
                {
                    $imageName = $data['current_admin_image'];
                }
                else{
                    $imageName = "";
                }
    
            //update admin details
            Admin::where('id',Auth::guard('admin')->user()->id)->update(['name'=>$data['admin_name'],'mobile'=>$data['admin_mobile'],'image'=>$imageName]);
            return redirect()->back()->with('success_message','Admin details updated successfully!');
        }
        return view('admin.settings.update_admin_details');
    }
    
    public function updateVendorDetails($slug,Request $request){
        if($slug=="personal"){
          Session::put('page','update_personal_details');
         if($request->isMethod('post'))
         {
            $data = $request->all();
         /*   echo "<pre>"; print_r($data); die;*/

             $rules = [
               'vendor_name'=>'required|regex:/^[\pL\s\-]+$/u',
               'vendor_mobile'=>'required|numeric'
            ];
            
            $customMessages = [
                'vendor_name.required' => 'Name is required',
                'vendor_name.regex' => 'Valid Name is required',
                'vendor_mobile.required' => 'Mobile Number is required',
                'vendor_mobile.numeric' => 'Valid Mobile Number is required',
            ];

            $this->validate($request,$rules,$customMessages);
        
            //Upload Admin Photo
            if($request->hasFile('vendor_image')){
             
                $image_tmp = $request->file('vendor_image');
                if($image_tmp->isValid()){
                    //Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    //Generate New Image Name
                    $imageName = rand(111,999999).'.'.$extension;
                    $imagePath = 'admin/images/photos/'.$imageName;
                    //Upload the Image
                    Image::make($image_tmp)->save($imagePath);
                }
               
            }
             else if(!empty($data['current_vendor_image']))
                {
                    $imageName = $data['current_vendor_image'];
                }
                else{
                    $imageName = "";
                }
    
            //update in admins table
            Admin::where('id',Auth::guard('admin')->user()->id)->update(['name'=>$data['vendor_name'],'mobile'=>$data['vendor_mobile'],'image'=>$imageName]);
            
            // Update in vendors table
            Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->update(['name'=>$data['vendor_name'],'mobile'=>$data['vendor_mobile'],'city'=>$data['vendor_city'],'state'=>$data['vendor_state'],'country'=>$data['vendor_country'],'pincode'=>$data['vendor_pincode'],'image'=>$imageName]);

            return redirect()->back()->with('success_message','Vendor details updated successfully!');
         }

         $vendorDetails = Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();

        }else if($slug=="business"){

          Session::put('page','update_business_details');
           
         if($request->isMethod('post'))
         {
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
             $rules = [
               'shop_name'=>'required|regex:/^[\pL\s\-]+$/u',
               'shop_city'=>'required|regex:/^[\pL\s\-]+$/u',
               'shop_mobile'=>'required|numeric',
               'address_proof'=>'required',
              
            ];
            
            $customMessages = [
                'shop_name.required' => 'Name is required',
                'shop_city.required' => 'City Name is required',
                'shop_name.regex' => 'Valid Shop Name is required',
                'shop_city.regex' => 'Valid City Name is required',
                'shop_mobile.required' => 'Mobile Number is required',
                'shop_mobile.numeric' => 'Valid Mobile Number is required',
             
            ];

            $this->validate($request,$rules,$customMessages);
        
            //Upload Admin Photo
            if($request->hasFile('address_bank_image')){
             
                $image_tmp = $request->file('address_proof_image');
                if($image_tmp->isValid()){
                    //Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    //Generate New Image Name
                    $imageName = rand(111,999999).'.'.$extension;
                    $imagePath = 'admin/images/proofs/'.$imageName;
                    //Upload the Image
                    Image::make($image_tmp)->save($imagePath);
                }
               
            }
              else if(!empty($data['current_address_proof']))
                {
                    $imageName = $data['current_address_proof'];
                }
                else{
                    $imageName = "";
                }
            
            $vendorCount = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
            if($vendorCount>0){
                 //update in vendors business details table
                VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update(['shop_name'=>$data['shop_name'],'shop_mobile'=>$data['shop_mobile'],'shop_city'=>$data['shop_city'],'shop_state'=>$data['shop_state'],'shop_country'=>$data['shop_country'],'shop_pincode'=>$data['shop_pincode'],'business_license_number'=>$data['business_license_number'],'gst_number'=>$data['gst_number'],'pan_number'=>$data['pan_number'],'address_proof'=>$data['address_proof'],'address_proof_image'=>$imageName]);
            }
            else
            {
                //insert vendor details
                VendorsBusinessDetail::insert(['vendor_id'=>Auth::guard('admin')->user()->vendor_id,'shop_name'=>$data['shop_name'],'shop_mobile'=>$data['shop_mobile'],'shop_city'=>$data['shop_city'],'shop_state'=>$data['shop_state'],'shop_country'=>$data['shop_country'],'shop_pincode'=>$data['shop_pincode'],'business_license_number'=>$data['business_license_number'],'gst_number'=>$data['gst_number'],'pan_number'=>$data['pan_number'],'address_proof'=>$data['address_proof'],'address_proof_image'=>$imageName]);
            }
           
          
            return redirect()->back()->with('success_message','Vendor details updated successfully!');
         }
         
         $vendorCount = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
         

           if($vendorCount>0){
            $vendorDetails = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
         }else{
             $vendorDetails = array();
         }
        }
        else if($slug=="bank"){
           Session::put('page','update_admin_details');
           if($request->isMethod('post'))
           {
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
             $rules = [
               'account_holder_name'=>'required|regex:/^[\pL\s\-]+$/u',
               'bank_name'=>'required',
               'account_number'=>'required|numeric',
               'bank_ifsc_code'=>'required',
              
            ];
            
            $customMessages = [
                'account_holder_name.required' => 'Account Holder Name is required',
                'account_holder_name.regex' => 'Valid Account Holder Name is required',
                'bank_name.required' => 'Bank Name is required',
                'account_number.required' => 'Mobile Number is required',
                'bank_ifsc_code.numeric' => 'Bank IFSC Code is required',
             
            ];

            $this->validate($request,$rules,$customMessages);
        
            //update in vendors bank details table
           $vendorCount = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
            if($vendorCount>0){
            VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update(['account_holder_name'=>$data['account_holder_name'],'bank_name'=>$data['bank_name'],'account_number'=>$data['account_number'],'bank_ifsc_code'=>$data['bank_ifsc_code']]);
            }
            else
            {
               VendorsBankDetail::insert(['vendor_id'=>Auth::guard('admin')->user()->vendor_id,'account_holder_name'=>$data['account_holder_name'],'bank_name'=>$data['bank_name'],'account_number'=>$data['account_number'],'bank_ifsc_code'=>$data['bank_ifsc_code']]); 
            }
            return redirect()->back()->with('success_message','Bank details updated successfully!');
         }

      
         $vendorCount = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();

         if($vendorCount>0)
         {
            $vendorDetails = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
         }
         else
         {
             $vendorDetails = array();
         }
        }

        $countries = Country::where('status',1)->get()->toArray();

        return view('admin.settings.update_vendor_details')->with(compact('slug','vendorDetails','countries'));
    }

    public function checkAdminPassword(Request $request){
         $data = $request->all();
         /*echo "<pre>"; print_r($data); exit;*/
         if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
            return "true";
         }
         else{
            return "false";
         }
    }

    public function login(Request $request)
    {    
         //echo $password = Hash::make('123456');die;
         if($request->isMethod('post'))
         {
            $data = $request->all();
           /* echo "<pre>"; print_r($data); exit();*/


            $rules = [
             'email' => 'required|email|max:255',
             'password' => 'required',
            ];

            $customMessages = [
              // Add custom messages here
              'email.required' => 'Email Address is required!',
              'email.email' => 'Valid Email Address is required!',
              'password.required' => 'Password is required'
            ];

            $this->validate($request,$rules,$customMessages);

            /*if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password'],'status'=>1])){
                 return redirect('admin/dashboard');
            }
            else{
               redirect()->back()->with("error_message","Invalid Email or Password"); 
            }*/

            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password']])){
                 if(Auth::guard('admin')->user()->type=="vendor" && Auth::guard('admin')->user()->confirm=="No")
                 {
                    return redirect()->back()->with('error_message','Pls Confirm Your email to activate your vendor account');
                 }
                 else if(Auth::guard('admin')->user()->type!="vendor" && Auth::guard('admin')->user()->status=="0"){
                   return redirect()->back()->with('error_message','Your admin account is not active');  
                 }
                 else{
                   return redirect('admin/dashboard');  
                 }
                 
            }
            else{
               redirect()->back()->with("error_message","Invalid Email or Password"); 
            }
            
         }
         return view('admin.login');
    }

    public function admins($type=null)
    {
       $admins = Admin::query();
       if(!empty($type)){
           $admins = $admins->where('type',$type);
           $title = ucfirst($type)."s";
           Session::put('page','view_'.strtolower($title));
       }else{
          $title = "Admins/Subadmins/Vendors";
          Session::put('page','view_all');
       }
      
       $admins = $admins->get()->toArray();
       //dd($admins);
       return view('admin.admins.admins')->with(compact('admins','title'));
    }

    public function viewVendorDetails($id){
        $vendorDetails = Admin::with('vendorPersonal','vendorBusiness','vendorBank')->where('id',$id)->first();
       // dd($vendorDetails);
        $vendorDetails = json_decode(json_encode($vendorDetails),true);
        //dd($vendorDetails);
        return view('admin.admins.view_vendor_details')->with(compact('vendorDetails'));
    }

    public function updateAdminStatus(Request $request){
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

            Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
            $adminDetails = Admin::where('id',$data['admin_id'])->first()->toArray();
            //$adminType = Auth::guard('admin')->user()->type;
            if($adminDetails['type']=="vendor" && $status==1)
            {
                Vendor::where('id',$adminDetails['vendor_id']);
                // send approval email
                $email = $adminDetails['email'];
                $messageData = [
                    'email' => $adminDetails['email'],
                    'name'  => $adminDetails['name'],
                    'mobile'  => $adminDetails['mobile']
                ];

            Mail::send('emails.vendor_approved',$messageData,function($message)use($email){
                   $message->to($email)->subject('Vendor Account is Approved');
                });
            }
            return response()->json(['status'=>$status,'admin_id'=>$data['admin_id']]);
        }
    }


    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }


}
