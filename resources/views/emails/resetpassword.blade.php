@component('mail::message')
# Reset your password

Click here to reset your password !

@component('mail::button', ['url' => 'http://selling-books.local/password/reset?token='.$token])
Reset your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
