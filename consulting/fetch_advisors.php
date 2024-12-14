<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the page number from the URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;  // Number of advisors per page
$offset = ($page - 1) * $limit;

// Query to fetch advisor data
$sql = "SELECT name, position, experience, qualifications, image_url FROM tax_advisors LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$advisors = [];
$totalAdvisors = 0;

// Fetch advisor data
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $advisors[] = $row;
  }
}

// Get total number of advisors for pagination
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM tax_advisors");
if ($totalResult->num_rows > 0) {
  $totalRow = $totalResult->fetch_assoc();
  $totalAdvisors = $totalRow['total'];
}

$conn->close();

// Calculate total pages
$totalPages = ceil($totalAdvisors / $limit);

// Return the data as JSON
echo json_encode([
  'advisors' => $advisors,
  'totalPages' => $totalPages
]);
?>
