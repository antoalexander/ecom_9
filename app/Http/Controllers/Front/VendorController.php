<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Vendor;
use DB;

class VendorController extends Controller
{
    

  public function loginRegister(){
      return view('front.vendors.login_register');
  }

  public function vendorRegister(Request $request){
     if($request->isMethod('post'))
     {
        $data = $request->all();
      //  echo "<pre>"; print_r($data); exit;

        //validator vendor
        $rules =[
           "name" => "required",
           "email" => "required|email|unique:admins|unique:vendors",
           "mobile" => "required|min:10|numeric|unique:admins|unique:vendors",
           "accept" => "required"
        ];
        $customMessages = [
          "name.required" => "Name is required",
          "email.required" => "Email is required",
          "email.unique" => "Email already Exists",
          "mobile.required" => "Mobile is required",
          "mobile.unique" => "Mobile already Exists",
          "accept.required" => "Please accept T&C"
        ];

        $validator = Validator::make($data,$rules,$customMessages);
        if($validator->fails())
        {
           return Redirect::back()->withErrors($validator);
        }
         
        DB::beginTransaction();

        //create vendor account
        
        //create vendor  detaisl in admin table 
        $vendor = new Vendor;
        $vendor->name = $data['name'];
        $vendor->mobile = $data['mobile'];
        $vendor->email = $data['email'];
        $vendor->status = 0;

        // set default time zone
        date_default_timezone_set("Asia/Kolkata");
        $vendor->created_at = date("Y-m-d H:i:s");
        $vendor->updated_at = date("Y-m-d H:i:s");
        $vendor->save();

        $vendor_id = DB::getPdo()->lastInsertId();

        // Insert the vendor details in admin table
        $admin = new Admin;
        $admin->type = 'vendor';
        $admin->vendor_id = $vendor_id;
        $admin->name = $data['name'];
        $admin->mobile = $data['mobile'];
        $admin->email = $data['email'];
        $admin->password = bcrypt($data['password']);
        $admin->status = 0;
        // set default time zone
        date_default_timezone_set("Asia/Kolkata");
        $admin->created_at = date("Y-m-d H:i:s");
        $admin->updated_at = date("Y-m-d H:i:s");
        $admin->save();

        // send confirmation email
        $email = $data['email'];
        $messageData = [
            'email' => $data['email'],
            'name'  => $data['name'],
            'code'  => base64_encode($data['email'])
        ];

        Mail::send('emails.vendor_confirmation',$messageData,function($message)use($email){
           $message->to($email)->subject('Confirm your Vendor Account');
        });
        
        DB::commit();

        // Redirect back vendor with success message

        $message = "Thanks for registering as Vendor. Pls Confirm your email to activate your account.";

        return redirect()->back()->with('success_message',$message);
     }
   }

   public function confirmVendor($email){
      // decode vendor email
      $email = base64_decode($email);
      // check vendor email exists
      $vendorCount  = Vendor::where('email',$email)->count();
      if($vendorCount>0){
        //vendor emailis already activated or not
        $vendorDetails = Vendor::where('email',$email)->first();
        if($vendorDetails->confirm == "Yes"){
           $message = "Your Vendor Account is already confirmed. You can login";
           return redirect('vendor/login-register')->with('error_message',$message);
        }
        else
        {
           //update confirm column to yes in both admins / vendors tables to active account
           Admin::where('email',$email)->update(['confirm'=>'Yes']);
           Vendor::where('email',$email)->update(['confirm'=>'Yes']);

           //send register email

       // $email = $data['email'];
        $messageData = [
            'email' => $email,
            'name'  => $vendorDetails->name,
            'mobile'=> $vendorDetails->mobile
        ];

        Mail::send('emails.vendor_confirmed',$messageData,function($message)use($email){
           $message->to($email)->subject('Your Vendor account is confirmed');
        });

           // redirect to vendor login/register page with success message
           $message = "Your Vendor Email account is confirmed. You can login and add your personal, business and bank details to activate your Vendor Account to add products.";
           return redirect('vendor/login-register')->with('success_message',$message);
        }
      }
      else
      {
         abort(404);
      }

   }

}
