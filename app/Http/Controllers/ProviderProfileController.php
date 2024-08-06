<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\ProviderController;
use App\Http\Controllers\Api\Auth\GalleryImageController;
use App\Http\Controllers\Api\Auth\GalleryVideoController;
use App\Http\Controllers\Api\Abdallah\JsonResourceAbdallah;
use App\Http\Controllers\Api\Auth\FavProviderController;
use App\Http\Controllers\Api\Auth\ProductController;

class ProviderProfileController
{

    public function show(Request $request) {

        //get provider content
        $providerCont = new ProviderController();
        $provider_content = $providerCont->getSingle($request->id);
      // $provider_content = ProviderController::getAllStatusAvaliable();

        //get images
        $imageCon = new GalleryImageController();
        $image_gallery = $imageCon->galleryForProviderId( $request->id);
 
        //get video
        $videoCon = new GalleryVideoController();
        $video_gallery = $videoCon->galleryForProviderId( $request->id);

        //get products
        $productCon = new ProductController();
        $product_provider = $productCon->productForProviderId( $request->id );

        //fav provider
        $favCon = new FavProviderController();
        $value =  $favCon->getFavorite(  $provider_content->id  );
        $provider_content["favorite"] = $value;

        //put inside dictionary
        $body = array();
        $body["provider_content"] = $provider_content;
        $body["image_gallery"] = $image_gallery;
        $body["video_gallery"] = $video_gallery;
        $body["product_provider"] = $product_provider;

        //json
        return JsonResourceAbdallah::array( $body );
    }


   

}
