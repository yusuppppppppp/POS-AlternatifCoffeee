<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Form Pendaftaran</h2>
    <form action="{{ route('registercheck') }}" method="POST">
        @csrf

        <label for="name">Nama:</label><br>
        <input type="text" name="name" placeholder="Enter Your Name"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" placeholder="Enter Your Email"><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" placeholder="Enter Your Password"><br><br>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
