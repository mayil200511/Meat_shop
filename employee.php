<?php
// employee.php
include 'db.php';

function add_employee($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee_btn'])) {
        $emp_name = $_POST['emp_name'];
        $emp_address = $_POST['emp_address'];
        $emp_phone = $_POST['emp_phone'];
        $emp_position = $_POST['emp_position'];
        $emp_salary = $_POST['emp_salary'];

        $stmt = $conn->prepare("INSERT INTO employees (emp_name, emp_address, emp_phone, emp_position, emp_salary) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $emp_name, $emp_address, $emp_phone, $emp_position, $emp_salary);
        $stmt->execute();
        $stmt->close();
    }
}

add_employee($conn);
function update_employee($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_employee'])) {
        $emp_id = $_POST['emp_id'];
        $emp_name = $_POST['emp_name'];
        $emp_address = $_POST['emp_address'];
        $emp_phone = $_POST['emp_phone'];
        $emp_position = $_POST['emp_position'];
        $emp_salary = $_POST['emp_salary'];

        $stmt = $conn->prepare("UPDATE employees SET emp_name = ?, emp_address = ?, emp_phone = ?, emp_position = ?, emp_salary = ? WHERE emp_id = ?");
        $stmt->bind_param("sssssi", $emp_name, $emp_address, $emp_phone, $emp_position, $emp_salary, $emp_id);
        $stmt->execute();
        $stmt->close();
    }
}

update_employee($conn);
function delete_employee($conn) {
    if (isset($_GET['delete'])) {
        $emp_id = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = ?");
        $stmt->bind_param("i", $emp_id);
        $stmt->execute();
        $stmt->close();
    }
}

delete_employee($conn);
function search_employee($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_employee'])) {
        $emp_name = $_POST['emp_name'];

        $stmt = $conn->prepare("SELECT emp_id, emp_name, emp_address, emp_phone, emp_position, emp_salary FROM employees WHERE emp_name LIKE ?");
        $search_term = "%{$emp_name}%";
        $stmt->bind_param("s", $search_term);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $employees = [];
            while ($row = $result->fetch_assoc()) {
                $employees[] = "ID: " . $row["emp_id"] . ", Name: " . $row["emp_name"] . ", Address: " . $row["emp_address"] . ", Phone: " . $row["emp_phone"] . ", Position: " . $row["emp_position"] . ", Salary: " . $row["emp_salary"];
            }
            $employee_details = implode("\\n", $employees);
            echo "<script>alert('Employees found:\\n$employee_details');</script>";
        } else {
            echo "<script>alert('No employees found');</script>";
        }
        $stmt->close();
    }
}
search_employee($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
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
    <a href="dash.php">
        <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;">
    </a>
</div>
<div style="position: fixed; top: 10px; left: 90px;">
    <button>
    <a href="emp_list.php">
        <img src="image/product_list_icon.png" alt="Employee List" style="width: 50px; height: 50px;">
    </a>
    </button>
    </div>   
   
<div style="position: fixed; top: 10px; right: 10px;">
        <button id="searchEmpBtn" style="border-radius: 50%;">
            <img src="image/search_icon.png" alt="Search" style="width: 50px; height: 50px;">
        </button>
        <div id="searchPopup" class="popup">
            <h2>Search Product</h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" id="emp_name" name="emp_name" required><br>
                <button type="submit" name="search_employee" style="border-radius: 10px; font-size: 16px;">Search Employee</button>
                <button type="button" onclick="searchPopup.classList.remove('active')" style="border-radius: 10px; font-size: 16px;">Cancel</button>
            </form>
        </div>
    </div>
    <script>
        const searchEmpBtn = document.getElementById('searchEmpBtn');
        const searchPopup = document.getElementById('searchPopup');

        searchEmpBtn.addEventListener('click', () => {
            searchPopup.classList.toggle('active');
        });
    </script>   
<div class="container">
            <div class="header" style="font-size: 24px; background-color: lightgray; padding: 10px; text-align: center;">
                <h2>Employee List</h2>

    <form method="POST">
        <input type="hidden" name="emp_id" id="emp_id">
        <label>Employee Name:</label>
        <input type="text" name="emp_name" id="emp_name_input" required>
        <label>Address:</label>
        <input type="text" name="emp_address" id="emp_address_input"  required>
        <label>Phone:</label>
        <input type="number" name="emp_phone" id="emp_phone_input"  required>
        <label>Position:</label>
        <input type="text" name="emp_position" id="emp_position_input"  required>
        <label>Salary:</label>
        <input type="number" name="emp_salary" id="emp_salary_input"  required>
        <br>
        <button type="submit" name="add_employee_btn" style="border-radius: 50%; "><img src="image/add_icon.png" alt="Add" style="width: 50px; height: 50px;"></button>
        <button type="submit" name="update_employee" style="border-radius: 50%;"><img src="image/update_icon.png" alt="update" style="width: 50px; height: 50px;"></button>
    </form>
    </div>
    <table border="1">
     <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Salary</th>
                <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM employees";
        $result = $conn->query($sql);    
        while ($row = $result->fetch_assoc()): ?>
        <tr>   
            <td><?= $row['emp_id'] ?></td>
            <td><?= $row['emp_name'] ?></td>
            <td><?= $row['emp_address'] ?></td>
            <td><?= $row['emp_phone'] ?></td>
            <td><?= $row['emp_position'] ?></td>
            <td>&#8377; <?= number_format($row['emp_salary'], 2) ?></td>
            <td>
                <img src="image/edit_icon.png" alt="Edit" style="width: 40px; height: 40px;" onclick="editEmployee(<?= $row['emp_id'] ?>, '<?= $row['emp_name'] ?>', '<?= $row['emp_address'] ?>', '<?= $row['emp_phone'] ?>', '<?= $row['emp_position'] ?>', <?= $row['emp_salary'] ?>)">
            <a href="?delete=<?= $row['emp_id'] ?>" onclick="return confirm('Are you sure?')"><img src="image/delete_icon.png" style="width: 40px; height: 40px;" alt="Delete"></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

    <script>
        function editEmployee( emp_id, emp_name, emp_address, emp_phone, emp_position, emp_salary) {
            document.getElementById('emp_id').value = emp_id;
            document.getElementById('emp_name_input').value = emp_name;
            document.getElementById('emp_address_input').value = emp_address;
            document.getElementById('emp_phone_input').value = emp_phone;
            document.getElementById('emp_position_input').value = emp_position;
            document.getElementById('emp_salary_input').value = emp_salary;
                       
        }
    </script>                                                                                    
    
</body>
</html>
<?php
$conn->close();
?>