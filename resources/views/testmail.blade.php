<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>test mail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    {{-- <form action="{{route('resetpassword', $token)}}" method="POST">
        @csrf
        <label for="email">Nhap email</label>
        <input type="hidden" name="email" value="{{$email}}">
        <input disabled="" type="text" name="showemail" value="{{$email}}"><br>
        <label for="email">Nhap mat khau</label>
        <input type="password" name="password"><br>
        <label for="email">Nhap lai mat khau</label>
        <input type="passowrd" name="confirm_password"><br>
        <input type="submit" name="Reset" value="Reset">
    </form> --}}
    <script>
        window.location="sellingbookstore.test:8080/#/resetpassword?token=".{{$token}}."&&email=".{{$email}}""
    </script>
</body>

</html>