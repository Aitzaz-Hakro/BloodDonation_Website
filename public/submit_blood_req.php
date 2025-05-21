<?php
$conn = new mysqli("localhost", "root", "", "blood_donation");

$name = $_POST['full_name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$number = $_POST['number'];
$address = $_POST['address'];
$blood_group = $_POST['blood_group'];  

$query = "INSERT INTO donor_details (donor_name, donor_number, donor_mail,donor_age,donor_gender,donor_blood,donor_address) 
          VALUES ('$name', '$number', '$email', '$age', '$gender', '$blood_group', '$address')";

if ($conn->query($query) === TRUE) {
    echo "<script>alert('Request Submitted'); window.location.href='adminPanel.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>
