<?php
require_once '../config.php';

// Check if donor ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../adminPanel.php');
    exit();
}

$donor_id = intval($_GET['id']);

// Fetch donor details
$sql = "SELECT * FROM donor_details WHERE donor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../adminPanel.php?error=donor_not_found');
    exit();
}

$donor = $result->fetch_assoc();
$stmt->close();

// Handle delete action
if (isset($_POST['delete_donor'])) {
    $deleteSql = "DELETE FROM donor_details WHERE donor_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $donor_id);
    if ($deleteStmt->execute()) {
        header('Location: ../adminPanel.php?success=donor_deleted');
        exit();
    }
    $deleteStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Details - Admin Panel</title>
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
        .donor-avatar {
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
        .donor-name {
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
        @media (max-width: 600px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>

<div class="container py-4">
    <div class="detail-card">
        <div class="detail-header">
            <div class="donor-avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <h1 class="donor-name"><?php echo htmlspecialchars($donor['donor_name']); ?></h1>
                <span class="blood-badge"><?php echo htmlspecialchars($donor['donor_blood']); ?></span>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fa-solid fa-hashtag me-2"></i>Donor ID</label>
                <span>#<?php echo $donor['donor_id']; ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-calendar me-2"></i>Age</label>
                <span><?php echo htmlspecialchars($donor['donor_age'] ?? 'N/A'); ?> years</span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-venus-mars me-2"></i>Gender</label>
                <span><?php echo htmlspecialchars($donor['donor_gender'] ?? 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-droplet me-2"></i>Blood Group</label>
                <span><?php echo htmlspecialchars($donor['donor_blood']); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-phone me-2"></i>Phone Number</label>
                <span><?php echo htmlspecialchars($donor['donor_number'] ?? 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-envelope me-2"></i>Email</label>
                <span><?php echo htmlspecialchars($donor['donor_mail'] ?? 'N/A'); ?></span>
            </div>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <label><i class="fa-solid fa-location-dot me-2"></i>Address</label>
                <span><?php echo htmlspecialchars($donor['donor_address'] ?? 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label><i class="fa-solid fa-clock me-2"></i>Registration Date</label>
                <span><?php echo date('F j, Y', strtotime($donor['registration_date'])); ?></span>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="../adminPanel.php" class="btn-back">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
            </a>
            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this donor?');">
                <button type="submit" name="delete_donor" class="btn-delete">
                    <i class="fa-solid fa-trash me-2"></i>Delete Donor
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
