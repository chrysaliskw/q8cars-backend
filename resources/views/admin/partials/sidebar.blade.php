<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div class="user-details">
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
                    {{ auth()->user()->name }}</h4>
                <!-- <p class="text-muted m-0">Administrator</p> -->
            </div>
        </div>
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                    <i class='fa fa-home'></i><span> Dashboard </span>
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

             
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
