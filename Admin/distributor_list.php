<?php
include 'db.php';

// Retrieve distributor list
$sql = "SELECT distributor_id, distributor_name, distributor_phone, distributor_address FROM distributors";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Distributor List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue;
            background-image: url('image.png');
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .header {
            font-size: 24px;
            background-color: lightgray;
            padding: 10px;
            text-align: center;
        }
        table {
            width: 100%;
            height: 80%;
            background-color: white;
            font-size: 24px;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th { 
            color: white;
            background-color: black
        }
        </style>
</head>
<body>
<div style="position: fixed; top: 10px; right: 10px;">
        <button onclick="window.print()" style="border-radius: 50%; transition: transform 0.3s ease;">
        <img src="image/print_icon.png" alt="Print" style="width: 50px; height: 50px;" >
        </button>
</div>
<div class="container">
    <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
          <h2>List of Distributors</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["distributor_id"]. "</td><td>" . $row["distributor_name"]. "</td><td>" . $row["distributor_phone"]. "</td><td>" . $row["distributor_address"]. "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No distributors found</td></tr>";
        }
        
        ?>
    </table>
</div>
</div>
<div
         style="position: fixed; bottom: 10px; right: 10px;">
            <button onclick="window.location.href='distributor.php'" style="border-radius: 50%; transition: transform 0.3s ease;">
                <img src="image/exit_icon.png" alt="Exit" style="width: 50px; height: 50px;">
            </button>
        </div>
</body>
</html>