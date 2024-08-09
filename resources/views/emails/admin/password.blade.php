@component('mail::message')
# Welcome {{ $admin->name }}

Please find your credentials for {{ config('app.name') }}  login.<br>
Email Id : {{ $admin->email }}<br>
Password : {{ $password }}<br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
