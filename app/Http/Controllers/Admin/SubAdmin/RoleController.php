<?php

namespace App\Http\Controllers\Admin\SubAdmin;


use Exception;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Rules\RegexAlphaNumSpace;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\DataGrids\Admin\SubAdmin\RoleDataGrid;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new RoleDataGrid(request()->query());

        return view('admin.sub-admin.role.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::where('name', 'not like', 'All')->get();
        dd($permissions);

        return view('admin.sub-admin.role.create', compact('permissions'));
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
                        $fail('The name has already taken.');
                    }
                },
            ],
            'permissions' => 'required|exists:' . Permission::class . ',id',
        ], ['permissions.required' => 'Please choose a permission.']);


        DB::beginTransaction();

        try {
            $role = Role::create(['name' => $request->name]);

            foreach ($request->permissions as $permissionId) {
                $permission = Permission::findById($permissionId);
                $role->givePermissionTo($permission);
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            throw ($ex);
            return back()->with('error', __('aap.error'))->withInput();
        }

        return redirect()->route('admin.sub-admin.role.index')
            ->with('success', 'Role created successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {

        $viewData = [
            'Name' => $role->name,
            'Permissions' => $this->getCurrentPermissions($role),
            'Created At' => dateTimeFormat($role->created_at),
            'Updated At' => dateTimeFormat($role->updated_at)
        ];

        return view('admin.sub-admin.role.show', compact('role', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::where('name', 'not like', 'All')->get();
        $currentPermissions = [];

        foreach ($role->permissions as $permission) {
            array_push($currentPermissions, $permission->id);
        }

        return view('admin.sub-admin.role.edit', compact('role', 'permissions', 'currentPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:200',
                function ($attribute, $value, $fail) use ($request) {
                    if ($this->assertNameIsUnique($request) == false) {
                        $fail('The name has already taken.');
                    }
                },
            ],
            'permissions' => 'required|exists:' . Permission::class . ',id',
        ], ['permissions.required' => 'Please choose a permission.']);


        DB::beginTransaction();

        try {
            $role->name = $request->name;
            $role->save();

            $admin = Admin::find(1);
            $role->revokePermissionTo($role->permissions);

            foreach ($request->permissions as $permissionId) {
                $permission = Permission::findById($permissionId);
                $role->givePermissionTo($permission);

                $admin->givePermissionTo($permission);
            }




            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.sub-admin.role.show', $role)
            ->with('success', 'Role updated successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();

        try {

            $userHasRole =  DB::table('model_has_roles')->where('role_id', $role->id)->get();

            if ($userHasRole->count() > 0) {
                return redirect()->route('admin.sub-admin.role.index')
                    ->with('error', 'Cannot delete role as there are active users associated with it !');
            }

            $role->delete();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app_error'))->withInput();
        }

        return redirect()->route('admin.sub-admin.role.index')->with('success', 'Role deleted successfully !');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    private function assertNameIsUnique(Request $request)
    {
        $exist = Role::where('name', $request->name)
            ->when($request->isMethod('put'), function ($query, $method) use ($request) {
                return $query->where('id', '<>', $request->route('role')->id);
            })
            ->exists();

        return $exist == false;
    }

    private function getCurrentPermissions(Role $role)
    {
        $rolePermissions = $role->permissions;

        $permissionList = '';

        foreach ($rolePermissions as $permission) {
            $permissionList = $permissionList . '  ' . $permission->name;
            // $permissionList = nl2br($permissionList);

        }

        return $permissionList;
    }
}
