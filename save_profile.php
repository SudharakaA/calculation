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
$email = sanitize($_POST['email']);
$password = $_POST['password'];
$rePassword = $_POST['re_password'];

// Server-side Email Validation
$email = sanitize($_POST['email']);

// Check if email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if (!empty($errors)) {
    die("Errors: " . implode(", ", $errors));
}

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

// Check if NIC already exists
$checkNIC = $conn->prepare("SELECT * FROM users WHERE nic = ?");
$checkNIC->bind_param("s", $nic);
$checkNIC->execute();
$result = $checkNIC->get_result();

if ($result->num_rows > 0) {
    die("Error: NIC already exists.");
}

// Handle photo upload
$photo = $_FILES['photo']['name'];
$targetDir = "uploads/";
$targetFile = $targetDir . basename($photo);

if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
    die("Error uploading file.");
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, dob, nic, mobile, residential_address, mailing_address, email, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $firstName, $lastName, $dob, $nic, $mobile, $residentialAddress, $mailingAddress, $email, $hashedPassword, $photo);

if ($stmt->execute()) {
    header("Location: Login.html");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
