<?php 

include "includes/auth.php";
    

    include "config/db.php";

    if(isset($_GET['remove'])){

    
        $index=$_GET['remove'];
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart']= array_values($_SESSION['cart']); //reindex
    }

    var_dump($_GET);
    exit;
    
    if(!isset($_SESSION['user_id'])){
        header("location:login.php");
        exit;
    }

    if(!isset($_SESSION['cart'])){
        $_SESSION['cart']=[];
    }
        if(isset($_POST['add_to_cart'])){
            
           
            $product_id=trim($_POST['product_id']);
            
            $qty=$_POST['quantity'];




            $productquery=mysqli_query($conn, 
            "SELECT * FROM products WHERE id='$product_id'");

      

        $p=mysqli_fetch_assoc($productquery);
            if(!$p){
                die("product not found");
            }
                

            

        if($qty>$p['quantity']){
            $error="NOT ENOUGH STOCK";
        } 

        else{
            
            $_SESSION['cart'][]=[
                'id'=>$p['id'],
                'name'=>$p['product_name'],
                'price'=>$p['price'],
                'qty'=>$qty,              
                'subtotal'=>$qty*$p['price']
            ];
                $success="Item added to cart";
        
            }

        }

            if(isset($_POST['complete_sale'])){
                if(empty($_SESSION['cart'])){
                    $error="cart is empty";
                }
                else{
                    mysqli_query($conn,
                    "INSERT INTO sales (sold_by, total_amount) VALUES ('{$_SESSION['user_id']}',0)");

                    $sale_id=mysqli_insert_id($conn);

                    $grand_total=0;
                    foreach($_SESSION['cart'] as $item){
                        mysqli_query($conn, 
                        "INSERT INTO sale_items(sale_Id, product_id, quantity, price, subtotal) VALUES 
                        ('$sale_id', '{$item['id']}', '{$item['qty']}', '{$item['price']}', '{$item['subtotal']}', )");

                        mysqli_query($conn, 
                        "UPDATE Products SET quantity=quantity-{$item['qty']} WHERE id='{$item['id']}'");

                        $grand_total+=$item['subtotal'];
                    }
                    $_SESSION['cart']=[];

                    header("location:Receipt.php?sale_id=$sale_id");
                    exit;
                }
            }

    $products=mysqli_query($conn, "SELECT * FROM products WHERE quantity >0");
   /*
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $product_id=$_POST['product_id']??'';
        
        $qty=$_POST['quantity']??0;

        if(empty($product_id)||$qty<=0){
        $error='invalid input';
        }
        else{
    
    
            $productquery=mysqli_query($conn, "SELECT * FROM products WHERE
             id='$product_id'");



            if(!$productquery || mysqli_num_rows($productquery)==0){
            $error="product not found";
            }

            else {
               $p = mysqli_fetch_assoc($productquery);

               
               if($qty>$p['quantity']){
               $error="not enough stock";

               }

               else{
                $total=$qty*$p['price'];

                    $insertsale=mysqli_query($conn,"INSERT INTO sales 
                    (product_id, quantity, total, sold_by) VALUES 
                    ('$product_id', '$qty', '$total','{$_SESSION['user_id']}')");
                    
                    $profit=($p['price']-$p['cost_price'])*$qty;

                    $sale_id=mysqli_insert_id($conn);
                   
               
                    
                    

                    if(!$insertsale){
                        die("Sales insert error:".
                        mysqli_error($conn));
                    }
                    mysqli_query($conn, "UPDATE products 
                    SET quantity=quantity-$qty WHERE id='$product_id'" );
                    //get the auto generated sales receipt
                        
                    $success= "sale recorded successfully";
               header("location:receipt.php?sale_id=$sale_id");     
                    exit;
                }

            
                }
                

               
               }
    }
             

*/
            
    



?>



<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/style.css">
        <title>
            Sell Product
        </title>
    </head>
    <body>
        <div class="container">
            <h2>Sell Prdocuct</h2>

            <?php 
             if(isset($success)) echo "<P style='color:green;'> $success </p>";
             if(isset($error))   echo "<p style='color:red;'> $error </P>";
            
            ?>

            <form action="" method="POST">
                <select name="product_id" id=""  >
                    <option value=""> Select Product</option>
                    <?php while($row=mysqli_fetch_assoc($products)){ ?>
                    <option value="<?php echo $row['id']; ?>">
                         <?php echo $row['product_name'] ?> </option>

                    <?php }  ?>

                </select>
               
                <br><br>

                <input type="number" name="quantity" id="" placeholder="quantity"> <br><br>
                



                <button type="submit" name="add_to_cart">Add Item</button>
                <button type="submit" name="complete_sale">Complete Sale</button>
            </form>

            <br>
            
            <?php 
                if(!empty($_SESSION['cart'])):?>
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>price</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($_SESSION['cart'] as $key => $item):?>   
                        <tr>
                            <td><?=$item['name']?></td>
                            <td><?=$item['qty']?></td>
                            <td><?=number_format($item['price'],2)?></td>
                            <td><?=number_format($item['subtotal'],2)?></td>
                            <td><a href="?remove=<?= $index ?>">Remove</a></td>
                        </tr>
                        <?php endforeach ; ?>
                </table>
<?php else: ?>

    <p>Cart is Empty</p>
    <?php endif; ?>
                
        <?php
        $grand_total=0;
        foreach($_SESSION['cart'] as $item){
            $grand_total+=$item['subtotal'];

        } 
        ?>

        

<h3>Total:₦<?=number_format($grand_total,2)?></h3> <br><br>


            <a href="dashboard.php">Dashboard</a>
        </div>
    </body>
</html>

