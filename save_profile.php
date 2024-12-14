<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "final"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate inputs
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$firstName = sanitize($_POST['first_name']);
$lastName = sanitize($_POST['last_name']);
$dob = sanitize($_POST['dob']);
$nic = sanitize($_POST['nic']);
$mobile = sanitize($_POST['mobile']);
$residentialAddress = sanitize($_POST['residential_address']);
$mailingAddress = sanitize($_POST['mailing_address']);
$password = $_POST['password'];
$rePassword = $_POST['re_password'];

// Validation
$errors = [];
if (!preg_match("/^[a-zA-Z ]+$/", $firstName)) {
    $errors[] = "First Name can only contain letters.";
}
if (!preg_match("/^[a-zA-Z ]+$/", $lastName)) {
    $errors[] = "Last Name can only contain letters.";
}
if (!preg_match("/^\d{10}$/", $mobile)) {
    $errors[] = "Mobile number must be 10 digits.";
}
if (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*]).{6,}$/", $password)) {
    $errors[] = "Password must be at least 6 characters long and include letters, numbers, and symbols.";
}
if ($password !== $rePassword) {
    $errors[] = "Passwords do not match.";
}

if (!empty($errors)) {
    die("Errors: " . implode(", ", $errors));
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Handle photo upload
$photo = $_FILES['photo']['name'];
$targetDir = "uploads/";
$targetFile = $targetDir . basename($photo);

if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
    die("Error uploading file.");
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO user_profiles (first_name, last_name, dob, nic, mobile, residential_address, mailing_address, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $firstName, $lastName, $dob, $nic, $mobile, $residentialAddress, $mailingAddress, $hashedPassword, $photo);

if ($stmt->execute()) {
    echo "Profile saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
