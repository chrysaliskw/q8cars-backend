<?php

namespace App\Http\Controllers\Admin\Trash;

use App\Http\Controllers\Controller;
use App\DataGrids\Admin\Trash\BrandDataGrid;
use App\Models\Brand;

class BrandTrashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new BrandDataGrid(request()->query());
        return view('admin.trash.brand.index',compact('grid'));
    }

    public function show($id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        return view('admin.trash.brand.show', compact('brand'));
    }

    public function edit($id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        if (empty($brand)) {
            abort(404);
        }
        try
        {
            $brand->restore();
        }
        catch (\Exception $ex)
        {
            logger($ex);
            return back()->with('error', __('app.error'));
        }

        return redirect()->route('admin.trash-brand.index')
            ->with('success', 'Brand restored successfully!');
    }
}
