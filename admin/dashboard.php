<?php
session_start();
include '../components/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Get user-specific data for charts
$user_id = $_SESSION['user_id'];

// 1. PIE CHART DATA - Stock Distribution by Category
$pie_query = "SELECT category, COUNT(*) as count FROM products WHERE user_id = ? GROUP BY category";
$pie_stmt = $con->prepare($pie_query);
$pie_stmt->bind_param("i", $user_id);
$pie_stmt->execute();
$pie_result = $pie_stmt->get_result();

$pie_categories = [];
$pie_counts = [];
while ($row = $pie_result->fetch_assoc()) {
    $pie_categories[] = $row['category'];
    $pie_counts[] = (int)$row['count'];
}
$pie_stmt->close();

// 2. BAR CHART DATA - Monthly Inventory Additions
$bar_query = "SELECT MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count 
              FROM products 
              WHERE user_id = ? AND YEAR(created_at) = YEAR(CURDATE())
              GROUP BY YEAR(created_at), MONTH(created_at)
              ORDER BY month";
$bar_stmt = $con->prepare($bar_query);
$bar_stmt->bind_param("i", $user_id);
$bar_stmt->execute();
$bar_result = $bar_stmt->get_result();

// Initialize array for all 12 months
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$bar_data = array_fill(0, 12, 0);

while ($row = $bar_result->fetch_assoc()) {
    $bar_data[$row['month'] - 1] = (int)$row['count'];
}
$bar_stmt->close();

// 3. LINE CHART DATA - Price Analysis (Average price by month)
$line_query = "SELECT MONTH(created_at) as month, YEAR(created_at) as year, AVG(price) as avg_price 
               FROM products 
               WHERE user_id = ? AND YEAR(created_at) = YEAR(CURDATE())
               GROUP BY YEAR(created_at), MONTH(created_at)
               ORDER BY month";
$line_stmt = $con->prepare($line_query);
$line_stmt->bind_param("i", $user_id);
$line_stmt->execute();
$line_result = $line_stmt->get_result();

$line_data = array_fill(0, 12, 0);
while ($row = $line_result->fetch_assoc()) {
    $line_data[$row['month'] - 1] = round((float)$row['avg_price'], 2);
}
$line_stmt->close();

// 4. Get some stats for display
$stats_query = "SELECT 
                COUNT(*) as total_products,
                COUNT(DISTINCT category) as total_categories,
                AVG(price) as avg_price,
                SUM(price) as total_value
                FROM products WHERE user_id = ?";
$stats_stmt = $con->prepare($stats_query);
$stats_stmt->bind_param("i", $user_id);
$stats_stmt->execute();
$stats_result = $stats_stmt->get_result();
$stats = $stats_result->fetch_assoc();
$stats_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard | <?php include '../components/title.php'; ?> </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../customStyles/index.css">
    <style>
        .chart-container {
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .stat-card {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include '../components/adminSidebar.php'; ?>

    <div class="main-content p-4">
        <h3 class="mb-4 text-muted text-center">Inventory Analytics</h3>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_products']); ?></div>
                    <div>Total Products</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_categories']); ?></div>
                    <div>Categories</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">₱<?php echo number_format($stats['avg_price'], 2); ?></div>
                    <div>Average Price</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">₱<?php echo number_format($stats['total_value'], 2); ?></div>
                    <div>Total Value</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Pie Chart -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="text-center text-muted">Products by Category</h5>
                    <?php if (count($pie_categories) > 0): ?>
                        <canvas id="pieChart" height="200"></canvas>
                    <?php else: ?>
                        <div class="text-center text-muted p-5">No data available. Add some products first!</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="text-center text-muted">Monthly Product Additions (<?php echo date('Y'); ?>)</h5>
                    <canvas id="barChart" height="200"></canvas>
                </div>

                <!-- Line Chart -->
                <div class="chart-container mt-4">
                    <h5 class="text-center text-muted">Average Product Price by Month</h5>
                    <canvas id="lineChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart JS Scripts -->
    <script>
        // Convert PHP data to JavaScript
        const pieCategories = <?php echo json_encode($pie_categories); ?>;
        const pieCounts = <?php echo json_encode($pie_counts); ?>;
        const barData = <?php echo json_encode($bar_data); ?>;
        const lineData = <?php echo json_encode($line_data); ?>;

        // Generate random colors for pie chart
        function generateColors(count) {
            const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#6c757d'];
            return colors.slice(0, count);
        }

        // Pie Chart - Only show if there's data
        <?php if (count($pie_categories) > 0): ?>
        const pieChart = new Chart(document.getElementById("pieChart"), {
            type: "pie",
            data: {
                labels: pieCategories,
                datasets: [{
                    data: pieCounts,
                    backgroundColor: generateColors(pieCategories.length),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        <?php endif; ?>

        // Bar Chart Data
        const barChart = new Chart(document.getElementById("barChart"), {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Products Added",
                    data: barData,
                    backgroundColor: "#0d6efd"
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Line Chart Data
        const lineChart = new Chart(document.getElementById("lineChart"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Average Price (₱)",
                    data: lineData,
                    borderColor: "#0d6efd",
                    backgroundColor: "rgba(13,110,253,0.1)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#0d6efd",
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>