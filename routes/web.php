<?php
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\CmsPage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
    // Admin login 
    Route::match(['get','post'],'login','AdminController@login');
     
    Route::group(['middleware'=>['admin']],function(){
      //Admin Dashboard 
      Route::get('dashboard','AdminController@dashboard');
      
      //update admin password
      Route::match(['get','post'],'update-admin-password','AdminController@updateAdminPassword');

      //Check  admin password
      Route::post('check-admin-password','AdminController@checkAdminPassword');

      //Update admin details
      Route::match(['get','post'],'update-admin-details','AdminController@updateAdminDetails');
      // update vendor details
      Route::match(['get','post'],'update-vendor-details/{slug}','AdminController@updateVendorDetails');
      
      // View Admins / Subadmins / Vendors
      Route::get('admins/{type?}','AdminController@admins');
      //view vendor details
      Route::get('view-vendor-details/{id}','AdminController@viewVendorDetails');
      
      //update admin status
      Route::post('update-admin-status','AdminController@updateAdminStatus');
      
      //Admin Logout
      Route::get('logout','AdminController@logout');

      //Sections 
      Route::get('sections','SectionController@sections');
      Route::post('update-section-status','SectionController@updateSectionStatus');
      Route::get('delete-section/{id}','SectionController@deleteSection');
      Route::match(['get','post'],'add-edit-section/{id?}','SectionController@addEditSection');

      //Brands 
      Route::get('brands','BrandController@brands');
      Route::post('update-brand-status','BrandController@updateBrandStatus');
      Route::get('delete-brand/{id}','BrandController@deleteBrand');
      Route::match(['get','post'],'add-edit-brand/{id?}','BrandController@addEditBrand');


      //Categories
      Route::get('categories','CategoryController@categories');
      Route::post('update-categories-status','CategoryController@updateCategoriesStatus');
      Route::match(['get','post'],'add-edit-category/{id?}','CategoryController@addEditCategory');
      Route::get('append-categories-level','CategoryController@appendCategoryLevel');
      Route::get('delete-category-image/{id}','CategoryController@deleteCategoryImage');

      //Products
      Route::get('products','ProductsController@products');
      Route::post('update-product-status','ProductsController@updateProductStatus');
      Route::get('delete-product/{id}','ProductsController@deleteProduct');
      Route::match(['get','post'],'add-edit-product/{id?}','ProductsController@addEditProduct');

      Route::get('delete-product-image/{id}','ProductsController@deleteProductImage');
      Route::get('delete-product-video/{id}','ProductsController@deleteProductVideo');

      //attributes
      Route::match(['get','post'],'add-edit-attributes/{id}','ProductsController@addAttributes');
      Route::post('update-attr-status','ProductsController@updateAttrStatus');
      Route::get('delete-product/{id}','ProductsController@deleteAttr');
      Route::match(['get','post'],'edit-attributes/{id}','ProductsController@editAttributes');

      //Filters
      Route::get('filters','FilterController@filters');
      Route::get('filters-values','FilterController@filtersValues');
      Route::post('update-filter-status','FilterController@updateFilterStatus');
      Route::post('update-filter-value-status','FilterController@updateFilterValueStatus');
      Route::match(['get','post'],'add-edit-filter/{id?}','FilterController@addEditFilter');
      Route::match(['get','post'],'add-edit-filter-value/{id?}','FilterController@addEditFilterValue');
      Route::post('category-filters','FilterController@categoryFilters');

      //Images
      Route::match(['get','post'],'add-images/{id}','ProductsController@addImages');
      Route::post('update-image-status','ProductsController@updateImageStatus');
      Route::get('delete-image/{id}','ProductsController@deleteImage');
     
      //Banners
      Route::get('banners','BannersController@banners');
      Route::post('update-banner-status','BannersController@updateBannerStatus');
      Route::get('delete-banner/{id}','BannersController@deleteBanner');
      Route::match(['get','post'],'add-edit-banner/{id?}','BannersController@addEditBanner');

      // CMS pages
      Route::get('cms-pages','CmsController@cmspages');
      Route::post('update-cms-page-status','CmsController@updatePageStatus');
      Route::get('delete-page/{id}','CmsController@deletePage');
      Route::match(['get','post'],'add-edit-cms-page/{id?}','CmsController@addEditCmsPage');

    });
   
  });

  Route::namespace('App\Http\Controllers\Front')->group(function(){
      Route::get('/','IndexController@index');
      //Listing/Categories Routes
      $catUrls = Category::select('url')->where('status',1)->get()->pluck('url')->toArray();
      //dd($catUrls); exit;
      foreach($catUrls as $key => $url){
         Route::match(['get','post'],'/'.$url,'ProductsController@listing');
      }
       
      // CMS pages Routes
      $cmsUrls = CmsPage::select('url')->where('status',1)->get()->pluck('url')->toArray();
      foreach($cmsUrls as $url){
        Route::get($url,'CmsController@cmsPage');
      }

      //vendor products
      Route::get('/products/{vendorid}','ProductsController@vendorListing');  
      
      //Products details page
      Route::get('/product/{id}','ProductsController@detail');

      // Get product attribute price
      Route::post('get-product-price','ProductsController@getProductPrice');
      
      // vendor login/register
      Route::get('/vendor/login-register','VendorController@loginRegister');
      Route::post('vendor/register','VendorController@vendorRegister');
      //confirm vendor account
      Route::get('vendor/confirm/{code}','VendorController@confirmVendor');

      // add to cart route
      Route::post('cart/add','ProductsController@cartAdd');
      
      //cart route
      Route::get('cart','ProductsController@cart');

      //update cart item quantity
      Route::post('cart/update','ProductsController@cartUpdate');

      //Delete cart item 
      Route::post('cart/delete','ProductsController@cartDelete');

      // user login/register
      Route::get('/user/login-register','UserController@loginRegister');

      //User Register 
      Route::post('user/register','UserController@userRegister');
      //User account
      Route::match(['GET','POST'],'user/account','UserController@userAccount');
      //User account
      Route::post('user/update-password','UserController@userUpdatePassword');

      //User login 
      Route::post('user/login','UserController@userLogin');
      //user forgot password
      Route::match(['get','post'],'user/forgot-password','UserController@forgotPassword');
      //user logout
      Route::get('user/logout','UserController@userLogout');

      //confirm user account
      Route::get('user/confirm/{code}','UserController@confirmAccount');
      
  }); 

