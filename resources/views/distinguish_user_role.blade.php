<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Distinguish User Role</title>
</head>
<body>
  <h2>Distinguish User Role Form</h2>
  <form action="/distinguish_user_role" method="POST">
    <!-- CSRF token for Laravel forms -->
    @csrf

    <!-- UID input -->
    <label for="uid">User ID:</label><br>
    <input type="text" id="uid" name="uid" required><br><br>

    <!-- Role input -->
    <label for="role">Role:</label><br>
    <input type="text" id="role" name="role" required><br><br>

    <!-- Submit button -->
    <input type="submit" value="Submit">
  </form>
</body>
</html>