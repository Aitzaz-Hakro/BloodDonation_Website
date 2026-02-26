<?php
require_once 'config.php';

// Fetch blood requests from database
$sql = "SELECT * FROM blood_requests ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blood Requests - Blood Bank</title>
    <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .table-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 1.5rem;
        }
        .badge-pending { background-color: #ffc107 !important; color: #000 !important; }
        .badge-approved { background-color: #28a745 !important; }
        .badge-rejected { background-color: #dc3545 !important; }
        .badge-completed { background-color: #17a2b8 !important; }
        .request-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: background 0.3s;
        }
        .request-btn:hover {
            background-color: #b02a37;
            color: white;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fa-solid fa-droplet me-2" style="color: #dc3545;"></i>Blood Request Records</h2>
        <a href="request_blood.php" class="request-btn">
            <i class="fa-solid fa-plus me-2"></i>Request Blood
        </a>
    </div>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle mb-0">
                <thead class="table-danger">
                    <tr>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Blood Group</th>
                        <th>Units Required</th>
                        <th>Hospital</th>
                        <th>Contact</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo htmlspecialchars($row['recipient_name']); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['recipient_blood_group']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['units_required']); ?></td>
                                <td><?php echo htmlspecialchars($row['hospital_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo $row['request_date'] ? date('Y-m-d', strtotime($row['request_date'])) : date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <?php
                                    $status = $row['request_status'];
                                    $badgeClass = 'badge-pending';
                                    if ($status == 'Approved') $badgeClass = 'badge-approved';
                                    elseif ($status == 'Rejected') $badgeClass = 'badge-rejected';
                                    elseif ($status == 'Completed') $badgeClass = 'badge-completed';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-muted py-4">No blood requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
