<?php
include '../components/config.php';
include '../controllers/productsController.php';

if(!isset($_SESSION['user_id'])){
    header('location: ../index.php ');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | <?php include '../components/title.php'; ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-grid.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../customStyles/index.css">
</head>
<body>

    <?php include '../components/adminSidebar.php'; ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


    
</body>
</html> 