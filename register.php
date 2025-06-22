<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $email = $_POST['email'];
    $password = $_POST['password']; // You should hash this in real systems!

    // Register table
    $sql1 = "INSERT INTO register (full_name, department, year, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("sssss", $full_name, $department, $year, $email, $password);
    $stmt1->execute();
    $stmt1->close();

    // Profile table
    $sql2 = "INSERT INTO profile (name, email, dept, year, photo) VALUES (?, ?, ?, ?, ?)";
    $empty_photo = 'uploads/default.png'; // Default photo
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sssss", $full_name, $email, $department, $year, $empty_photo);
    $stmt2->execute();
    $stmt2->close();

    echo "<script>alert('Registration Successful!'); window.location.href = 'index.html';</script>";
}
$conn->close();
?>