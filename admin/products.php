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

    <div class="main-content p-5 py-5 mt-5">
        <h3 class="text-center text-muted">Manage Products</h3>
        <div class="text-end">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                Add Product
            </button>
        </div>

        <!-- Products Table -->
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Expiration Date</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($con, "SELECT * FROM products");
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['product_name']}</td>";
                    echo "<td>{$row['category']}</td>";
                    echo "<td>{$row['unit']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['expiration_date']}</td>";
                    echo "<td>{$row['created_at']}</td>";
                    echo "<td>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editProductModal{$row['product_id']}'>Edit</button>
                        <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteProductModal{$row['product_id']}'>Delete</button>
                    </td>";
                    echo "</tr>";

                    // Edit Modal
                    echo "<div class='modal fade' id='editProductModal{$row['product_id']}' tabindex='-1'>
                        <div class='modal-dialog'>
                            <form action='../controllers/productsController.php'  method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'>Edit Product</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <input type='hidden' name='edit_id' value='{$row['product_id']}'>
                                        <div class='mb-2'><label>Name</label><input type='text' name='edit_name' class='form-control' value='{$row['product_name']}' required></div>
                                        <div class='mb-2'><label>Category</label><input type='text' name='edit_category' class='form-control' value='{$row['category']}' required></div>
                                        <div class='mb-2'><label>Unit</label><input type='text' name='edit_unit' class='form-control' value='{$row['unit']}' required></div>
                                        <div class='mb-2'><label>Price</label><input type='number' step='0.01' name='edit_price' class='form-control' value='{$row['price']}' required></div>
                                        <div class='mb-2'><label>Expiration Date</label><input type='date' name='edit_expiration' class='form-control' value='{$row['expiration_date']}'></div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' name='updateProduct' class='btn btn-primary'>Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>";

                    // Delete Modal
                    echo "<div class='modal fade' id='deleteProductModal{$row['product_id']}' tabindex='-1'>
                        <div class='modal-dialog'>
                            <form action='../controllers/productsController.php' method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'>Delete Product</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <input type='hidden' name='delete_id' value='{$row['product_id']}'>
                                        <p>Are you sure you want to delete <strong>{$row['product_name']}</strong>?</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' name='deleteProduct' class='btn btn-danger'>Delete</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2"><label>Product Name</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-2"><label>Category</label><input type="text" name="category" class="form-control" required></div>
                        <div class="mb-2"><label>Unit</label><input type="text" name="unit" class="form-control" required></div>
                        <div class="mb-2"><label>Price</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                        <div class="mb-2"><label>Expiration Date</label><input type="date" name="expiration" class="form-control"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="addProduct" class="btn btn-success">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>