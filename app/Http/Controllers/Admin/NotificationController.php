<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserNotificationMapping;
use App\Services\Admin\NotificationService;
use App\DataGrids\Admin\NotificationDataGrid;
use App\Http\Requests\Admin\NotificationRequest;
use App\Services\PushNotification\FirebasePushNotificationService;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new NotificationDataGrid(request()->query());

        return view('admin.notifications.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NotificationRequest $request)
    {
        try {
            $service = new NotificationService();
            $notification = $service->create($request);

            $image = $request->has('image') ? file_asset('files-notifications', $request->image) : null;

            try {
                $title = $notification->title;
                $body = $notification->description;
                $topic = Notification::COMMON_CHANNEL;

                $fcmService = new FirebasePushNotificationService();

                $fcmService->sendTopicNotification($topic, $title, $body, $image);

            } catch (Exception  $ex) {
                logger($ex);
                return back()->with('error', __('app.error'))->withInput();
            }

        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.notifications.show', $notification)->with('success', 'Notification created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        $viewData = [
            'Title' => empty($notification->title) ? 'NIL' : $notification->title,
            'Description' => empty($notification->description) ? 'NIL' : $notification->description,
            'Image' => empty($notification->image) ? 'NIL' : '<img src="' . url(file_asset('files-notifications', $notification->image)) . '" alt="Notification Image" style="max-width: 200px;"/>',
            'Logo' => empty($notification->logo) ? 'NIL' : '<img src="' . url(file_asset('files-notifications', $notification->logo)) . '" alt="Notification Logo" style="max-width: 200px;"/>',
            'Start date' => empty($notification->start_date) ? 'NIL' : dateTimeFormat($notification->start_date),
            'End date' => empty($notification->end_date) ? 'NIL' : dateTimeFormat($notification->end_date),
            'Status' => $notification->status == Notification::STATUS_ACTIVE ? 'Active' :
                       ($notification->status == Notification::STATUS_INACTIVE ? 'Inactive' :
                       ($notification->status == Notification::STATUS_EXPIRED ? 'Expired' : 'Unknown')),
            'Created At' => dateTimeFormat($notification->created_at),
            'Updated At' => dateTimeFormat($notification->updated_at),
        ];

        return view('admin.notifications.show', compact('notification', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        return view('admin.notifications.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NotificationRequest $request, Notification $notification)
    {
        try {
            $service = new NotificationService();
            $service->update($request, $notification);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.notifications.show', $notification)->with('success', 'Notification updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        try {
            UserNotificationMapping::where('notification_id', $notification->id)->delete();
            $notification->delete();
        }
        catch (Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully!');
    }
}
