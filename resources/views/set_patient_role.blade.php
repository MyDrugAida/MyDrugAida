<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Patient Role</title>
</head>
<body>
    <h2>Set Patient Role Form</h2>
    <form action="/set_patient_role" method="POST">
        @csrf
        <label for="uid">User ID:</label>
        <input type="text" id="uid" name="uid" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>