
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
</div>  <script>
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
    <div  style="position: absolute; right: 10px; top: 10px;">
            <img src="image/logout_icon.png" alt="Logout" style="width: 60px; height: 80px;"onclick="window.location.href='logout.php'">
    </div>
    <div class="container">
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
