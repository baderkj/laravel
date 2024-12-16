<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

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
}
