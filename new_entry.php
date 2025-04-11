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
    <img src="image/home_icon.png" alt="Home" style="width: 50px; height: 50px;" onclick="window.location.href='dash.php'">
</div>
<div style="position: fixed;top:25px;left: 200px;">
    <img src="image/product_list_icon.png" alt="Sales list"  style="height: 60px; width: 60px;"onclick="window.location.href='sales_entry.php'">
</div>
    <div class="container">
    <form id="billForm" action="save_bill.php" method="post">
        <div style="text-align: center; font-size: 30px; margin-bottom: 20px;">
            <strong>Meat Shop</strong>
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
            <td><input type="text" name="s_no[]" value="1" readonly></td><td>
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
                    <td><input type="text" name="s_no[]" value="${table.rows.length - 5}" readonly></td>
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
                    document.getElementById('grand_total   ').textContent = grandTotal;
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

    <div style="position: fixed; bottom: 10px; left: 50%;">
    <button type="button" onclick="PrintBill(),saveBill()" style="font-size: 20px; padding: 5px 10px;">Print Bill(F6)</button>

        </div>     
        <script>
    // Add event listener for F5 key to trigger PrintBill and saveBill functions         
    document.addEventListener('keydown', function(event) {
        if (event.key === 'F6') { // Use F6 instead of F5
        event.preventDefault();
        PrintBill();
        saveBill();
        }
    });
        function PrintBill() {
            // Collecting bill number and date
            const billNo = document.querySelector('[name="bill_no"]').value;
            const date = document.querySelector('[name="date"]').value;
            const cusName = document.querySelector('[name="cus_name"]').value;
            const cusPhone = document.querySelector('[name="cus_phone"]').value;


            // Initialize bill content
            let billContent = `
                <h2 style="font-size: 30px; text-align: center;">Meat Shop</h2>
                <p><strong>Bill No:</strong> ${billNo}</p>
                <p><strong>Date/Time:</strong> ${date}</p>
                <p><strong>Customer Name:</strong> ${cusName}</p>
                <p><strong>Customer Phone:</strong> ${cusPhone}</p>
                <p style="text-align: center; font-size: 20px;">Bill Details</p>               
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th>S No</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total Amt</th>
                    </tr>`;

            // Loop through table rows to get product data
            let totalAmount = 0;
            document.querySelectorAll('table tr').forEach(function(row, index) {
                if (index > 4) { // Skip header rows
                    const sNo = row.querySelector('[name="s_no[]"]') ? row.querySelector('[name="s_no[]"]').value : '';
                    const productName = row.querySelector('[name="product_name[]"]') ? row.querySelector('[name="product_name[]"]').value : '';
                    const price = parseFloat(row.querySelector('[name="product_price[]"]') ? row.querySelector('[name="product_price[]"]').value : 0);
                    const quantity = parseInt(row.querySelector('[name="quantity[]"]') ? row.querySelector('[name="quantity[]"]').value : 0);
                    const totalAmt = price * quantity;

                    if (productName && price && quantity) {
                        billContent += `
                            <tr>
                                <td>${sNo}</td>
                                <td>${productName}</td>
                                <td>${price.toFixed(2)}</td>
                                <td>${quantity}</td>
                                <td>${totalAmt.toFixed(2)}</td>
                            </tr>`;
                        totalAmount += totalAmt;
                    }
                }
            });

            const grandTotal = document.getElementById('grand_total') ? document.getElementById('grand_total').textContent : totalAmount.toFixed(2);
            billContent += `
                </table>
                <div style="text-align: right; font-size: 20px; margin-top: 20px;">
                    <strong>Grand Total:</strong> ${grandTotal}
                </div>
                <div style="text-align: center; font-size: 18px; margin-top: 10px;">
                    <em>Thanks For Shopping!..</em>
                </div>`;

            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Bill</title></head><body>');
            printWindow.document.write(billContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
        function saveBill() {
    const form = document.getElementById('billForm');
    const formData = new FormData(form);

    fetch('save_bill.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Bill saved successfully!');
            // Update bill number
            document.querySelector('[name="bill_no"]').value = data.new_bill_no;
            // Clear item rows and reset form
            resetForm();
        } else {
            alert('Error saving bill.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save bill.');
    });
}
function resetForm() {
    // Clear product rows except first
    const table = document.querySelector('table');
    while (table.rows.length > 7) {
        table.deleteRow(-1);
    }

    // Reset first row
    const inputs = table.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.name !== 'bill_no' && input.name !== 'date' && input.name !== 'cus_phone') {
            input.value = '';
        }
    });

    // Reset customer selection
    document.getElementById('cus_name').value = '';
    document.getElementById('cus_phone').value = '';
    document.getElementById('grand_total').textContent = '0';

    // Update date
    document.querySelector('[name="date"]').value = getCurrentDateTime();
}
 
    </script>
    </div>

</body>
</html>