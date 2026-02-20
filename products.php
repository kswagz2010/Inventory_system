<?php
 
    include "config/db.php";
    include "includes/auth.php";
    requireRole('admin');

    if(!isset($_SESSION['user_id'])){
        header("location:login.php");

        exit;
    }

$query="SELECT * FROM products ORDER BY id DESC";
$result=mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/style.css">
        <title>
            products
        </title>
    </head>
    <body>
        <div class="container">
            <h2>Products</h2>

            <table border="1" width="100%" cellpadding="10%">
                <tr>
                    <th>ID</th>
                    <th>Prduct name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
<?php while($row=mysqli_fetch_assoc($result)){?>
    <tr>
        <td> <?php echo $row['id']; ?></td>
        <td> <?php echo $row['product_name']; ?></td>
        <td> <?php echo $row['price']; ?></td>
        <td> <?php echo $row['quantity']; ?></td>
        <td>
            <a href="edit_product.php?id=<?php echo $row['id'];?>">Edit</a>
            <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('delete this product')"> Delete</a>

        </td>




    </tr>
    <?php
} ?>

            </table>
            <br>
            <a href="add_product.php">Add New Product </a> <p>
            <a href="dashboard.php">dashboard</a> </p>
        </div>
    </body>
</html>