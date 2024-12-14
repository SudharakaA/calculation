<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}
//
$advisorId = $_POST['advisorId'];
$advisorName = $_POST['advisorName'];
$clientName = $_POST['clientName'];
$email = $_POST['email'];
$mobileNo = $_POST['mobile'];
$faxNo = $_POST['faxNo'];
$landLine = $_POST['landLine'];
$organization = $_POST['organization'];
$occupation = $_POST['occupation'];
$message = $_POST['message'];

if (empty($clientName) || empty($email) || empty($mobileNo) || empty($message)) {
    echo json_encode(["message" => "Please fill all required fields."]);
    exit();
}

$sql = "INSERT INTO client_contacts (advisor_id, advisor_name, client_name, email, mobile_no, fax_no, land_line, organization, occupation, message)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isssssssss",
    $advisorId,
    $advisorName,
    $clientName,
    $email,
    $mobileNo,
    $faxNo,
    $landLine,
    $organization,
    $occupation,
    $message
);

if ($stmt->execute()) {
    echo json_encode(["message" => "Your message has been sent successfully!"]);
} else {
    echo json_encode(["message" => "Failed to send your message: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>
