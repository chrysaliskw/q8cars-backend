<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataGrids\Admin\UserDataGrid;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Exception;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\DB;
use App\Jobs\JunkFileDeleteJob;
use App\Models\OfferRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new UserDataGrid(request()->query());

        return view('admin.user.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $service = new UserService($request);
            $user = $service->handle();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.user.show', $user)->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        // dd($request->all());
        try {
            $service = new UserService($request, $user);
            $user = $service->handle();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.user.show', $user)->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $oldPicture[] = $user->icon;
            // JunkFileDeleteJob::dispatchAfterResponse(User::FILE_DIR, $oldPicture);
            $user->delete();
            DB::commit();
        } catch (Exception $ex) {
            logger($ex);
            DB::rollBack();
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully!');
    }
    public function select(Request $request)
    {
        $page = $request->query('page');
        $term = $request->query('search');
        $countryId = $request->query('country_id');
        $limit = 100;
        $offset = ($page - 1) * $limit;

        // $query = OfferRequest::whereHas('user')->where();
        // $query = User::whereHas('offerRequests')->where('mobile', 'like', "%$term%");
        $query = User::whereHas('offerRequests', function ($query) use ($term) {
            // Concatenate 'phone_code' and 'mobile' to search for both
            $query->whereRaw("CONCAT(phone_code, mobile) LIKE ?", ["%$term%"]);
        });

        $mobile = $query->select(['id', DB::raw("CONCAT(phone_code, mobile) AS text")])
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->toArray();
        $response['results'] = $mobile;
        $response['pagination'] = ['more' => !empty($banks) ?? false];
        logger($response);
        return $response;
    }
}
