<?php
    include "includes/auth.php";
    requireRole('admin');
?>

<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="assets/style.css">
        <title>
            Admin Dashboard
        </title>
    </head>

    <body>
        <div class="container">
   
    <h2>Welcome, <?php echo $_SESSION['name'];?></h2>

            
           <P> <a href="products.php">Manage product</a></p>
           <p><a href="sales.php">View Reports</a></p>
           <p><a href="users.php">manage users</a></p>
           <p><a href="Sell.php">Sell Produt</a></p>
           <p><a href="logout.php">Log out</a></p>
</div>
     </body>
     </html>
