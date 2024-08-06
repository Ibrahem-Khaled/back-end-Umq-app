<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\FavGalleryImage;
use Illuminate\Http\Request;

class FavGalleryImageController extends Controller
{

    public function getFavorite($target_id): int
    {

        //get userid from token
        $user_id = SecurityAbdallah::getUserId();

        //check user id not found
        if ($user_id == null || $user_id == 0) {
            return 0;
        }

        //get object
        $fav_obj = FavGalleryImage::
            where('user_id', '=', $user_id)
            ->where('gallery_id', '=', $target_id)
            ->first();
        // dd($fav_product );

        //check no object found
        if ($fav_obj == null) {
            return 0;
        }

        //return status
        return $fav_obj->favorite;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            //get userid from token
            $user_id = SecurityAbdallah::getUserId();
            // dd( $user_id);

            //check user id not found
            if ($user_id == null || $user_id == 0) {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "Guest Not ALlowed To Get Favorite",
                ], 200);
            }

            $fav_gallery_image = FavGalleryImage::with(['users'])
                ->where('user_id', '=', $user_id)
                ->where("favorite", "=", 1)
                ->get();
            // ->paginate($request->paginator);
            // dd($fav_gallery_image);

            //gallery
            foreach ($fav_gallery_image as $m) {
                $con = new GalleryImageController();
                $value = $con->getSingleModel($m->gallery_id);
                $m["gallery_image"] = $value;
            }

            if ($fav_gallery_image) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_gallery_image,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get fav_gallery_image, please try again. {$exception->getMessage()}",
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
            //get previous
            $previous = FavGalleryImage::
                where('gallery_id', '=', $request->gallery_id)
                ->where('user_id', '=', $request->user_id)
                ->first();

            // dd($previous);

            //check update values of previous record
            if ($previous != null) {
                return $this->update($request, $previous->id);
            }

            $fav_gallery_image = FavGalleryImage::create($request->all());
            $fav_gallery_image->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $fav_gallery_image,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store fav_gallery_image, please try again. {$exception->getMessage()}",
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
            $requestData = ['id', 'user_id', 'gallery_id'];
            $fav_gallery_image = FavGalleryImage::with(['users'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($fav_gallery_image) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_gallery_image,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get fav_gallery_image, please try again. {$exception->getMessage()}",
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
            $fav_gallery_image = FavGalleryImage::with(['users'])->where('id', '=', $id)->first();
            if ($fav_gallery_image) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_gallery_image,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get fav_gallery_image data, please try again. {$exception->getMessage()}",
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

            $fav_gallery_image = FavGalleryImage::find($id);

            $fav_gallery_image->user_id = $input['user_id'];
            $fav_gallery_image->gallery_id = $input['gallery_id'];
            $fav_gallery_image->favorite = $input['favorite'];

            $res = $fav_gallery_image->update();
            if ($res) {
                return response([
                    'status' => 'success update',
                    'code' => 1,
                    'data' => $fav_gallery_image,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update fav_gallery_image",
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update fav_gallery_image, please try again. {$exception->getMessage()}",
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
            $res = FavGalleryImage::find($id)->delete();
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
                    'data' => "Failed to delete fav_gallery_image",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete fav_gallery_image, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
