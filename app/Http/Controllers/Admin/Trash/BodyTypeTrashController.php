<?php

namespace App\Http\Controllers\Admin\Trash;

use App\Http\Controllers\Controller;
use App\DataGrids\Admin\Trash\BodyTypeDataGrid;
use App\Models\BodyType;

class BodyTypeTrashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new BodyTypeDataGrid(request()->query());
        return view('admin.trash.body-type.index',compact('grid'));
    }

    public function show($id)
    {
        $bodyType = BodyType::onlyTrashed()->find($id);
        return view('admin.trash.body-type.show', compact('bodyType'));
    }

    public function edit($id)
    {
        $bodyType = BodyType::onlyTrashed()->find($id);
        if (empty($bodyType)) {
            abort(404);
        }
        try
        {
            $bodyType->restore();
        }
        catch (\Exception $ex)
        {
            logger($ex);
            return back()->with('error', __('app.error'));
        }

        return redirect()->route('admin.trash-body-type.index')
            ->with('success', 'Brand restored successfully!');
    }
}
