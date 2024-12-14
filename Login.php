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
        
        // Query to check if the email exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // User found, check the password
            $user = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Credentials are correct, redirect to the dashboard or another page
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                header("Location: dashboard.html"); // Redirect to the dashboard
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Recheck the email and the password.";
                header("Location: userlogin.php"); // Redirect back to login page
                exit();
            }
        } else {
            // Email does not exist
            $_SESSION['error'] = "Recheck the email and the password.";
            header("Location: userlogin.php"); // Redirect back to login page
            exit();
        }
    }
?>
