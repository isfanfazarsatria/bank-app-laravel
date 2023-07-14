<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/script.js') }}"></script>
</head>
<body>
    <div class="card">
        <h2>Register</h2>
        <form method="POST" action="/register">
            @csrf
            <label>Name:</label>
            <input type="text" name="name" required>
            <br><br>
            <label>Email:</label>
            <input type="email" name="email" required>
            <!-- <div id="emailError" class="alert alert-danger" style="display: none;"></div> -->
            <br><br>
            <label>Password:</label>
            <input type="password" name="password" required>
            <br><br>
            <button type="submit">Register</button>
        </form>
    </div>
    
    @if(isset($error))
        <script>
            showErrorAlert("{{ $error }}");
        </script>
    @endif
</body>
</html>
