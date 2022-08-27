@component('mail::message')
# Please verify your account

Hi! {{ $userData->name }} <br />
Please verify your email. {{$userData->email}}

@component('mail::button', ['url' => ''])
Click Here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent