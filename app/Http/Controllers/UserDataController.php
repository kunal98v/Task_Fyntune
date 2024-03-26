<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserDataController extends Controller
{   
    public function list()
    {   
    $data = UserData::all();
    return response()->json([$data]);

    }
    
    public function create(Request $request){
    try {
        $rules = [
            'name' => 'required|string',
            'email'=> 'required|email',
            'mobile' => 'required|numeric|min:10'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
        $user = new UserData();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        try{
        $user->save();
        }
        catch(Exception $e){
            echo $e;
            $user->save();

        }

        return response()->json(['status' =>true, 'data' => $user]);

     } catch (Throwable $e) {
        Log::error($e->getMessage(), [$e->getTraceAsString()]);
        throw new HttpResponseException(response()->json(['status' => false , 'message' => 'Something Went Wrong', 'errors' => $e->getTraceAsString()]));
    }
    }   

    public function update(Request $request){

        try {
            $rules = [
            'name' => 'string',
            'email'=> 'email',
            'mobile' => 'numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails() ) {
                return response()->json(['status' => false, 'errors' => $validator->errors()]);
            }

        $record = UserData::find($request->id);

        if($record){
            $record->name = $request->name;
            $record->update(); 
            return response()->json(['status' =>true, 'data' => $record]);

        }else{
            return response()->json(['status' =>false, 'message' => "No Data Found"]);
        }
    }
        catch(Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw new HttpResponseException(response()->json(['status' => false , 'message' => 'Something Went Wrong', 'errors' => $e->getTraceAsString()]));
        }
     }

     public function delete(Request $request){
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email'=> 'email',
            'mobile' => 'numeric'
    ]);
        if ($validator->fails() ) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $record = UserData::find($request->id );
        if($record){
        $record->delete(); 
        
        return response()->json(['status' =>true, 'data' => $record]);
        }
        else{
            return response()->json(['status' =>false, 'message' => "No Data Found"]);
        }
    }catch(Throwable $e) {
        Log::error($e->getMessage(), [$e->getTraceAsString()]);
        throw new HttpResponseException(response()->json(['status' => false , 'message' => 'Something Went Wrong', 'errors' => $e->getTraceAsString()]));
    }

     }

    }