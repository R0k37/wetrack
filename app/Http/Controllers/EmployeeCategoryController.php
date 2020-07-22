<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeCategory;
use Validator;
use JsValidator;
use Toastr;

class EmployeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $employeeCategory=new EmployeeCategory;
      if(request()->ajax())
      {
          return datatables()->of(EmployeeCategory::latest()->get())
          ->addColumn('action',function($data){
              $button='<button type="button" name="edit" id="edit" data-toggle="modal" data-target="#editModal" data-id="'.$data->emp_cat_id.'" class="edit btn btn-primary"><i class="fas fa-edit"></i></button>';
              $button.='&nbsp;&nbsp;';
              $button.='<button type="button" name="delete" id="delete" data-id="'.$data->emp_cat_id.'" class="delete btn btn-danger"><i class="fas fa-trash"></i></button>';
              return $button;
          })
          ->rawColumns(['action'])
          ->make(true);
      }
      $validator=JsValidator::make($employeeCategory->validation());
      return view('admin.employee.employeeCategory.index',['validator'=>$validator]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      {
          $employeeCategory = new EmployeeCategory;
          $validation=Validator::make($request->all(),$employeeCategory->validation());
          $jsValidator = JsValidator::validator($validation);

              $employeeCategory->emp_cat_name=$request->name;
              $employeeCategory->emp_cat_detils=$request->details;

              $employeeCategory->save();
          Toastr::success('Congratulation! New EmployeeCategory Information Saved Successfully', 'EmployeeCategory',["positionClass" => "toast-top-right"]);
          return redirect()->back();
      }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeCategory  $employeeCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id=$request->id;
        $employeeCategory=EmployeeCategory::find($id);
        return response()->json($employeeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeCategory  $employeeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $employeeCategory=New EmployeeCategory;
      $validation=Validator::make($request->all(),$employeeCategory->validation());
      $jsValidator=JsValidator::validator($validation);

      $employeeCategory=EmployeeCategory::find($id);
          $employeeCategory->emp_cat_name=$request->name;
          $employeeCategory->emp_cat_detils=$request->details;

      $employeeCategory->save();
      Toastr::success('Congratulation! New EmployeeCategory Information Updated Successfully', 'EmployeeCategory',["positionClass" => "toast-top-right"]);
      return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeCategory  $employeeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      EmployeeCategory::where('emp_cat_id',$id)->delete();
      $status=200;
      $response=[
          'status'=>$status,
          'message'=>'Successfully Deleted',
      ];
      return response()->json($response,$status);


    }
}
