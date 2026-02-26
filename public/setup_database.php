<?php
/**
 * Database Setup Script
 * Run this once to create the database and tables
 */

$host = "localhost";
$user = "root";
$password = "";

// Connect without database first
$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Blood Donation Database Setup</h2>";
echo "<pre>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS blood_donation";
if ($conn->query($sql) === TRUE) {
    echo "✓ Database 'blood_donation' created or already exists\n";
} else {
    echo "✗ Error creating database: " . $conn->error . "\n";
}

// Select database
$conn->select_db("blood_donation");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'users' created or already exists\n";
} else {
    echo "✗ Error creating users table: " . $conn->error . "\n";
}

// Create admin_info table
$sql = "CREATE TABLE IF NOT EXISTS admin_info (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_username VARCHAR(100) NOT NULL UNIQUE,
    admin_password VARCHAR(255) NOT NULL,
    admin_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'admin_info' created or already exists\n";
} else {
    echo "✗ Error creating admin_info table: " . $conn->error . "\n";
}

// Insert default admin
$sql = "INSERT IGNORE INTO admin_info (admin_username, admin_password, admin_email) 
        VALUES ('admin', 'admin123', 'admin@bloodbank.com')";
if ($conn->query($sql) === TRUE) {
    echo "✓ Default admin user created (username: admin, password: admin123)\n";
}

// Create donors table
$sql = "CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    blood_type VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'donors' created or already exists\n";
} else {
    echo "✗ Error creating donors table: " . $conn->error . "\n";
}

// Create donor_details table
$sql = "CREATE TABLE IF NOT EXISTS donor_details (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(255) NOT NULL,
    donor_number VARCHAR(50),
    donor_mail VARCHAR(255),
    donor_age INT,
    donor_gender VARCHAR(20),
    donor_blood VARCHAR(10) NOT NULL,
    donor_address TEXT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'donor_details' created or already exists\n";
} else {
    echo "✗ Error creating donor_details table: " . $conn->error . "\n";
}

// Create blood_requests table
$sql = "CREATE TABLE IF NOT EXISTS blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_name VARCHAR(255) NOT NULL,
    recipient_blood_group VARCHAR(10) NOT NULL,
    units_required INT NOT NULL DEFAULT 1,
    patient_condition VARCHAR(255),
    hospital_name VARCHAR(255),
    contact_number VARCHAR(50),
    request_status ENUM('Pending', 'Approved', 'Rejected', 'Completed') DEFAULT 'Pending',
    request_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'blood_requests' created or already exists\n";
} else {
    echo "✗ Error creating blood_requests table: " . $conn->error . "\n";
}

// Create blood_inventory table
$sql = "CREATE TABLE IF NOT EXISTS blood_inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    blood_group VARCHAR(10) NOT NULL,
    units_available INT NOT NULL DEFAULT 0,
    minimum_threshold INT DEFAULT 5,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expiry_date DATE
)";
if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'blood_inventory' created or already exists\n";
} else {
    echo "✗ Error creating blood_inventory table: " . $conn->error . "\n";
}

// Insert initial blood inventory with 3-day expiry
$bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
$expiryDate = date('Y-m-d', strtotime('+3 days'));
foreach ($bloodGroups as $bg) {
    $sql = "INSERT IGNORE INTO blood_inventory (blood_group, units_available, minimum_threshold, expiry_date) 
            VALUES ('$bg', 10, 5, '$expiryDate')";
    $conn->query($sql);
}
echo "✓ Initial blood inventory data inserted (expiry: $expiryDate)\n";

echo "\n</pre>";
echo "<h3 style='color: green;'>✓ Database setup completed successfully!</h3>";
echo "<p><strong>Note:</strong> Blood expires 3 days after being added to inventory.</p>";
echo "<p><a href='index.php'>Go to Homepage</a></p>";

$conn->close();
?>
