<?php
include 'db.php';

$sql = "SELECT * FROM `order`";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue;
            background-image: url('image/image.png');
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
        <h2>Order List</h2>
    </div>
    <?php
   
    if ($result->num_rows > 0) {
        echo "<table border='1' width='100%' height='80%' style='background-color: white; font-size: 24px;'>      
            <tr>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Product Quantity</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
            </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["customerName"]. "</td>
                <td>" . $row["product"]. "</td>
                <td>" . $row["quantity"]. "</td>
                <td>" . $row["orderDate"]. "</td>
                <td>" . $row["deliveryDate"]. "</td>
              </tr>";
        }
        echo "</table>";
    } else {
        echo "Empty table";
    }
?>
</div>

<div
         style="position: fixed; bottom: 10px; right: 10px;">
            <button onclick="window.location.href='order.php'" style="border-radius: 50%; transition: transform 0.3s ease;">
                <img src="image/exit_icon.png" alt="Exit" style="width: 50px; height: 50px;">
            </button>
        </div>
</body>
</html>

<?php
$conn->close();
?>