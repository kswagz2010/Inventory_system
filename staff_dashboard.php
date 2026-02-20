<?php
    include "includes/auth.php";
   ?>


    <!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="assets/style.css">
        <title>
            Staff Dashboard
        </title>
    </head>

    <body>
        <div class="container">
   
    <h2>Welcome, <?php echo $_SESSION['name'];?></h2>

            
              
           <p><a href="Sell.php">Sell Produt</a></p>
           <p><a href="logout.php">Log out</a></p>
            
</div>
     </body>
     </html>
    

            
       
    
