<?php
require_once '../config.php';

// Check if request ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../adminPanel.php');
    exit();
}

$request_id = intval($_GET['id']);
$success_msg = '';
$error_msg = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = sanitize($_POST['status']);
    $valid_statuses = ['Pending', 'Approved', 'Rejected', 'Completed'];
    
    if (in_array($new_status, $valid_statuses)) {
        $updateSql = "UPDATE blood_requests SET request_status = ? WHERE request_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $new_status, $request_id);
        
        if ($updateStmt->execute()) {
            $success_msg = "Status updated to '$new_status' successfully!";
        } else {
            $error_msg = "Failed to update status. Please try again.";
        }
        $updateStmt->close();
    } else {
        $error_msg = "Invalid status selected.";
    }
}

// Handle delete action
if (isset($_POST['delete_request'])) {
    $deleteSql = "DELETE FROM blood_requests WHERE request_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $request_id);
    if ($deleteStmt->execute()) {
        header('Location: ../adminPanel.php?success=request_deleted');
        exit();
    }
    $deleteStmt->close();
}

// Fetch request details
$sql = "SELECT * FROM blood_requests WHERE request_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../adminPanel.php?error=request_not_found');
    exit();
}

$request = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details - Admin Panel</title>
    <link rel="icon" type="image/svg" href="../media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .detail-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 700px;
            margin: 2rem auto;
        }
        .detail-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 1.5rem;
        }
        .request-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
        }
        .patient-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        .blood-badge {
            display: inline-block;
            background: #dc3545;
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1rem;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-left: 10px;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-approved { background: #28a745; color: #fff; }
        .status-rejected { background: #dc3545; color: #fff; }
        .status-completed { background: #17a2b8; color: #fff; }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        .detail-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .detail-item label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
        }
        .detail-item span {
            font-size: 1.1rem;
            color: #333;
            font-weight: 600;
        }
        .status-update-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }
        .status-update-section h4 {
            margin-bottom: 1rem;
            color: #333;
        }
        .status-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .status-form select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            min-width: 200px;
        }
        .status-form select:focus {
            outline: none;
            border-color: #dc3545;
        }
        .btn-update {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-update:hover {
            background: #218838;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f0f0f0;
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
        .btn-delete {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-delete:hover {
            background: #b02a37;
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
        @media (max-width: 600px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .status-form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>

<div class="container py-4">
    <div class="detail-card">
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
        
        <div class="detail-header">
            <div class="request-icon">
                <i class="fa-solid fa-droplet"></i>
            </div>
            <div>
                <h1 class="patient-name"><?php echo htmlspecialchars($request['recipient_name']); ?></h1>
                <span class="blood-badge"><?php echo htmlspecialchars($request['recipient_blood_group']); ?></span>
                <?php
                $status = $request['request_status'];
                $statusClass = 'status-pending';
                if ($status == 'Approved') $statusClass = 'status-approved';
                elseif ($status == 'Rejected') $statusClass = 'status-rejected';
                elseif ($status == 'Completed') $statusClass = 'status-completed';
                ?>
                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fa-solid fa-hashtag me-2"></i>Request ID</label>
                <span>#<?php echo $request['request_id']; ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-vial me-2"></i>Units Required</label>
                <span><?php echo htmlspecialchars($request['units_required']); ?> unit(s)</span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-heart-pulse me-2"></i>Patient Condition</label>
                <span><?php echo htmlspecialchars($request['patient_condition'] ?? 'Not specified'); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-hospital me-2"></i>Hospital</label>
                <span><?php echo htmlspecialchars($request['hospital_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-phone me-2"></i>Contact Number</label>
                <span><?php echo htmlspecialchars($request['contact_number'] ?? 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-calendar me-2"></i>Request Date</label>
                <span><?php echo $request['request_date'] ? date('F j, Y', strtotime($request['request_date'])) : 'N/A'; ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-clock me-2"></i>Created At</label>
                <span><?php echo date('F j, Y H:i', strtotime($request['created_at'])); ?></span>
            </div>
        </div>
        
        <!-- Status Update Section -->
        <div class="status-update-section">
            <h4><i class="fa-solid fa-edit me-2"></i>Update Request Status</h4>
            <form method="POST" class="status-form">
                <select name="status" required>
                    <option value="Pending" <?php echo ($request['request_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Approved" <?php echo ($request['request_status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                    <option value="Rejected" <?php echo ($request['request_status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="Completed" <?php echo ($request['request_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
                <button type="submit" name="update_status" class="btn-update">
                    <i class="fa-solid fa-save me-2"></i>Update Status
                </button>
            </form>
        </div>
        
        <div class="action-buttons">
            <a href="../adminPanel.php" class="btn-back">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
            </a>
            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this request?');">
                <button type="submit" name="delete_request" class="btn-delete">
                    <i class="fa-solid fa-trash me-2"></i>Delete Request
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
