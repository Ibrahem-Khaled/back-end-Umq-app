<?php

namespace App\Http\Controllers\Api\Abdallah\Language;

use App\Models\Users;
 
use App\Jobs\ProcessPodcast;
 
 
use App\Traits\ResponseForm;
 
use Illuminate\Http\Request;
use App\Services\UploadService;
 
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
 
class LanguageTools extends Controller {

    //----------------------------------------------------- constant


    public  $defaultLang = "ar";

    //----------------------------------------------------- language getter

    /**
     pares the header "lang" value
     */
    public static  function getLang(  Request $request ) : String  {
       // $headers = $request->header();
        $lang = $request->header('lang');
        // dump("getLang() - lang: ".$lang);

        if( $lang == null ) {
            $obj = new LanguageTools();
            return $obj->defaultLang; 
        }
        return $lang;
    }


    public static function isArabic(  Request $request ) : int  {
        // $headers = $request->header();
         $lang = LanguageTools::getLang( $request );
         return $lang == "ar" ;
     }


     public static function isEnglish(  Request $request ) : int  {
        // $headers = $request->header();
         $lang = LanguageTools::getLang( $request );
         return $lang == "en" ;
     }
 

     /**
      example:
              $msg = LanguageTools::choose( $request, "طلبك قيد التحضير",  "Your Order Under Preapearing");  

              - code to copy
         LanguageTools::choose( $request, "en", "ar") ,

      */
     public static function choose(Request $request, String $msgEn,  String $msgAr ) {
        $isArabic = LanguageTools::isArabic( $request );
        // dump( "LanguageTools - choose() -  isArabic: $isArabic /msgAr: $msgAr /msgEn: $msgEn ");
        if( $isArabic ) {
            return $msgAr;
        } else {
            return $msgEn;
        }
     }


     public static function msg_success(Request $request  ) {
        return LanguageTools::choose($request, "success", "تم بنجاح");
     }


     public static function msg_failed(Request $request  ) {
        return LanguageTools::choose($request, "action failed", "فشلت العملية");
     }
}
 