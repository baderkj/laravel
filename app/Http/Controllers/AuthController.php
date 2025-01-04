<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'mobile_number' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $imageUrl=null;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $imageUrl=Storage::url($imagePath);

        }
    
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'location' => $request->location,
            'image' => $imageUrl,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
       
        ]);
        $token=$user->createToken('auth_token')->plainTextToken;
        return response()->json(['user' => $user,
        'token'=> $token,   
    ], 201);
    }

    public function login(Request $request)
    {
        try{
            $checkReq=$request->validate([
                'mobile_number' => 'required|string|',
                'password' => 'required|string',
            ]);
            $user = User::where('mobile_number',$checkReq['mobile_number'])->first();
            if(! $user )
            {
                return response()->json(['message' => 'Wrong Mobile Number'], 401);
            }
            elseif(! Hash::check($checkReq['password'], $user->password))
            {
                return response()->json(['message' => 'Wrong Password'], 401);
            }

            $token=$user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
            
    
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 201);
        }
        
        
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
