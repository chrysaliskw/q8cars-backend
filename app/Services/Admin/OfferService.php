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

            $data['html_description'] = '<p style="text-align:left;">' . $request->input('description') . '</p>';
            $data['html_key_feature_1'] = '<p style="text-align:left;">' . $request->input('key_feature_1') . '</p>';
            $data['html_key_feature_2'] = '<p style="text-align:left;">' . $request->input('key_feature_2') . '</p>';

            if ($request->hasFile('key_icon_1') && $request->file('key_icon_1')->isValid()) {
                $data['key_icon_1'] = $request->file('key_icon_1')->store(Offer::FILE_DIR);
            }

            if ($request->hasFile('key_icon_2') && $request->file('key_icon_2')->isValid()) {
                $data['key_icon_2'] = $request->file('key_icon_2')->store(Offer::FILE_DIR);
            }

            $offer = Offer::create($data);

            DB::commit();
            return $offer;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        DB::beginTransaction();

        try{
            $data = $request->all();

            $data['show_in_suggestions'] = $request->has('show_in_suggestions') ? 1 : 2;

            $data['html_description'] = '<p style="text-align:left;">' . $request->input('description') . '</p>';
            $data['html_key_feature_1'] = '<p style="text-align:left;">' . $request->input('key_feature_1') . '</p>';
            $data['html_key_feature_2'] = '<p style="text-align:left;">' . $request->input('key_feature_2') . '</p>';

            if ($request->hasFile('key_icon_1') && $request->file('key_icon_1')->isValid()) {
                if ($offer->key_icon_1) {
                    Storage::disk('public')->delete($offer->key_icon_1);
                }
                $data['key_icon_1'] = $request->file('key_icon_1')->store(Offer::FILE_DIR);
            }

            if ($request->hasFile('key_icon_2') && $request->file('key_icon_2')->isValid()) {
                if ($offer->key_icon_2) {
                    Storage::disk('public')->delete($offer->key_icon_2);
                }
                $data['key_icon_2'] = $request->file('key_icon_2')->store(Offer::FILE_DIR);
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
