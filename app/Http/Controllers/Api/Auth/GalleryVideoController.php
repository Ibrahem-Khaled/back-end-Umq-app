<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\GalleryVideo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class GalleryVideoController extends Controller
{

    
    public function getSingleModel(int $gallery_id)
    {
        $gallery_image = GalleryVideo::
              with(['provider'])
            ->where("id", "=", $gallery_id)
            ->first();
        // dd( $gallery_image );

        //get favorite
        $favCon = new FavGalleryImageController();
        $value = $favCon->getFavorite($gallery_image->id);
        $gallery_image["favorite"] = $value;
        $gallery_image["user_id"] = SecurityAbdallah::getUserId();

        return $gallery_image;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = GalleryVideo::with(['provider'])
            ->where("hidden", "=", 0 );

            //optional provider id
            if( $request->provider_id !=  ""  ) {
                $query->where( "provider_id", "=", $request->provider_id);
            }
            
            $gallery_video = $query->paginate($request->paginator);
            if ($gallery_video) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_video,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get gallery_video, please try again. {$exception->getMessage()}",
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
            $gallery_video = GalleryVideo::create($request->all());
            $gallery_video->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $gallery_video,
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store gallery_video, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function galleryForProviderId($provider_id)
    {
        $gallery_video = GalleryVideo::
            with(['provider'])
            ->where("provider_id", "=", $provider_id)
            ->where("hidden", "=", 0 )
            ->get();

        //get favorite
        foreach ($gallery_video as $m) {
            $favCon = new FavGalleryVideoController();
            $value = $favCon->getFavorite($m->id);
            $m["favorite"] = $value;
            $m["user_id"] = SecurityAbdallah::getUserId();
        }

        // return  $gallery_video;
        return $gallery_video;
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
            $requestData = ['id', 'provider_id', 'file', 'hidden', 'published', 'created_at', 'updated_at'];
            $gallery_video = GalleryVideo::with(['provider'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($gallery_video) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_video,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get gallery_video, please try again. {$exception->getMessage()}",
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
            $gallery_video = GalleryVideo::with(['provider'])->where('id', '=', $id)->first();
            if ($gallery_video) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_video,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get gallery_video data, please try again. {$exception->getMessage()}",
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

            $gallery_video = GalleryVideo::find($id);

            
            $gallery_video->id = $input['id'];
            
            if( $request->provider_id  ) {
                $gallery_video->provider_id = $input['provider_id'];
            }
       
            if( $request->file  ) { 
                $gallery_video->file = $input['file'];
            }
                   
            if( $request->hidden != ""  ) { 
                $gallery_video->hidden = $input['hidden'];
            }
                   
            if( $request->published != ""  ) { 
                $gallery_video->published = $input['published'];
            }


            $res = $gallery_video->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_video,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update gallery_video",
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update gallery_video, please try again. {$exception->getMessage()}",
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
            $gallery_video = GalleryVideo::find($id);

            $gallery_video->hidden =  1 ;
            $res = $gallery_video->update();

            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Deleted successfully",
                    'data' => $gallery_video,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,  
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete gallery_video, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
