<?php
// Start the session
session_start();

// Database connection
$servername = "localhost"; // Replace with your DB host
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "final"; // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables for the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the dashboard
            header("Location: dashboard.html");
            exit();
        } else {
            // Incorrect password
            echo "Invalid credentials.";
        }
    } else {
        // User not found
        echo "No user found with that email.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>
