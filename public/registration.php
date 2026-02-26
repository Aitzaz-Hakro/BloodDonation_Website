<?php
require_once 'config.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $gender = sanitize($_POST['gender'] ?? '');
    $blood_group = sanitize($_POST['blood_group'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $number = sanitize($_POST['number'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $last_donation = sanitize($_POST['last_donation'] ?? '');
    $medical_conditions = sanitize($_POST['medical_conditions'] ?? '');
    
    // Validation
    if (empty($full_name) || empty($gender) || empty($blood_group) || empty($email) || empty($number) || empty($address)) {
        $error = 'Please fill all required fields.';
    } elseif ($age < 18 || $age > 65) {
        $error = 'Donors must be between 18 and 65 years old.';
    } else {
        // Insert into database
        $sql = "INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisss", $full_name, $number, $email, $age, $gender, $blood_group, $address);
        
        if ($stmt->execute()) {
            $success = 'Registration successful! Thank you for becoming a donor.';
        } else {
            $error = 'Registration failed. Please try again.';
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donor Registration - Blood Bank</title>
    <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('media/pexels-pranidchakan-boonrom-101111-1350560.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .navbar {
            background-color: rgba(255,255,255,0.95) !important;
        }
        .form-container {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .title {
            color: #fff;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8);
            margin: 2rem 0;
        }
        fieldset {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        legend {
            font-weight: 600;
            color: #dc3545;
            padding: 0 10px;
            width: auto;
        }
        .form-container input,
        .form-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        .form-container input:focus,
        .form-container select:focus {
            outline: none;
            border-color: #dc3545;
        }
        .form-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .form-row > * {
            flex: 1;
            min-width: 150px;
        }
        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 1rem 0;
        }
        .checkbox-container input {
            width: auto;
            margin: 0;
        }
        .checkbox-container label {
            font-size: 0.9rem;
            color: #666;
        }
        .submit-btn {
            width: 100%;
            padding: 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .submit-btn:hover {
            background-color: #b02a37;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="container py-4">
    <h2 class="title text-center">Donor Registration</h2>
    
    <div class="form-container">
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="registration.php">
            <fieldset>
                <legend><i class="fa-solid fa-user me-2"></i>Personal Information</legend>
                <input type="text" placeholder="Full Name *" name="full_name" required 
                    value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                <div class="form-row">
                    <input type="number" placeholder="Age *" name="age" min="18" max="65" required 
                        value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                    <select name="gender" required>
                        <option value="">Select Gender *</option>
                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <select name="blood_group" required>
                        <option value="">Blood Group *</option>
                        <option value="A+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend><i class="fa-solid fa-address-book me-2"></i>Contact Information</legend>
                <input type="email" placeholder="Email Address *" name="email" required 
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <input type="tel" placeholder="Phone Number *" name="number" pattern="[0-9+\-\s]+" required 
                    value="<?php echo isset($_POST['number']) ? htmlspecialchars($_POST['number']) : ''; ?>">
                <input type="text" placeholder="Full Address *" name="address" required 
                    value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
            </fieldset>

            <fieldset>
                <legend><i class="fa-solid fa-notes-medical me-2"></i>Medical Information</legend>
                <select name="last_donation">
                    <option value="">Last Donation (if any)</option>
                    <option value="never">Never Donated</option>
                    <option value="3months">Less than 3 months ago</option>
                    <option value="6months">3-6 months ago</option>
                    <option value="1year">6-12 months ago</option>
                    <option value="over1year">Over 1 year ago</option>
                </select>
                <select name="medical_conditions">
                    <option value="none">Any Medical Conditions?</option>
                    <option value="none">None</option>
                    <option value="diabetes">Diabetes</option>
                    <option value="hypertension">Hypertension</option>
                    <option value="heart">Heart Condition</option>
                    <option value="other">Other (will be verified)</option>
                </select>
            </fieldset>

            <div class="checkbox-container">
                <input type="checkbox" id="agree" name="agree" required>
                <label for="agree">I confirm that the information provided is accurate and I agree to the terms and conditions</label>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fa-solid fa-heart-pulse me-2"></i>REGISTER AS DONOR
            </button>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
