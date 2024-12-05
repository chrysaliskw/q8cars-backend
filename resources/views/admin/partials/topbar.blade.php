<!-- Top Bar Start -->
<div class="topbar" style="z-index:999999999">
    <div class="topbar-left" style="background-color:#fff;">
        <div class="text-center">
            <a href="{{ route('admin.dashboard') }}" class="logo">
            <!-- https://dev.kuwait-cars.aufaitux.com/_next/static/media/kuwait-logo-dark.ddd20637.svg -->
            <!-- https://dev.kuwait-cars.aufaitux.com/images/svg/kuwait-logo-light.svg -->
                <img src="https://dev.kuwait-cars.aufaitux.com/_next/static/media/kuwait-logo-dark.ddd20637.svg" alt="logo" >
                <span style="color:#333;"> </span>
            </a>
        </div>
    </div>

    @php
        $pendingTestRideRequestCount = \App\Models\TestDrive::where('status',App\Models\TestDrive::STATUS_SUBMITTED)->count();
        $pendingOfferRequestCount = \App\Models\OfferRequest::where('status',App\Models\OfferRequest::STATUS_PENDING)->where('type', App\Models\OfferRequest::TYPE_OFFER)->count();
        $pendingOnRoadPriceRequestCount =  \App\Models\OfferRequest::where('status',App\Models\OfferRequest::STATUS_PENDING)->where('type', App\Models\OfferRequest::TYPE_ONROAD_PRICE)->count();
        $pendingEmiOfferRequestCount = \App\Models\OfferRequest::where('status',App\Models\OfferRequest::STATUS_PENDING)->where('type', App\Models\OfferRequest::TYPE_EMI_OFFER)->count();
        $pendingLoanRequestCount = \App\Models\BankSuggestionRequest::where('status',App\Models\BankSuggestionRequest::STATUS_SUBMITTED)->where('type', App\Models\BankSuggestionRequest::TYPE_LOAN)->count();
        $pendingBankSuggestionRequestCount = \App\Models\BankSuggestionRequest::where('status',App\Models\BankSuggestionRequest::STATUS_SUBMITTED)->where('type', App\Models\BankSuggestionRequest::TYPE_BANK)->count();
        $pendingReviewRequestCount = \App\Models\Review::where('status',App\Models\Review::STATUS_SUBMITTED)->count();

        $count =  $pendingTestRideRequestCount +  $pendingOfferRequestCount + $pendingOnRoadPriceRequestCount + $pendingEmiOfferRequestCount + $pendingLoanRequestCount + $pendingBankSuggestionRequestCount + $pendingReviewRequestCount;

    @endphp

    <nav class="navbar navbar-default">

        <div class="container-fluid">

            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <a href="#" class="button-menu-mobile open-left">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
                <li>
                    <p class="sosblink" style="padding: 26px 0px 0px 463px;color:#fff;"><strong>
                        <?php

                        if (strpos($_SERVER['SERVER_NAME'], '127.0.0.1') !== false) {
                            echo 'LOCAL';
                        }

                        ?>
                    </strong></p>
                </li>
            </ul>

            <ul class="nav navbar-right float-right list-inline">

                <li class="dropdown d-none d-sm-block">
                    <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                        <i class="md md-notifications"></i>
                         <span class="badge badge-pill badge-xs badge-danger">{{$count}}</span>
                    </a>

                    @if($count > 0)
                        <ul class="dropdown-menu dropdown-menu-lg notification-dropdown">
                        <li class="text-center notifi-title">Notifications </li>
                            @if($pendingTestRideRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.test-ride-requests.index',['status' => 1]) }}" class="list-group-item">
                                        <div class="media">
                                            <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending TestRide Requests - <span class="text-primary">{{ $pendingTestRideRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            @if( $pendingOfferRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.offer-requests.index',['status' => 1, 'type' => 1]) }}" class="list-group-item">
                                        <div class="media">
                                            <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending Offer Requests - <span class="text-primary">{{ $pendingOfferRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            @if( $pendingOnRoadPriceRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.offer-requests.index',['status' => 1, 'type' => 2]) }}" class="list-group-item">
                                        <div class="media">
                                             <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending  Onroad Price Request - <span class="text-primary">{{ $pendingOnRoadPriceRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            @if( $pendingEmiOfferRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.offer-requests.index',['status' => 1, 'type' => 3]) }}" class="list-group-item">
                                        <div class="media">
                                             <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending EMI Offer Request - <span class="text-primary">{{ $pendingEmiOfferRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            @if( $pendingLoanRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.loan-requests.index',['status' => 1]) }}" class="list-group-item">
                                        <div class="media">
                                             <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending Loan Request - <span class="text-primary">{{ $pendingLoanRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            @if( $pendingBankSuggestionRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.suggested-banks.index',['status' => 1]) }}" class="list-group-item">
                                        <div class="media">
                                             <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending Bank Suggestion Request - <span class="text-primary">{{ $pendingBankSuggestionRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            @if( $pendingReviewRequestCount > 0)
                                <li class="list-group">
                                    <a href="{{ route('admin.reviews.index',['status' => 2]) }}" class="list-group-item">
                                        <div class="media">
                                             <div class="media-left pr-2">
                                                <em class="fa fa-bell-o fa-2x text-danger"></em>
                                            </div>
                                            <div class="media-body clearfix">
                                                <p class="m-0">
                                                    <small>
                                                        Pending Review Request - <span class="text-primary">{{ $pendingReviewRequestCount}}</span><br>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </li>

                <li class="d-none d-sm-block">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="md md-crop-free"></i></a>
                </li>
                <li class="dropdown open">
                    <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
                    @if(Auth::user()->picture ?? '')
                        <img src="{{ file_asset('files-admin', Auth::user()->picture) }}" alt="user-img" class="rounded-circle">
                    @else
                        <img src="{{ asset('moltran-asset/images/dp.png') }}" alt="user-img" class="rounded-circle">
                    @endif

                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" onclick="event.preventDefault();
                                                     document.getElementById('profile-form').submit();" class="dropdown-item"><i class="md md-face-unlock mr-2"></i> Profile</a>

                           <form id="profile-form" action="{{ route('admin.profile') }}" method="GET" style="display:none">
                            </form>
                        </li>
                        <li>
                            <a href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item"><i class="md md-settings-power mr-2"></i> Logout</a>

                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- Top Bar End -->
