<?php
    session_start();
    include "config/db.php";

    if(!isset($_SESSION['user_id'])){
        header("location:login.php");
        exit;
    }

        $where="1=1"; //default=no filter

        if(isset($_GET['date']) && !empty($_GET['date'])){
            $date=$_GET['date'];
            $where.=" AND DATE(sales.sold_at)='$date'";
        }

        if(isset($_GET['month']) && !empty($_GET['month'])){
            $month=$_GET['date'];
            $where.=" AND DATE_FORMAT(sales.sold_at, '%Y-%m')='$month'";
        }



    $query="SELECT 
    products.product_name,
     sale_items.quantity, 
    sale_items.subtotal,
     sale_items.profit,
     sales.sold_at,
     users.full_name
     FROM sale_items
     JOIN sales ON sale_items.sale_id=sales.id
     JOIN products ON sale_items.product_id=products.id
     JOIN users ON sales.sold_by=users.id
";


      $where="";

      if(!empty($_GET['from']) && !empty($_GET['to'])){
        $from=$_GET['from'];
        $to=$_GET['to'];
      

      $where="WHERE DATE(sales.sold_at)
      BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
      }

      $query.=$where;
      $query.="ORDER BY sales.sold_at DESC";

        $result=mysqli_query($conn,$query);










        $grandTotal=0;
        $grandProfit=0;
        while($row=mysqli_fetch_assoc($result)){
            $grandTotal+=$row['subtotal'];
            $grandProfit+=$row['profit'];
            $rows[] = $row;
        }
?>

<h2>Sales Report</h2>

<form action="" method="GET" style="margin-botton:15px;">
    <label for="">
        From:

        <input type="date" name="from" value= "<?=isset($_GET['from']) ? $_GET['from']:''?>"> 

    </label>

    <label for="">
        To:
         <input type="date" name="to" value="<?=isset($_GET['to'])? $_GET['to']:''?>">
    </label>

        <button type="submit">filter</button>
        <a href="sales.php">Reset</a>
        
</form>

<a href="export_sales_pdf.php?<?=http_build_query($_GET)?> "target="_blank">Export PDF </a>

<table border="1" cellpadding="10">
    <tr>
        <thead>
        
        <th>Product</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Profit</th>
        <th>Sold By</th>
        <th>Date</th>
    </tr>
</thead>
<tbody>
    <?php if(!empty($rows)):?>
        <?php foreach($rows as $row): ?>
    
        <tr>
            
            <td><?=htmlspecialchars($row['product_name'])?></td>
            <td><?=$row['quantity']?></td>
            <td>₦<?=number_format($row['subtotal'],2)?></td>
            <td>₦<?=number_format($row['profit'],2)?></td>
            <td><?=htmlspecialchars($row['full_name'])?></td>
            <td><?=$row['sold_at']?></td>
        </tr>
<?php endforeach;?>
<tr>
    <td colspan="2"><strong>Grand Total</strong></td>
    <td><strong><?="₦".number_format($grandTotal, 2)?></strong></td>
        <td><strong><?="₦".number_format($grandProfit, 2)?></strong></td>
        <td colspan="2"></td>
        </tr>
        <?php else: ?>
            <tr>
                <td colspan="6">No Sales Found for this period.</td>
           </tr>
           <?php endif; ?>
        </tbody>
</table>

