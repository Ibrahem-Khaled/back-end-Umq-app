<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

class providerController extends Controller
{
    public function index()
    {
        $users = Users::where('role', 'provider')->with('products')->get();
        return view('dashboard.provider', compact('users'));
    }
}
