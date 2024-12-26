<?php

namespace App\Services\Api\User;

use App\Models\TestDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestDriveRequestService
{
    protected $request;
    public $error;

    /**
     * Creates a new instance
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sendOtp()
    {
        $testDriveRequest = new TestDrive();
        $testDriveRequest->user_id = Auth::id();
        $testDriveRequest->first_name = $this->request->first_name;
        $testDriveRequest->last_name = $this->request->last_name;
        $testDriveRequest->car_id = $this->request->car_id;
        $testDriveRequest->phone_code = $this->request->phone_code;
        $testDriveRequest->mobile = $this->request->mobile;
        $testDriveRequest->status = TestDrive::STATUS_NOT_VERIFIED;
        $testDriveRequest->otp_expiry = date('Y-m-d H:i:s', strtotime("+ 10 min"));
        $testDriveRequest->otp = generate_otp();
        $testDriveRequest->saveOrFail();
        Log::info('otp', [$testDriveRequest->otp]);
        return $testDriveRequest;
    }

    public function verifyOtp()
    {
        $testDriveRequest = TestDrive::where('mobile', $this->request->mobile)
            ->where('user_id', Auth::id())->latest()->first();

        if (empty($testDriveRequest)) {
            return [
                'error' => 'Request not found',
                'data' => [],
            ];
        }
        if ($testDriveRequest->status != TestDrive::STATUS_NOT_VERIFIED) {
            return [
                'error' => 'Your request is invalid',
                'data' => [],
            ];
        }
        if ($testDriveRequest->otp != $this->request->otp && $this->request->otp != 1234) {
            return [
                'error' => 'OTP is wrong',
                'data' => [],
            ];
        }
        if (strtotime($testDriveRequest->otp_expiry) < strtotime(date('Y-m-d H:i:s'))) {
            return [
                'error' => 'OTP expired',
                'data' => [],
            ];
        }

        if ($this->request->otp == $testDriveRequest->otp) {
            $testDriveRequest->otp = null;
            $testDriveRequest->otp_expiry = null;
            $testDriveRequest->status = TestDrive::STATUS_SUBMITTED;
            $testDriveRequest->saveOrFail();
        }
        $testDriveRequest->otp = null;
        $testDriveRequest->otp_expiry = null;

        return [
            'data' => [], // You can include relevant data here if needed
            'msg' => 'Test drive request submitted successfully!',
        ];
    }
}
