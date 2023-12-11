<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Session;

class CmsController extends Controller
{
   
   public function cmspages(){
      Session::put('page','cmspages');
      $cms_pages = CmsPage::get()->toArray();
      return view('admin.pages.cms_pages')->with(compact('cms_pages'));

   }

   public function updatePageStatus(Request $request){
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

        CmsPage::where('id',$data['page_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'page_id'=>$data['page_id']]);
    }
   }

public function deletePage($id){
    //delete Page
    CmsPage::where('id',$id)->delete();
    $message = "CMS Page has been deleted successfully!";
    return redirect()->back()->with('success_message',$message);
}

public function addEditCmsPage(Request $request,$id=null){
    Session::put('page','cmspages');
    if($id==""){
        $title = "Add CMS Page";
        $cmspage =  new CmsPage();
        $message = "CMS Page added successfully!";
    }
    else{
        $title = "Edit CMS Page";
        $cmspage = CmsPage::find($id);
        $message = "CMS Page Updated successfully!";  
    }
  
    if($request->isMethod('post')){
      
        $data  = $request->all();
      
        $rules = [
            'title'=>'required',
            'url'=>'required',
            'discription'=>'required',
        ];
        
        $customMessages = [
            'title.required' =>'Title is required',
            'url.required' => 'URL is required',
            'discription.required' => 'Description is required',
        ];

         $this->validate($request,$rules,$customMessages);

         // echo "<pre>"; print_r($data); exit;
         $cmspage->title = $data['title'];
         $cmspage->url = $data['url'];
         $cmspage->description = $data['discription'];
         $cmspage->meta_title = $data['meta_title'];
         $cmspage->meta_description = $data['meta_description'];
         $cmspage->meta_keywords = $data['meta_keywords'];
         $cmspage->status = 1;
         $cmspage->save();
         return redirect('admin/cms-pages')->with('success_message',$message);
       } 
       
       return view('admin.pages.add_edit_cmspage')->with(compact('cmspage','title','message'));

   }

}
