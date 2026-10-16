<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

    <h1>Create Account</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif

    <form action="/register" method="POST">

        @csrf

        <label>Name:</label>
        <input type="text" name="name"
               value="{{ old('name') }}" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email"
               value="{{ old('email') }}" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <label>Confirm Password:</label>
        <input type="password"
               name="password_confirmation" required>

        <br><br>

        <button type="submit">Register</button>

    </form>

</body>
</html>