<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Car;
use App\Models\Bank;
use App\Models\User;
use App\Models\Brand;
use App\Models\Review;
use App\Models\TestDrive;
use App\Models\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BankSuggestionRequest;
use App\DataGrids\Admin\LoanRequestDataGrid;
use App\DataGrids\Admin\TestRideRequestDataGrid;
use App\DataGrids\Admin\Dashboard\LoanDashboardDataGrid;
use App\DataGrids\Admin\Dashboard\OfferDashboardDataGrid;
use App\DataGrids\Admin\Dashboard\TestdriveDashboardDataGrid;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testRideMonthlyCounts = TestDrive::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
        ->whereYear('created_at', now()->year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month')
        ->toArray();

        $loanMonthlyCounts = BankSuggestionRequest::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->year)
            ->where('type', BankSuggestionRequest::TYPE_LOAN)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();

        $testRideMonthlyCountsFilled = [];
        $loanMonthlyCountsFilled = [];
        for ($i = 1; $i <= 12; $i++) {
            $testRideMonthlyCountsFilled[] = $testRideMonthlyCounts[$i] ?? 0;
            $loanMonthlyCountsFilled[] = $loanMonthlyCounts[$i] ?? 0;
        }

        $brands = Brand::all();

        $testDriveDataGrid = new TestdriveDashboardDataGrid(request()->query());
        $offerDataGrid = new OfferDashboardDataGrid(request()->query());
        $loanDataGrid= new LoanDashboardDataGrid(request()->query());

        $viewData = [
            'totalCars' =>  Car::count(),
            'upcomingCars' => Car::upcoming()->count(),
            'justLaunchedCars' => Car::where('is_just_launched',Car::JUST_LAUNCHED)->count(),
            'totalUsers' => User::count(),
            'activeUsers' => User::active()->count(),
            'inactiveUser' => User::where('status',User::STATUS_INACTIVE)->count(),
            'totalBanks' => Bank::count(),
            'activeBanks' => Bank::active()->count(),
            'inactiveBanks' => Bank::where('status',User::STATUS_INACTIVE)->count(),
            'totalLoanRequests' => BankSuggestionRequest::where('type',BankSuggestionRequest::TYPE_LOAN)->count(),
            'newLoanRequests' => BankSuggestionRequest::where('type',BankSuggestionRequest::TYPE_LOAN)->where('status',BankSuggestionRequest::STATUS_SUBMITTED)->count(),
            'completedLoanRequests' => BankSuggestionRequest::where('type',BankSuggestionRequest::TYPE_LOAN)->where('status',BankSuggestionRequest::STATUS_ACCEPTED)->count(),
            // 'totalTestRideRequest' =>TestDrive::count(),
            'totalTestRideRequest' => TestDrive::where('status', '!=', TestDrive::STATUS_NOT_VERIFIED)->count(),
            'newTestRideRequest' => TestDrive::where('status',TestDrive::STATUS_SUBMITTED)->count(),
            'completedTestRideRequest' => TestDrive::where('status',TestDrive::STATUS_COMPLETED)->count(),
            'totalOfferRequests' => OfferRequest::count(),
            'completedOfferRequests' => OfferRequest::where('status', OfferRequest::STATUS_COMPLETED)->count(),
            'newOfferRequests' => OfferRequest::where('status', OfferRequest::STATUS_PENDING)->count(),
            'testRideRequestsCounts' => $testRideMonthlyCountsFilled,
            'loanRequestsCounts' => $loanMonthlyCountsFilled,
            'brands' => $brands,
            'testDriveDataGrid' => $testDriveDataGrid,
            ];

        return view('admin.dashboard',compact('viewData', 'testDriveDataGrid', 'offerDataGrid', 'loanDataGrid'));
    }

    public function getBrandData(Request $request)
    {
        $carId = $request->input('brand_id');

        $offerRequests = OfferRequest::count();
        // $testRideRequests = TestDrive::count();
        $testRideRequests = TestDrive::where('status', '!=', TestDrive::STATUS_NOT_VERIFIED)->count();
        $reviews = Review::count();

        if (!$carId) {
            return response()->json([
                'offerRequests' => $offerRequests,
                'testRideRequests' => $testRideRequests,
                'reviews' => $reviews,
            ]);
        }

        $carIds = Car::where('brand_id', $carId)->active()->launched()->pluck('id');

        Log::info($carIds);

        if (!$carIds) {
            return response()->json([
                'offerRequests' => 0,
                'testRideRequests' => 0,
                'reviews' => 0,
            ]);
        }

        // $brandId = $car->brand_id;

        // $offerRequests = OfferRequest::where('car_id', $carId)->count();
        // $testRideRequests = TestDrive::where('car_id', $carId)->count();
        // $reviews = Review::where('car_id', $carId)->count();

        $offerRequests = OfferRequest::whereIn('car_id',$carIds)
        ->where('status', OfferRequest::STATUS_COMPLETED)
        ->count();

        Log::info($offerRequests);

        $testRideRequests = TestDrive::whereIn('car_id',$carIds)
        ->where('status', TestDrive::STATUS_COMPLETED)
        ->count();

        Log::info($testRideRequests);

        $reviews = Review::whereIn('car_id',$carIds)
        ->where('status', Review::STATUS_VERIFIED)
        ->count();

        Log::info($reviews);

        return response()->json([
            'offerRequests' => $offerRequests,
            'testRideRequests' => $testRideRequests,
            'reviews' => $reviews,
        ]);
    }

}
