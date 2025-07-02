<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the Admin profile.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $admin = Auth::user();

        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $admin = Admin::find(Auth::id());

        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dd($request);
        $admin = Admin::find(Auth::id());

        $validatedData = $request->validate([
            'name' => [
                'required', 'regex:/^[a-zA-Z\s\d]*$/', 'string', 'max:200',
                Rule::unique(Admin::class)->ignore($admin->id)
            ],
            'email' => [
                'required', 'email',
                Rule::unique(Admin::class)->ignore($admin->id)
            ],
            'password' => [
                'required_with:new_password', 'nullable' ,
                function($attribute, $value, $fail) use ($admin) {
                    if(!Hash::check($value, $admin->password))
                        $fail('The current password is incorrect');
                }
            ],
            'new_password' => 'nullable|string|min:8|max:15|regex:/^[a-zA-Z\d!@#$%^&*_]*$/',
            'confirm_password' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    return $request->filled('new_password');
                }),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('new_password') && $value !== $request->input('new_password')) {
                        $fail('The confirmation password must match the new password.');
                    }
                },
            ],
            'picture' => 'mimes:jpg,png,jpeg|max:2048|nullable',
        ]);

        try
        {
            if($request->hasFile('picture'))
            {
                $request->picture->store(Admin::FILE_DIR);
                $admin->picture = $request->picture->hashName();
            }
            if($validatedData['new_password'] != null) {
                $admin->password = Hash::make($validatedData['new_password']);
            }
            $admin->name = $validatedData['name'];
            $admin->email = $validatedData['email'];
            $admin->update();
        }

        catch(Exception $ex){
            logger($ex);
            return back()->with('error', __('app.error'));
        }
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }
}
