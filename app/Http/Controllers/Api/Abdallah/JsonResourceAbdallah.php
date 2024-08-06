<?php
 
namespace App\Http\Controllers\api\Abdallah;

use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Http\Request;


class JsonResourceAbdallah {

    static function responseLikeLaravelPaginate(   $data,   $total,   $current_page,   $per_page ) {
        $body = array(); 

        $current_page_int = (int)$current_page;
        $per_page_int = (int)$per_page;

        //header
        $body["current_page"] = $current_page_int;
        $body["data"] = $data;
        $body["first_page_url"] = "moke_laravel_response";
        $body["from"] =  $current_page;
        $body["last_page"] =  $total;
        $body["last_page_url"] = "moke_laravel_response";
        
        //links
        $links  = array(); 
        $linksItem = [];
        $linksItem["url"] = "moke_laravel_response";
        $linksItem["label"] = "moke_laravel_response";
        $linksItem["active"] = 1;
        $links[] = $linksItem;
        $body["links"] =$links;

        //footoer
        $body["next_page_url"] =  "moke_laravel_response";
        $body["path"] =  "moke_laravel_response";
        $body["per_page"] =  $per_page_int;
        $body["prev_page_url"] = "moke_laravel_response";
        $body["to"] =  $per_page_int * $current_page_int;
        $body["total"] =  $total;

        // dd($body);
        return   $body;
    }


    /**
     - example of useage:
             //get data from many tables
        $slider = new SlidersController();
        $arraySlider =  $slider->getAllSlideModelStatusAvaliable( $request ); 
        $arrayProvider = Provider::where('id', ">", 0)->get();

        //put inside dictionary 
        $body = array();
        $body["sliders"] = $arraySlider;
        $body["provider"] = $arrayProvider;

        //json
        return JsonResourceAbdallah::array( $body );
     */
    static function array( array  $data ) {
        $body = array();
        //check token expire
        // $body["token_expire"] = SecurityAbdallah::isTokenExpire();
        $body["status"] = 'success';
        $body["code"] = 1 ;
        $body["data"] = $data;
        return   $body;
    }

    static function arrayWithTokenExpire( Request $request, array  $data ) {
        $body = array();
        //check token expire
        $body["token_expire"] = SecurityAbdallah::isTokenExpire($request);
        $body["status"] = 'success';
        $body["code"] = 1 ;
        $body["data"] = $data;
        return   $body;
    }

    static function printDataInDD(    $data  ) {
        dd( "data:".$data );
        return $data;
    }

    static function jsonString( String  $data ) {
        return $data;
    }

    /**
     * - example how to call:
      $arraySlider = Sliders::where('id', ">", 0)->get();
        return JsonResourceAbdallah::record( $arraySlider );

     */
    static function record( Object  $data ) {
        return response([
            'status' => 'success',
            'code' => 1,
            'data' => $data
        ], 200);
    }


    /**
     * - example:
         JsonResourceAbdallah::failed( "failed"   );
     */
    static function failed( String  $msg  ) {
        return response([
            'status' => 'failed',
            'code' => 0,
            'msg' => $msg 
        ], 200);
    }

    static function failedShapeArray( String $key1, String $value1 ) {
        $body = array();
        $body["status"] = 'failed';
        $body["code"] = 0;
        $body["msg"] = 'failed';
        $body[ $key1 ] =  $value1;
        return   json_encode($body);;
    }

    /***
     * 
     - call example:
      return JsonResourceAbdallah::failed_keyValue( "failed", "user_not_found", true  );
     
    - example response :
    {
    "status": "failed",
    "code": 1,
    "msg": "failed",
    "user_not_found": true
}

     */
    static function failed_keyValue( String  $msg , String $key , bool $value ) {
        return response([
            'status' => 'failed',
            'code' => 1,
            'msg' => $msg ,
            "".$key => $value 
        ], 200);
    }

}