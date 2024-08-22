<?php

namespace App\Http\Controllers\Admin\Trash;

use App\Http\Controllers\Controller;
use App\DataGrids\Admin\Trash\UserDataGrid;
use App\Models\User;

class UserTrashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new UserDataGrid(request()->query());
        return view('admin.trash.user.index',compact('grid'));
    }

    public function show($id)
    {
        $user = User::onlyTrashed()->find($id);
        return view('admin.trash.user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::onlyTrashed()->find($id);
        if (empty($user)) {
            abort(404);
        }
        try
        {
            $user->restore();
        }
        catch (\Exception $ex)
        {
            logger($ex);
            return back()->with('error', __('app.error'));
        }

        return redirect()->route('admin.trash-user.index')
            ->with('success', 'Customer restored successfully!');
    }
}
