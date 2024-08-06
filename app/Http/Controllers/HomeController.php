<?php

namespace App\Http\Controllers;

use App\Models\Sliders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Auth\SlidersController;
use App\Http\Controllers\Api\Abdallah\JsonResourceAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Api\Auth\ProviderController;
use App\Models\Provider;

class HomeController extends Controller
{

    public function home(Request $request) {
        //get data from many tables
        $slider = new SlidersController();
        $provider = new ProviderController();
        $arraySlider =  $slider->getAllSlideModelStatusAvaliable( $request ); 
        $arrayProvider = $provider->getAllStatusAvaliable( $request );
         // Provider::where('id', ">", 0)->get();

        //put inside dictionary 
        $body = array();
        $body["slider"] = $arraySlider;
        $body["provider"] = $arrayProvider;

    
        //json
        return JsonResourceAbdallah::arrayWithTokenExpire(  $request, $body );
    }

}
