<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Sliders;
use Illuminate\Http\Request;
use Facade\Ignition\Tabs\Tab;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class SlidersController extends Controller
{

    //---------------------------------------------------------------------------- v2

    public function updateData(Request $request )  {

        // admin only allowed
        $isAdmin = SecurityAbdallah::isUserAdmin();
        if( $isAdmin == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not Allowed Action",
            ], 200); 
        }

        //query
        $m = Sliders::where('id', '=', $request->id)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this object id",
            ], 200);
        }

        //update
        if ($request->image != null) {
            $m->image = $request->image;
        }
    
        //update
        $res = $m->update();

        //when user create return "json"
        if ($res) {
            return response([
                'status' => 'success update',
                'code' => 1,
                'data' => $m,
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update",
            ], 200);
        }

    }

    
    public function hidden(Request $request) {

        //   dd($request);
   
           //check admin only
           if( SecurityAbdallah::isUserAdmin() == false ) {
               return response([
                   'status' => 'error not allowed',
                   'code' => 0,
                   'message' => "Not allowed action",
               ], 200);
           }
   
           //query
           $m = Sliders::where('id', '=', $request->target)

           ->first();
   
           //check not found
           if ($m == null) {
               return response([
                   'status' => 'error not found',
                   'code' => 0,
                   'message' => "Not found this target",
               ], 200);
           }
   
           //update
           $m->status = $request->value;
   
           //update
           $res = $m->update();
           if ($res) {
               return response([
                   'status' => 'success update',
                   'code' => 1,
                   'data' => $m,
               ], 200);
           } else {
               return response([
                   'status' => 'error',
                   'code' => 0,
                   'data' => "Failed to update",
               ], 200);
           }
       }
   

    public  function getAllSlideModelStatusAvaliable(Request $request )    {
        $sliders = Sliders::
        // with(['provider','product'])
        where('id', ">", 0)
        ->where( "status", "!=", "0") 
        ->orderBy('id', 'desc')
        ->get();
        return $sliders; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
        
            //with paginate
            $sliders = Sliders::
            //with(['provider','product'])
            where( "status", "!=", "0") 
            ->orderBy('id', 'desc')
            ->paginate($request->paginator);

            if ($sliders) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $sliders
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get sliders, please try again. {$exception->getMessage()}"
            ], 200);
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
            $sliders = Sliders::create($request->all());
            $sliders->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $sliders
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store sliders, please try again. {$exception->getMessage()}"
            ], 200);
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
            $requestData = ['id','image','object_type','provider_id','product_id','created_at','updated_at'];
            $sliders = Sliders::with(['provider','product'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($sliders) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $sliders
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get sliders, please try again. {$exception->getMessage()}"
            ], 200);
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
            $sliders = Sliders::with(['provider','product'])->where('id', '=', $id)->first();
            if ($sliders) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $sliders
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get sliders data, please try again. {$exception->getMessage()}"
            ], 200);
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

            $sliders = Sliders::find($id);

           $sliders->image = $input['image'];$sliders->object_type = $input['object_type'];$sliders->provider_id = $input['provider_id'];$sliders->product_id = $input['product_id'];$sliders->created_at = $input['created_at'];$sliders->updated_at = $input['updated_at'];

            $res = $sliders->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $sliders
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update sliders"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update sliders, please try again. {$exception->getMessage()}"
            ], 200);
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
            $res = Sliders::find($id)->delete();
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
                    'data' => "Failed to delete sliders"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete sliders, please try again. {$exception->getMessage()}"
            ], 200);
        }
    }
}

