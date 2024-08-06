<?php

namespace App\Http\Controllers\Api\Abdallah;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Services\UploadService;
// use App\Traits\ResponseForm;
// use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Auth\UsersController;

class FileController extends Controller
{
 

  
    private array $allowedFiles = ['png', 'jpg', 'jpeg' , 'txt', 'csv', 'xlsx', "ogg", "mp4", "webm", "m4a"];
 
    /**
     there is there server:
     1- local host
     2- hostinger live
     3- 000webhostapp free for test

         // private String $domain_live = "https://scuba-ksa.com/storage/uploads/";
//    private String $domain_beta = "https://samolaapp.000webhostapp.com/storage/uploads/";

//https://umq.app/php/

     */
    private String $domainUploadFile_local = "http://192.168.1.6/Scuba/storage/uploads/";
    private String $domainUploadFile_server  = "https://umq.app/php/storage/uploads/";

    private String $domainGenerate_local  = "http://192.168.1.6/Scuba/storage/app/public/";
    private String $domainGenerate_server = "https://umq.app/php/storage/app/public/";

   //----------------------------------------------------------------------------------- generate file

   
   public function generate(Request $request ) {
     
       //check user access isUserAdmin
       if( SecurityAbdallah::isUserAdmin() == false ) {
           return response([
               'status' => 'Not Admin',
               'code' => 0,
               'message' => "UnAuthorized"
               ], 200);
       }
       //dd (  "here test file" );

        //check file required
       $validator = Validator::make($request->all(),[
           'type' => 'required',
           'data' => 'required',
           'file_name_unique' => 'required'
       ]);
      if ($validator->fails()) { 
       return response([
           'status' => 'missed',
           'code' => 0,
           'message' => "File missed"
           ], 200);
       }  
       
       //check hacker insert any unsecure data
       if( SecurityAbdallah::checkHtmlContainHackingCommand( $request->data) ) {

           return response([
               'status' => 'hacking-content',
               'code' => 0,
               'message' => "Remove all command programming"
               ], 200);
       }

      //generate final name
      // $timeUniqueName =  date('Y_m_d__H_i_s') ;
       $exe = $request->type; //"txt"; //security: avoid make it dyanmic to avoid hacker create html or js file
    //    $fileNameEncrypted = md5($request->file_name)  ; 
      // $finalName = $timeUniqueName . "__" . $fileNameEncrypted . '.' . $exe; 
      $finalName =  $request->file_name_unique . '.' . $exe; 

       //generate now
       $generateResult = Storage::disk('public')->put($finalName, $request->data );
      
      
       //full path
       $fullPath = "";

       //set domain 
       if( DeviceToolsAbdallah::is_local_server() ) {
            $fullPath = $this->domainGenerate_local . $finalName ;
       } else {
        //$fullPath = $this->domainGenerate . $finalName ;
            $fullPath = $this->domainGenerate_server . $finalName ;
       }


       //response
       return response([
           'status' => 'success-Generate',
           'code' => 1,
           'result' => $generateResult,
           'message' => "File Generate Successfuly",
           'type' => $request->type,
           // 'data' => $request->data,
           'finalName' => $finalName,
           "fullPath" => $fullPath
       ], 200);
    }

      //----------------------------------------------------------------------------------- upload file

    /**
     test apk
     */
    public function upload(Request $request) {

        // return $this->testResponse($request->file, $request->name);
        return $this->upload_live($request );
    }

    
    public function testResponse($file, $name = '')  {
        $fileData = [
            'path' => "https://firebasestorage.googleapis.com/v0/b/scuba-6780c.appspot.com/o/example_mock_data%2Fface%2Fscuba_man_face.jpg?alt=media&token=e71f078a-ad12-4b62-8854-10d72922d367"
        ];
        return response([
            'status' => 'success',
            'code' => 1,
            'message' => "File uploaded Successfuly",
            'data' => $fileData
        ], 200);
    }


 
    public function upload_live(Request $request) {
  

        $validator = Validator::make($request->all(),[
            'file' => 'required'
        ]);
       // dd ($validator );

       //check file required
        if ($validator->fails()) {

         
            return response([
            'status' => 'missed',
            'code' => 0,
            'message' => "File missed"
            ], 200);
        }
            

            
            
        //read extension :  check allowed extension
        $exe = $request->file->getClientOriginalExtension();
        if (!in_array($exe, $this->allowedFiles)) {
            return $this->returnError('File not Allowed in types ' . implode(',', $this->allowedFiles), 400);
        }

        //upload now
        return $this->uploadFunction($request );
    }



    public function uploadFunction(Request $request)  {


        //get data 
        $size = $request->file->getSize();
        $exe = $request->file->getClientOriginalExtension();
        $destinationPath = storage_path('uploads/');
        $flieNameOrginal =  $request->file->getClientOriginalName();
        $fileNameEncrypted = md5($flieNameOrginal)  ;
        $timeUniqueName =  date('Y_m_d__H_i_s') ;
     
        //generate final name
        $finalName = $timeUniqueName . "__" . $fileNameEncrypted . '.' . $exe; 
   
  
        $fullPath = "";

        //set domain 
        if( DeviceToolsAbdallah::is_local_server() ) {
            $fullPath = $this->domainUploadFile_local . $finalName ;
        } else {
            $fullPath = $this->domainUploadFile_server . $finalName ;
        }

 
        //move file
        $request->file->move($destinationPath, $finalName);

        $fileData = [
            'flieNameOrginal' => $flieNameOrginal,
            'size' => $size,
            'exe' => $exe,
            'path' => $fullPath
        ];
 
        //check type 
        $updatePhoto = false;
        if( $request->type == "photo_user" ) {
            $con = new UsersController();
            $updatePhoto = $con->updatePhoto( $request, $fullPath);
        }

        //response
        return response([
            'status' => 'success',
            'code' => 1,
            "version" => "2023-04-22__2:51",
          //  "data_request" => $request->data,
            'updatePhoto' => $updatePhoto,
            'message' => "File uploaded Successfuly",
            'data' => $fileData
        ], 200);

    }


}
