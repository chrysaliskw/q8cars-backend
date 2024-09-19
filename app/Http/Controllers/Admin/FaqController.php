<?php

namespace App\Http\Controllers\Admin;

use App\DataGrids\Admin\FaqDataGrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use Exception;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new FaqDataGrid(request()->query());

        return view('admin.faq.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        try{
            $faq = new Faq();
            $faq->brand_id = $request->brand_id;
            $faq->car_id = $request->car_id;
            if($request->car_version_id){
                $faq->car_version_id = $request->car_version_id;
            } 
            $faq->question = $request->question;
            $faq->answer = '<p style="text-align:left;">'.$request->answer.'</p>';
            $faq->sort_order = $request->sort_order;
            $faq->question_status = $request->status;
            $faq->answer_status = $request->status;

            $faq->saveOrFail();

        }catch(Exception $ex){
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.faq.show', $faq)->with('success', 'Question created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        $viewData = [
            'Question' => $faq->question,
            'Answer' => $faq->answer,
            // 'Questioned By' => 'Q8Cars',
            'Brand' => empty($faq->brand) ? 'NIL' : $faq->brand->name,
            'Car' => empty($faq->car) ? 'NIL' : $faq->car->model_name,
            'Car Version' => empty($faq->carVersion) ? 'NIL' : $faq->carVersion->varient_name,
            'Sort Order' => empty($faq->sort_order) ? 'Nil' : $faq->sort_order,
            
            'Posted On' => dateTimeFormat($faq->created_at),
            'Status' => config('params.faq.status')[$faq->question_status],
            'Updated At' => dateTimeFormat($faq->updated_at),
        ];
        
        return view('admin.faq.show', compact('faq', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        $currentBrand = null;
        $currentBrand = json_encode([
            'id' => $faq->brand_id,
            'text' => $faq->brand->name
        ]);
        $currentCar = null;
        $currentCar = json_encode([
            'id' => $faq->car_id,
            'text' => $faq->car->model_name
        ]);
        $currentVersion = null;
        $currentVersion = json_encode([
            'id' => $faq->car_version_id,
            'text' => $faq->carVersion->varient_name
        ]);
        return view('admin.faq.edit', compact('faq', 'currentBrand', 'currentCar', 'currentVersion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $faq)
    {
         try{
            
            $faq->brand_id = $request->brand_id;
            $faq->car_id = $request->car_id;
            if($request->car_version_id){
                $faq->car_version_id = $request->car_version_id;
            } 
            $faq->question = $request->question;
            $faq->answer = '<p style="text-align:left;">'.$request->answer.'</p>';
            $faq->sort_order = $request->sort_order;
            $faq->question_status = $request->status;
            $faq->answer_status = $request->status;

            $faq->saveOrFail();

        }catch(Exception $ex){
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.faq.show', $faq)->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
       
        try {  
            $faq->delete();
        } catch (Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }
 
        return redirect()->route('admin.faq.index')->with('success', 'Question deleted successfully!');
    }
}
