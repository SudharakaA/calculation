<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Usage Graph</title>

    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        canvas {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <h2>Enterprise Package Usage Graph</h2>
    <canvas id="packageChart" width="400" height="200"></canvas>

    <?php
    // Database connection settings
    $host = 'localhost';
    $dbname = 'your_database_name';
    $username = 'your_username';
    $password = 'your_password';

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to get all services from the table
        $stmt = $pdo->query("SELECT services FROM enterprise_package");
        $servicesData = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Process data to count each service occurrence
        $serviceCounts = [];
        foreach ($servicesData as $services) {
            $serviceList = explode(',', $services);  // Split services by comma
            foreach ($serviceList as $service) {
                $service = trim($service);  // Remove extra spaces
                if (isset($serviceCounts[$service])) {
                    $serviceCounts[$service]++;
                } else {
                    $serviceCounts[$service] = 1;
                }
            }
        }

        // Prepare data for Chart.js
        $labels = array_keys($serviceCounts);
        $counts = array_values($serviceCounts);

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
        $labels = [];
        $counts = [];
    }
    ?>

    <script>
        // Chart data from PHP
        const labels = <?php echo json_encode($labels); ?>;
        const data = <?php echo json_encode($counts); ?>;

        // Create a bar chart
        const ctx = document.getElementById('packageChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Users',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Enterprise Package Usage'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
