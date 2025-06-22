

<?php
session_start();
// After session_start(), check if the email is set
if (isset($_SESSION['email'])) {
    echo "Session email: " . $_SESSION['email'];
} else {
    echo "Session email is not set.";
}
$email = $_SESSION['email'];

$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content here -->
         <meta charset="UTF-8">
    <title>Update Profile - E-Learning Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Same CSS as your original, untouched for styling */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #333;
            padding: 15px 30px;
            color: #fff;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
            padding: 0;
            margin: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            padding: 8px;
            display: block;
        }

        a:hover {
            background: orange;
            border-radius: 4px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            display: block;
            border: 3px solid #ddd;
        }

        #upload-label {
            margin-top: 10px;
            display: block;
            text-align: center;
            color: #555;
        }

        #photo {
            display: block;
            margin: 0 auto;
        }

        .success-msg {
            text-align: center;
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Update Profile</h2>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <p class="success-msg">Profile updated successfully!</p>
        <?php endif; ?>

        <form method="POST" action="update-profile-handler.php" enctype="multipart/form-data">
            <img id="previewImg" src="<?= isset($user['photo']) ? htmlspecialchars($user['photo']) : 'https://via.placeholder.com/120' ?>" alt="Profile Picture" class="profile-img">
            <label for="photo">Upload Profile Photo</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($user['photo'] ?? '') ?>">

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>

            <label for="dept">Department</label>
            <select id="dept" name="dept" required>
                <option value="">--Select Department--</option>
                <?php
                $departments = ['CSE' => 'Computer Science', 'ECE' => 'Electronics', 'ME' => 'Mechanical', 'CE' => 'Civil', 'EE' => 'Electrical'];
                foreach ($departments as $code => $name) {
                    $selected = ($user['dept'] === $code) ? 'selected' : '';
                    echo "<option value=\"$code\" $selected>$name</option>";
                }
                ?>
            </select>

            <label for="year">Year</label>
            <select id="year" name="year" required>
                <option value="">--Select Year--</option>
                <?php
                $years = ['FE' => 'First Year', 'SE' => 'Second Year', 'TE' => 'Third Year', 'BE' => 'Fourth Year'];
                foreach ($years as $code => $label) {
                    $selected = ($user['year'] === $code) ? 'selected' : '';
                    echo "<option value=\"$code\" $selected>$label</option>";
                }
                ?>
            </select>

            <button type="submit">Update Profile</button>
        </form>
    </div>

</body>
</html>
