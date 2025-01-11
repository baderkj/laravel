<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteProductsController extends Controller
{
    public function favorite(Request $request, $id)
    {
        $request->user()->favoriteProducts()->attach($id);

        return response()->json(['message' => 'Product favorited successfully']);
    }

    public function unfavorite(Request $request, $id)
    {
        $request->user()->favoriteProducts()->detach($id);

        return response()->json(['message' => 'Product unfavorited successfully']);
    }

    public function favorites(Request $request)
    {
        $favorites = $request->user()->favoriteProducts()->get();

        return response()->json(['products'=>$favorites],200);
    }
}
