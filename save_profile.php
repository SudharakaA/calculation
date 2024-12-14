<?php
// Database connection details
$host = "localhost";
$dbname = "Final";
$username = "root";
$password = ""; // Replace with your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $nic = $_POST['nic'];
    $mobile = $_POST['mobile'];
    $residentialAddress = $_POST['residentialAddress'];
    $mailingAddress = $_POST['mailingAddress'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Handle the file upload for the photo
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = file_get_contents($_FILES['photo']['tmp_name']);
    }

    // Insert the data into the database
    $stmt = $conn->prepare("INSERT INTO user_profiles (first_name, last_name, dob, nic, mobile, residential_address, mailing_address, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstName, $lastName, $dob, $nic, $mobile, $residentialAddress, $mailingAddress, $password, $photo);

    if ($stmt->execute()) {
        echo "Profile saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>