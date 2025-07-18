<?php
session_start();
include '../components/config.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = trim($_POST['email']);
        $password = md5(trim($_POST['password']));

        //perform database query
        $query = "SELECT user_id, name, address, role FROM users WHERE email = ? AND password = ? ";
        $stmt = mysqli_prepare($con, $query);

        //check if the condition is true
        if($stmt){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                //store sessions into variables
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['address'] = $row['address'];
                $_SESSION['role'] = $row['role'];

                //check role if admin or user
                if($_SESSION['role'] === 'admin'){
                    header('location: ../admin/dashboard.php');
                }else{
                    header('location: ../home.php ');
                }
                exit();
            }else{
                echo "<script type='text/javascript'>alert('Invalid Username or Password!');
                document.location='../index.php'</script>";  
            }
        }else{
            echo "<script>alert('Database error. Please try again later.');</script>";
        }

    }

?>