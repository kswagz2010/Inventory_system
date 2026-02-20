<?php
include "includes/auth.php";
include "config/db.php";
    
if(!isset($_SESSION['user_id']))
    {
        header("location:login.php");
        exit;
    }

        $today=date('Y-m-d');
        $month=date('Y-m');

        //TODAY

        $todayQ=mysqli_query($conn, "SELECT 
        SUM(total) AS sales,
        SUM(profit) AS profit
        FROM sales WHERE DATE(sold_at) = '$today'");

        $todayData=mysqli_fetch_assoc($todayQ);

        //THIS MONTH

        $monthQ=mysqli_query($conn, "SELECT 
        SUM(total) AS sales,
        SUM(Profit) AS profit
        FROM sales WHERE DATE_FORMAT(sold_at, '%Y-%m')='$month'");


$monthData=mysqli_fetch_assoc($monthQ);



   /*$role=$_SESSION['role']??'';

if($role ==='admin'){
    header("location:admin_dashboard.php") ;
    exit;
}

if($role ==='staff'){
    header("location:staff_dashboard.php") ;
    exit;
}

 die("invalid role");
*/
    ?>

    <!DOCTYPE html>

    <html>
        <HEAd>
            <TITle>
                Dashboard
            </TITle>
            <link rel="stylesheet" href="assets/style.css">
        </HEAd>
        <body>
            <div class='container'>
                <div style="display:flex; gap:20px; flex-wrap:wrap;">
                    <div style="border:1px solid #ccc; padding:15px
                    width:220px;"> <h4>Today's Sales</h4>
                    <strong>₦<?=number_format($todayData['sales']??0,2)?></strong>
                    </div>

                    <div style="border:1px solid #ccc; padding:15px; width:220px;"><h4>This Month Sales</h4>
                    <strong>₦<?=number_format($todayhData['profit']??0,2)?></strong></div>

                    <div style="border:1px solid #ccc; padding:15px; width:220px;">
                        <h4>This Month Profit</h4>
                        <strong>₦<?=number_format($monthData['sales']??0,2)?></strong>
                    </div>

                    <div style="border:1px solid #ccc; padding:15px; width:220px;"> 
                        <h4>This Month Profit</h4>
                        <strong>₦<?=number_format($monthData['profit']??0,2)?></strong>
                    </div>
</div>         
                

            <h2>Welcome, <?php echo $_SESSION['name']; ?></h2>

            <p>Role: <?php echo $_SESSION['role']; ?></p>
            <?php if($_SESSION['role']==='admin'){ ?>
           <P> <a href="products.php">Manage product</a></p>
           <p><a href="sales.php">View Reports</a></p>
           <p><a href="users.php">manage users</a></p>
            <?php }?>

            <?php if($_SESSION['role']==='admin'): ?> <a href="users.php">Manage Users</a> <?php endif;?>
            <p> <a href="sell.php">Sell product</a> </p>
            <a href="logout.php">logout</a>

            </div>
        </body>
    </html>