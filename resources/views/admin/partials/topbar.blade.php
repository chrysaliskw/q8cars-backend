<!-- Top Bar Start -->
<div class="topbar">
    <div class="topbar-left" style="background-color:#fff;">
        <div class="text-center">
            <a href="{{ route('admin.dashboard') }}" class="logo">
            <!-- https://dev.kuwait-cars.aufaitux.com/_next/static/media/kuwait-logo-dark.ddd20637.svg -->
            <!-- https://dev.kuwait-cars.aufaitux.com/images/svg/kuwait-logo-light.svg -->
                <img src="https://dev.kuwait-cars.aufaitux.com/_next/static/media/kuwait-logo-dark.ddd20637.svg" alt="logo" width="50" height="50"> 
                <span style="color:#333;"> {{ config('app.name') }} </span>
            </a>
        </div>
    </div>
    

    <nav class="navbar navbar-default" style="background-color:red !important;">

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
                         <span class="badge badge-pill badge-xs badge-danger"></span>
                    </a>
                   
                </li>
                
                <li class="d-none d-sm-block">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="md md-crop-free"></i></a>
                </li>
                <li class="dropdown open">
                    <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
                    @if(Auth::user()->picture)
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