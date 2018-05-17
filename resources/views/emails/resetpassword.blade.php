@component('mail::message')
# Reset your password

Click here to reset your password !

@component('mail::button', ['url' => 'http://sellingbooks.local/#/resetpassword?token='.$token.'&&email='.$email])
Reset your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent