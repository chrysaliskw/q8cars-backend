<?php

namespace App\Services\Admin;

use App\Models\CuratedComparison;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CuratedCompareRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CuratedComparisonService
{
    protected $curatedcomparison;
    protected $request;

    public function create(CuratedCompareRequest $request)
    {
        DB::beginTransaction();
        try {
           
            $data = $request->all();

            $data['html_content'] = '<p style= "text-align:left;">' . $request->input('content') . '</p>';

            if ($request->hasFile('image_1') && $request->file('image_1')->isValid()) {
                $data['image_1'] = $request->file('image_1')->store(CuratedComparison::FILE_DIR);
            }
            if ($request->hasFile('image_2') && $request->file('image_2')->isValid()) {
                $data['image_2'] = $request->file('image_2')->store(CuratedComparison::FILE_DIR);
            }
            else
            {
                $data['image_2'] = null;
            }
            if ($request->hasFile('image_3') && $request->file('image_3')->isValid()) {
                $data['image_3'] = $request->file('image_3')->store(CuratedComparison::FILE_DIR);
            } else {
                $data['image_3'] = null; // Set to null if image_3 is not provided
            }
            $data['published_date'] = Carbon::createFromFormat('d-m-Y', $request->published_date)->format('Y-m-d');


            $curatedcomparison = CuratedComparison::create([
                'brand_id_1' => $request->brand_1_id,
                'brand_id_2' => $request->brand_2_id,
                'brand_id_3' => $request->brand_3_id,
                'car_id_1' => $request->car_1_id,
                'car_id_2' => $request->car_2_id,
                'car_id_3' => $request->car_3_id,
                'title' => $request->title,
                'content' => $request->content,
                'source' => $request->source,
                'image_1' =>  $data['image_1'],
                'image_2' => $data['image_2'],
                'image_3' => $data['image_3'],
                'html_content' => $data['html_content'],
                'status' => $request->status,
                'published_date' => $data['published_date'],
            ]);


            DB::commit();
            // dd($curatedcomparison);
            return $curatedcomparison;
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error') . $ex)->withInput();
        }
    }

    public function update(CuratedCompareRequest $request, CuratedComparison $curatedcomparison)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            // dd($data);
            $data['html_content'] = '<p style= "text-align:left;">' . $request->input('content') . '</p>';

      
            if ($request->hasFile('image_1') && $request->file('image_1')->isValid()) {
                $data['image_1'] = $request->file('image_1')->store(CuratedComparison::FILE_DIR);
            }
            if ($request->hasFile('image_2') && $request->file('image_2')->isValid()) {
                $data['image_2'] = $request->file('image_2')->store(CuratedComparison::FILE_DIR);
            }
            else
            {
                $data['image_2'] = null;
            }
            if ($request->hasFile('image_3') && $request->file('image_3')->isValid()) {
                $data['image_3'] = $request->file('image_3')->store(CuratedComparison::FILE_DIR);
            } else {
                $data['image_3'] = null; // Set to null if image_3 is not provided
            }

            $data['published_date'] = Carbon::createFromFormat('d-m-Y', $request->published_date)->format('Y-m-d');

            // $curatedcomparison->update($data);
            $curatedcomparison->update([
                'brand_id_1' => $request->brand_1_id,
                'brand_id_2' => $request->brand_2_id,
                'brand_id_3' => $request->brand_3_id,
                'car_id_1' => $request->car_1_id,
                'car_id_2' => $request->car_2_id,
                'car_id_3' => $request->car_3_id,
                'title' => $request->title,
                'content' => $request->content,
                'source' => $request->source,
                'image_1' =>  $data['image_1'],
                'image_2' => $data['image_2'],
                'image_3' => $data['image_3'],
                'html_content' => $data['html_content'],
                'status' => $request->status,
                'published_date' => $data['published_date'],
            ]);
            DB::commit();
            return $curatedcomparison;
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error') . $ex)->withInput();
        }
    }
}
