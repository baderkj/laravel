<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
class StoresController extends Controller
{
   public function index()
   {
    return Store::paginate(10);
   }
   public function show($id)
   {
      $store=Store::find($id);
      $storeProducts=$store->products;
      return response()->json(['storeProducts'=>$storeProducts]);
   }
   public function search($name)
   {
    $Stores=Store::
    where('name',"like",'%'.$name.'%')->paginate(10);
    return response(['Stores'=>$Stores]);
   }
}
