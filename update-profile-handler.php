<?php
session_start();

$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if session email is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page if email session is not set
    header("Location: index.html");
    exit();
}

$email = $_SESSION['email']; // Now safe to access the email session
$full_name = $_POST['full_name'];
$department = $_POST['department'];
$year = $_POST['year'];
$password = $_POST['password'];
$photo_name = "";

// Handle file upload
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $photo_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $photo_name;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
}

// Build SQL
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE register SET full_name=?, department=?, year=?, password=?, photo=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $department, $year, $hashed_password, $photo_name, $email);
} else {
    $sql = "UPDATE register SET full_name=?, department=?, year=?, photo=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $full_name, $department, $year, $photo_name, $email);
}

if ($stmt->execute()) {
    header("Location: update_profile.php?status=success");
    exit();
} else {
    echo "Error updating profile.";
}

$stmt->close();
$conn->close();
?>
