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
use Illuminate\Support\Carbon;

class NotificationService
{
    protected $notification;
    protected $request;

    public function create(NotificationRequest $request)
    {
        $data = $request->all();
        $expiryDate = Carbon::parse($data['end_date'])->addHours(23)->addMinutes(59)->addSeconds(59);
        $data['end_date'] = $expiryDate;

        DB::beginTransaction();

        try{
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $request->file('image')->store(Notification::FILE_DIR);
                $data['image'] = $request->file('image')->hashName();
            }

            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $request->file('logo')->store(Notification::FILE_DIR);
                $data['logo'] = $request->file('logo')->hashName();
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
        $expiryDate = Carbon::parse($data['end_date'])->addHours(23)->addMinutes(59)->addSeconds(59);
        $data['end_date'] = $expiryDate;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($notification->image) {
                    Storage::disk('public')->delete($notification->image);
                }
                $request->file('image')->store(Notification::FILE_DIR);
                $data['image'] = $request->file('image')->hashName();
            }

            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                if ($notification->logo) {
                    Storage::disk('public')->delete($notification->logo);
                }
                $request->file('logo')->store(Notification::FILE_DIR);
                $data['logo'] = $request->file('logo')->hashName();
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
        $users = User::active()->where('is_mute', 0)->get();

        foreach ($users as $user) {
            UserNotificationMapping::create([
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'read_status' => 0,
            ]);
        }
    }
}
