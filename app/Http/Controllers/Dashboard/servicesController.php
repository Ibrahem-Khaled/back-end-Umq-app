<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class servicesController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('dashboard.services', compact('services'));
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
        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
        ]);
        return redirect()->back()->with('success', 'تم اضافة الخدمة بنجاح');
    }
    public function delete(Request $request, $id)
    {

        $service = Service::find($id);
        $service->delete();
        return redirect()->back()->with('success', 'تم مسح الخدمة بنجاح');
    }
}
