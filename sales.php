<?php
    session_start();
    include "config/db.php";

    if(!isset($_SESSION['user_id'])){
        header("location:login.php");
        exit;
    }

        $where="1"; //default=no filter

        if(!empty($_GET['date'])){
            $date=$_GET['date'];
            $where="DATE(sales.sold_at)='$date'";
        }

        elseif(!empty($_GET['month'])){
            $month=$_GET['month'];
            $where="DATE_FORMAT(sales.sold_at, '%Y-%m')='$month'";
        }




    $query="SELECT sales.id, 
    products.product_name AS product_name,
     sales.quantity, 
     sales.total,
     sales.profit,
     sales.sold_at,
     users.full_name AS sold_by
     FROM sales
     JOIN products ON sales.product_id=products.id
     JOIN users ON sales.sold_by=users.id
     WHERE $where
     ORDER BY sales.sold_at DESC ";

        $result=mysqli_query($conn,$query);



?>


<form action="" method="GET" style="margin-botton:15px;">
    <label for="">
        Select Date:
        <input type="date" name="date" onchange="this.form.month.value=''">
    </label>

    <label for="">
        Select Month:
        <input type="month" name="month" onchange="this.form.date.value=''">
    </label>

        <button type="submit">filter</button>
        <a href="sales.php">Reset</a>
        
</form>

<a href="export_sales_pdf.php?<?=http_build_query($_GET)?> "target="_blank">Export PDF </a>

<table border="1" cellpadding="10">
    <tr>
        <th>
            Product
        </th>
        <th>Qyy</th>
        <th>Total</th>
        <th>Profit</th>
        <th>Sold By</th>
        <th>Date</th>
    </tr>

    <?php while($row=mysqli_fetch_assoc($result)):?>
        <tr>
            <td><?=$row['product_name']?></td>
            <td><?=$row['quantity']?></td>
            <td>₦<?=number_format($row['total'],2)?></td>
            <td>₦<?=number_format($row['profit'],2)?></td>
            <td><?=$row['sold_by']?></td>
            <td><?=$row['sold_at']?></td>
        </tr>
<?php endwhile;?>
</table>

<?php
$today=date('Y-m-d');
$daily=mysqli_query($conn, "SELECT SUM(total) AS total_sales
FROM sales WHERE DATE(sold_at)='$today'");

$dailyTotal=mysqli_fetch_assoc($daily)['total_sales']??0;



?>
<?php
//display ?>
<h3>Today's Sales: ₦<?=number_format($dailyTotal, 2)?></h3>

<?php 
    $month=date('Y-m');

    $monthly=mysqli_query($conn, 
    "SELECT SUM(total) AS total_sales FROM sales WHERE DATE_FORMAT(sold_at, '%Y-%m')='$month'");

    $monthlyTotal=mysqli_fetch_assoc($monthly)
    ['total_sales']??0;
?>


<?php //display
 ?>
    <h3> This Month: ₦<?=number_format($monthlyTotal, 2)?></h3>

<?php $totalQuery=mysqli_query($conn, "SELECT SUM(total) AS total_sales FROM sales WHERE $where");

$totalSales=mysqli_fetch_assoc($totalQuery)
['total_sales']??0;
$profitQuery = mysqli_query($conn, "SELECT SUM(profit) AS total_profit FROM sales WHERE $where");
$totalProfit=mysqli_fetch_assoc($profitQuery)['total_profit']??0;
?>
    <h3>This day sales is: ₦<?=number_format($totalSales,2);?></h3>    
<?php var_dump($totalSales); ?>
<h3>Total Profit: ₦<?=number_format($totalProfit, 2)?> </h3>
