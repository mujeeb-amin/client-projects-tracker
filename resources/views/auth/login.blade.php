<!DOCTYPE html>
<html>
<head>
    <title>Login - Client Projects Tracker</title>
</head>
<body>

    <h1>Login</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif

    <form action="/login" method="POST">

        @csrf

        <label>Email:</label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               required>

        <br><br>

        <label>Password:</label>
        <input type="password"
               name="password"
               required>

        <br><br>

        <button type="submit">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="/register">Register</a>
    </p>

</body>
</html>