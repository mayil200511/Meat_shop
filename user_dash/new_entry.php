<?php
// new_entry.php
include 'db.php';  

// Fetch last bill number from database
$sql = "SELECT MAX(bill_no) as last_bill_no FROM bills";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($last_bill_no);
$stmt->fetch();
$stmt->close();
$bill_no = $last_bill_no ? $last_bill_no + 1 : 250000;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bill_no = $_POST['bill_no'];
    $date = $_POST['date'];
    $cus_name = $_POST['cus_name'];
    $cus_phone = $_POST['cus_phone'];
    $grand_total = 0;
    if (is_array($_POST['total_amt'])) {
        foreach ($_POST['total_amt'] as $total_amt) {
            $grand_total += $total_amt;
        }
    }

    // Insert into bills table
    $sql = "INSERT INTO bills (bill_no, date, cus_name, cus_phone, grand_total) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $bill_no, $date, $cus_name, $cus_phone, $grand_total);
    $stmt->execute();
    //$stmt->close();
 
    
    // Insert into bill_items table
    if (is_array($_POST['s_no'])) {
        $stmt = $conn->prepare("INSERT INTO bill_items (bill_no, s_no, product_name, product_quantity, quantity, product_price, total_amt) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (is_array($_POST['s_no'])) {
            foreach ($_POST['s_no'] as $index => $s_no) {
                $s_no = $_POST['s_no'][$index];
                $product_name = $_POST['product_name'][$index];
                $product_quantity = $_POST['product_quantity'][$index];
                $quantity = $_POST['quantity'][$index];
                $product_price = $_POST['product_price'][$index];
                $total_amt = $_POST['total_amt'][$index];

                $stmt->bind_param("sssssss", $bill_no, $s_no, $product_name, $product_quantity, $quantity, $product_price, $total_amt);
                $stmt->execute();
                $s_no++;
            }
        }
        $stmt->close();
    }

    echo '<script>alert("Bill saved successfully!")</script>';
}
// Fetch products
$products = [];
$sql = "SELECT product_name, product_quantity, product_price FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($product_name, $product_quantity, $product_price);
while ($stmt->fetch()) {
    $products[] = ['name' => $product_name, 'quantity' => $product_quantity, 'price' => $product_price];
}
$stmt->close();

// Fetch customers
$customers = [];
$sql = "SELECT cus_name, cus_phone FROM customer";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($cus_name, $cus_phone);
while ($stmt->fetch()) {
    $customers[] = ['name' => $cus_name, 'phone' => $cus_phone];
}
$stmt->close();


// Update product quantities in inventory
function updateProductQuantities($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (is_array($_POST['product_name'])) {
            foreach ($_POST['product_name'] as $index => $product_name) {
                $quantity_sold = $_POST['quantity'][$index];

                // Check if enough quantity is available
                $sql = "SELECT product_quantity FROM products WHERE product_name = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $product_name);
                $stmt->execute();
                $stmt->bind_result($product_quantity);
                $stmt->fetch();
                $stmt->close();

                if ($product_quantity >= $quantity_sold) {
                    // Update product quantity
                    $sql = "UPDATE products SET product_quantity = product_quantity - ? WHERE product_name = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $quantity_sold, $product_name);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo '<script>alert("Not enough stock for ' . $product_name . '")</script>';
                    exit;
                }
            }
        }
    }
}

updateProductQuantities($conn);
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Billing Software</title>
   <link rel="stylesheet" type="text/css" href="new_entry.css">
</head>
<body>
<h2>New Bill Entry</h2>
<div style="position: fixed; top: 25px; left: 110px;">
    <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;" onclick="window.location.href='user_dash.php'">
</div>
<div style="position: fixed;top:25px;left: 200px;">
    <img src="image/product_list_icon.png" alt="Sales list"  style="height: 60px; width: 60px;"onclick="window.location.href='sales_entry.php'">
</div>
    <div class="container">
    <form action="new_entry.php" method="post">
        <table align="center" border="1">
        <tr>
            <th colspan="5" align="right" style="background-color:gray;">Bill No:</th>
            <td><input type="text" name="bill_no" value="<?php echo $bill_no; ?>" readonly></td>
        </tr>
        <tr>
            <th colspan="5" align="right" style="background-color:gray;">Date/Time:</th>
            <td><input type="text" name="date" value="date" readonly></td>
        </tr></tr>
        <tr>
            <th colspan="5" align="right" style="background-color:gray;">Customer Name:</th>
            <td>
            <select name="cus_name" id="cus_name" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                <option value="<?php echo $customer['name']; ?>" data-phone="<?php echo $customer['phone']; ?>"><?php echo $customer['name']; ?></option>
                <?php endforeach; ?>
            </select>
            </td>
        </tr>
        <tr>
            <th colspan="5" align="right" style="background-color:gray;">Customer Phone:</th>
            <td>
            <input type="text" name="cus_phone" id="cus_phone" readonly>
            </td>
        </tr>
        <script>
            document.getElementById('cus_name').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var phone = selectedOption.getAttribute('data-phone');
            document.getElementById('cus_phone').value = phone;
            });
        </script>
        <tr>
            <th>S No</th><th>Product Name</th><th>Stock</th><th>Quantity</th><th>Price</th><th>Total Amt</th>
        </tr>
        <tr>
            <td><input type="text" name="s_no[]" value="1" readonly></td>
            <td>
                <select name="product_name[]" required>
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['name']; ?>" data-quantity="<?php echo $product['quantity']; ?>" data-price="<?php echo $product['price']; ?>"><?php echo $product['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="number" name="product_quantity[]" readonly></td>
            <td><input type="number" name="quantity[]" required></td>
            <td><input type="number" name="product_price[]" readonly></td>
            <td><input type="number" name="total_amt[]" value="0" required readonly></td>
        </tr>
        <script>
            document.querySelector('[name="product_name[]"]').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var quantity = selectedOption.getAttribute('data-quantity');
                var price = selectedOption.getAttribute('data-price');
                this.parentElement.parentElement.querySelector('[name="product_quantity[]"]').value = quantity;
                this.parentElement.parentElement.querySelector('[name="product_price[]"]').value = price;
            });
        </script>
        </table>
        <div style="text-align: right; font-size: 50px; margin-top: 20px;">
            Total Amount: <span id="grand_total">0</span>
        </div>
    </form>
    </div>
    
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                var table = document.querySelector('table');
                var newRow = table.insertRow(table.rows.length);
                newRow.innerHTML = `
                    <td><input type="text" name="s_no" value="${table.rows.length - 5}" readonly></td>
                    <td>
                        <select name="product_name[]" required>
                            <option value="">Select Product</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['name']; ?>" data-quantity="<?php echo $product['quantity']; ?>" data-price="<?php echo $product['price']; ?>"><?php echo $product['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="product_quantity[]" readonly></td>
                    <td><input type="number" name="quantity[]" required></td>
                    <td><input type="number" name="product_price[]" readonly></td>
                    <td><input type="number" name="total_amt[]" value="0" required readonly></td>
                `;

                newRow.querySelector('[name="product_name[]"]').addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var quantity = selectedOption.getAttribute('data-quantity');
                    var price = selectedOption.getAttribute('data-price');
                    this.parentElement.parentElement.querySelector('[name="product_quantity[]"]').value = quantity;
                    this.parentElement.parentElement.querySelector('[name="product_price[]"]').value = price;
                });

                newRow.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    var quantity = this.value;
                    var price = this.parentElement.parentElement.querySelector('[name="product_price[]"]').value;
                    var totalAmt = quantity * price;
                    this.parentElement.parentElement.querySelector('[name="total_amt[]"]').value = totalAmt;

                    var grandTotal = 0;
                    document.querySelectorAll('[name="total_amt[]"]').forEach(function(input) {
                        grandTotal += Number(input.value);
                    });
                    document.getElementById('grand_total').textContent = grandTotal;
                });
            }
        });
    </script>
    <script>
        function getCurrentDateTime() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var hh = String(today.getHours()).padStart(2, '0');
            var min = String(today.getMinutes()).padStart(2, '0');

            return yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + min;
        }

        document.querySelector('[name="date"]').value = getCurrentDateTime();
    </script>
     <script>

        document.querySelector('[name="quantity[]"]').addEventListener('input', function() {
            var quantity = this.value;
            var price = this.parentElement.parentElement.querySelector('[name="product_price[]"]').value;
            var totalAmt = quantity * price;
            this.parentElement.parentElement.querySelector('[name="total_amt[]"]').value = totalAmt;

            var grandTotal = 0;
            document.querySelectorAll('[name="total_amt[]"]').forEach(function(input) {
                grandTotal += Number(input.value);
            });
            document.getElementById('grand_total').textContent = grandTotal;
        });

     </script>

        </table>    
    </div>

    <div>
    <div style="position: fixed; bottom: 10px; left: 10px; ">
        <button onclick="printBill()" style="font-size: 20px; padding: 5px 10px;">Print Bill (F5)</button>
        <button onclick="saveBill()" style="font-size: 20px; padding: 5px 10px;">Save Bill (F6)</button>
        
    </div>
   
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F5') {
                event.preventDefault();
                printBill();
            } else if (event.key === 'F6','F5') {
                event.preventDefault();
                saveBill();
            }
        });

        function printBill() {
            var billContent = `
            <h2 style="font-size: 30px; text-align: center;">Meat shop</h2>
            <p><strong>Bill No:</strong> ${document.querySelector('[name="bill_no"]').value}</p>
            <p><strong>Date/Time:</strong> ${document.querySelector('[name="date"]').value}</p>
            <p><strong>Customer Name:</strong> ${document.querySelector('[name="cus_name"]').value}</p>
            <p><strong>Customer Phone:</strong> ${document.querySelector('[name="cus_phone"]').value}</p>
            <table border="1" style="width: 100%; border-collapse: collapse;">
            <tr>
            <th>S No</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total Amt</th>
            </tr>`;

            document.querySelectorAll('table tr').forEach(function(row, index) {
            if (index > 4) { // Skip header rows
            var s_no = row.querySelector('[name="s_no[]"]') ? row.querySelector('[name="s_no[]"]').value : '';
            var product_name = row.querySelector('[name="product_name[]"]') ? row.querySelector('[name="product_name[]"]').value : '';
            var product_price = row.querySelector('[name="product_price[]"]') ? row.querySelector('[name="product_price[]"]').value : '';
            var quantity = row.querySelector('[name="quantity[]"]') ? row.querySelector('[name="quantity[]"]').value : '';
            var total_amt = row.querySelector('[name="total_amt[]"]') ? row.querySelector('[name="total_amt[]"]').value : '';

            if (product_name) {
            billContent += `
                <tr>
                <td>${s_no}</td>
                <td>${product_name}</td>
                <td>${product_price}</td>
                <td>${quantity}</td>
                <td>${total_amt}</td>
                </tr>`;
            }
            }
            });

            billContent += `
            </table>
            <div style="text-align: right; font-size: 20px; margin-top: 20px;">
            <strong>Grand Total:</strong> ${document.getElementById('grand_total').textContent}
            </div>
            <div style="text-align: center; font-size: 18px; margin-top: 10px;">
            <em>Thanks For Shopping!..</em>
            </div>`;

            var printWindow = window.open('', '', 'height=600,width=700');
            printWindow.document.write('<html><head><title>Print Bill</title></head><body>');
            printWindow.document.write(billContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        function saveBill() {
            var form = document.querySelector('form');
            form.submit();
        }
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            fetch('new_entry.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Bill saved successfully!');
                // Optionally, you can reset the form or update the page content here
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>

    </div>

</body>
</html>