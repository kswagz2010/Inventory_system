<?php
session_start();

include "config/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=$_POST['email'];
    $password=$_POST['password']; 


    $query= "SELECT * FROM users WHERE email='$email'";

    $result=mysqli_query($conn,$query); 
    
    if(mysqli_num_rows($result)==1){

            $user = mysqli_fetch_assoc($result);

            if(password_verify($password,$user['password'])){

            $_SESSION['user_id']= $user['id'];
            $_SESSION['role']=$user['role'];
            $_SESSION['name']=$user['full_name'];

            header("location: dashboard.php" );
            exit;
            } else 
            {
                $error="invalid login details";
            }

        }else{
                $error="invalid login";
            }

       // echo "login successfully, kcee you have done well";}
       // else {"invalid credentials";}
    
    
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            login Page
        </title>
        <link rel="stylesheet" href=
        "assets/style.css"
    </head>

    <body> 

        <div class="container">
        <h2>Login</h2>
        </div>
                <?php if (isset($error)) echo "<p style='color:red;'> $error; </p>" ; ?>
            <form action="" method="POST">
            
            <input type="email" name="email" placeholder="Email" required> <br> <br> <br>

            <input type="password" name="password" placeholder="password" required> <br><br><br>

            <button type="submit">Please Login</button>


            </form>
    
    </body>
</html>
