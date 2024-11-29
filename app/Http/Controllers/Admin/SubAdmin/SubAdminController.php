<?php

namespace App\Http\Controllers\Admin\SubAdmin;

use Exception;
use App\Models\Town;
use App\Models\Admin;
use App\Models\Region;
use App\Models\Country;
use App\Models\District;
use App\Mail\passwordMail;
//use App\Mail\Admin\PasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\SubAdminRequest;
use App\DataGrids\Admin\SubAdmin\SubAdminDataGrid;
use App\Models\Area;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new SubAdminDataGrid(request()->query());

        return view('admin.sub-admin.sub-admin-users.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', 'not like', 'Super Admin')->get();

        return view('admin.sub-admin.sub-admin-users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        // dd($request->all());
        request()->validate([
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique(Admin::class),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:200',
                Rule::unique(Admin::class),
            ],
            'password' => 'required|string|min:8|max:15|regex:/^[a-zA-Z\d!@#$%^&*_]*$/',
            'status' => 'required',
            'role' => 'required',
        ]);
        dd($request->all());
        DB::beginTransaction();

        try {

            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            if (empty($request->password) == false) {
                $admin->password = Hash::make($request->password);
            }

            if (!empty($request->file_name)) {
                $request->file_name->store(Admin::FILE_DIR);
                $admin->picture = $request->file_name->hashName();
            }
            $admin->status = $request->status;

            $admin->save();

            //Assigning role to subAdmin
            $role = Role::findById($request->role);
            $admin->assignRole($role);


            DB::commit();

            //Mail::to($admin->email)->send(new passwordMail($admin, $request->password));
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app_error'))->withInput();;
        }

        return redirect()->route('admin.sub-admin.admin.index')->with('success', 'Sub Admin created successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $roles = $admin->roles()->pluck('id');
        $roleId = $roles[0];

        $role = Role::findById($roleId);


        return view('admin.sub-admin.sub-admin-users.show', compact('admin', 'role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {


        if ($admin->country_id) {
            $currentCountry = json_encode([
                'id' => $admin->country_id,
                'name' => $admin->country->name,
            ]);
        }
        if ($admin->area_id) {
            $currentArea = json_encode([
                'id' => $admin->area_id,
                'name' => $admin->area->name,
            ]);
        }

        $role = $admin->roles()->pluck('id');

        if (!empty($role)) {
            $roleId = $role[0];
        }

        $roles = Role::where('name', 'not like', 'Super Admin')->get();

        return view('admin.sub-admin.sub-admin-users.edit', compact('admin', 'roleId', 'roles', 'countries', 'areas', 'currentCountry', 'currentArea'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(SubAdminRequest $request, admin $admin)
    {
        DB::beginTransaction();

        try {

            $admin->name = $request->name;
            $admin->email = $request->email;
            if (! empty($request->password)) {
                $admin->password = Hash::make($request->password);
            }

            if (! empty($request->file_name)) {
                $request->file_name->store(Admin::FILE_DIR);
                $admin->picture = $request->file_name->hashName();
            }
            $admin->status = $request->status;
            $admin->country_id = $request->country_id;
            $admin->area_id = $request->area_id;
            $admin->save();

            //Delete current role
            $roleId = $role = $admin->roles()->pluck('id');
            $currentRole = Role::findById($roleId[0]);
            $admin->removeRole($currentRole);

            //Assigning new role to subAdmin
            $role = Role::findById($request->role);
            $admin->assignRole($role);

            DB::commit();

            // if (! empty($request->password)) {
            //     Mail::to($admin->email)->send(new PasswordMail($admin, $request->password));
            // }

        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app_error'))->withInput();;
        }

        return redirect()->route('admin.sub-admin.admin.show', $admin)->with('success', 'Sub Admin updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        DB::beginTransaction();

        try {

            //Delete all roles assigned to sub admin
            DB::table('model_has_roles')->where('model_id', $admin->id)->delete();

            //Delete all permission granted to sub admin
            DB::table('model_has_permissions')->where('model_id', $admin->id)->delete();

            //Make sub admin inactive
            /*$admin->status = Admin::STATUS_INACTIVE;
            $admin->save();*/

            $admin->delete();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app_error'))->withInput();
        }

        return redirect()->route('admin.sub-admin.admin.index')->with('success', 'Sub Admin deleted successfully !');
    }
}
