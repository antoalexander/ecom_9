<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Sms;
use App\Models\Cart;
use App\Models\Country;
use Auth;
use Validator;
use Session;

class UserController extends Controller
{
    public function loginRegister(){
        return view('front.users.login_register');
    }

    public function userAccount(Request $request){
        if($request->ajax()){
            $data = $request->all();
            /* echo "<pre>"; print_r($data); exit;*/
            $validator = Validator::make($request->all(),[
                 'name' => 'required|string|max:100',
                 'city' => 'required|string|max:100',
                 'state' => 'required|string|max:100',
                 'address' => 'required|string|max:100',
                 'country' => 'required|string|max:100',
                 'mobile' => 'required|numeric|digits:10',
                 'pincode' => 'required|numeric|digits:6',
                 
             ]);

            if($validator->passes())
            {
              // update user details
              User::where('id',Auth::user()->id)->update(['name'=>$data['name'],'mobile'=>$data['mobile'],'city'=>$data['city'],'state'=>$data['state'],'country'=>$data['country'],'pincode'=>$data['pincode'],'address'=>$data['address']]);
               //redirect back user with success message
               return response()->json(['type'=>'success','message'=>'Your Contact/billing Details Succesfully Updated']);

              
            }
            else
            {
             return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }
        else
        {
             $countries = Country::where('status',1)->get()->toArray();
            return view('front.users.user_account')->with(compact('countries'));
        }

    }
   
    public function userUpdatePassword(Request $request){
        if($request->ajax()){
            $data = $request->all();
            echo "<pre>"; print_r($data); exit;
            $validator = Validator::make($request->all(),[
                 'current_password' => 'required',
                 'new_password' => 'required|min:6',
                 'confirm_password' => 'required|min:6|same:new_password'
             ]);

            if($validator->passes())
            {
             
               //redirect back user with success message
               return response()->json(['type'=>'success','message'=>'Your Contact/billing Details Succesfully Updated']);

              
            }
            else
            {
             return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }
        else
        {
             $countries = Country::where('status',1)->get()->toArray();
            return view('front.users.user_account')->with(compact('countries'));
        }

    }



    public function userRegister(Request $request)
    {
        if($request->ajax()){
             $data = $request->all();
             /*echo "<pre>"; print_r($data);   exit;*/

             $validator = Validator::make($request->all(),[
                 'name' => 'required|string|max:100',
                 'mobile' => 'required|numeric|digits:10',
                 'email' => 'required|email|max:150|unique:users',
                 'password' => 'required|min:6', 
                 'accept' => 'required', 
             ],
              [
                'accept.required' => 'Please accept our Terms & Conditions'
              ]
             );

             if($validator->passes()){
                 //register the user
                 $user = new User;
                 $user->name = $data['name'];
                 $user->mobile = $data['mobile'];
                 $user->email = $data['email'];
                 $user->password = bcrypt($data['password']);
                 $user->status = 0;
                 $user->save();
                 
                /* activate the user when user  confirming his email account */
                $email = $data['email'];
                $messageData = ['name'=>$data['name'],'email'=>$data['email'],'code'=>base64_encode($data['email'])];
                 
                 Mail::send('emails.confirmation',$messageData,function($message)use($email){
                     $message->to($email)->subject('Confirm Your Stack Developers Account');
                 });

                 //Redirect back user with success message
                 $redirectTo = url('user/login-register');
                 return response()->json(['type'=>'success','url'=>$redirectTo,'message'=>'Please confirm your email to activate your account!']);
                 
                /* activate the user when user creates his account*/

                /* // send register email
                 $email = $data['email'];
                 $messageData = ['name'=>$data['name'],'mobile'=>$data['mobile'],'email'=>$data['email']];

                 Mail::send('emails.register',$messageData,function($message)use($email){
                     $message->to($email)->subject('Welcome to E-Commerce');
                 });

                 // send register sms
                 $message ="Dear Customer, you have been successfully register with stack developers. Login to your account to access orders, addresses & available offers.";

                 $mobile = $data['mobile'];
                 Sms::sendSms($message,$mobile);

                 if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                    $redirectTo = url('cart');
                    return response()->json(['type'=>'success','url'=>$redirectTo]);
                  }*/
             }
                else{
                   return response()->json(['type'=>'error','errors'=>$validator->messages()]);
                 }

            
        }

    }

    public function forgotPassword(Request $request){
        if($request->ajax()){
            $data = $request->all();
           // echo "<pre>"; print_r($data); exit;
            $validator = Validator::make($request->all(),[
               'email' => 'required|email|max:150|exists:users',
             ],
             [
                'email.exists' => 'Email does not exists',
             ]
            );

            if($validator->passes()){
                //$userDetails = User::where('email',$data['email'])->first();
                 //generate new password
                 $new_password = Str::random(16);
                 //Update new password
                 User::where('email',$data['email'])->update(['password'=>bcrypt($new_password)]);
                 //get user details
                 $userDetails = User::where('email',$data['email'])->first()->toArray();
                 //send email to user 
                 $email = $data['email'];
                 $messageData = ['name'=>$userDetails['name'],'email'=>$email,
                 'password'=>$new_password];
                  Mail::send('emails.user_forgot_password',$messageData,function($message)use($email){
                     $message->to($email)->subject('New password - Stack Developer');
                  });

                  //show success message
                  return response()->json(['type'=>'success','message'=>'New Password sent to your registed email.']);

            }
            else
            {
               return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }

        }
        return view('front.users.forgot_password');
    }

    public function userLogin(Request $request){
        if($request->Ajax()){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); exit;*/

            $validator = Validator::make($request->all(),[
                 'email' => 'required|email|max:150|exists:users',
                 'password' => 'required|min:6', 
              ]);
            if($validator->passes()){
               if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){

                    if(Auth::user()->status==0){
                       Auth::logout();
                       return response()->json(['type'=>'inactive','message'=>'Your account is inactive Pls contact Admin']);
                    }

                    //Update User cart with user id
                    if(!empty(Session::get('session_id'))){
                        $user_id = Auth::user()->id;
                        $session_id = Session::get('session_id');
                        Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                    }

                    $redirectTo = url('cart');
                    return response()->json(['type'=>'success','url'=>$redirectTo]);
                  }
                  else{
                     return response()->json(['type'=>'incorrect','message'=>'Incorrect Email Or Password']);
                  }
            }
            else{
                return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }
    }

    public function userLogout(){
         Auth::logout();
         return redirect('/');
    }

    public function confirmAccount($code){
       $email = base64_decode($code);
       $userCount = User::where('email',$email)->count();
       if($userCount>0){
          $userDetails = User::where('email',$email)->first();
          if($userDetails->status==1){
             // redirect the user to login/register page with error message
             return redirect('login')->with('error_message','Your account is already activated. You can login now.');
          }else{
             User::where('email',$email)->update(['status'=>1]);
            
             //send welcome email
             $messageData = ['name'=>$userDetails->name,'mobile'=>$userDetails->mobile,'email'=>$email];

             Mail::send('emails.register',$messageData,function($message)use($email){
                     $message->to($email)->subject('Welcome to E-Commerce');
             });
            
             return redirect('user/login-register')->with('success_message','Your account is activated. You can login now.');

          }
       }
       else{
          abort(404);
       }
    }



}
