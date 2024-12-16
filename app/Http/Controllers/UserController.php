<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::all();

    }
    
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'location' => 'required|string|max:255',
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:8', 
        // ]);
    
       
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
    
        // Create a new user
        try{
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'location' => $request->location,
                'image' => $request->image , // Assuming you have a method to handle the image upload
                'mobile_number' => $request->mobile_number,
                'password' => Hash::make($request->password), // Hash the password for security
            ]);
        
            // Return response
            return response()->json(['user' => $user], 201);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 201);
        }
    }
}
