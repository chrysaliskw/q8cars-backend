<?php

namespace App\Http\Controllers\admin;

use App\DataGrids\Admin\CarComparisonGrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarComparisonListRequest;
use App\Models\Car;
use App\Models\CarComparisonList;
use Illuminate\Http\Request;
use App\Models\CarVersion;

class CarComparisonListsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new CarComparisonGrid();
        return view('admin.comparison.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.comparison.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarComparisonListRequest $request)
    {
        // dd($request->all());
        if ($request->car_id_1 == $request->car_id_2 || $request->car_id == $request->car_id_1 || $request->car_id == $request->car_id_2) {
            return back()->with('error', 'Compare Model 1 and Compare Model 2 cannot be same!')->withInput()->withErrors(['car_id_1' => 'Compare Model 1 and Compare Model 2 cannot be same!']);
        }
        if (($request->car_version_1_id != null && $request->car_version_2_id != null) && $request->car_version_1_id == $request->car_version_2_id) {
            return back()->with('error', 'Car version 1 and Car version 2 cannot be same!')->withInput()->withErrors(['car_version_1_id' => 'Car version 1 and Car version 2 cannot be same!']);
        }
        
        try {
            
            if (CarComparisonList::where('car_id', $request->car_id)->where('page', CarComparisonList::CAR_DETAIL_PAGE)->exists()) {
                return back()->withErrors(['car_id' => 'Main car comparison list already exists!'])
                ->with('error',  'Main car comparison list already exists!')
                ->withInput();
            }
                      // dd($request->all());



            CarComparisonList::create(
                [
                    'page' => $request->page,
                    'body_type' => $request->body_type_id,
                    'car_id' => $request->car_id,
                    'car_1_id' => $request->car_id_1,
                    'car_version_1_id' => $request->car_version_1_id,
                    'car_2_id' => $request->car_id_2,
                    'car_version_2_id' => $request->car_2_version_id,
                    'brand_id' => $request->brand_id,
                    'brand_1_id' => $request->brand_1_id,
                    'brand_2_id' => $request->brand_2_id,
                ]

            );
            return redirect()->route('admin.comparison.index')->with('success', 'New car comparison list created!');
        } catch (\Exception $ex) {
            return back()->with('error', __('app.error') . $ex)->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        // Prepare data for the view
        $carComparisonList = CarComparisonList::find($id);
        $id = $carComparisonList->id;

        // dd($carComparisonList->car->model_name);

        $viewData = [

            'Page' => $carComparisonList->page == 1 ? 'Home Page' : 'Detailed Page',
            'Car Body Type' => $carComparisonList->body_type ?? 'N/A',
            'Car Image' => $carComparisonList->car->image ?? 'N/A',
            'Main Brand' => $carComparisonList->brand->name ?? 'N/A',
            'Car Model' => $carComparisonList->car->model_name ?? 'N/A',


            'Brand 1' => $carComparisonList->brand1->name ?? 'N/A',
            'Car 1 Image' => $carComparisonList->car1->image ?? 'N/A',
            'Car 1 Model' => $carComparisonList->car1->model_name ?? 'N/A',
            'Car version 1' => $carComparisonList->version1 ? $carComparisonList->version1->varient_name : 'N/A',

            'Brand 2' => $carComparisonList->brand2->name ?? 'N/A',
            'Car 2 Image' => $carComparisonList->car2->image ?? 'N/A',
            'Car 2 Model' => $carComparisonList->car2->model_name ?? 'N/A',
            'Car version 2' => $carComparisonList->version2 ? $carComparisonList->version2->varient_name : 'N/A',
        ];
        // dd($viewData);

        return view('admin.comparison.show', compact('viewData', 'id'));
    }
    public function edit(string $id)
    {
        $carComparisonList = CarComparisonList::find($id);
        if (!$carComparisonList) {
            return redirect()->route('admin.comparison.index')->with('error', 'Car comparison not found.');
        }


        $currentCarModel = $carComparisonList->car ? json_encode([
            'id' => $carComparisonList->car_id,
            'text' => $carComparisonList->car->model_name
        ]) : null;

        $currentCarModel1 = $carComparisonList->car1 ? json_encode([
            'id' => $carComparisonList->car_1_id,
            'text' => $carComparisonList->car1->model_name
        ]) : null;

        $currentCarModel2 = $carComparisonList->car2 ? json_encode([
            'id' => $carComparisonList->car_2_id,
            'text' => $carComparisonList->car2->model_name
        ]) : null;

        $currentbrand1 = $carComparisonList->brand1 ? json_encode([
            'id' => $carComparisonList->brand_1_id,
            'text' => $carComparisonList->brand1->name
        ]) : null;

        $currentCarVersion1 = $carComparisonList->version1 ? json_encode([
            'id' => $carComparisonList->car_version_1_id,
            'text' => $carComparisonList->version1->varient_name
        ]) : null;

        $currentCarVersion2 = $carComparisonList->version2 ? json_encode([
            'id' => $carComparisonList->car_version_2_id,
            'text' => $carComparisonList->version2->varient_name
        ]) : NULL;

        $currentPage = $carComparisonList->page ? json_encode([
            'id' => $carComparisonList->page,
            'text' => $carComparisonList->page == 1 ? 'Home Page' : 'Detailed Page'
        ]) : null;

        $currentbrand = $carComparisonList->brand ? json_encode([
            'id' => $carComparisonList->brand_id,
            'text' => $carComparisonList->brand->name
        ]) : null;
        $currentbrand2 = $carComparisonList->brand2 ? json_encode([
            'id' => $carComparisonList->brand_2_id,
            'text' => $carComparisonList->brand2->name
        ]) : null;
        $currentBodyType = $carComparisonList->body_type ? json_encode([
            'id' => $carComparisonList->body_type,
            'text' => $carComparisonList->bodyType->name
        ]) : null;

        // dd([$currentCarVersion1, $currentCarVersion2    ]);
        return view('admin.comparison.edit', compact(
            'carComparisonList',
            'currentCarModel',
            'currentCarModel1',
            'currentCarModel2',
            'currentCarVersion1',
            'currentCarVersion2',
            'currentPage',
            'currentbrand',
            'currentbrand1',
            'currentbrand2',
            'currentBodyType'

        ));
    }
    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    public function update(CarComparisonListRequest $request, string $id)

    {
        
        if ($request->car_id_1 == $request->car_id_2 || $request->car_id == $request->car_id_1 || $request->car_id == $request->car_id_2) {
            return back()->with('error', 'Compare Models cannot be same!')->withInput()->withErrors(['car_id_1' => 'Compare Model 1 and Compare Model 2 cannot be same!']);
        }
        if (($request->car_version_1_id != null && $request->car_version_2_id != null) && $request->car_version_1_id == $request->car_version_2_id) {
            return back()->with('error', 'Car version 1 and Car version 2 cannot be same!')->withInput()->withErrors(['car_version_1_id' => 'Car version 1 and Car version 2 cannot be same!']);
        }
        try {
            CarComparisonList::find($id)->update(
                [
                    'page' => $request->page,
                    'car_id' => $request->car_id,
                    'car_1_id' => $request->car_id_1,
                    'car_version_1_id' => $request->car_version_1_id,
                    'car_2_id' => $request->car_id_2,
                    'car_version_2_id' => $request->car_2_version_id,
                    'brand_id' => $request->brand_id,
                    'brand_1_id' => $request->brand_1_id,
                    'brand_2_id' => $request->brand_2_id,
                    'body_type' => $request->body_type
                ]
            );


            return redirect()->route('admin.comparison.index')->with('success', 'Car comparison updated successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', __('app.error') . $ex)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            CarComparisonList::find($id)->delete();
        } catch (\Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.comparison.index')->with('success', 'Car comparison deleted successfully!');
    }


    public function select(Request $request)
    {
        $page = $request->query('page');

        $bodyTypeId = $request->get('body_type');
        $brand = $request->get('brand_id');
        $limit = 100;
        $offset = ($page - 1) * $limit;

        // Fetch cars based on the body type
        $query = Car::whereHas('carVersions', function ($query) use ($bodyTypeId) {
            $query->where('body_type', $bodyTypeId);
        })->where('brand_id', $brand);
        // ->pluck('id', 'model_name as text');

        $cars = $query->select(['id', 'model_name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $cars;
        $response['pagination'] = ['more' => !empty($cars) ?? false];

        return $response;

        //    return response()->json($cars);
    }

    // public function getCarModels(Request $request)
    // {
    //     $bodyTypeId = $request->get('body_type_id');
    //     $query = Car::query();

    //     if ($request->has('brand_id')) {
    //         $query->where('brand_id', $request->brand_id);
    //     }
    //  // Fetch cars based on the body type

    //     $cars = Car::whereHas('carVersions', function($query) use ($bodyTypeId) {
    //         $query->where('body_type', $bodyTypeId);
    //     })->pluck('id', 'model_name');

    //     return response()->json($query->get(['id', 'model_name']));
    // }

}
