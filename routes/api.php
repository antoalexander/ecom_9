<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CmsPage;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('App\Http\Controllers\API')->group(function(){
  // Register user API for react app
  Route::post('register-user','APIController@registerUser');
  // Login use for react app
  Route::post('login-user','APIController@loginUser');

  //update user details / Profile API for react app.
  Route::post('update-user','APIController@updateUser');
  
   // CMS pages Routes
   $cmsUrls = CmsPage::select('url')->where('status',1)->get()->pluck('url')->toArray();
   foreach($cmsUrls as $url){
     Route::get($url,'APIController@cmsPage');
   }

   // Categories menu url
   Route::get('menu','APIController@menu');
  
  // Listing products
  Route::get('listing/{url}','APIController@listing');
  
});