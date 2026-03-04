<?php
session_start();
require "config/db.php";
require "fpdf/fpdf.php";

if(!isset($_SESSION['user_id'])){
    die("Unauthorized");
}

//optional filters
$from=isset($_GET['from'])?$_GET['from']:'';
$to=isset($_GET['to'])?$_GET['to']:'';


/* ==========================
FILTER LOGIC (SAME AS sales.php)
============================== */


    /*===================================
    FETCH SALES

    ==================================== */

    $query = " SELECT products.product_name, 
    sale_items.quantity,
    sale_items.subtotal,
    sale_items.profit,
    users.full_name AS sold_by,
    sales.sold_at

    FROM sale_items
    JOIN products ON sale_items.product_id=products.id
    JOIN sales ON sale_items.sale_id=sales.id
    JOIN users ON sales.sold_by = users.id
";

if(!empty($from) && !empty($to)){
    $query.="WHERE DATE(sales.sold_at) BETWEEN '$from' AND '$to'
    
    ";
}

$query.="ORDER BY sales.sold_at DESC";

$result=$conn->query($query);

if(!$result){
    die("Query Error:".$conn->error);
}

/*==================================

TOTALS

====================================*/


/*$totalSalesQ=mysqli_query($conn, "SELECT SUM('total') AS total_sales, SUM(profit) AS total_profit 
FROM sale_items WHERE $where");

$totals=mysqli_fetch_assoc($totalSalesQ);

$where="1";
$title="All Sales Report";

if(!empty($_GET['date'])){
    $date=$_GET['date'];
    $where DATE(sales.sold_at) = '$date';
    $title= "Sales Report for $date";
} elseif(!empty($_GET['month'])){
    $month=$_GET['month'];
    $where=DATE_FORMAT(sales.sold_at, '%Y-%m')='$month';

    $title="Sales Report for $month";
}

*/
/* =================================
PDF GENERATION

==============================*/

$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(190,10,'Sales Report',0,1,"C");

$pdf->Ln(5);


//table headers
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40,8, "Product", 1);
$pdf->Cell(15, 8 , "Qty", 1);
$pdf->Cell(25, 8, "Total",1);
$pdf->Cell(25,8,"Profit",1);
$pdf->Cell(40,8, "Sold By",1);
$pdf->Cell(35,8, "Date",1);
$pdf->Ln();

//table data
$pdf->SetFont("Arial", "", 9);

$totalSales=0;
$totalProfit=0;



while(
    $row=$result->fetch_assoc()){
        
        $pdf->Cell(40,8,$row['product_name'], 1);
        $pdf->Cell(15,8,$row['quantity'], 1);
        $pdf->Cell(25,8,number_format($row['subtotal'], 2),1);
         $pdf->Cell(25,8,number_format($row['profit'], 2),1);
         $pdf->Cell(40,8,$row['sold_by'], 1);
         $pdf->cell(35,8, date('Y-m-d', strtotime($row['sold_at'])),1);
         $pdf->Ln();
            $totalSales+=$row['subtotal'];
            $totalProfit+=$row['profit'];

    }
    
    $pdf->ln(5);
    $pdf->SetFont("Arial", "B", 11);
    $pdf->Cell(90,8, 'Total Sales:',1);
    $pdf->cell(40,8, number_format($totalSales, 2), 1);
    $pdf->ln();

    $pdf->Cell(90,8, 'Total Profit:',1);
    $pdf->cell(40,8, number_format($totalProfit, 2), 1);

    $pdf->output();



?>