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

                @canany(['All', 'Users'])
                    <li>
                        <a href="{{ route('admin.user.index') }}" class="waves-effect">
                            <i class="fa fa-user"></i><span> Users</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Brands'])
                    <li>
                        <a href="{{ route('admin.brand.index') }}" class="waves-effect">
                            <i class="fa fa-car"></i><span> Brands </span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Body Types'])
                    <li>
                        <a href="{{ route('admin.body-type.index') }}" class="waves-effect">
                            <i class="fa fa-truck"></i><span> Body Types </span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Colors'])
                    <li>
                        <a href="{{ route('admin.color.index') }}" class="waves-effect">
                            <i class="fa fa-paint-brush"></i><span> Colors </span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Emi Calculator'])
                    <li>
                        <a href="{{ route('admin.emi-info.index') }}" class="waves-effect">
                            <i class="fa fa-calculator"></i><span> EMI Calculator </span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Loan Eligibility Calculator'])
                    <li>
                        <a href="{{ route('admin.loan-info.index') }}" class="waves-effect">
                            <i class="fa fa-calculator"></i><span> Loan Eligibility Calculator </span>
                        </a>
                    </li>
                @endcanany


                @canany(['All', 'Car Management'])
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
                @endcanany
                @canany(['All', 'Test Drive Requests'])
                    <li>
                        <a href="{{ route('admin.test-ride-requests.index') }}" class="waves-effect">
                            <i class="fa fa-taxi"></i><span> Test Ride Requests</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Offers'])
                    <li>
                        <a href="{{ route('admin.offers.index') }}" class="waves-effect">
                            <i class="fa fa-gift"></i><span> Offers</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Offers Requests'])
                    <li>
                        <a href="{{ route('admin.offer-requests.index') }}" class="waves-effect">
                            <i class="fa fa-gift"></i><span> Enquiries</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Notifications'])
                    <li>
                        <a href="{{ route('admin.notifications.index') }}" class="waves-effect">
                            <i class="fa fa-bell"></i><span> Notifications</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Banks'])
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
                @endcanany
                @canany(['All', 'Reviews'])
                    <li>
                        <a href="{{ route('admin.reviews.index') }}" class="waves-effect">
                            <i class="fa fa-comment"></i><span> Reviews</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Faq'])
                    <li>
                        <a href="{{ route('admin.faq.index') }}" class="waves-effect">
                            <i class="fa fa-question"></i><span> FAQ</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'News'])
                    <li>
                        <a href="{{ route('admin.news.index') }}" class="waves-effect">
                            <i class="fa fa-newspaper-o"></i><span> News</span>
                        </a>
                    </li>
                @endcanany
                @canany(['All', 'Reports'])
                    <li class="has_sub">
                        <a href="#" class="waves-effect"><i class="fa fa-file"></i> <span> Reports </span> <span
                                class="pull-right"><i class="md md-add"></i></span></a>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('admin.reports.user.index') }}">User Report</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.car.index') }}">Car Report</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.test-ride.index') }}">Test Ride Requests Report</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.loan-requests.index') }}">Loan Requests Report</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.offer-request.index') }}">Enquiries Report</a>
                            </li>

                        </ul>
                    </li>
                @endcanany

                @canany(['All'])
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
                            <li>
                                <a href="{{ route('admin.sub-admin.admin.index') }}">Users</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.configurations') }}">Configurations</a>
                            </li>
                        </ul>
                    </li>
                @endcanany
                @canany(['All'])
                    <li class="has_sub"> 
                    <a href="#" class="waves-effect"><i class="fa fa-cog"></i><span> Settings </span><span class="pull-right"><i class="md md-add"></i></span></a>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('admin.configurations') }}">Configurations</a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                @canany(['All', 'Trash'])
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
                @endcanany


        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
