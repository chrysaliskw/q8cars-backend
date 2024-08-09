<x-admin-layout title="Profile">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.profile') }}">Profile</a></li>
        <li class="active">Update</li>
    </x-slot>

    

    <div id="edit-page" class="row">
        <div class="col-xl-12">
            <div class="card bg-secondary">
                <div class="card-header">
                    <h3 class="card-title text-white float-left">Update Admin Profile</h3>
                    <div class="dt-buttons float-right">
                        <button type="button" onclick="onSubmit()" id="submit-btn" class="btn btn-primary buttons-copy buttons-html5 btn-md">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs tabs" role="tablist" id="business-user-profile-tab">
            <li class="nav-item tab">
                    <a class="nav-link active" id="about-tab-2" data-toggle="tab" href="#about-2" role="tab" 
                        onclick="onTab('about')" aria-controls="about-2" aria-selected="true">
                        <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                        <span class="d-none d-sm-block">About</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="password-tab-2" data-toggle="tab" href="#password-2" role="tab" 
                        onclick="onTab('password')" aria-controls="password-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                        <span class="d-none d-sm-block">Password</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="images-tab-2" data-toggle="tab" href="#images-2" role="tab" 
                        onclick="onTab('images')" aria-controls="images-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Images</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <form method="POST" id="profile-update-form" action="{{ route('admin.profile.update') }}" 
                    @submit.prevent="handleSubmit(onSubmit)" class="form" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                    <div class="tab-pane show active" id="about-2" role="tabpanel" aria-labelledby="about-tab-2">
                        @include('admin.profile.about')
                    </div>
                    <div class="tab-pane" id="password-2" role="tabpanel" aria-labelledby="contact-tab-2">
                        @include('admin.profile.password')
                    </div>
                    <div class="tab-pane" id="images-2" role="tabpanel" aria-labelledby="images-tab-2">
                        @include('admin.profile.image')
                    </div>

                   

                </form>
            </div>
        </div>
    </div>

    <x-slot name="scripts">

        <script defer type="application/javascript">

            function onTab(tab) 
            {
                    $("#submit-btn").show();
            }

            function onSubmit()
            {
                document.getElementById("profile-update-form").submit();
            }

            

        </script>

    </x-slot>

</x-admin-layout>