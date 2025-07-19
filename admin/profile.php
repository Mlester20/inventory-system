<?php
session_start();
include '../components/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $con->prepare("SELECT user_id, name, address, email, password FROM users WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $current_password = md5($_POST['current_password']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($current_password !== $user['password']) {
                echo "<script type='text/javascript'>alert('Current password not correct!');
                document.location='../index.php'</script>";  
    } else {
        // Update user info
        $stmt = $con->prepare("UPDATE users SET name = ?, address = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param('sssi', $name, $address, $email, $user_id);
        $stmt->execute();
        $stmt->close();

        // Update password if provided
        if (!empty($new_password) || !empty($confirm_password)) {
            if ($new_password === $confirm_password) {
                $hashed = md5($new_password);
                $stmt = $con->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $stmt->bind_param('si', $hashed, $user_id);
                $stmt->execute();
                $stmt->close();
                echo "<script type='text/javascript'>alert('Profile updated successfully!');
                document.location='profile.php'</script>";  
            } else {
                echo "<script type='text/javascript'>alert('Confirmed password not matched!');
                document.location='profile.php'</script>";  
            }
        } else {
                echo "<script type='text/javascript'>alert('Profile updated successfully!');
                document.location='profile.php'</script>";  
        }

        // Refresh user data
        $stmt = $con->prepare("SELECT user_id, name, address, email, password FROM users WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | <?php include '../components/title.php'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../customStyles/index.css">
    <style>
        .main-content {
            padding: 40px;
        }
        .form-label {
            font-weight: 600;
        }
        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php include '../components/adminSidebar.php'; ?>

    <div class="main-content py-5 p-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" class="form-section">
                    <h3 class="mb-4 text-center text-muted">Update Profile</h3>

                    <?php if (!empty($update_msg)) : ?>
                        <div class="alert alert-info"><?= $update_msg ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label text-danger">Current Password <small>(for verification)</small></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">New Password <small>(optional)</small></label>
                            <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" name="update_profile" class="btn btn-success px-4">
                            <i class="fa fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
