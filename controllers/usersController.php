<?php
session_start();

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $address = mysqli_real_escape_string($con, $_POST['address']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $role = mysqli_real_escape_string($con, $_POST['role']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (name, address, email, role, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sssss", $name, $address, $email, $role, $password);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('User added successfully!'); window.location.href='manageUsers.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Error adding user: " . mysqli_error($con) . "'); window.location.href='manageUsers.php';</script>";
                    exit;
                }
                mysqli_stmt_close($stmt);
                break;
            case 'update':
                $user_id = (int)$_POST['user_id'];
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $address = mysqli_real_escape_string($con, $_POST['address']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $role = mysqli_real_escape_string($con, $_POST['role']);
                $sql = "UPDATE users SET name=?, address=?, email=?, role=? WHERE user_id=?";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "ssssi", $name, $address, $email, $role, $user_id);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('User updated successfully!'); window.location.href='manageUsers.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Error updating user: " . mysqli_error($con) . "'); window.location.href='manageUsers.php';</script>";
                    exit;
                }
                mysqli_stmt_close($stmt);
                break;
            case 'delete':
                $user_id = (int)$_POST['user_id'];
                $sql = "DELETE FROM users WHERE user_id=?";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('User deleted successfully!'); window.location.href='manageUsers.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Error deleting user: " . mysqli_error($con) . "'); window.location.href='manageUsers.php';</script>";
                    exit;
                }
                mysqli_stmt_close($stmt);
                break;
        }
    }
}

// Fetch all users
$users = [];
$sql = "SELECT * FROM users";
$result = mysqli_query($con, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Get user for editing
$edit_user = null;
if (isset($_GET['edit'])) {
    $user_id = (int)$_GET['edit'];
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $edit_result = mysqli_stmt_get_result($stmt);
    $edit_user = mysqli_fetch_assoc($edit_result);
    mysqli_stmt_close($stmt);
}
?>