@component('mail::message')
# Reset your password

Click here to reset your password !

@component('mail::button', ['url' => 'http://selling-books.local/api/reset/password?token='.$token.'&&email='.$email])
Reset your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent