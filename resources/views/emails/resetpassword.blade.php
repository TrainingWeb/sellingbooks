@component('mail::message') 
# Reset your password Click here to reset your password ! 
@component('mail::button', ['url' =>'http://sellingbookstore.test/#/resetpassword?token='.$token.'&&email='.$email]) 
Reset your password 
@endcomponent 
Thanks,

<<<<<<< HEAD
<br> {{ config('app.name') }}
=======
Click here to reset your password !

@component('mail::button', ['url' => 'http://selling-books.local/#/resetpassword?token='.$token.'&&email='.$email])
Reset your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
>>>>>>> 9ef98bf70a3bcf072a0f0f81997fd5673d3e15fa
@endcomponent