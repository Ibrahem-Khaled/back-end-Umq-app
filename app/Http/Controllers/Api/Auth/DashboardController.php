<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;
use Carbon\Carbon;
use App\Models\Users;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\City; use Facade\Ignition\Tabs\Tab;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\GalleryImage;
use App\Models\GalleryVideo;
use App\Models\OrderProduct;
use App\Models\Product;

class DashboardController extends Controller {



    //-----------------------------------------------------  get single model

    public function admin_counter_analytics(  Request $request ) { 
        try {
 
            //check admin
            if( SecurityAbdallah::isUserAdmin()  == false ) {
                return response([
                    'status' => 'error not allowed',
                    'code' => 0,
                    'message' => "Not allowed action",
                ], 200);
            }

            $student = Users::where('role', '=',  "student")->count();
            $provider = Users::where('role', '=',  "provider")->count();
            $admin = Users::where('role', '=',  "admin")->count();
            $admin_plus_provider = $provider + $admin;
            $order = OrderProduct::count();
            $chat_message = ChatMessage::count();
            $gallery_image = GalleryImage::count();
            $gallery_video = GalleryVideo::count();
            $product = Product::count();
            $product_category = Category::count();
            $city = City::count();

            return response([
                'status' => 'success_response',
                'code' => 1,
                'message' => "success",
                "student" => $student,
                "provider" => $admin_plus_provider,
                "product" => $product,
                "product_category" => $product_category,
                "gallery_image" => $gallery_image,
                "gallery_video" => $gallery_video,
                "chat_message" => $chat_message,
                "order" => $order,
                "subscribers" => 0,
                "city" => $city 
            ], 200);

        } catch (\Exception$exception) {
            return null; // new Users();
        }

    }


}