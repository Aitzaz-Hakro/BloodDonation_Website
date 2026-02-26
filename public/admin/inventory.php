<?php
require_once '../config.php';

$success_msg = '';
$error_msg = '';

// Handle add blood units
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_blood'])) {
    $blood_group = sanitize($_POST['blood_group']);
    $units = intval($_POST['units']);
    
    // Expiry date is 3 days from now
    $expiry_date = date('Y-m-d', strtotime('+3 days'));
    
    if ($units > 0 && $units <= 50) {
        // Update existing inventory
        $updateSql = "UPDATE blood_inventory SET units_available = units_available + ?, expiry_date = ?, last_updated = NOW() WHERE blood_group = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iss", $units, $expiry_date, $blood_group);
        
        if ($updateStmt->execute()) {
            $success_msg = "Added $units unit(s) of $blood_group blood. Expiry date: $expiry_date";
        } else {
            $error_msg = "Failed to add blood units.";
        }
        $updateStmt->close();
    } else {
        $error_msg = "Please enter valid units (1-50).";
    }
}

// Handle remove blood units
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_blood'])) {
    $blood_group = sanitize($_POST['blood_group']);
    $units = intval($_POST['units']);
    
    if ($units > 0) {
        // Check available units
        $checkSql = "SELECT units_available FROM blood_inventory WHERE blood_group = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $blood_group);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $current = $checkResult->fetch_assoc();
        $checkStmt->close();
        
        if ($current && $current['units_available'] >= $units) {
            $updateSql = "UPDATE blood_inventory SET units_available = units_available - ?, last_updated = NOW() WHERE blood_group = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("is", $units, $blood_group);
            
            if ($updateStmt->execute()) {
                $success_msg = "Removed $units unit(s) of $blood_group blood.";
            } else {
                $error_msg = "Failed to remove blood units.";
            }
            $updateStmt->close();
        } else {
            $error_msg = "Not enough units available to remove.";
        }
    } else {
        $error_msg = "Please enter valid units.";
    }
}

// Fetch inventory
$inventory = $conn->query("SELECT * FROM blood_inventory ORDER BY blood_group");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory - Admin Panel</title>
    <link rel="icon" type="image/svg" href="../media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .inventory-container {
            max-width: 1000px;
            margin: 2rem auto;
        }
        .inventory-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .add-blood-form {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .add-blood-form h4 {
            margin-bottom: 1rem;
            color: #333;
        }
        .form-row {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .form-group {
            flex: 1;
            min-width: 150px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #666;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #dc3545;
        }
        .btn-add {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-add:hover {
            background: #218838;
        }
        .btn-remove {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-remove:hover {
            background: #b02a37;
        }
        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .blood-card {
            background: #fff;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .blood-card:hover {
            border-color: #dc3545;
            transform: translateY(-5px);
        }
        .blood-card.low-stock {
            border-color: #ffc107;
            background: #fff9e6;
        }
        .blood-card.critical {
            border-color: #dc3545;
            background: #fff5f5;
        }
        .blood-type {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc3545;
            margin-bottom: 0.5rem;
        }
        .units-available {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        .units-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .expiry-date {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }
        .expiry-date.expired {
            color: #dc3545;
            font-weight: 600;
        }
        .expiry-date.expiring-soon {
            color: #ffc107;
            font-weight: 600;
        }
        .threshold-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 10px;
            background: #e9ecef;
            color: #666;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-back {
            background: #6c757d;
            color: #fff;
            padding: 10px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-back:hover {
            background: #5a6268;
            color: #fff;
        }
        .info-note {
            background: #e7f3ff;
            border: 1px solid #b6d4fe;
            color: #0c5460;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>

<div class="container py-4">
    <div class="inventory-container">
        <?php if ($success_msg): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle me-2"></i><?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_msg); ?>
            </div>
        <?php endif; ?>
        
        <div class="inventory-card">
            <h1 class="page-title"><i class="fa-solid fa-warehouse me-2" style="color: #dc3545;"></i>Blood Inventory Management</h1>
            
            <div class="info-note">
                <i class="fa-solid fa-info-circle me-2"></i>
                <strong>Note:</strong> Blood units expire 3 days after being added to the inventory. Please manage inventory accordingly.
            </div>
            
            <!-- Add Blood Form -->
            <div class="add-blood-form">
                <h4><i class="fa-solid fa-plus-circle me-2"></i>Add Blood Units (from donor)</h4>
                <form method="POST" class="form-row">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="blood_group" required>
                            <option value="">Select Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Units to Add</label>
                        <input type="number" name="units" min="1" max="50" placeholder="1-50" required>
                    </div>
                    <button type="submit" name="add_blood" class="btn-add">
                        <i class="fa-solid fa-plus me-2"></i>Add Units
                    </button>
                </form>
            </div>
            
            <!-- Remove Blood Form -->
            <div class="add-blood-form" style="background: #fff5f5;">
                <h4><i class="fa-solid fa-minus-circle me-2"></i>Remove Blood Units (for requests)</h4>
                <form method="POST" class="form-row">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="blood_group" required>
                            <option value="">Select Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Units to Remove</label>
                        <input type="number" name="units" min="1" placeholder="Enter units" required>
                    </div>
                    <button type="submit" name="remove_blood" class="btn-remove">
                        <i class="fa-solid fa-minus me-2"></i>Remove Units
                    </button>
                </form>
            </div>
            
            <!-- Inventory Grid -->
            <h4 class="mb-3"><i class="fa-solid fa-boxes-stacked me-2"></i>Current Stock</h4>
            <div class="inventory-grid">
                <?php if ($inventory && $inventory->num_rows > 0): ?>
                    <?php while ($item = $inventory->fetch_assoc()): 
                        $isLowStock = $item['units_available'] <= $item['minimum_threshold'];
                        $isCritical = $item['units_available'] <= 2;
                        $cardClass = $isCritical ? 'critical' : ($isLowStock ? 'low-stock' : '');
                        
                        $expiryClass = '';
                        $expiryText = 'No expiry set';
                        if ($item['expiry_date']) {
                            $expiryDate = strtotime($item['expiry_date']);
                            $today = strtotime('today');
                            $daysUntilExpiry = ($expiryDate - $today) / (60 * 60 * 24);
                            
                            if ($daysUntilExpiry < 0) {
                                $expiryClass = 'expired';
                                $expiryText = 'EXPIRED';
                            } elseif ($daysUntilExpiry <= 1) {
                                $expiryClass = 'expiring-soon';
                                $expiryText = 'Expires: ' . date('M j', $expiryDate);
                            } else {
                                $expiryText = 'Expires: ' . date('M j', $expiryDate);
                            }
                        }
                    ?>
                        <div class="blood-card <?php echo $cardClass; ?>">
                            <div class="blood-type"><?php echo htmlspecialchars($item['blood_group']); ?></div>
                            <div class="units-label">Units Available</div>
                            <div class="units-available"><?php echo $item['units_available']; ?></div>
                            <div class="expiry-date <?php echo $expiryClass; ?>">
                                <i class="fa-solid fa-calendar-xmark me-1"></i><?php echo $expiryText; ?>
                            </div>
                            <div class="threshold-badge mt-2">
                                Min: <?php echo $item['minimum_threshold']; ?> units
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">No inventory data available.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="../adminPanel.php" class="btn-back">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
