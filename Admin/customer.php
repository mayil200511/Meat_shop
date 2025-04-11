<?php
include 'db.php';

// Add customer
if (isset($_POST['add_customer'])) {
    $cus_id = $_POST['cus_id'];
    $cus_name = $_POST['cus_name'];
    $cus_address = $_POST['cus_address'];
    $cus_phone = $_POST['cus_phone'];

    $sql = "INSERT INTO customer (cus_id, cus_name, cus_phone, cus_address) VALUES ('$cus_id', '$cus_name', '$cus_phone', '$cus_address')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New customer added successfully');</script>";
    } else {
        echo "<script>alert('Error: {$sql}<br>{$conn->error}');</script>";
    }
}

// Update customer
if (isset($_POST['update_customer'])) {
    $cus_id = $_POST['cus_id'];
    $cus_name = $_POST['cus_name'];
    $cus_address = $_POST['cus_address'];
    $cus_phone = $_POST['cus_phone'];

    $sql = "UPDATE customer SET cus_name='$cus_name', cus_address='$cus_address', cus_phone='$cus_phone' WHERE cus_id='$cus_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Customer updated successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Delete customer
if (isset($_POST['delete_customer'])) {
    $cus_id = $_POST['cus_id'];

    $sql = "DELETE FROM customer WHERE cus_id='$cus_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Customer deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Search customer
if (isset($_POST['search_customer'])) {
    $cus_name = $_POST['cus_name'];

    $sql = "SELECT cus_id, cus_name, cus_address, cus_phone FROM customer WHERE cus_name LIKE '%$cus_name%'";
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = "ID: " . $row["cus_id"] . ", Name: " . $row["cus_name"] . ", Address: " . $row["cus_address"] . ", Phone: " . $row["cus_phone"];
    }
    $customer_details = implode("\\n", $customers);
    
    echo "<script>alert('Search results found:\\n$customer_details');</script>";
} else {
    echo "<script>alert('No results found');</script>";
}
}

// Fetch customers
$sql = "SELECT cus_id, cus_name, cus_address, cus_phone FROM customer";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <style>
      body {
            background-image: url('image/image.png');
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
            width: 22%;
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
    <button>
    <a href="dash.php">
        
        <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; left: 100px;">
    <button>
    <a href="customer_list.php">
        <img src="image/product_list_icon.png" alt="Customer List" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; right: 10px;">
        <button id="searchCustomerBtn" style="border-radius: 50%;">
            <img src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
        </button>
        <div id="searchPopup" class="popup">
            <h2>Search Customer</h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" id="cus_name" name="cus_name" required><br>
                <button type="submit" name="search_customer" style="border-radius: 10px; font-size: 16px;">Search Customer</button>
            </form>
        </div>
    </div>
    <script>
        const searchCustomerBtn = document.getElementById('searchCustomerBtn');
        const searchPopup = document.getElementById('searchPopup');

        searchCustomerBtn.addEventListener('click', () => {
            searchPopup.classList.toggle('active');
        });
    </script>   
<div class="container">
            <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
                <h2>Customer List</h2>

    <form method="POST">
        <input type="hidden" name="cus_id" id="cus_id">
        <label>Customer Name:</label>
        <input type="text" name="cus_name" id="cus_name_input" required>
        <label>Address:</label>
        <input type="text" name="cus_address" id="cus_address_input" required>
        <label>Phone:</label>
        <input type="text" name="cus_phone" id="cus_phone_input" required>
        <button type="submit" name="add_customer" style="border-radius: 50%; "><img src="image/add_icon.png" alt="Add" style="width: 50px; height: 50px;"></button>
        <button type="submit" name="update_customer" style="border-radius: 50%;"><img src="image/update_icon.png" alt="Update" style="width: 50px; height: 50px;"></button>
    </form>
    </div>
    <table border="1">
     <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM customer";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['cus_id'] ?></td>
            <td><?= $row['cus_name'] ?></td>
            <td><?= $row['cus_address'] ?></td>
            <td><?= $row['cus_phone'] ?></td>
            <td>
                <img src="image/edit_icon.png" alt="Edit" style="width: 40px; height: 40px;" onclick="editCustomer(<?= $row['cus_id'] ?>, '<?= $row['cus_name'] ?>', '<?= $row['cus_address'] ?>', '<?= $row['cus_phone'] ?>')">
            <a href="?delete=<?= $row['cus_id'] ?>" onclick="return confirm('Are you sure?')"><img src="image/delete_icon.png" style="width: 40px; height: 40px;" alt="Delete"></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

    <script>
        function editCustomer(cus_id, cus_name, cus_address, cus_phone) {
            document.getElementById('cus_id').value = cus_id;
            document.getElementById('cus_name_input').value = cus_name;
            document.getElementById('cus_address_input').value = cus_address;
            document.getElementById('cus_phone_input').value = cus_phone;
        }
    </script>

</body>
<?php
$conn->close();
?>
</body>
</html>