<?php
require_once 'config.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_name = sanitize($_POST['recipient_name'] ?? '');
    $blood_group = sanitize($_POST['blood_group'] ?? '');
    $units_required = intval($_POST['units_required'] ?? 1);
    $patient_condition = sanitize($_POST['patient_condition'] ?? '');
    $hospital_name = sanitize($_POST['hospital_name'] ?? '');
    $contact_number = sanitize($_POST['contact_number'] ?? '');
    $request_date = sanitize($_POST['request_date'] ?? date('Y-m-d'));
    
    // Validation
    if (empty($recipient_name) || empty($blood_group) || empty($hospital_name) || empty($contact_number)) {
        $error = 'Please fill all required fields.';
    } elseif ($units_required < 1 || $units_required > 10) {
        $error = 'Units required must be between 1 and 10.';
    } else {
        // Insert into database
        $sql = "INSERT INTO blood_requests (recipient_name, recipient_blood_group, units_required, patient_condition, hospital_name, contact_number, request_date, request_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissss", $recipient_name, $blood_group, $units_required, $patient_condition, $hospital_name, $contact_number, $request_date);
        
        if ($stmt->execute()) {
            $success = 'Blood request submitted successfully! We will contact you soon.';
        } else {
            $error = 'Failed to submit request. Please try again.';
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
    <title>Request Blood - Blood Bank</title>
    <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
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
            max-width: 600px;
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
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        .form-container input:focus,
        .form-container select:focus,
        .form-container textarea:focus {
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
        .urgency-note {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="container py-4">
    <h2 class="title text-center"><i class="fa-solid fa-droplet me-2"></i>Request Blood</h2>
    
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
        
        <div class="urgency-note">
            <i class="fa-solid fa-info-circle me-2"></i>
            <strong>Note:</strong> For emergency blood requests, please also contact our helpline: +92 304 3005127
        </div>
        
        <form method="POST" action="request_blood.php">
            <fieldset>
                <legend><i class="fa-solid fa-user-injured me-2"></i>Patient Information</legend>
                <input type="text" placeholder="Patient Name *" name="recipient_name" required 
                    value="<?php echo isset($_POST['recipient_name']) ? htmlspecialchars($_POST['recipient_name']) : ''; ?>">
                <div class="form-row">
                    <select name="blood_group" required>
                        <option value="">Blood Group Needed *</option>
                        <option value="A+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                    </select>
                    <input type="number" placeholder="Units Required *" name="units_required" min="1" max="10" required 
                        value="<?php echo isset($_POST['units_required']) ? htmlspecialchars($_POST['units_required']) : '1'; ?>">
                </div>
                <select name="patient_condition">
                    <option value="">Patient Condition (if known)</option>
                    <option value="Surgery" <?php echo (isset($_POST['patient_condition']) && $_POST['patient_condition'] == 'Surgery') ? 'selected' : ''; ?>>Surgery</option>
                    <option value="Accident" <?php echo (isset($_POST['patient_condition']) && $_POST['patient_condition'] == 'Accident') ? 'selected' : ''; ?>>Accident</option>
                    <option value="Anemia" <?php echo (isset($_POST['patient_condition']) && $_POST['patient_condition'] == 'Anemia') ? 'selected' : ''; ?>>Anemia</option>
                    <option value="Cancer Treatment" <?php echo (isset($_POST['patient_condition']) && $_POST['patient_condition'] == 'Cancer Treatment') ? 'selected' : ''; ?>>Cancer Treatment</option>
                    <option value="Pregnancy Complication" <?php echo (isset($_POST['patient_condition']) && $_POST['patient_condition'] == 'Pregnancy Complication') ? 'selected' : ''; ?>>Pregnancy Complication</option>
                    <option value="Other" <?php echo (isset($_POST['patient_condition']) && $_POST['patient_condition'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </fieldset>

            <fieldset>
                <legend><i class="fa-solid fa-hospital me-2"></i>Hospital & Contact</legend>
                <input type="text" placeholder="Hospital Name *" name="hospital_name" required 
                    value="<?php echo isset($_POST['hospital_name']) ? htmlspecialchars($_POST['hospital_name']) : ''; ?>">
                <input type="tel" placeholder="Contact Number *" name="contact_number" pattern="[0-9+\-\s]+" required 
                    value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number']) : ''; ?>">
                <input type="date" name="request_date" value="<?php echo isset($_POST['request_date']) ? htmlspecialchars($_POST['request_date']) : date('Y-m-d'); ?>">
            </fieldset>

            <button type="submit" class="submit-btn">
                <i class="fa-solid fa-paper-plane me-2"></i>SUBMIT REQUEST
            </button>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
