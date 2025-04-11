<?php
include 'db.php';
session_start();

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
    <link rel="stylesheet" type="text/css" href="ad.css">
    </head>
    <body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row) {
                if ($row['user_type'] == 'admin') {
                    $_SESSION['username'] = $username;
                    header("Location: Admin/dash.php");
                } elseif ($row['user_type'] == 'user') {
                    $_SESSION['username'] = $username;
                    header("Location: user_dash/user_dash.php");
                } else {
                    echo "<p style='color: red;'>Invalid username or password</p>";
                }
            } else {
                echo "<p style='color: red;'>Invalid username or password</p>";
            }
            $stmt->close();
        }   
            ?>       
        <div class="form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="text" id="username" name="username" placeholder="USERNAME" required>
                <input type="password" id="password" name="password"  placeholder="PASSWORD"required>
                
                <button type="submit" name="login" style="align-text:center">Login</button>            
        </form>
        
    </div>
    </body>
    </html>
    <?php
    $conn->close();    
    ?>