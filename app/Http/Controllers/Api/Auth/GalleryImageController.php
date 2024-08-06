<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryImageController extends Controller
{

    public function getSingleModel(int $gallery_id)
    {
        $gallery_image = GalleryImage::
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

            $query = GalleryImage::with(['provider'])
            ->where("hidden", "=", 0 );
            
            //optional provider id
            if( $request->provider_id !=  ""  ) {
                $query->where( "provider_id", "=", $request->provider_id);
            }
            
            $gallery_image = $query->paginate($request->paginator);

            if ($gallery_image) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_image,
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
                'message' => "Failed to get gallery_image, please try again. {$exception->getMessage()}",
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
            $gallery_image = GalleryImage::create($request->all());
            $gallery_image->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $gallery_image,
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store gallery_image, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function galleryForProviderId($provider_id)
    {
            $gallery_image = GalleryImage::
            with(['provider'])
            ->where("provider_id", "=", $provider_id)
            ->where("hidden", "=", 0 )
            ->get();


        //get favorite
        foreach ($gallery_image as $m) {
            $favCon = new FavGalleryImageController();
            $value = $favCon->getFavorite($m->id);
            $m["favorite"] = $value;
            $m["user_id"] = SecurityAbdallah::getUserId();

        }

        return $gallery_image;
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

            $gallery_image = GalleryImage::
                with(['provider'])
                ->where(function ($q) use ($requestData, $searchQuery) {
                    foreach ($requestData as $field) {
                        $q->orWhere($field, 'like', "%{$searchQuery}%");
                    }

                })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($gallery_image) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_image,
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
                'message' => "Failed to get gallery_image, please try again. {$exception->getMessage()}",
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
            $gallery_image = GalleryImage::with(['provider'])->where('id', '=', $id)->first();
            if ($gallery_image) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_image,
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
                'message' => "Failed to get gallery_image data, please try again. {$exception->getMessage()}",
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

            $gallery_image = GalleryImage::find($id);

            $gallery_image->id = $input['id'];
            $gallery_image->provider_id = $input['provider_id'];
            $gallery_image->file = $input['file'];
            $gallery_image->hidden = $input['hidden'];
            $gallery_image->published = $input['published'];
            $gallery_image->created_at = $input['created_at'];
            $gallery_image->updated_at = $input['updated_at'];

            $res = $gallery_image->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $gallery_image,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update gallery_image",
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update gallery_image, please try again. {$exception->getMessage()}",
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
            // $res = GalleryImage::find($id)->delete();
            $gallery_image = GalleryImage::find($id);

            $gallery_image->hidden =  1 ;
            $res = $gallery_image->update();

            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Deleted successfully",
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete gallery_image",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete gallery_image, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
