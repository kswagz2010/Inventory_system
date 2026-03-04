<?php
session_start();

include "config/db.php";
if(!isset($_SESSION['user_id'])){
    die("Unauthorized");
}

if(!isset($_GET['sale_id'])){
    die("Sales not Specific");
}

$sale_id=$_GET['sale_id'];

$querySale="
SELECT 
sales.total_amount,
sales.id,
sales.sold_at,
users.full_name
FROM sales
JOIN users ON sales.sold_by=users.id
 WHERE sales.id='$sale_id' ";

$resultSale=mysqli_query($conn,$querySale);
$sale=mysqli_fetch_assoc($resultSale);


if(mysqli_num_rows($resultSale)==0){
    die("Sale not found");

}


if(!$sale){
    die("sale not found");
}

$queryItems="
    SELECT 
    products.product_name,
    sale_items.quantity,
    sale_items.subtotal,
    sale_items.price,
    sale_items.profit,
    sales.sold_at,
    users.full_name AS sold_by
    FROM sale_items

    JOIN products ON sale_items.product_id=products.id
    JOIN sales ON sale_items.sale_id=sales.id
    JOIN users ON sales.sold_by =users.id
    WHERE sale_items.sale_id='$sale_id' 
    
    ";
$resultItems=mysqli_query($conn,$queryItems);
$sales=mysqli_query($conn, $queryItems);
$sale=mysqli_fetch_assoc($sales);
if(mysqli_num_rows($resultItems)==0){
    die("no items found for this sale");
}


$grand_total=0;
$total_profit=0;




?>


<!DOCTYPE html> 
<html>
    <head>
        <style>
            body{
                font-family:monospace;
                width:280px; /* 80mm */
                margin:auto;
            }
            .center{
                text-align:center;
            }
            hr{border:dashes 1px #000;}

            @media Print{
                button{
                    display:none;
                }
            }
        </style>
        <title>
            Receipt
        </title>
    </head>
    <body>
        
        <div class="center">
            <h3>MY SHOP</h3>
            <p>Sales Receipt</p>
             <p>Sold By: <?=$sale['sold_by']?></p>
        <p>Date:<?=$sale['sold_at']?></p>
        </div>
          
            <table width='100%'>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    
                </tr>
                
<?php mysqli_data_seek($sales,0); 
while($item=mysqli_fetch_assoc($resultItems)){
   ?>

        <tr>
        <td><?=$item['product_name']?></td>
        <td><?=$item['quantity']?></td>
        <td> ₦<?=number_format($item['price'],2)?></td>
        <td><strong>₦<?=number_format($item['subtotal'], 2)?></strong></td>   
</tr>

         <?php  $grand_total=$grand_total+=$item['subtotal'];  }?> <br>
         
         <hr>
       </table>
       
        <hr> <h3>
       <?php echo "<strong> GRAND TOTAL: ₦".$grand_total."</strong>"; ?> </h3>
        <div class="center">
        <p>Thank YOu</p>
        <button onclick="window.print()">Print Receipt</button>

        <script>
        window.onload=function(){
            window.print();
        };

        </script>
        </div>
    </body>
</html>