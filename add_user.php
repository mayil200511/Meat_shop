<?php
// Include database connection file
include 'db.php';
// Add user
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = $_POST['user_type'];

    $sql = "INSERT INTO user (username, password, user_type) VALUES ('$username', '$password', '$user_type')";
    if ($conn->query($sql) === TRUE) {
        echo "New user added successfully.";
    } else {
        echo "Error: {$sql}<br>{$conn->error}";
    }
}

// Update user
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $sql = "UPDATE user SET username='$username', password='$password', user_type='$user_type' WHERE user_id='$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully.";
    } else {
        echo "Error: {$sql}<br>{$conn->error}";
    }
}

// Delete user
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM user WHERE user_id='$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully.";
    } else {
        echo "Error: {$sql}<br>{$conn->error}";
    }
}

// Fetch users
$sql = "SELECT user_id, username, user_type FROM user";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            background-image: url('image/image2.png'); /* Ensure this path is correct */
            background-size: cover;
            background-position: center;
        }
        .container {
            margin: 50px auto;
            padding: 20px;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .container input, .container select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container button {
            padding: 10px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: flex-end; padding: 20px;">I. 
        <button onclick="window.location.href='dash.php'" style="padding: 10px 20px; font-size: 16px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Exit</button>
    </div>
<div class="container">
    <h2>User Management</h2>
    <form method="POST">
        <input type="hidden" name="user_id" id="user_id">
        <label>Username:</label>
        <input type="text" name="username" id="username" required>
        <label>Password:</label>
        <input type="password" name="password" id="password" required>
        <label>User Type:</label>
        <select name="user_type" id="user_type" required>
            <option value="" disabled selected>Select user type</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button type="submit" name="add_user" style="width: 30%; margin: 0 auto;">Add User</button>
        <button type="submit" name="update_user"style="width: 30%;margin: 0 auto;">Update User</button>
    </form>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>User Type</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['user_type'] ?></td>
            <td>
                <button onclick="editUser(<?= $row['user_id'] ?>, '<?= $row['username'] ?>', '<?= $row['user_type'] ?>')">Edit</button>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                    <button type="submit" name="delete_user" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<script>
    function editUser(user_id, username, user_type) {
        document.getElementById('user_id').value = user_id;
        document.getElementById('username').value = username;
        document.getElementById('user_type').value = user_type;
    }
</script>
</body>
</html>
<?php
$conn->close();
?>
