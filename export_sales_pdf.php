<?php
session_start();
require "config/db.php";
require "fpdf/fpdf.php";

if(!isset($_SESSION['user_id'])){
    die("Unauthorized");
}


/* ==========================
FILTER LOGIC (SAME AS sales.php)
============================== */

$where="1";
$title="All Sales Report";

if(!empty($_GET['date'])){
    $date=$_GET['date'];
    $where = "DATE(sales.sold_at) = '$date'";
    $title= "Sales Report for $date";
} elseif(!empty($_GET['month'])){
    $month=$_GET['month'];
    $where="DATE_FORMAT(sales.sold_at, '%Y-%m')='$month'";

    $title="Sales Report for $month";
}

    /*===================================
    FETCH SALES

    ==================================== */

    $query = " SELECT products.product_name, 
    sales.quantity,
    sales.total,
    sales.profit,
    sales.sold_at,
    users.full_name AS sold_by 
    FROM sales
    JOIN products ON sales.product_id=products.id
    JOIN users ON sales.sold_by = users.id
    WHERE $where
    ORDER BY sales.sold_at DESC ";
    



$result=mysqli_query($conn,$query);

/*==================================

TOTALS

====================================*/


$totalSalesQ=mysqli_query($conn, "SELECT SUM('total') AS total_sales, SUM(profit) AS total_profit 
FROM sales WHERE $where");

$totals=mysqli_fetch_assoc($totalSalesQ);

/* =================================
PDF GENERATION

==============================*/

$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(0,10,$title,0,1,"C");

$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40,8, "Product", 1);
$pdf->Cell(15, 8 , "Qty", 1);
$pdf->Cell(25, 8, "Total",1);
$pdf->Cell(25,8,"Profit",1);
$pdf->Cell(40,8, "Sold By",1);
$pdf->Cell(35,8, "Date",1);
$pdf->Ln();

$pdf->SetFont("Arial", "", 9);

while(
    $row=mysqli_fetch_assoc($result)){
        
        $pdf->Cell(40,8,$row['product_name'], 1);
        $pdf->Cell(15,8,$row['quantity'], 1);
        $pdf->Cell(25,8,number_format($row['total'], 2),1);
         $pdf->Cell(25,8,number_format($row['profit'], 2),1);
         $pdf->Cell(40,8,$row['sold_by'], 1);
         $pdf->Cell(40,8,$row['sold_at'], 1);
         $pdf->Ln();


    }
        //var_dump($totals);
    $pdf->ln(5);
    $pdf->SetFont("Arial", "B", 11);
    $pdf->Cell(0,8, "Total Sales: ₦" .number_format($totals['total_sales'], 2), 0, 1);
    $pdf->Cell(0,8, "Total Profit: N" .number_format($totals['total_profit'], 2), 0, 1);

    $pdf->Output("I", "sales_report.pdf");



?>