<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        {{--  <div class="user-details">
            <div class="pull-left">
                @if (Auth::user()->picture)
                    <img src="{{ file_asset('files-admin', Auth::user()->picture) }}" alt=""
                        class="thumb-md rounded-circle">
                @else
                    <img src="{{ asset('moltran-asset/images/dp.png') }}" alt="" class="thumb-md rounded-circle">
                @endif

            </div>
            <div class="user-info">
                <h4 class="text-black font-weight-bold admin-name" title="{{ auth()->user()->name }}">
                    </h4>
                <!-- <p class="text-muted m-0">Administrator</p> -->
            </div>
        </div> --}}
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class='fa fa-home'></i><span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.user.index') }}" class="waves-effect">
                        <i class="fa fa-user"></i><span> Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.brand.index') }}" class="waves-effect">
                        <i class="fa fa-car"></i><span> Brands </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.body-type.index') }}" class="waves-effect">
                        <i class="fa fa-truck"></i><span> Body Types </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.color.index') }}" class="waves-effect">
                        <i class="fa fa-paint-brush"></i><span> Colors </span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.car.index') }}" class="waves-effect">
                    <i class="fa fa-car"></i><span> Cars </span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('admin.emi-info.index') }}" class="waves-effect">
                        <i class="fa fa-calculator"></i><span> EMI Calculator </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.loan-info.index') }}" class="waves-effect">
                        <i class="fa fa-calculator"></i><span> Loan Eligibility Calculator </span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="fa fa-car"></i> <span> Car Management </span> <span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('admin.car.index') }}">Car</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.comparison.index') }}">Compare Cars</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.curated-comparison.index') }}">Curated Comparison</a>
                        </li>
                    </ul>
                </li>

                {{-- <li>
                    <a href="{{ route('admin.comparison.index') }}" class="waves-effect">
                    <i class="fa fa-car"></i><span> Compare Cars </span>
                    </a>
                </li> --}}

                {{-- <li>
                    <a href="{{ route('admin.curated-comparison.index') }}" class="waves-effect">
                    <i class="fa fa-car"></i><span> Curated Comparison </span>
                    </a>
                </li> --}}

                <li>
                    <a href="{{ route('admin.test-ride-requests.index') }}" class="waves-effect">
                        <i class="fa fa-taxi"></i><span> Test Ride Requests</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.offers.index') }}" class="waves-effect">
                        <i class="fa fa-gift"></i><span> Offers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.offer-requests.index') }}" class="waves-effect">
                        <i class="fa fa-gift"></i><span> Offer Requests</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.notifications.index') }}" class="waves-effect">
                        <i class="fa fa-bell"></i><span> Notifications</span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="fa fa-bank"></i> <span> Banks </span> <span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('admin.partner-banks.index') }}">Partner Banks</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.suggested-banks.index') }}">Suggested Banks</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.loan-requests.index') }}">Loan Requests</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}" class="waves-effect">
                        <i class="fa fa-comment"></i><span> Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.faq.index') }}" class="waves-effect">
                        <i class="fa fa-question"></i><span> FAQ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.news.index') }}" class="waves-effect">
                        <i class="fa fa-newspaper-o"></i><span> News</span>
                    </a>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="fa fa-user"></i> <span> Sub Admins </span> <span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('admin.sub-admin.permission.index') }}">Permissions</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.sub-admin.role.index') }}">Roles</a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('admin.partner-banks.index') }}">Partner Banks</a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('admin.suggested-banks.index') }}">Suggested Banks</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.loan-requests.index') }}">Loan Requests</a>
                        </li> --}}
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="fa fa-trash"></i> <span> Trash </span> <span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('admin.trash-user.index') }}">User</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.trash-brand.index') }}">Brand</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.trash-body-type.index') }}">Body Type</a>
                        </li>
                    </ul>
                </li>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
