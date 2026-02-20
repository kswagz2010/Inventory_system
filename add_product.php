<?php
   
    include "includes/auth.php";
    requireRole('admin');
    include 'config/db.php';

    


    if(!isset($_SESSION['user_id'])){
        header('location:login.php');

        exit;

    }

    if($_SERVER['REQUEST_METHOD']=="POST") {
        $name=$_POST['name'];
        $price=$_POST['price'];
        $cost_price=$_POST['Cost_Price'];
        $qty=$_POST['qty'];
    
        $query = "INSERT INTO products(product_name,price, cost_price, quantity)
        VALUES('$name','$price', '$cost_price', '$qty')";

        mysqli_query($conn, $query);

        $success="product added successfully";
            }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Add product
        </title>
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body>

        <div class="container">
            <h2>Add product</h2>
            <?php if(isset($success)) echo "<p style='color:green; '> $success</p>"
            ; ?>

            <form action="" method="post">
                <input type="text" name="name" placeholder="product name" required> <Br> <br>

                <input type="number" name="price" placeholder="price" required><br><br>

                <input type="number" name="Cost_Price" placeholder="Cost Price" step="0.01" required><br><br>

                <input type="number" name="qty" placeholder="quantity" required><br><br>

                <button type ="submit"> save product</button>

            </form>
            <br>
        <a href="dashboard.php">back to dassboard</a>
        </div>
    </body>
</html>
