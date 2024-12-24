<?php
// ...existing code...
// Connect to DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tax_services";
$conn = new mysqli($servername, $username, $password, $dbname);

$notification = "";

// Add new consultant
if (isset($_POST['add_advisor'])) {
  $name = $_POST['name'];
  $position = $_POST['position'];
  $experience = $_POST['experience'];
  $qualifications = $_POST['qualifications'];
  $imageUrl = $_POST['image_url'];
  $addSql = "INSERT INTO tax_advisors (name, position, experience, qualifications, image_url) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($addSql);
  $stmt->bind_param("sssss", $name, $position, $experience, $qualifications, $imageUrl);
  if ($stmt->execute()) {
    $notification = "Advisor added successfully!";
  } else {
    $notification = "Error adding advisor: " . $stmt->error;
  }
  $stmt->close();
}

// Update consultant
if (isset($_POST['update_advisor'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $position = $_POST['position'];
  $experience = $_POST['experience'];
  $qualifications = $_POST['qualifications'];
  $imageUrl = $_POST['image_url'];
  $updateSql = "UPDATE tax_advisors SET name=?, position=?, experience=?, qualifications=?, image_url=? WHERE id=?";
  $stmt = $conn->prepare($updateSql);
  $stmt->bind_param("sssssi", $name, $position, $experience, $qualifications, $imageUrl, $id);
  if ($stmt->execute()) {
    $notification = "Advisor updated successfully!";
  } else {
    $notification = "Error updating advisor: " . $stmt->error;
  }
  $stmt->close();
}

// Delete consultant
if (isset($_POST['delete_advisor'])) {
  $id = $_POST['id'];
  $deleteSql = "DELETE FROM tax_advisors WHERE id=?";
  $stmt = $conn->prepare($deleteSql);
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    $notification = "Advisor deleted successfully!";
  } else {
    $notification = "Error deleting advisor: " . $stmt->error;
  }
  $stmt->close();
}

// Fetch all consultants
$result = $conn->query("SELECT * FROM tax_advisors");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - TaxGen</title>
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-color: #eef2f7;
      color: #333;
    }

    header {
      background-color: #003366;
      color: #fff;
      padding: 10px;
      text-align: center;
    }

    header h1 {
      margin: 0;
      font-size: 24px;
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    h2 {
      color: #003366;
    }

    form input, form button {
      display: block;
      width: calc(100% - 20px);
      margin: 10px auto;
      padding: 10px;
      font-size: 14px;
    }

    form button {
      background-color: #003366;
      color: white;
      border: none;
      cursor: pointer;
    }

    form button:hover {
      background-color: #00509e;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    table th, table td {
      padding: 12px;
      text-align: left;
      border: 1px solid #ddd;
    }

    table th {
      background-color: #003366;
      color: #fff;
    }

    .actions button {
      padding: 8px;
      background-color: #ff4d4d;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-right: 5px;
    }

    .actions button.update {
      background-color: #4CAF50;
    }

    .actions button:hover {
      opacity: 0.8;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .taxgen__navbar-links_logo {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
    .user-info {
        display: flex;
        align-items: center;
    }
    .profile-photo {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .user-info span {
        margin-right: 10px;
    }
    .btn {
        padding: 8px;
        background-color: #ff4d4d;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 5px;
    }
    .actions button.update {
        background-color: #4CAF50;
    }
    .actions button:hover {
        opacity: 0.8;
    }
    .notification {
      margin: 20px auto;
      padding: 10px;
      border-radius: 4px;
      max-width: 1200px;
      text-align: center;
    }
    .notification.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .notification.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>
  <header>
    <div class="navbar">
      <div class="taxgen__navbar-links_logo">
        <img src="data:image/png;base64,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" alt="logo" class="logo">
      </div>
    </div>
    <h1>Admin Panel</h1>
  </header>
  <?php if ($notification): ?>
    <div class="notification <?php echo strpos($notification, 'Error') === false ? 'success' : 'error'; ?>">
      <?php echo $notification; ?>
    </div>
  <?php endif; ?>
  <div class="container">
    <h2>Add New Consultant</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Name" required>
      <input type="text" name="position" placeholder="Position" required>
      <input type="text" name="experience" placeholder="Experience" required>
      <input type="text" name="qualifications" placeholder="Qualifications" required>
      <input type="text" name="image_url" placeholder="Image URL">
      <button type="submit" name="add_advisor">Add Advisor</button>
    </form>

    <h2>Existing Consultants</h2>
    <table>
      <tr>
        <th>Name</th>
        <th>Position</th>
        <th>Experience</th>
        <th>Qualifications</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="POST">
          <td><input type="text" name="name" value="<?php echo $row['name']; ?>"></td>
          <td><input type="text" name="position" value="<?php echo $row['position']; ?>"></td>
          <td><input type="text" name="experience" value="<?php echo $row['experience']; ?>"></td>
          <td><input type="text" name="qualifications" value="<?php echo $row['qualifications']; ?>"></td>
          <td><input type="text" name="image_url" value="<?php echo $row['image_url']; ?>"></td>
          <td class="actions">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <button class="update" type="submit" name="update_advisor">Update</button>
            <button type="submit" name="delete_advisor">Delete</button>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
