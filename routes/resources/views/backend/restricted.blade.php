<html>

<head>
</head>

<body>
    Your account is pending approval/verification.
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="submit" name="logout" value="Logout" class="btn btn-link">
    </form>
</body>

</html>
