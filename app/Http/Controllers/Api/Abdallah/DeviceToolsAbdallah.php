<?php

namespace App\Http\Controllers\Api\Abdallah;

use App\Http\Controllers\Api\Auth\UsersController;
use App\Http\Controllers\Controller;
// use App\Services\UploadService;
// use App\Traits\ResponseForm;
// use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceToolsAbdallah extends Controller {
 


static public function is_local_server() {
        if($_SERVER['HTTP_HOST'] == 'localhost'
            || substr($_SERVER['HTTP_HOST'],0,3) == '10.'
            || substr($_SERVER['HTTP_HOST'],0,7) == '192.168') return true;
        return false;
    }

}