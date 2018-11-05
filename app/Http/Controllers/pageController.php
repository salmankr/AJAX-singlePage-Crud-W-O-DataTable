<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\citizen;

class pageController extends Controller
{
    public function index(){
    	return view('welcome');
    }

    public function create(Request $request){
    	$request->validate([
            'name'=>'required',
            'cnic'=>'required|unique:citizens',
    	]);
        $data = new citizen;
        $data->name = $request->get('name');
        $data->cnic = $request->get('cnic');
        $data->save();
        return $data;
    }

    public function show(){
    	$data = citizen::all();
    	return $data;
    }
    public function delete($id){
    	$data = citizen::find($id);
    	$data->delete();
    	return $data;
    }
    public function getData($id){
        $data = citizen::find($id);
        return $data;
    }
    public function update(Request $request, $id){
        $request->validate([
            'name'=>'required',
            'cnic'=>'required',
        ]);
        $data = citizen::find($id);
        $data->name = $request->get('name');
        $data->cnic = $request->get('cnic');
        $data->save();
        return $data;
    }
}
