<?php

namespace App\Services\Admin;

use Exception;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\OfferRequest;

class OfferService
{
    protected $offer;

    protected $request;

    public function create(Request $request)
    {
        DB::beginTransaction();

        try{
            $offer = new Offer();
            $offer->brand_id = $request->brand_id;
            $offer->car_id = $request->car_id;
            $offer->car_version_id = $request->car_version_id;
            $offer->title = $request->title;
            $offer->description =  htmlspecialchars_decode($request->description);
            $offer->description = strip_tags( $offer->description);
            $offer->description = str_replace("&nbsp", " " , $offer->description );
            $offer->html_description = '<p style="text-align:left;">'. $offer->description.'</p>';
            $offer->key_feature_1 =  htmlspecialchars_decode($request->key_feature_1);
            $offer->key_feature_1 = strip_tags( $offer->key_feature_1);
            $offer->key_feature_1 = str_replace("&nbsp", " " , $offer->key_feature_1 );
            $offer->html_key_feature_1 = '<p style="text-align:left;">'. $offer->key_feature_1.'</p>';
            $offer->key_feature_2 =  htmlspecialchars_decode($request->key_feature_2);
            $offer->key_feature_2 = strip_tags( $offer->key_feature_2);
            $offer->key_feature_2 = str_replace("&nbsp", " " , $offer->key_feature_2 );
            $offer->html_key_feature_2 = '<p style="text-align:left;">'. $offer->key_feature_2.'</p>';
            if($request->hasfile('key_icon_1')){
                $request->key_icon_1->store(Offer::FILE_DIR);
                $offer->key_icon_1 = $request->key_icon_1->hashName();  
            }
            if($request->hasfile('key_icon_2')){
                $request->key_icon_2->store(Offer::FILE_DIR);
                $offer->key_icon_2 = $request->key_icon_2->hashName();  
            }
            $offer->start_date = $request->start_date;
            $offer->end_date = $request->end_date;
            $offer->offer = $request->offer;
            $offer->status = $request->status;
            $offer->show_in_suggestions = $request->show_in_suggestions ? 1:2;
            $offer->save();

            DB::commit();
            return $offer;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error' . $ex))->withInput();
        }
    }

    public function update(Request $request, Offer $offer)
    {
        DB::beginTransaction();

        try{
            $offer->brand_id = $request->brand_id;
            $offer->car_id = $request->car_id;
            $offer->car_version_id = $request->car_version_id;
            $offer->title = $request->title;
            $offer->description =  htmlspecialchars_decode($request->description);
            $offer->description = strip_tags( $offer->description);
            $offer->description = str_replace("&nbsp", " " , $offer->description );
            $offer->html_description = '<p style="text-align:left;">'. $offer->description.'</p>';
            $offer->key_feature_1 =  htmlspecialchars_decode($request->key_feature_1);
            $offer->key_feature_1 = strip_tags( $offer->key_feature_1);
            $offer->key_feature_1 = str_replace("&nbsp", " " , $offer->key_feature_1 );
            $offer->html_key_feature_1 = '<p style="text-align:left;">'. $offer->key_feature_1.'</p>';
            $offer->key_feature_2 =  htmlspecialchars_decode($request->key_feature_2);
            $offer->key_feature_2 = strip_tags( $offer->key_feature_2);
            $offer->key_feature_2 = str_replace("&nbsp", " " , $offer->key_feature_2 );
            $offer->html_key_feature_2 = '<p style="text-align:left;">'. $offer->key_feature_2.'</p>';
            if($request->hasfile('key_icon_1')){
                $request->key_icon_1->store(Offer::FILE_DIR);
                $offer->key_icon_1 = $request->key_icon_1->hashName();  
            }
            if($request->hasfile('key_icon_2')){
                $request->key_icon_2->store(Offer::FILE_DIR);
                $offer->key_icon_2 = $request->key_icon_2->hashName();  
            }
            $offer->start_date = $request->start_date;
            $offer->end_date = $request->end_date;
            $offer->offer = $request->offer;
            $offer->status = $request->status;
            $offer->show_in_suggestions = $request->show_in_suggestions ? 1:2;
            $offer->save();

          
            DB::commit();
            return $offer;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
    }
}
