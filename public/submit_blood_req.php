<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "blood_donation");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data with validation
$name = isset($_POST['full_name']) ? $conn->real_escape_string(trim($_POST['full_name'])) : '';
$age = isset($_POST['age']) ? intval($_POST['age']) : 0;
$gender = isset($_POST['gender']) ? $conn->real_escape_string(trim($_POST['gender'])) : '';
$email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : '';
$number = isset($_POST['number']) ? $conn->real_escape_string(trim($_POST['number'])) : '';
$address = isset($_POST['address']) ? $conn->real_escape_string(trim($_POST['address'])) : '';
$blood_group = isset($_POST['blood_group']) ? $conn->real_escape_string(trim($_POST['blood_group'])) : '';

// Validate required fields
if (empty($name) || empty($blood_group) || empty($email) || empty($number)) {
    header("Location: registration.html?status=error&msg=missing_fields");
    exit();
}

// Validate age (must be 18-65)
if ($age < 18 || $age > 65) {
    header("Location: registration.html?status=error&msg=invalid_age");
    exit();
}

// Insert into database using prepared statement
$query = "INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssisss", $name, $number, $email, $age, $gender, $blood_group, $address);

if ($stmt->execute()) {
    // Success - redirect to registration page with success message
    header("Location: registration.html?status=success");
    exit();
} else {
    // Error - redirect with error message
    header("Location: registration.html?status=error&msg=" . urlencode($stmt->error));
    exit();
}

$stmt->close();
$conn->close();
?>
