<?php
include 'db.php';

// Fetch the last purchase ID
$sql = "SELECT purchase_id FROM purchases ORDER BY purchase_id DESC LIMIT 1";
$result = $conn->query($sql);
$last_purchase_id = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_purchase_id = $row['purchase_id'];
}

// Add Purchase
if (isset($_POST['add'])) {
    $purchase_id = $_POST['purchase_id'];
    $distributor_name = $_POST['distributor_name'];
    $product_name = $_POST['product_name'];
    $product_quantity = $_POST['product_quantity'];
    $price = $_POST['price'];
    $purchase_date = $_POST['purchase_date'];
    $stmt = $conn->prepare("INSERT INTO purchases (purchase_id, distributor_name, product_name, product_quantity, price, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issids", $purchase_id, $distributor_name, $product_name, $product_quantity, $price, $purchase_date);
    $stmt->execute();
    $stmt->close();
}

// Update Purchase
if (isset($_POST['update'])) {
    $purchase_id = $_POST['purchase_id'];
    $distributor_name = $_POST['distributor_name'];
    $product_name = $_POST['product_name'];
    $product_quantity = $_POST['product_quantity'];
    $price = $_POST['price'];
    $purchase_date = $_POST['purchase_date'];
    $stmt = $conn->prepare("UPDATE purchases SET distributor_name=?, product_name=?, product_quantity=?, price=?, purchase_date=? WHERE purchase_id=?");
    $stmt->bind_param("ssidsi", $distributor_name, $product_name, $product_quantity, $price, $purchase_date, $purchase_id);
    $stmt->execute();
    $stmt->close();
}

// Delete Purchase
if (isset($_GET['delete'])) {
    $purchase_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM purchases WHERE purchase_id=?");
    $stmt->bind_param("i", $purchase_id);
    $stmt->execute();
    $stmt->close();
}

// Search Purchase
if (isset($_POST['search_purchase'])) {
    $purchase_id = $_POST['purchase_id'];
    $stmt = $conn->prepare("SELECT purchase_id, distributor_name, product_name, product_quantity, price, purchase_date FROM purchases WHERE purchase_id LIKE ?");
    $search_term = "%{$purchase_id}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $purchases = [];
        while ($row = $result->fetch_assoc()) {
            $purchases[] = "ID: " . $row["purchase_id"] . ", Distributor: " . $row["distributor_name"] . ", Product: " . $row["product_name"] . ", Quantity: " . $row["product_quantity"] . ", Price: " . $row["price"] . ", Date: " . $row["purchase_date"];
        }
        $purchase_details = implode("\\n", $purchases);
        echo "<script>alert('Purchases found:\\n$purchase_details');</script>";
    } else {
        echo "<script>alert('No purchases found');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Management</title>
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
        .container input, .container select {
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
        .popup input, .popup select {
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
    <a href="purchase_list.php">
        <img src="image/product_list_icon.png" alt="Purchase List" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; right: 10px;">
    <button id="searchPurchaseBtn" style="border-radius: 50%;">
        <img src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
    </button>
    <div id="searchPopup" class="popup">
        <h2>Search Purchase</h2>
        <form method="post">
            <label>Purchase ID:</label>
            <input type="text" id="purchase_id" name="purchase_id" required><br>
            <button type="submit" name="search_purchase" style="border-radius: 10px; font-size: 16px;">Search Purchase</button>
        </form>
    </div>
</div>
<script>
    const searchPurchaseBtn = document.getElementById('searchPurchaseBtn');
    const searchPopup = document.getElementById('searchPopup');

    searchPurchaseBtn.addEventListener('click', () => {
        searchPopup.classList.toggle('active');
    });
</script>   
<div class="container">
    <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
        <h2>Purchase List</h2>
        <form method="POST">
            <input type="hidden" name="purchase_id" id="purchase_id">
            <label>Distributor Name:</label>
            <select name="distributor_name" id="distributor_name_input" required>
                <option value="">Select distributor</option>
                <?php
                include 'db.php';
                $sql = "SELECT distributor_name FROM distributors";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['distributor_name']}'>{$row['distributor_name']}</option>";
                    }
                } else {
                    echo "<option value=''>No distributors found</option>";
                }
                $conn->close();
                ?>
            </select>
            <label>Product Name:</label>
            <select name="product_name" id="product_name_input" required>
                <option value="">Select product</option>
                <?php
                include 'db.php';
                $sql = "SELECT product_name FROM products";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['product_name']}'>{$row['product_name']}</option>";
                    }
                } else {
                    echo "<option value=''>No products found</option>";
                }
                $conn->close();
                ?>
            </select>
            <label>Quantity:</label>
            <input type="number" name="product_quantity" id="product_quantity_input" required>
            <label>Price:</label>
            <input type="number" name="price" id="price_input" step="0.01" required>
            <label>Purchase Date:</label>
            <input type="date" name="purchase_date" id="purchase_date_input" required>
            <br>
            <button type="submit" name="add" style="border-radius: 50%;"><img src="image/add_icon.png" alt="Add" style="width: 50px; height: 50px;"></button>
            <button type="submit" name="update" style="border-radius: 50%;"><img src="image/update_icon.png" alt="Update" style="width: 50px; height: 50px;"></button>
        </form>
    </div>
    <table border="1">
        <tr>
            <th>Purchase ID</th>
            <th>Distributor Name</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Purchase Date</th>
            <th>Action</th>
        </tr>
        <?php
        include 'db.php';
        $sql = "SELECT * FROM purchases";
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['purchase_id'] ?></td>
            <td><?= $row['distributor_name'] ?></td>
            <td><?= $row['product_name'] ?></td>
            <td><?= $row['product_quantity'] ?></td>
            <td>&#8377; <?= number_format($row['price'], 2) ?></td>
            <td><?= $row['purchase_date'] ?></td>
            <td>
                <img src="image/edit_icon.png" alt="Edit" style="width: 40px; height: 40px;" onclick="editPurchase(<?= $row['purchase_id'] ?>, '<?= $row['distributor_name'] ?>', '<?= $row['product_name'] ?>', <?= $row['product_quantity'] ?>, <?= $row['price'] ?>, '<?= $row['purchase_date'] ?>')">
                <a href="?delete=<?= $row['purchase_id'] ?>" onclick="return confirm('Are you sure?')"><img src="image/delete_icon.png" style="width: 40px; height: 40px;" alt="Delete"></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    function editPurchase(purchase_id, distributor_name, product_name, product_quantity, price, purchase_date) {
        document.getElementById('purchase_id').value = purchase_id;
        document.getElementById('distributor_name_input').value = distributor_name;
        document.getElementById('product_name_input').value = product_name;
        document.getElementById('product_quantity_input').value = product_quantity;
        document.getElementById('price_input').value = price;
        document.getElementById('purchase_date_input').value = purchase_date;
    }
</script>

</body>
</html>
<?php
$conn->close()
?>