<?php
session_start();
include '../components/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
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
    </style>
</head>
<body>

<?php include '../components/adminSidebar.php'; ?>

<div class="main-content p-4">
    <h2 class="mb-4 text-center text-muted">Inventory Analytics</h2>

    <div class="row g-4">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="text-center text-muted">Stock Distribution</h5>
                <canvas id="pieChart" height="200"></canvas>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="text-center text-muted">Monthly Inventory Additions</h5>
                <canvas id="barChart" height="200"></canvas>
            </div>

            <!-- Line Chart (Sales Analytics) placed directly below bar chart -->
            <div class="chart-container mt-4">
                <h5 class="text-center text-muted">Sales Analytics (Monthly Revenue)</h5>
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

        const pieChart = new Chart(document.getElementById("pieChart"), {
            type: "pie",
            data: {
                labels: ["Feed", "Medicine", "Tools", "Others"],
                datasets: [{
                    data: [40, 25, 20, 15],
                    backgroundColor: ["#0d6efd", "#198754", "#ffc107", "#dc3545"],
                }]
            },
            options: {
                responsive: true,
            }
        });

        // Bar Chart Data
        const barChart = new Chart(document.getElementById("barChart"), {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Items Added",
                    data: [25, 35, 30, 40, 28, 50],
                    backgroundColor: "#0d6efd"
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    // Line Chart Data
    const lineChart = new Chart(document.getElementById("lineChart"), {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [{
                label: "Sales in PHP",
                data: [5000, 6200, 7000, 6800, 7200, 8100],
                borderColor: "#0d6efd",
                backgroundColor: "rgba(13,110,253,0.1)",
                fill: true,
                tension: 0.4, // smooth curve
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
                            return '₱' + value;
                        }
                    }
                }
            }
        }
    });

    </script>

</body>
</html>
