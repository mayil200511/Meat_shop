<?php

include 'db.php';
// order.php
// Function to get the current date and time
function getCurrentDateTime() {
    return date('y-m-d H:i:s');
}
$orderDate = getCurrentDateTime();

// Function to create an order
function createOrder($customerName,$cus_phone, $product, $quantity,$orderDate, $deliveryDate) {
  
    global $conn;
    $stmt = $conn->prepare("INSERT INTO `order` (customerName, cus_phone, product, quantity, orderDate, deliveryDate) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $customerName, $cus_phone, $product, $quantity, $orderDate, $deliveryDate);
    $stmt->execute();
    $stmt->close();
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $customerName = $_POST['customerName'];
    $cus_phone = $_POST['cus_phone'];
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $deliveryDate = $_POST['deliveryDate'];
    $orderDate = getCurrentDateTime();

    createOrder($customerName, $cus_phone, $product, $quantity, $orderDate, $deliveryDate);
    header("Location: order.php");
    exit();
}
if (isset($_GET['delete'])) {
    $order_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM `order` WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
    header("Location: order.php");
    exit();
}
// Function to update an order
function updateOrder($order_id, $customerName, $cus_phone, $product, $quantity, $deliveryDate) {
    global $conn;
    $stmt = $conn->prepare("UPDATE `order` SET customerName = ?, cus_phone = ?, product = ?, quantity = ?, deliveryDate = ? WHERE order_id = ?");
    $stmt->bind_param("ssissi", $customerName, $cus_phone, $product, $quantity, $deliveryDate, $order_id);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editOrder'])) {
    $order_id = $_POST['order_id'];
    $customerName = $_POST['customerName'];
    $cus_phone = $_POST['cus_phone'];
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $deliveryDate = $_POST['deliveryDate'];

    updateOrder($order_id, $customerName, $cus_phone, $product, $quantity, $deliveryDate);
    header("Location: order.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <style>
        body {
            background-image: url('image/image.png');
            font-family: Arial, sans-serif;
        }
        
        .container {
            margin-top: 100px;
            margin-left: 70px;
            margin-right: 70px;
            padding: 30px;
        }
        
        .container h2 {
            text-align: center;
            color: black;
        }
        
        .container input {
            width: 20%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 24px;
        }      
        .container select {
            width: 20%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 24px;
        }
        button {
            border-radius: 50%;
            transition: transform 0.3s ease;
        }
        
        table {
            font-family: 'Times New Roman', Times, serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 24px;
            border: 1px solid black;
        }
        
        table tr th {
            background-color: #333;
            color: white;
        }
        
        table tr td {
            background-color: white;
            color: black;
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
    <a href="user_dash.php">
        <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; left: 100px;">
    <button>
    <a href="order_list.php">
        <img src="image/product_list_icon.png" alt="Order List" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; right: 10px;">
    <button id="searchOrderBtn" style="border-radius: 50%;">
        <img src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
    </button>
    <div id="searchPopup" class="popup">
        <h2>Search Order</h2>
        <form method="post">
            <label>Order ID:</label>
            <input type="text" id="order_id" name="order_id" required><br>
            <button type="submit" name="search_order" style="border-radius: 10px; font-size: 16px;">Search Order</button>
        </form>
    </div>
</div>
<script>
    const searchOrderBtn = document.getElementById('searchOrderBtn');
    const searchPopup = document.getElementById('searchPopup');

    searchOrderBtn.addEventListener('click', () => {
        searchPopup.classList.toggle('active');
    });
</script>   
<div class="container">
    <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
        <h2>Order List</h2>
        <form method="POST">
            <input type="hidden" name="order_id" id="order_id_input">
            <label>Customer Name:</label>
            <input type="text" name="customerName" id="customerName_input" required>
            <label>Customer Phone:</label>
            <input type="text" name="cus_phone" id="cus_phone_input" required>
                <label>Product:</label>
            <input type="text" name="product" id="product_input" required>
            <label>Quantity:</label>
            <input type="text" name="quantity" id="quantity_input" required>
            <label>Delivery Date:</label>
            <input type="date" name="deliveryDate" id="deliveryDate_input" required><br>
            <button type="submit" name="createOrder" style="border-radius: 50px; font-size: 16px;">
                <img src="image/add_icon.png" alt="Create Order" style="width: 50px; height: 50px;">
            </button>
            <button type="submit" name="updateOrder" style="border-radius: 50px; font-size: 16px;">
                <img src="image/update_icon.png" alt="Update Order" style="width: 50px; height: 50px;"> 
            </button></form>
    </div>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Order Date</th>
            <th>Delivery Date</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM `order`";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['order_id'] ?></td>
            <td><?= $row['customerName'] ?></td>
            <td><?= $row['cus_phone'] ?></td>
            <td><?= $row['product'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['orderDate'] ?></td>
            <td><?= $row['deliveryDate'] ?></td>
            <td>
                <img src="image/edit_icon.png" alt="Edit" style="width: 40px; height: 40px;" onclick="editOrder(<?= $row['order_id'] ?>, '<?= $row['customerName'] ?>', '<?= $row['cus_phone'] ?>', '<?= $row['product'] ?>', '<?= $row['quantity'] ?>', '<?= $row['deliveryDate'] ?>')">
                <a href="?delete=<?= $row['order_id'] ?>" onclick="return confirm('Are you sure?')"><img src="image/delete_icon.png" style="width: 40px; height: 40px;" alt="Delete"></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    function editOrder(order_id, customer_name, cus_phone, product, quantity, deliveryDate) {
        document.getElementById('order_id_input').value = order_id;
        document.getElementById('customerName_input').value = customer_name;
        document.getElementById('cus_phone_input').value = cus_phone;
        document.getElementById('product_input').value = product;
        document.getElementById('quantity_input').value = quantity;
        document.getElementById('deliveryDate_input').value = deliveryDate;

    }
</script>

</body>
<?php
$conn->close();
?>
</html>
