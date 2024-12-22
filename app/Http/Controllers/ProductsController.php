<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductsController extends Controller
{
    public function index()
    {
     return Product::paginate(10);
    }
    public function search($name)
    {
 
 
      $Products=Product::
      where('name',"like",'%'.$name.'%')->paginate(10);
      return response(['products'=>$Products]);
    }
    public function show($id)
    {
      $Product=Product::find($id);
      return response(['product'=>$Product]);
    }
    public function mostRelevant()
    {
      ////my push dddd
      $topProducts = Product::orderBy('sales', 'desc')->take(5)->get();
    return response()->json([
      'RelevantProducts'=>$topProducts
    ]);
    }
}
