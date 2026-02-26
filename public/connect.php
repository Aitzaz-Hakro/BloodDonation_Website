<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "blood_donation";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO blood_donation (name, email) VALUES ('$name', '$text')";

if ($conn->query($sql) === TRUE) {
    // ✅ Redirect to a local HTML file (in the same folder)
    header("Location: index.html");
    exit();
} else {
    echo "❌ Error: " . $conn->error;
}

$conn->close();
?>
