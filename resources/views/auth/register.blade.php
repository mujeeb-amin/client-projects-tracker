<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

    <h1>Create Account</h1>

    <form action="/register" method="POST">
        @csrf

        <label>Name:</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name')
            <p>{{ $message }}</p>
        @enderror

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>
        @error('password')
            <p>{{ $message }}</p>
        @enderror

        <br><br>

        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>