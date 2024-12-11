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

    public function create(OfferRequest $request)
    {
        DB::beginTransaction();

        try{
            $data = $request->all();

            $data['show_in_suggestions'] = $request->has('show_in_suggestions') ? 1 : 2;

            $data['description'] = htmlspecialchars_decode($data['description']);
            $data['description']  = strip_tags($data['description']);
            $data['description'] = str_replace("&nbsp", " " , $data['description'] );
            $data['html_description'] = '<p style="text-align:left;">'.$data['description'].'</p>';

            $data['key_feature_1'] = htmlspecialchars_decode($data['key_feature_1']);
            $data['key_feature_1']  = strip_tags($data['key_feature_1']);
            $data['key_feature_1'] = str_replace("&nbsp", " " , $data['key_feature_1'] );
            $data['html_key_feature_1'] = '<p style="text-align:left;">' .  $data['key_feature_1'] . '</p>';
           
            $data['key_feature_2'] = htmlspecialchars_decode($data['key_feature_2']);
            $data['key_feature_2']  = strip_tags($data['key_feature_2']);
            $data['key_feature_2'] = str_replace("&nbsp", " " , $data['key_feature_2'] );
            $data['html_key_feature_2'] = '<p style="text-align:left;">' .  $data['key_feature_2'] . '</p>';

            if ($request->hasFile('key_icon_1') && $request->file('key_icon_1')->isValid()) {
                compressAndResizeImage($data['key_icon_1']->path(), $data['key_icon_1']->path());
                $data['key_icon_1']->store(Offer::FILE_DIR);
                $data['key_icon_1'] = $data['key_icon_1']->hashName();
            }

            if ($request->hasFile('key_icon_2') && $request->file('key_icon_2')->isValid()) {
                compressAndResizeImage($data['key_icon_2']->path(), $data['key_icon_2']->path());
                $data['key_icon_2']->store(Offer::FILE_DIR);
                $data['key_icon_2'] = $data['key_icon_2']->hashName();
            }

            $offer = Offer::create($data);

            DB::commit();
            return $offer;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error' . $ex))->withInput();
        }
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        DB::beginTransaction();

        try{
            $data = $request->all();

            $data['show_in_suggestions'] = $request->has('show_in_suggestions') ? 1 : 2;

            $data['description'] = htmlspecialchars_decode($data['description']);
            $data['description']  = strip_tags($data['description']);
            $data['description'] = str_replace("&nbsp", " " , $data['description'] );
            $data['html_description'] = '<p style="text-align:left;">'.$data['description'].'</p>';

            $data['key_feature_1'] = htmlspecialchars_decode($data['key_feature_1']);
            $data['key_feature_1']  = strip_tags($data['key_feature_1']);
            $data['key_feature_1'] = str_replace("&nbsp", " " , $data['key_feature_1'] );
            $data['html_key_feature_1'] = '<p style="text-align:left;">' .  $data['key_feature_1'] . '</p>';
           
            $data['key_feature_2'] = htmlspecialchars_decode($data['key_feature_2']);
            $data['key_feature_2']  = strip_tags($data['key_feature_2']);
            $data['key_feature_2'] = str_replace("&nbsp", " " , $data['key_feature_2'] );
            $data['html_key_feature_2'] = '<p style="text-align:left;">' .  $data['key_feature_2'] . '</p>';

            if ($request->hasFile('key_icon_1') && $request->file('key_icon_1')->isValid()) {
                if ($offer->key_icon_1) {
                    Storage::disk('public')->delete($offer->key_icon_1);
                }
                compressAndResizeImage($data['key_icon_1']->path(), $data['key_icon_1']->path());
                $data['key_icon_1']->store(Offer::FILE_DIR);
                $data['key_icon_1'] = $data['key_icon_1']->hashName();
            }

            if ($request->hasFile('key_icon_2') && $request->file('key_icon_2')->isValid()) {
                if ($offer->key_icon_2) {
                    Storage::disk('public')->delete($offer->key_icon_2);
                }
                compressAndResizeImage($data['key_icon_2']->path(), $data['key_icon_2']->path());
                $data['key_icon_2']->store(Offer::FILE_DIR);
                $data['key_icon_2'] = $data['key_icon_2']->hashName();
            }

            $offer->update($data);
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
