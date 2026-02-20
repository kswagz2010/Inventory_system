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

$query="
SELECT 
sales.id,
sales.quantity,
sales.total,
sales.profit,
sales.sold_at,
products.product_name,
products.price,
users.full_name AS sold_by FROM sales

JOIN products ON sales.product_id = products.id
JOIN users ON sales.sold_by=users.id
WHERE sales.id='$sale_id'";

$result=mysqli_query($conn,$query);
$sale=mysqli_fetch_assoc($result);

//var_dump($sale);
//var_dump($_GET['sale_id']);
if(!$sale){
    die('Sales not Found');
}
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
        </div>
        
        <hr>
        <p>Product:<?=$sale['product_name']?></p>
        <p>qty:<?=$sale['quantity']?></p>
        <p>Price ₦<?=number_format($sale['price'],2)?></p>

        <hr>

        <p><strong>Total:₦<?=number_format($sale['total'], 2)?></strong></p>

        <hr>
        <p>Sold By: <?=$sale['sold_by']?></p>
        <p>Date:<?=$sale['sold_at']?></p>

        <hr>

        <div class="center">
        <p>Thank YOu</p>
        </div>

        <button onclick="window.print()">Print Receipt</button>

        <script>
        window.onload=function(){
            window.print();
        };
        </script>
    </body>
</html>