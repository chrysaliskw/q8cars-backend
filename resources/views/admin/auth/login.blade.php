<x-guest-layout title="Admin Login">
    <div class="card card-pages">
        <div class="card-header bg-img" style="display: flex;justify-content: center;">
            <div class="bg-overlay"></div>
            <h3 class="text-center m-t-10 text-white"> Sign In to <strong>{{ config('app.name') }}</strong> </h3>
        </div>
        <div class="card-body">
            <form method="POST" class="form-horizontal m-t-20" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="form-group">
                    <div class="col-12">
                        <input id="email" type="email" class="form-control input-lg @error('email') is-invalid @enderror" type="text"  placeholder="Email" name="email" autofocus>

                        @error('email')
                        <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12">
                        <input id="password" class="form-control input-lg @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" autocomplete="current-password">

                        @error('password')
                        <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12">
                        <div class="checkbox checkbox-primary">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-signup">
                                Remember me
                            </label>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center m-t-40">
                    <div class="col-12">
                        <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">
                            Log In
                        </button>
                    </div>
                </div>
                
                <div class="form-group row m-t-30">
                    <div class="col-sm-7">
                        {{--{{ route('admin.login.forgot-password') }}--}}
                        <!-- <a href="#"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a> -->
                    </div>
                    <div class="col-sm-5 text-right">
                    {{--    <a href="#">Create an account</a>--}}
                    </div>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>