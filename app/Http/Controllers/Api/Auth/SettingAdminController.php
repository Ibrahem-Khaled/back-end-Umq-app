<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\SettingAdmin;
use Illuminate\Http\Request;
use Facade\Ignition\Tabs\Tab;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class SettingAdminController extends Controller
{

    //---------------------------------------------------------------------------------------------------- get data for server

    static public function is_subscribe_prevented() {
        $payment_subscribe_status = SettingAdmin::where("key", "=", "payment_subscribe_status")->first();   
        $result = $payment_subscribe_status->value  == "prevent";
        if($result == true ) {
            return 1;
        } else {
            return 0;
        }
        // return $result;
    }

    
    static public function is_subscribe_allowed() { 
        return ! SettingAdminController::is_subscribe_prevented();
    }


    //---------------------------------------------------------------------------------------------------- get data for mobile

    public function getPublicData(Request $request)
    {
        try {
        
            /// english
            // $about_us_url = SettingAdmin::where("key", "=", "about_us_url")->first();
            // $terms_url = SettingAdmin::where("key", "=", "terms_url")->first();
            $about_us_html = SettingAdmin::where("key", "=", "about_us_html")->first();
            $terms_html = SettingAdmin::where("key", "=", "terms_html")->first();

            /// _arabic
            // $about_us_url_arabic = SettingAdmin::where("key", "=", "about_us_url_arabic")->first();
            // $terms_url_arabic = SettingAdmin::where("key", "=", "terms_url_arabic")->first();
            $about_us_html_arabic = SettingAdmin::where("key", "=", "about_us_html_arabic")->first();
            $terms_html_arabic = SettingAdmin::where("key", "=", "terms_html_arabic")->first();     

            return response([
                'status' => 'success',
                'code' => 1,
                // 'about_us_url' => $about_us_url->value,
                // 'terms_url' => $terms_url->value,
                'about_us_html' => $about_us_html->value,
                'terms_html' => $terms_html->value,
                
                // 'about_us_url_arabic' => $about_us_url_arabic->value,
                // 'terms_url_arabic' => $terms_url_arabic->value,
                'about_us_html_arabic' => $about_us_html_arabic->value,
                'terms_html_arabic' => $terms_html_arabic->value
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }


    public function getPaymentSetting(Request $request) {
        $payment_subscribe_status = SettingAdmin::where("key", "=", "payment_subscribe_status")->first();     
        $paypal_domain = SettingAdmin::where("key", "=", "paypal_domain")->first();     
        /**
         * payment_subscribe_status symbole is "PSS"
         */
        return response([
            'status' => 'success',
            'code' => 1,
            "paypal_domain" =>  $paypal_domain->value, // "https://scuba-world.net/paypal",
            'PSS' => $payment_subscribe_status->value 
             
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAboutUsAndTerms(Request $request )
    {
        try {

         //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }

            //::::::::::::::::::::::: english
           
            // //update about_us_url
            // if( $request->about_us_url ) {
            //     $about_us_url = SettingAdmin::where( "key", "=", "about_us_url")->first();
            //     $about_us_url->value = $request->about_us_url;
            //     $about_us_url->update();    
            // }

            // //update terms_url
            // if( $request->terms_url ) {
            //     $terms_url = SettingAdmin::where( "key", "=", "terms_url")->first();
            //     $terms_url->value = $request->terms_url;
            //     $terms_url->update();    
            // }            
 
            //update about_us_html
            if( $request->about_us_html ) {
                $about_us_html = SettingAdmin::where( "key", "=", "about_us_html")->first();
                $about_us_html->value = $request->about_us_html;
                $about_us_html->update();    
            }

            //update terms_html
            if( $request->terms_html ) {
                $terms_html = SettingAdmin::where( "key", "=", "terms_html")->first();
                $terms_html->value = $request->terms_html;
                $terms_html->update();    
            }  

            
            //::::::::::::::::::::::: arabic
           
            // //update about_us_url_arabic
            // if( $request->about_us_url_arabic ) {
            //     $about_us_url_arabic = SettingAdmin::where( "key", "=", "about_us_url_arabic")->first();
            //     $about_us_url_arabic->value = $request->about_us_url_arabic;
            //     $about_us_url_arabic->update();    
            // }

            // //update terms_url_arabic
            // if( $request->terms_url_arabic ) {
            //     $terms_url_arabic = SettingAdmin::where( "key", "=", "terms_url_arabic")->first();
            //     $terms_url_arabic->value = $request->terms_url_arabic;
            //     $terms_url_arabic->update();    
            // }            
 
            //update about_us_html_arabic
            if( $request->about_us_html_arabic ) {
                $about_us_html_arabic = SettingAdmin::where( "key", "=", "about_us_html_arabic")->first();
                $about_us_html_arabic->value = $request->about_us_html_arabic;
                $about_us_html_arabic->update();    
            }

            //update terms_html_arabic
            if( $request->terms_html_arabic ) {
                $terms_html_arabic = SettingAdmin::where( "key", "=", "terms_html_arabic")->first();
                $terms_html_arabic->value = $request->terms_html_arabic;
                $terms_html_arabic->update();    
            }  


            return response([
                'status' => 'success',
                "version_arabic_fixed" => 2,
                'code' => 1 
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }


    //---------------------------------------------------------------------------------------------------- verison 1

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

      
        //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }
        
            $setting_admin = SettingAdmin::get();
            if ($setting_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $setting_admin
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

                    //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }

            $setting_admin = SettingAdmin::create($request->all());
            $setting_admin->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $setting_admin
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($search, Request $request)
    {
        try {

                    //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }

            $searchQuery = trim($search);
            $requestData = ['id','key','value'];
            $setting_admin = SettingAdmin::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($setting_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $setting_admin
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

                    //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }

            $setting_admin = SettingAdmin::where('id', '=', $id)->first();
            if ($setting_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $setting_admin
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get setting_admin data, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

                    //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }

            $input = $request->all();

            $setting_admin = SettingAdmin::find($id);

           $setting_admin->key = $input['key'];
           $setting_admin->value = $input['value'];

            $res = $setting_admin->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $setting_admin
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update setting_admin"
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

                    //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }

            $res = SettingAdmin::find($id)->delete();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Deleted successfully"
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete setting_admin"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete setting_admin, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
}

