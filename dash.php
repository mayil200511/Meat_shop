
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image/image2.png');
        }
        h1 {
            font-family: 'Times New Roman', Times, serif;
            color: whitesmoke;
            font-size: 50px;          
        }
        .container {
            margin: 100px 0 0 200px;
            width: 70%;
            height: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;            
            gap: 20px;
        }
       
        
    .card {
        font-size: 30px;
        background-color: white;
        padding: 40px;
        border: 2px solid black;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        text-align: center;
        width: 250px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        background-color:rgb(56, 205, 250);
    }
        
        .card img {
            width: 48px;
            height: 48px;
            vertical-align: middle;
        }
    </style>  
        <script>
            let images = ['image/image2.png','image/image.png','image/image3.png' ];
            let currentIndex = 0;

            function changeBackground() {
                document.body.style.backgroundImage = `url('${images[currentIndex]}')`;
                currentIndex = (currentIndex + 1) % images.length;
            }

            setInterval(changeBackground, 5000); // Change background every 5 seconds
        </script>

      </head>
<div style="padding: 10px; text-align: center;">
    <h1>Meat Shop Dashboard</h1>
</div>
<div style="position: absolute; left: 20px; top: 10px;">
    <button style="background-color: white; border: none; width: 120px;height: 50px;border-radius :10px; background-color: red;" onclick="window.location.href='new_entry.php'">
    <img src="image/add_icon.png" alt="New Bill Entry" style="width: 20px; height: 20px;" onclick="window.location.href='new_entry.php'"> BILLING(F4)
       </button>
</div>
  <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F4') {
                window.location.href = 'new_entry.php';
            }
        });
    </script>
<div style="position: absolute; right: 20px; bottom: 10px; background-color: aliceblue;">
    <?php
    session_start();
    if (isset($_SESSION['username'])) {
        echo "<p>Welcome, " . htmlspecialchars($_SESSION['username']) . "...</p>";
    } else {
        echo "<p>  Welcome, Guest</p>";
    }
    ?>
       </div>
       <div style="position: absolute; right: 90px; top: 20px;">
                   <div style="position: relative; display: inline-block;">
                <button style="background-color: white; width: 50px; height: 50px; border-radius: 50px;">
                    <img src="image/profile_icon.png" alt="Profile" style="width: 50px; height: 50px;">
                </button>
                <div style="display: none; position: absolute; right: 0; background-color: white; border: 1px solid #ccc; border-radius: 0px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 1;" id="dropdownMenu">
                    <a href="profile.php" style="display: block; padding: 10px; text-decoration: none; color: black;">Profile</a>
                    <a href="add_user.php" style="display: block; padding: 10px; text-decoration: none; color: black;">Add User</a>
               
                </div>
                </div>
            </div>
            <script>
                const profileButton = document.querySelector('div[style*="position: relative"] > button');
                const dropdownMenu = document.getElementById('dropdownMenu');

                profileButton.addEventListener('click', () => {
                    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
                });

                window.addEventListener('click', (event) => {
                    if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.style.display = 'none';
                    }
                });
            </script>
               </div>
    <div  style="position: absolute; right: 10px; top: 10px;">
            <img src="image/logout_icon.png" alt="Logout" style="width: 60px; height: 80px;"onclick="window.location.href='logout.php'">
    </div>
    <div class="container">
        <div class="card">
            <a href="customer.php" style="text-decoration: none; color: black;">
                <img src="image/customer_icon.png" alt="Customer"> Customer
            </a>
        </div>
        <div class="card">
            <a href="employee.php" style="text-decoration: none; color: black;">
                <img src="image/employee_icon.png" alt="Employee"> Employee
            </a>
        </div>
        <div class="card">
            <a href="inven.php" style="text-decoration: none; color: black;">
                <img src="image/inventory_icon.png" alt="Inventory"> Inventory
            </a>
        </div>
        <div class="card">
            <a href="order.php" style="text-decoration: none; color: black;">
                <img src="image/order_icon.png" alt="Order"> Order
            </a>
        </div>
        <div class="card">
            <a href="sales_entry.php" style="text-decoration: none; color: black;">
                <img src="image/billing_icon.png" alt="Bill Entry"> Sales Entry
            </a>
        </div>

        <div class="card">
            <a href="distributor.php" style="text-decoration: none; color: black;">
                <img src="image/distributor_icon.png" alt="Distributor"> Distributor
            </a>
        </div>
        
        <div class="card">
            <a href="purchase.php" style="text-decoration: none; color: black;">
                <img src="image/purchase_icon.png" alt="Payment"> Purchase
            </a>
        </div>
        <div class="card">
            <a href="tools.php" style="text-decoration: none; color: black;">
                <img src="image/tools_icon.png" alt="Payment"> Tools
            </a>
        </div>
        <div class="card">
            <a href="report.php" style="text-decoration: none; color: black;">
                <img src="image/report_icon.png" alt="Report"> Report
            </a>
        </div>
    </div>
    </div>
</body>
</html>
