<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductsFilter;
use App\Models\ProductsFiltersValue;
use App\Models\Section;
use Session;
use DB;

class FilterController extends Controller
{
    public function filters(){
        Session::put('page','filters');
        $filters = ProductsFilter::get()->toArray();
        //dd($filters); die;
        return view('admin.filters.filters')->with(compact('filters'));
   }

   public function filtersValues(){
        Session::put('page','filters');
        $filters_values = ProductsFiltersValue::get()->toArray();
        //dd($filters); die;
        return view('admin.filters.filters_values')->with(compact('filters_values'));
   }

   public function updateFilterStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
           ProductsFilter::where('id',$data['filter_id'])->update(['status'=>$status]);
           return response()->json(['status'=>$status,'filter_id'=>$data['filter_id']]);
        }
    }

    

      public function updateFilterValueStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
           ProductsFiltersValue::where('id',$data['filter_id'])->update(['status'=>$status]);
           return response()->json(['status'=>$status,'filter_id'=>$data['filter_id']]);
        }
    }

    public function addEditFilter(Request $request,$id=null)
    {
        Session::put('page','filters');
        if($id==""){
            $title ="Add Filter Column";
            $filter =new ProductsFilter;
            $message = "Filter added successfully";
        }
        else
        {
            $title ="Edit Filter Column";
            $filter =ProductsFilter::find($id);
            $message = "Filter Updated successfully";
        }

        if($request->isMethod('post'))
        {
            $data = $request->all();
            //echo "<pre>"; print_r($data); exit;

            $cat_ids = implode(',',$data['cat_ids']);

            // Save filter column details in products_filters table
            $filter->cat_ids = $cat_ids;
            $filter->filter_name = $data['filter_name'];
            $filter->filter_column = $data['filter_column'];
            $filter->status = 1;
            $filter->save();

            //Add filter column in products table
            DB::statement('alter table products add '.$data['filter_column'].' varchar(255) after description');
            return redirect('admin/filters')->with('success_message',$message);
        }
        
        // get sectionswith categories and sub categories

        $categories = Section::with('categories')->get()->toArray();

        return view('admin.filters.add_edit_filter')->with(compact('title','categories','filter'));
    }

     public function addEditFilterValue(Request $request,$id=null)
    {
        Session::put('page','filters');
        if($id==""){
            $title ="Add Filter Value";
            $filter =new ProductsFiltersValue;
            $message = "Filter Value added successfully";
        }
        else
        {
            $title ="Edit Filter Values Value";
            $filter =ProductsFiltersValue::find($id);
            $message = "Filter Value Updated successfully";
        }

        if($request->isMethod('post'))
        {
            $data = $request->all();
            //echo "<pre>"; print_r($data); exit;
            
            // Save filter column details in products_filters_values  table
          
            $filter->filter_id = $data['filter_id'];
            $filter->filter_value = $data['filter_value'];
            $filter->status = 1;
            $filter->save();

            return redirect('admin/filters-values')->with('success_message',$message);
        }
        
        //get Filters
        $filters = ProductsFilter::where('status',1)->get()->toArray();

        //echo "<pre>"; print_r($filters); exit;
        
        return view('admin.filters.add_edit_filter_value')->with(compact('title','filter','filters'));

   }

   public function categoryFilters(Request $request){
        if($request->ajax()){
            $data = $request->all();
            //echo "<pre>"; print_r($data); exit;
            $category_id = $data['category_id'];
            return response()->json([
                'view'=>(String)View::make('admin.filters.category_filters')->with(compact('category_id'))
            ]);
        }
   }
     

}
