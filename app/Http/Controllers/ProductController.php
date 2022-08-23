<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request){
    
        $product = Product::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $product->when($q, function($query) use ($q){
            $query->whereRaw("name LIKE '%'".strtolower($q)."%'");
        });

        $product->when($q, function($query) use ($status){
            $query->where("status","=",$status);
        });
        return response()->json([
            'status' => 'success',
            'data' => $product->paginate(10)
        ]);

    }

    public function create(Request $request){
        // no need in test
    }
    public function update(Request $request,$id){
        // no need in test
    }
    public function delete(Request $request,$id){
        // no need in test
    }

}
?>