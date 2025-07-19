<?php
session_start();
include '../components/config.php';

// Handle Create
if (isset($_POST['add_stock'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO stocks (product_id, quantity, last_updated) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('iis', $product_id, $quantity, $now);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update_stock'])) {
    $stock_id = $_POST['stock_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE stocks SET product_id=?, quantity=?, last_updated=? WHERE stock_id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('iisi', $product_id, $quantity, $now, $stock_id);
    $stmt->execute();
}

// Handle Delete
if (isset($_POST['delete_stock'])) {
    $stock_id = $_POST['stock_id'];
    $sql = "DELETE FROM stocks WHERE stock_id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $stock_id);
    $stmt->execute();
}

// Fetch products for dropdown
$products = [];
$result = $con->query("SELECT product_id, product_name FROM products");
while ($row = $result->fetch_assoc()) {
    $products[$row['product_id']] = $row['product_name'];
}

// Fetch stocks with product info
$stocks = $con->query("SELECT s.stock_id, s.product_id, s.quantity, s.last_updated, p.product_name FROM stocks s JOIN products p ON s.product_id = p.product_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Stocks | <?php include '../components/title.php'; ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-grid.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../customStyles/index.css">
</head>
<body>
    <?php include_once '../components/adminSidebar.php'; ?>
    <div class="main-content py-5 p-5 mt-4">
        <h3 class="text-center text-muted">Stock Management</h3>
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="product_id" class="form-control" required>
                    <option value="">Select Product</option>
                    <?php foreach ($products as $id => $name): ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
            </div>
            <div class="col-md-3">
                <button type="submit" name="add_stock" class="btn btn-success">Add Stock</button>
            </div>
        </form>

        <!-- Stocks Table -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Stock ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stocks->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <td><?php echo $row['stock_id']; ?></td>
                        <td>
                            <select name="product_id" class="form-control" required>
                                <?php foreach ($products as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php if ($row['product_id'] == $id) echo 'selected'; ?>><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" class="form-control" required></td>
                        <td><?php echo $row['last_updated']; ?></td>
                        <td>
                            <input type="hidden" name="stock_id" value="<?php echo $row['stock_id']; ?>">
                            <button type="submit" name="update_stock" class="btn btn-primary btn-sm">Update</button>
                            <button type="submit" name="delete_stock" class="btn btn-danger btn-sm" onclick="return confirm('Delete this stock?');">Delete</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>