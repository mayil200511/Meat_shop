<?php
include 'db.php';

$data = $_POST;
$bill_no = $data['bill_no'];
$date = $data['date'];
$cus_name = $data['cus_name'];
$cus_phone = $data['cus_phone'];
$grand_total = 0;

foreach ($data['total_amt'] as $total_amt) {
    $grand_total += $total_amt;
}

// Insert into bills
$sql = "INSERT INTO bills (bill_no, date, cus_name, cus_phone, grand_total) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $bill_no, $date, $cus_name, $cus_phone, $grand_total);
$stmt->execute();
$stmt->close();

// Insert bill items
$sql_item = "INSERT INTO bill_items (bill_no, s_no, product_name, product_quantity, quantity, product_price, total_amt) 
             VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_item = $conn->prepare($sql_item);
foreach ($data['s_no'] as $index => $s_no) {
    $stmt_item->bind_param(
        "sssssss",
        $bill_no,
        $s_no,
        $data['product_name'][$index],
        $data['product_quantity'][$index],
        $data['quantity'][$index],
        $data['product_price'][$index],
        $data['total_amt'][$index]
    );
    $stmt_item->execute();
}
$stmt_item->close();

// Update product quantities
foreach ($data['product_name'] as $index => $product_name) {
    $quantity_sold = $data['quantity'][$index];
    $sql = "UPDATE products SET product_quantity = product_quantity - ? WHERE product_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quantity_sold, $product_name);
    $stmt->execute();
    $stmt->close();
}

// Fetch new bill number
$sql = "SELECT MAX(bill_no) as last_bill_no FROM bills";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$new_bill_no = $row['last_bill_no'] + 1;

echo json_encode(['status' => 'success', 'new_bill_no' => $new_bill_no]);
?>
