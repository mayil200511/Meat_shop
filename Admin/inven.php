<?php
include 'C:\xampp\htdocs\meat_shop2\db.php';

// Add Product
if (isset($_POST['add'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_quantity, product_price) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $product_name, $product_quantity, $product_price);
    $stmt->execute();
    $stmt->close();
}

// Update Product
if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_quantity = $_POST['product_quantity'];
    $product_price = $_POST['product_price'];
    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_quantity=?, product_price=? WHERE product_id=?");
    $stmt->bind_param("siii", $product_name, $product_quantity, $product_price, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Delete Product
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
}

// Search Product
if (isset($_POST['search_product'])) {
    $product_name = $_POST['product_name'];
    $stmt = $conn->prepare("SELECT product_id, product_name, product_quantity, product_price FROM products WHERE product_name LIKE ?");
    $search_term = "%{$product_name}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = "ID: " . $row["product_id"] . ", Name: " . $row["product_name"] . ", Price: " . $row["product_price"] . ", Quantity: " . $row["product_quantity"];
        }
        $product_details = implode("\\n", $products);
        echo "<script>alert('Products found:\\n$product_details');</script>";
    } else {
        echo "<script>alert('No products found');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
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
    <button>
    <a href="dash.php">
        
        <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; left: 100px;">
    <button>
    <a href="product_list.php">
        <img src="image/product_list_icon.png" alt="Product List" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; right: 10px;">
        <button id="searchProductBtn" style="border-radius: 50%;">
            <img src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
        </button>
        <div id="searchPopup" class="popup">
            <h2>Search Product</h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" id="product_name" name="product_name" required><br>
                <button type="submit" name="search_product" style="border-radius: 10px; font-size: 16px;">Search Product</button>
            </form>
        </div>
    </div>
    <script>
        const searchProductBtn = document.getElementById('searchProductBtn');
        const searchPopup = document.getElementById('searchPopup');

        searchProductBtn.addEventListener('click', () => {
            searchPopup.classList.toggle('active');
        });
    </script>   
<div class="container">
            <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
                <h2>Product List</h2>

    <form method="POST">
        <input type="hidden" name="product_id" id="product_id">
        <label>Product Name:</label>
        <input type="text" name="product_name" id="product_name_input" required>
        <label>Quantity(KG):</label>
        <input type="number" name="product_quantity" id="product_quantity_input"  required>
        <label>Price(KG):</label>
        <input type="number" name="product_price" id="product_price_input"  step="0.01" required>
        <button type="submit" name="add" style="border-radius: 50%; "><img src="image/add_icon.png" alt="Add" style="width: 50px; height: 50px;"></button>
        <button type="submit" name="update"style="border-radius: 50%;"><img src="image/update_icon.png" alt="update" style="width: 50px; height: 50px;"></button>
    </form>
    </div>
    <table border="1">
     <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity(KG)</th>
            <th>Price(KG)</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= $row['product_name'] ?></td>
            <td><?= $row['product_quantity'] ?></td>
            <td>&#8377; <?= number_format($row['product_price'], 2) ?></td>
            <td>
                <img src="image/edit_icon.png" alt="Edit" style="width: 40px; height: 40px;" onclick="editProduct(<?= $row['product_id'] ?>, '<?= $row['product_name'] ?>', <?= $row['product_quantity'] ?>, <?= $row['product_price'] ?>)">
            <a href="?delete=<?= $row['product_id'] ?>" onclick="return confirm('Are you sure?')"><img src="image/delete_icon.png" style="width: 40px; height: 40px;" alt="Delete"></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

    <script>
        function editProduct(product_id, product_name, product_quantity, product_price) {
            document.getElementById('product_id').value = product_id;
            document.getElementById('product_name_input').value = product_name;
            document.getElementById('product_quantity_input').value = product_quantity;
            document.getElementById('product_price_input').value = product_price;
        }
    </script>

</body>
</html>
<?php
$conn->close();
?>