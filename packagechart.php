<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Data Visualization</title>

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

    <h2>Number of Users by Package ID</h2>
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

        // Query to count users by package_id
        $stmt = $pdo->query("SELECT package_id, COUNT(*) AS user_count FROM user_profiles GROUP BY package_id");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Encode data as JSON for JavaScript
        $jsonData = json_encode($data);

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
        $jsonData = json_encode([]);
    }
    ?>

    <script>
        // Parse PHP data into JavaScript
        const chartData = <?php echo $jsonData; ?>;

        // Extract labels and data
        const packageIds = chartData.map(item => item.package_id);
        const userCounts = chartData.map(item => item.user_count);

        // Create a bar chart
        const ctx = document.getElementById('packageChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: packageIds,
                datasets: [{
                    label: 'Number of Users',
                    data: userCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    borderColor: ['#FF6384', '#36A2EB', '#FFCE56'],
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
                        text: 'User Distribution by Package ID'
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
