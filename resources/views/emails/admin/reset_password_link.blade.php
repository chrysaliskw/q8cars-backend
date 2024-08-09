@component('mail::message')
# Welcome 

Please click on the below link to reset your password.

@component('mail::button', [
    'url' => route('admin.login.show-form')
])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
