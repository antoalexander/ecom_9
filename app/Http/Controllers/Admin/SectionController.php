<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Session;

class SectionController extends Controller
{
     

    public function sections(){
         Session::put('page','sections');
         $sections = Section::get()->toArray();
         /*dd($sections);*/
         return view('admin.sections.sections')->with(compact('sections'));
     }

     public function updateSectionStatus(Request $request){
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

            Section::where('id',$data['section_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'section_id'=>$data['section_id']]);
        }
    }

    public function deleteSection($id){
        //delete section
        Section::where('id',$id)->delete();
        $message = "Section has been deleted successfully!";
        return redirect()->back()->with('success_message',$message);
    }
    
    public function addEditSection(Request $request,$id=null){
        Session::put('page','sections');
       
        if($id==""){
            $title = "Add Section";
            $section = new Section;
            $message = "Section added successfully!";
        }else{
            $title = "Edit Section";
            $section = Section::find($id);
            $message = "Section Updated successfully!";
        }

        if($request->isMethod('post')){
            $data = $request->all();
           // echo "<pre>"; print_r($data); die;

            $rules = [
               'section_name'=>'required|regex:/^[\pL\s\-]+$/u',
            ];
            
            $customMessages = [
                'section_name.required' => 'Section Name is required',
                'section_name.regex' => 'Valid Section Name is required',
            ];

            $this->validate($request,$rules,$customMessages);
            // echo "<pre>"; print_r($data); exit;
            $section->name = $data['section_name'];
            $section->status = 1;
            $section->save();

            return redirect('admin/sections')->with('success_message',$message);
        }

        return view('admin.sections.add_edit_section')->with(compact('title','section'));


    }

}
