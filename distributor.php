<?php
include 'db.php';

// Add distributor
if (isset($_POST['add_distributor'])) {
    $distributor_id = $_POST['distributor_id'];
    $distributor_name = $_POST['distributor_name'];
    $distributor_address = $_POST['distributor_address'];
    $distributor_phone = $_POST['distributor_phone'];

    $sql = "INSERT INTO distributors (distributor_id, distributor_name, distributor_phone, distributor_address) VALUES ('$distributor_id', '$distributor_name', '$distributor_phone', '$distributor_address')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New distributor added successfully');</script>";
    } else {
        echo "<script>alert('Error: {$sql}<br>{$conn->error}');</script>";
    }
}

// Update distributor
if (isset($_POST['update_distributor'])) {
    $distributor_id = $_POST['distributor_id'];
    $distributor_name = $_POST['distributor_name'];
    $distributor_address = $_POST['distributor_address'];
    $distributor_phone = $_POST['distributor_phone'];

    $sql = "UPDATE distributors SET distributor_name='$distributor_name', distributor_address='$distributor_address', distributor_phone='$distributor_phone' WHERE distributor_id='$distributor_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Distributor updated successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Delete distributor
if (isset($_POST['delete_distributor'])) {
    $distributor_id = $_POST['distributor_id'];

    $sql = "DELETE FROM distributors WHERE distributor_id='$distributor_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Distributor deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Search distributor
if (isset($_POST['search_distributor'])) {
    $distributor_name = $_POST['distributor_name'];

    $sql = "SELECT distributor_id, distributor_name, distributor_address, distributor_phone FROM distributors WHERE distributor_name LIKE '%$distributor_name%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $distributors = [];
        while ($row = $result->fetch_assoc()) {
            $distributors[] = "ID: " . $row["distributor_id"] . ", Name: " . $row["distributor_name"] . ", Address: " . $row["distributor_address"] . ", Phone: " . $row["distributor_phone"];
        }
        $distributor_details = implode("\\n", $distributors);

        echo "<script>alert('Search results found:\\n$distributor_details');</script>";
    } else {
        echo "<script>alert('No results found');</script>";
    }
}

// Fetch distributors
$sql = "SELECT distributor_id, distributor_name, distributor_address, distributor_phone FROM distributors";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor Management</title>
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
    <a href="distributor_list.php">
        <img src="image/product_list_icon.png" alt="Distributor List" style="width: 50px; height: 50px;">
    </a>
    </button>
</div>
<div style="position: fixed; top: 10px; right: 10px;">
        <button id="searchDistributorBtn" style="border-radius: 50%;">
            <img src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
        </button>
        <div id="searchPopup" class="popup">
            <h2>Search Distributor</h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" id="distributor_name" name="distributor_name" required><br>
                <button type="submit" name="search_distributor" style="border-radius: 10px; font-size: 16px;">Search Distributor</button>
            </form>
        </div>
    </div>
    <script>
        const searchDistributorBtn = document.getElementById('searchDistributorBtn');
        const searchPopup = document.getElementById('searchPopup');

        searchDistributorBtn.addEventListener('click', () => {
            searchPopup.classList.toggle('active');
        });
    </script>   
<div class="container">
            <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
                <h2>Distributor List</h2>

    <form method="POST">
        <input type="hidden" name="distributor_id" id="distributor_id">
        <label>Distributor Name:</label>
        <input type="text" name="distributor_name" id="distributor_name_input" required>
        <label>Address:</label>
        <input type="text" name="distributor_address" id="distributor_address_input" required>
        <label>Phone:</label>
        <input type="text" name="distributor_phone" id="distributor_phone_input" required>
        <button type="submit" name="add_distributor" style="border-radius: 50%; "><img src="image/add_icon.png" alt="Add" style="width: 50px; height: 50px;"></button>
        <button type="submit" name="update_distributor" style="border-radius: 50%;"><img src="image/update_icon.png" alt="Update" style="width: 50px; height: 50px;"></button>
    </form>
    </div>
    <table border="1">
     <tr>
            <th>Distributor ID</th>
            <th>Distributor Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM distributors";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['distributor_id'] ?></td>
            <td><?= $row['distributor_name'] ?></td>
            <td><?= $row['distributor_address'] ?></td>
            <td><?= $row['distributor_phone'] ?></td>
            <td>
                <img src="image/edit_icon.png" alt="Edit" style="width: 40px; height: 40px;" onclick="editDistributor(<?= $row['distributor_id'] ?>, '<?= $row['distributor_name'] ?>', '<?= $row['distributor_address'] ?>', '<?= $row['distributor_phone'] ?>')">
            <a href="?delete=<?= $row['distributor_id'] ?>" onclick="return confirm('Are you sure?')"><img src="image/delete_icon.png" style="width: 40px; height: 40px;" alt="Delete"></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

    <script>
        function editDistributor(distributor_id, distributor_name, distributor_address, distributor_phone) {
            document.getElementById('distributor_id').value = distributor_id;
            document.getElementById('distributor_name_input').value = distributor_name;
            document.getElementById('distributor_address_input').value = distributor_address;
            document.getElementById('distributor_phone_input').value = distributor_phone;
        }
    </script>

</body>
<?php
$conn->close();
?>
</body>
</html>
