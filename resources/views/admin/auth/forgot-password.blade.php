<x-guest-layout title="Admin Reset Password">
<div class="w-100">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b class="session-msg">{{ __('Success') }}! {{ __(session('success')) }}</b>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b class="session-msg">{{ __('Warning') }}! {{ __(session('error')) }}</b>
                            </div>
                        @endif
                    </div>
    <div class="card card-pages">
        <div class="card-header bg-img" style="display: flex;justify-content: center;">
            <div class=""></div>
            <h3 class="text-center m-t-10" style="color:#fff"> Reset Password for <strong>{{ config('app.name') }}</strong> </h3>
        </div>
      
        <div class="card-body">
            <form method="GET" class="form-horizontal m-t-20" action="{{ route('admin.login.send-reset-password-link') }}">
              
                <div class="form-group">
                    <div class="col-12">
                        <input id="email" name="email" type="email" class="form-control input-lg @error('email') is-invalid @enderror" type="text"  placeholder="Enter your registered Email" name="email" autofocus>

                        @error('email')
                        <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group text-center m-t-40">
                    <div class="col-12">
                        <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit" style="color:#fff !important">
                           Submit
                        </button>
                    </div>
                </div>
                
             
            </form>
        </div>

    </div>
</x-guest-layout>