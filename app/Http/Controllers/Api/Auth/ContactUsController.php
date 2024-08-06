<?php

// namespace App\Http\Controllers\Api\Auth;

// use App\Http\Controllers\Controller;
// use App\Models\ContactUs;
// use Facade\Ignition\Tabs\Tab;
// use Illuminate\Http\Request;

namespace App\Http\Controllers\Api\Auth;

use Validator;
use Carbon\Carbon;
 
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Facade\Ignition\Tabs\Tab;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\FcmAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class ContactUsController extends Controller
{

    //-------------------------------------------------------------------------------- v2

    public function contactGuest( Request $request ) {
 

        try {
            $contact_us = ContactUs::create($request->all());
            $contact_us->save();

            //fcm
            $conFCM = new FcmAbdallah();
            $resFCM_admin = $conFCM->contact_us_to_admin($request, $contact_us);

            return response([
                'status' => 'success',
                'code' => 1,
                "fcm" => $resFCM_admin,
                'data' => $contact_us
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store contact_us, please try again. {$exception->getMessage()}"
            ], 203);
        }
    }

    //------------------------------------------------------------------------------- v1

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
        
            $contact_us = ContactUs::
                orderBy('id', 'desc')
                ->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($contact_us) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $contact_us
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 202);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get contact_us, please try again. {$exception->getMessage()}"
            ], 203);
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


    //save when login type
    public function store(Request $request)
    {
        try {
            $contact_us = ContactUs::create($request->all());
            $contact_us->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $contact_us
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store contact_us, please try again. {$exception->getMessage()}"
            ], 203);
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
            $searchQuery = trim($search);
            $requestData = ['1','user_id','subject','message','guest_name','guest_email','guest_phone','read_status','created_at','updated_at'];
            $contact_us = ContactUs::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($contact_us) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $contact_us
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 202);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get contact_us, please try again. {$exception->getMessage()}"
            ], 203);
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
            $contact_us = ContactUs::where('1', '=', $id)->first();
            if ($contact_us) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $contact_us
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found"
                ], 202);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get contact_us data, please try again. {$exception->getMessage()}"
            ], 203);
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
            $input = $request->all();

            $contact_us = ContactUs::find($id);

           $contact_us->user_id = $input['user_id'];$contact_us->subject = $input['subject'];$contact_us->message = $input['message'];$contact_us->guest_name = $input['guest_name'];$contact_us->guest_email = $input['guest_email'];$contact_us->guest_phone = $input['guest_phone'];$contact_us->read_status = $input['read_status'];$contact_us->created_at = $input['created_at'];$contact_us->updated_at = $input['updated_at'];

            $res = $contact_us->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $contact_us
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update contact_us"
            ], 203);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update contact_us, please try again. {$exception->getMessage()}"
            ], 203);
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
            $res = ContactUs::find($id)->delete();
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
                    'data' => "Failed to delete contact_us"
                ], 203);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete contact_us, please try again. {$exception->getMessage()}"
            ], 203);
        }
    }
}

