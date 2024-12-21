<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
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
   public function mostRelevant()
   {
      $topStores = []; 
      $topProducts = Product::orderBy('sales', 'desc')->take(5)->get();
      
      foreach($topProducts as $product) {
          $topStores[] = Store::find($product->store->id); 
      }
      

      $topStores = array_unique($topStores);
   return response()->json([
     'RelevantStores'=>$topStores
   ]);
   }
}
