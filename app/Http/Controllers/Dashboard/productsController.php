<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Service;
use Illuminate\Http\Request;

class productsController extends Controller
{
    public function index()
    {
        $products = Products::where('provider_id', \Auth::user()->id)->get();
        $Allproducts = Products::all();
        $services = Service::all();
        return view('dashboard.product', compact('products', 'services', 'Allproducts'));
    }

    public function store(Request $request)
    {
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/', $filename);
            $image = $filename;
        }
        Products::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'service_id' => $request->service_id,
            'provider_id' => \Auth::user()->id,
            'image' => $image,
        ]);
        return redirect()->back()->with('success', 'تم اضافة الخدمة بنجاح');
    }
    public function delete(Request $request, $id)
    {
        $product = Products::find($id);
        $product->delete();
        return redirect()->back()->with('success', 'تم مسح المنتج بنجاح');
    }
}
