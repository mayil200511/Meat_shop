<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
          background-color: black;
        }
        h1 {
            color: white;
            font-size: 40px;          
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
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        text-align: center;
        width: 50px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        background-color: red;
    }
        
        .card img {
            width: 48px;
            height: 48px;
            vertical-align: middle;
        }
    </style> 
</head>
<body>
<div style="color: white; padding: 10px; text-align: center;">
        <h1>Tools</h1>
    </div>
    <div class="container">
        <div class="card">
            <a href="calc.php" style="text-decoration: none; color: black;">
                <img src="image/calculator_icon.png" alt="Calculator">
            </a>
        </div>
    </div>
    <div
         style="position: fixed; bottom: 10px; right: 10px;">
            <button onclick="window.location.href='dash.php'" style="border-radius: 50%; transition: transform 0.3s ease;">
                <img src="image/exit_icon.png" alt="Exit" style="width: 50px; height: 50px;">
            </button>
        </div>
    </body>
</html>