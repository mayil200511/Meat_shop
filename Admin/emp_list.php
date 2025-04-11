<?php
// Database connection
include 'db.php';
// Fetch employees from database
$sql = "SELECT emp_id, emp_name,emp_address,emp_phone, emp_position, emp_salary FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee List</title>
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
<h2>Employee List</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Position</th>
        <th>Salary</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["emp_id"]. "</td>
                    <td>" . $row["emp_name"]. "</td>
                    <td>" . $row["emp_address"]. "</td>
                    <td>" . $row["emp_phone"]. "</td>
                    <td>" . $row["emp_position"]. "</td>
                    <td>₹ " . $row["emp_salary"]. "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No employees found</td></tr>";
    }
    $conn->close();
    ?>
</table>
</div>
</div>
<div
         style="position: fixed; bottom: 10px; right: 10px;">
            <button onclick="window.location.href='employee.php'" style="border-radius: 50%; transition: transform 0.3s ease;">
                <img src="image/exit_icon.png" alt="Exit" style="width: 50px; height: 50px;">
            </button>
        </div>
</body>
</html>