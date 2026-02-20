<?php

        include "includes/auth.php";
        requireRole('admin');
        include "config/db.php";

        if($_SERVER['REQUEST_METHOD']=="POST"){
            $name=trim($_POST['full_name']);
            $email=trim($_POST['email']);
            $password=$_POST['password'];
            $role=$_POST['role'];
                $checkemail=mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");


            if(empty($name) || empty($email) || empty($password)){
                $error="ALL fields are required";
            }
            if(mysqli_num_rows($checkemail)>0){

            $error="Email already Exist";

            }
            else{
                $hashpassword=password_hash($password,PASSWORD_DEFAULT);

                $insert=mysqli_query($conn, "INSERT INTO users (full_name, email, password, role) 
                VALUES ('$name', '$email', '$hashpassword', '$role')");

                if($insert){
                    $success="user created sucessfuly";

                }

                else{
                    $error="email already exits";
                }
            }

        }
            $users=mysqli_query($conn, "SELECT id, full_name, email, role FROM users");


?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/style.css">
        <title>
            User Management
        </title>
    </head>
    <body>
        <h2>USer Management</h2>
        <div class="container"> 
            
            <?php 
                if(isset($success)) echo "<P style='color:Green;'> $success</p>";
                if(isset($error)) echo "<P style='color:red;'> $error</p>";
                 
            ?>

                <h3>Add New USer</h3>
                <form action="" method='POST'>
                    <input type="text" name="full_name" placeholder="FULL NAME"id="" required>
                    <br><br>
                    <input type="email" name="email" placeholder="email"id="" required>
                    <input type="password" name="password" placeholder="password"id="" required>
                    <br><br>

                    <select name="role" id=""> 
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select> <br><br>

                    <button type="submit">Create User</button>
                
                </form>
<hr>

                    <h3>Existing Users</h3>
                    <table border="1" cellpadding="5">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                        <?php
                            while($u=mysqli_fetch_assoc($users)){ ?>

                            <tr>
                                <td><?php echo $u['full_name']; ?></td>
                                <td><?php echo $u['email']; ?></td>
                                <td><?php echo $u['role']; ?></td>
                            </tr>
 <?php } ?> 
                        <br>
                        
                    </table><br><br>
                    <a href="admin_dashboard.php"><p style='color:red;'> Back to Dashboard</p></a>
                </div>
    </body>
</html>