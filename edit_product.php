<?php
    include "includes/auth.php";
    requireRole('admin');


    include "config/db.php";

    if(!isset($_SESSION['user_id'])){
        header("location:login.php");
        exit;
    }

    $id=$_GET["id"];

    //fetch product
    $query="SELECT * FROM products WHERE id=$id";
    $results=mysqli_query($conn, $query);
    $product=mysqli_fetch_assoc($results);

    //update product

    if($_SERVER['REQUEST_METHOD']=="POST"){
        $name=$_POST['name'];
        $price=$_POST['price'];
        $qty=$_POST['quantity'];

        $update="UPDATE products SET product_name='$name', price='$price', 
        quantity='$qty' WHERE id='$id' ";

        mysqli_query($conn,$update);

        
        header("location:products.php");

        exit;

    }

?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/style.css">
        <title>
            Edit Product
        </title>
    </head>
    <body>
        <div class="container">
            <h2>Edit Produt</h2>
                <form action="" method="POST">
                    <input type="text" name="name" value="<?php echo $product['product_name']; ?>" required> <br> <br>
                    <input type="number" name="price" value="<?php echo $product['price']; ?>" required> <br> <br>
                    <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required> <br> <br>
                    <button type="submit">Update Product</button>

                    
                </form>
<br>
<br>
<a href="products.php">Back</a>

        </div>
    </body>
</html>