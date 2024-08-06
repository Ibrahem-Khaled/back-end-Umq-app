<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\FavGalleryVideo;
use Illuminate\Http\Request;

class FavGalleryVideoController extends Controller
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
        $fav_obj = FavGalleryVideo::
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

            //check user id not found
            if ($user_id == null || $user_id == 0) {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "Guest Not ALlowed To Get Favorite",
                ], 200);
            }

            $fav_gallery_video = FavGalleryVideo::with(['users'])
                ->where('user_id', '=', $user_id)
                ->where("favorite", "=", 1)
                ->get();
            //->paginate($request->paginator);

            //gallery
            foreach ($fav_gallery_video as $m) {
                $con = new GalleryVideoController();
                $value = $con->getSingleModel($m->gallery_id);
                $m["gallery_video"] = $value;
            }

            if ($fav_gallery_video) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_gallery_video,
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
                'message' => "Failed to get fav_gallery_video, please try again. {$exception->getMessage()}",
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
            $previous = FavGalleryVideo::
                where('gallery_id', '=', $request->gallery_id)
                ->where('user_id', '=', $request->user_id)
                ->first();

            //check update values of previous record
            if ($previous != null) {
                return $this->update($request, $previous->id);
            }

            $fav_gallery_video = FavGalleryVideo::create($request->all());
            $fav_gallery_video->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $fav_gallery_video,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store fav_gallery_video, please try again. {$exception->getMessage()}",
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
            $fav_gallery_video = FavGalleryVideo::with(['users'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($fav_gallery_video) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_gallery_video,
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
                'message' => "Failed to get fav_gallery_video, please try again. {$exception->getMessage()}",
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
            $fav_gallery_video = FavGalleryVideo::with(['users'])->where('id', '=', $id)->first();
            if ($fav_gallery_video) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_gallery_video,
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
                'message' => "Failed to get fav_gallery_video data, please try again. {$exception->getMessage()}",
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

            $fav_gallery_video = FavGalleryVideo::find($id);

            $fav_gallery_video->user_id = $input['user_id'];
            $fav_gallery_video->gallery_id = $input['gallery_id'];
            $fav_gallery_video->favorite = $input['favorite'];

            $res = $fav_gallery_video->update();
            if ($res) {
                return response([
                    'status' => 'success update',
                    'code' => 1,
                    'data' => $fav_gallery_video,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update fav_gallery_video",
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update fav_gallery_video, please try again. {$exception->getMessage()}",
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
            $res = FavGalleryVideo::find($id)->delete();
            if ($res) {
                return response([
                    'status' => 'success destory',
                    'code' => 1,
                    'message' => "Deleted successfully",
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete fav_gallery_video",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete fav_gallery_video, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
