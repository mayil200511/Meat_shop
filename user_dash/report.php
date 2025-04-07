<?php
include 'db.php';

// Function to get sales data
function getSalesData($conn, $interval) {
    $sql = "SELECT SUM(grand_total) as total_sales FROM bills WHERE date >= DATE_SUB(CURDATE(), INTERVAL $interval)";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_sales'] ?? 0;
}

// Get sales data
$todaySales = getSalesData($conn, '1 DAY');
$weeklySales = getSalesData($conn, '1 WEEK');
$monthlySales = getSalesData($conn, '1 MONTH');

if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $customSales = getSalesDataCustom($conn, $startDate, $endDate);
} else {
    $customSales = 0;
}

// Function to get sales data for a custom date range
function getSalesDataCustom($conn, $startDate, $endDate) {
    $sql = "SELECT SUM(grand_total) as total_sales FROM bills WHERE date BETWEEN '$startDate' AND '$endDate'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_sales'] ?? 0;
}

// Close connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue;
            background-image: url('image/image.png');
        }
        .container {
            text-align: center;
            font-size: 20px;
            width: 80%;
            margin: 0 auto;
            background-color: white;
            color: black;
        }
    
          table {
            width: 100%;
            height: 80%;
            background-color: white;
            font-size: 24px;
        }
        button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: darkgreen;
        }
         td {
            padding: 10px;
            text-align: left;
            color: black;
        }
        th { 
            color: white;
            background-color: #333;
        }
        input{
            padding: 10px;   
        }

        </style>
</head>
<div>
<div
         style="position: fixed; top: 40px; right: 40px;">
                <img onclick="window.location.href='user_dash.php'" src="image/exit_icon.png" alt="Exit" style="width: 70px; height: 70px; background-color: white; border-radius: 50%;">
                  </div>
    <div class="container">
    <h1>Sales Report</h1>
 
    <h2>Sales</h2>
    <table border="1">
        <tr>
            <th style="width: 50%;">Period</th>
            <th style="width: 50%;">Amount (₹)</th>
        </tr>
        <tr>
            <td style="width: 50%;">Today's Sales</td>
            <td style="width: 50%;"><?php echo number_format($todaySales, 2); ?></td>
        </tr>
        <tr>
            <td style="width: 50%;">Weekly Sales</td>
            <td style="width: 50%;"><?php echo number_format($weeklySales, 2); ?></td>
        </tr>
        <tr>
            <td style="width: 50%;">Monthly Sales</td>
            <td style="width: 50%;"><?php echo number_format($monthlySales, 2); ?></td>
        </tr>
        <tr>
            <td style="width: 50%;">Custom Date Range Sales <br><br>
                <form method="GET" action="">
                    <input type="date" name="startDate" required>
                    <input type="date" name="endDate" required>
                    <input type="submit" value="Submit" style=" background-color: lightblue;">
                </form>
            </td>
            <td style="width: 50%;"><?php echo number_format($customSales, 2); ?></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;"><button onclick="printSalesReport()" style="padding: 10px;">Print Sales Report</button></td>
        </tr>
    </table>
    </div>
    <script>
    function printSalesReport() {
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Sales Report</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; } h1, p { text-align: center; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1>Sales Report</h1>');
        printWindow.document.write('<p><strong>Today\'s Sales:</strong> ₹' + <?php echo json_encode(number_format($todaySales, 2)); ?> + '</p>');
        printWindow.document.write('<p><strong>Weekly Sales:</strong> ₹' + <?php echo json_encode(number_format($weeklySales, 2)); ?> + '</p>');
        printWindow.document.write('<p><strong>Monthly Sales:</strong> ₹' + <?php echo json_encode(number_format($monthlySales, 2)); ?> + '</p>');
        printWindow.document.write('<p><strong>Custom Date Range Sales:</strong> ₹' + <?php echo json_encode(number_format($customSales, 2)); ?> + '</p>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    </script>

    
</body>
</html>