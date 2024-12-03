<?php

namespace App\Http\Controllers\Admin\SubAdmin;

use Exception;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\RegexAlphaNumSpace;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\DataGrids\Admin\SubAdmin\PermissionDataGrid;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new PermissionDataGrid(request()->query());

        return view('admin.sub-admin.permission.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sub-admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => [
                'required',
                new RegexAlphaNumSpace,
                'string',
                'max:200',
                function ($attribute, $value, $fail) use ($request) {
                    if ($this->assertNameIsUnique($request) == false) {
                        $fail('This permission already exists.');
                    }
                },
            ],
            'section' => ['required', Rule::in(array_keys(config('params.sub-admin.sections')))],
            'subsection' => 'required_if:subsectionCheck,1',
        ], ['subsection.required_if' => 'Please select a subsection.']);

        try {
            $permission = Permission::create(['name' => $request->name]);

            //Assign newly created permission to Super Admin Role
            $role = Role::where('name', 'like', 'Super Admin')->first();
            $role->givePermissionTo($permission);

            //Assign newly created permission to Super Admin Model
            $admin = Admin::find(1);
            $admin->givePermissionTo($permission);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.sub-admin.permission.index')
            ->with('success', 'Permission created successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {

        $viewData = [
            'Name' => $permission->name,
            'Created At' => dateTimeFormat($permission->created_at),
            'Updated At' => dateTimeFormat($permission->updated_at)
        ];

        return view('admin.sub-admin.permission.show', compact('permission', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {

        return view('admin.sub-admin.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:200',
                function ($attribute, $value, $fail) use ($request) {
                    if ($this->assertNameIsUnique($request) == false) {
                        $fail('This permission already exists.');
                    }
                },
            ],

        ]);

        try {
            $permission->name = $request->name;
            $permission->save();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.sub-admin.permission.show', $permission)
            ->with('success', 'Permission updated successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();

        try {

            // Delete the current permision from all roles
            DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();

            // Delete the current permision from all models
            DB::table('model_has_permissions')->where('permission_id', $permission->id)->delete();

            // Delete permission
            $permission->delete();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app_error'))->withInput();
        }
        return redirect()->route('admin.sub-admin.permission.index')->with('success', 'Permission deleted successfully !');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    private function assertNameIsUnique(Request $request)
    {
        $exist = Permission::where('name', $request->name)
            ->when($request->isMethod('put'), function ($query, $method) use ($request) {
                return $query->where('id', '<>', $request->route('permission')->id);
            })
            ->exists();

        return $exist == false;
    }

    public function getSubSection(Request $request)
    {
        $result = [];
        $st = 'params.sub-admin.' . $request->section . '-sub-sections';
        $subsection = config('params.sub-admin.' . $request->section . '-sub-sections');

        if ($subsection) {
            array_push($result, 'All');
            foreach ($subsection as $key => $value) {
                array_push($result, $value);
            }
        }
        return response()->json($result);
    }
}
