<?php

namespace App\Http\Controllers\ApiV3;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Service;
use Illuminate\Http\Request;

class productsController extends Controller
{
    public function services()
    {
        $services = Service::all();
        return response()->json($services);
    }
    public function products($serviceId)
    {
        $products = Service::find($serviceId);
        $products->products;
        return response()->json($products);
    }
    public function productDetails($productId)
    {
        $products = Products::find($productId);
        $products->provider;
        return response()->json($products);
    }
}
