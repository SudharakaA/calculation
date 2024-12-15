<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $organization = $_POST['organization'];
    $occupation = $_POST['occupation'];
    $services = isset($_POST['services']) ? implode(", ", $_POST['services']) : "None";
    $message = $_POST['message'];

    // File Upload Handling
    $uploadDir = 'uploads/';
    $uploadedFiles = [];
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['documents'])) {
        foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['documents']['name'][$key]);
                $targetFilePath = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $targetFilePath)) {
                    $uploadedFiles[] = $fileName;
                }
            }
        }
    }
    $uploadedFilesString = implode(", ", $uploadedFiles);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'final');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO form_submissions (name, email, phone, organization, occupation, services, message, documents)
            VALUES ('$name', '$email', '$phone', '$organization', '$occupation', '$services', '$message', '$uploadedFilesString')";

    if ($conn->query($sql) === TRUE) {
        echo "Form submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
