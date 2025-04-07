<?php
// Include database connection file
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = $_POST['user_type'];

    // Validate input
    if (!empty($username) && !empty($password) && !empty($user_type)) {
        $sql = "INSERT INTO user (username, password, user_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $user_type);

        if ($stmt->execute()) {
            echo "User created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h1>Create User</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="user_type">User type:</label>
        <input type="text" id="user_type" name="user_type" required><br><br>

        <button type="submit">Create User</button>
    </form>
</body>
</html>