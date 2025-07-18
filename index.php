<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php include_once 'components/title.php'; ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>


    <form action="controllers/auth.php" method="post">
        <input type="email" name="email">
        <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>