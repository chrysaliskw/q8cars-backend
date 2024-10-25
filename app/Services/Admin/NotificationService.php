<?php

namespace App\Services\Admin;

use Exception;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use App\Models\UserNotificationMapping;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\NotificationRequest;

class NotificationService
{
    protected $notification;
    protected $request;

    public function create(NotificationRequest $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try{
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data['image'] = $request->file('image')->store(Notification::FILE_DIR);
            }

            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $data['logo'] = $request->file('logo')->store(Notification::FILE_DIR);
            }

            $notification = Notification::create($data);

            $this->mapNotificationToAllUsers($notification);

            DB::commit();
            return $notification;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

    }

    public function update(NotificationRequest $request, Notification $notification)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($notification->image) {
                    Storage::disk('public')->delete($notification->image);
                }
                $data['image'] = $request->file('image')->store(Notification::FILE_DIR);
            }

            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                if ($notification->logo) {
                    Storage::disk('public')->delete($notification->logo);
                }
                $data['logo'] = $request->file('logo')->store(Notification::FILE_DIR);
            }

            $notification->update($data);

            DB::commit();
            return $notification;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
    }

    private function mapNotificationToAllUsers(Notification $notification)
    {
        $users = User::all();

        foreach ($users as $user) {
            UserNotificationMapping::create([
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'read_status' => 0,
            ]);
        }
    }
}
