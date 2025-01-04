<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function index()
    {
        return User::all();

    }
    
    public function store(Request $request)
    {
        $user=$request->user();
        $request->validate([ 
            'image' => 'nullable|image|max:2048',
        ]);
          
    
        $imageUrl=null;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $imageUrl=Storage::url($imagePath);

        }
            $user->update([ 
                'image' => $imageUrl ,       
            ]);
        
            // Return response
            return response()->json(['user' => $user], 201);

       
    }
}
