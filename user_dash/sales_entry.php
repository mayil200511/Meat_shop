<?php
include 'db.php';
// Search bills function
function searchBill($conn, $search_bill_no) {
    $sql = "SELECT * FROM bills WHERE bill_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search_bill_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $bill = $result->fetch_assoc();
    $stmt->close();

    if ($bill) {
        return $bill;
    } else {
        echo '<script>alert("Bill not found!")</script>';
        return null;
        }
    }

if (isset($_POST['searchBill'])) {
    $search_bill_no = $_POST['bill_no'];
    $bill = searchBill($conn, $search_bill_no);
    if ($bill) {
        // Display the bill details
        echo "<script>alert('Bill found: Bill No: " . $bill['bill_no'] . ", Customer Name: " . $bill['cus_name'] . ", Total Amount: " . $bill['grand_total'] . ", Date: " . $bill['date'] . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills</title>
    <style>
      body {
            background-image: url('image/image2.png');
            font-family: Arial, sans-serif;
        }
        
        .container {
            
            margin-top: 100px;
            margin-left: 70px; /* Same as the width of the sidebar */
            margin-right: 70px;
            padding: 30px;
        }
        .container h2{
            text-align: center;
            color: black;
        }
        .container input{
            width: 21%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 24px;
        }
        
        button{
            border-radius: 50%;
            transition: transform 0.3s ease;
        }
        table{
            font-family: 'Times New Roman', Times, serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 24px;

            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
            font-family: 'Times New Roman', Times, serif;
        }
       table tr th{
            background-color: #333;
            color: white;
        }
        table tr td{
            background-color: white;
            color: black;
        }

        button{
            position: relative;
        }
        .popup {
           
           display: none;
           position: fixed;
           left: 80%;
           top: 40%;
           transform: translate(-50%, -50%);
           border: 1px solid #ccc;
           padding: 20px;
           background-color: #fff;
           box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
           height: 300px;
           width: 400px;
           border-radius: 10px;
           justify-content: center;
           align-items: center;

       }
       .popup.active {
           display: block;
       }
       .popup h2 {
           text-align: center;
           margin-bottom: 24px;
       }
       .popup form {
           display: flex;
           flex-direction: column;
           gap: 5px;
       }
       .popup label {
           font-size: 20px;
       }
       .popup input {
           padding: 10px;
           width: 90%;
           font-size: 16px;
           border-radius: 5px;
           border: 2px solid black;
       }
       .popup button {
           padding: 10px;
           width: 50%;
           font-size: 16px;
           border-radius: 5px;
           border: 2px solid black;
           background-color: #333;
           color: white;
           cursor: pointer;
           text-align: center;
           margin: 0 auto;
       }
      .popup button:hover {
           background-color: #555;
       }
      
      
    </style>
   
</head>
<body>
<div style="position: fixed; top: 10px; left: 10px;">
    <a href="user_dash.php">
        <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;">
    </a>
</div>
<div style="position: fixed; top: 10px; left: 100px;">
<a href="new_entry.php">
        <img src="image/add_icon.png" alt="Add" style="width: 50px; height: 50px;">
    </a>       
</div>

<div style="position: fixed; top: 10px; right: 10px;">
            <img id="searchBtn" src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
        <div id="searchPopup" class="popup">
            <h2>Search Bill</h2>
            <form method="post">
                <label>Bill NO:</label>
                <input type="text" id="bill_no" name="bill_no" required><br>
                <button type="submit" name="searchBill" style="border-radius: 10px; font-size: 16px;">Search Bill</button>
                <button type="button" onclick="document.getElementById('searchPopup').classList.remove('active')" style="border-radius: 10px; font-size: 16px;">Cancel</button>
            </form>
         </div>
    </div>
    <script>
        document.getElementById('searchBtn').addEventListener('click', function() {
            document.getElementById('searchPopup').classList.add('active');

        });


    </script>

   
        <div class="container">
            <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
                <h2>Bills</h2>
                <table border="1">
                    <tr>
                        <th>Bill No</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                    </tr>
                    <?php
                    $query = "SELECT * FROM bills";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['bill_no'] . "</td>";
                            echo "<td>" . $row['cus_name'] . "</td>";
                            echo "<td>" . $row['grand_total'] . "</td>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No bills found</td></tr>";
                    }
                    ?>
                </table>
                </div>
        </div>
</body>
</html>