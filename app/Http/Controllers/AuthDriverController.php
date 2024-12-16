<?php

namespace App\Http\Controllers;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthDriverController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:drivers',
            'password' => 'required|string|min:8',
        ]);

        $driver=Driver::create([
            'name' => $request->name,
            
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
       
        ]);
        $token=$driver->createToken('driver_auth')->plainTextToken;
        return response()->json(['driver' => $driver,
        'token'=> $token,   
    ], 201);
    }

    public function login(Request $request)
    {
        try{
            $checkReq=$request->validate([
                'phone' => 'required|string',
                'password' => 'required|string',
            ]);
            $driver = Driver::where('phone',$checkReq['phone'])->first();
            if(! $driver )
            {
                return response()->json(['message' => 'Wrong Mobile Number'], 401);
            }
            elseif(! Hash::check($checkReq['password'], $driver->password))
            {
                return response()->json(['message' => 'Wrong Password'], 401);
            }

            $token=$driver->createToken('driver_auth')->plainTextToken;
            return response()->json(['token' => $token, 'driver' => $driver], 200);
            
    
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 201);
        }
        
        
    }

    public function logout(Request $request)
    {
        $request->user('driver')->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
