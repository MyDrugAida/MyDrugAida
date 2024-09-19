<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OAuth Frontend</title>
</head>
<body>
  <h1>Login with Google</h1>

  <button id="googleLogin">Login with Google</button>

  <script>
    document.getElementById('googleLogin').addEventListener('click', function() {
      // Replace this with your Laravel endpoint that starts the Google OAuth flow
      window.location.href = '/auth/google/patient';
    });
  </script>
</body>
</html>