<?php
require_once 'config.php';

// Handle success/error messages from redirects
$success_msg = '';
$error_msg = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'donor_deleted') $success_msg = 'Donor deleted successfully.';
    if ($_GET['success'] == 'request_deleted') $success_msg = 'Request deleted successfully.';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'donor_not_found') $error_msg = 'Donor not found.';
    if ($_GET['error'] == 'request_not_found') $error_msg = 'Request not found.';
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel - Blood Bank</title>
  <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .dashboard-card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin-bottom: 1.5rem;
    }
    .dashboard-card .card-header {
      border-radius: 12px 12px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-header-link {
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      opacity: 0.9;
      transition: opacity 0.3s;
    }
    .card-header-link:hover {
      opacity: 1;
      color: #fff;
    }
    .table th {
      background-color: #f8f9fa;
    }
    .table-clickable tbody tr {
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .table-clickable tbody tr:hover {
      background-color: #fff3f3 !important;
    }
    .badge-pending { background-color: #ffc107 !important; color: #000 !important; }
    .badge-approved { background-color: #28a745 !important; }
    .badge-rejected { background-color: #dc3545 !important; }
    .badge-completed { background-color: #17a2b8 !important; }
    .stats-row {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 2rem;
    }
    .stat-box {
      flex: 1;
      min-width: 150px;
      background: #fff;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
      text-decoration: none;
      color: inherit;
    }
    .stat-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .stat-box i {
      font-size: 2rem;
      color: #dc3545;
      margin-bottom: 0.5rem;
    }
    .stat-box h3 {
      font-size: 2rem;
      margin: 0.5rem 0;
      color: #333;
    }
    .stat-box p {
      margin: 0;
      color: #666;
    }
    .click-hint {
      font-size: 0.8rem;
      color: #999;
      margin-top: 0.5rem;
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
    .view-btn {
      background: #dc3545;
      color: #fff;
      border: none;
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: background 0.3s;
    }
    .view-btn:hover {
      background: #b02a37;
    }
    .low-stock {
      background-color: #fff3cd !important;
    }
    .critical-stock {
      background-color: #f8d7da !important;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- ADMIN DASHBOARD -->
<div class="container my-4">
  <h2 class="text-center mb-4"><i class="fa-solid fa-gauge-high me-2"></i>Admin Dashboard</h2>
  
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
  
  <!-- Statistics Row -->
  <div class="stats-row">
    <?php
    // Count donors
    $donorCount = $conn->query("SELECT COUNT(*) as count FROM donor_details")->fetch_assoc()['count'] ?? 0;
    // Count blood requests
    $requestCount = $conn->query("SELECT COUNT(*) as count FROM blood_requests")->fetch_assoc()['count'] ?? 0;
    // Count pending requests
    $pendingCount = $conn->query("SELECT COUNT(*) as count FROM blood_requests WHERE request_status = 'Pending'")->fetch_assoc()['count'] ?? 0;
    // Count users
    $userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'] ?? 0;
    ?>
    <div class="stat-box">
      <i class="fa-solid fa-users"></i>
      <h3><?= $donorCount ?></h3>
      <p>Total Donors</p>
    </div>
    <div class="stat-box">
      <i class="fa-solid fa-droplet"></i>
      <h3><?= $requestCount ?></h3>
      <p>Blood Requests</p>
    </div>
    <div class="stat-box">
      <i class="fa-solid fa-clock"></i>
      <h3><?= $pendingCount ?></h3>
      <p>Pending Requests</p>
    </div>
    <a href="admin/inventory.php" class="stat-box">
      <i class="fa-solid fa-warehouse"></i>
      <h3><i class="fa-solid fa-arrow-right" style="font-size: 1.5rem;"></i></h3>
      <p>Manage Inventory</p>
    </a>
  </div>

  <!-- Donors List -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <span><i class="fa-solid fa-hand-holding-medical me-2"></i>Registered Donors</span>
      <span class="click-hint"><i class="fa-solid fa-mouse-pointer me-1"></i>Click row to view details</span>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover table-clickable mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Blood Group</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM donor_details ORDER BY donor_id DESC LIMIT 10");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              echo "<tr onclick=\"window.location='admin/view_donor.php?id={$row['donor_id']}'\" style='cursor:pointer;'>
                <td>#{$row['donor_id']}</td>
                <td><strong>{$row['donor_name']}</strong></td>
                <td>{$row['donor_number']}</td>
                <td>{$row['donor_mail']}</td>
                <td>{$row['donor_age']}</td>
                <td>{$row['donor_gender']}</td>
                <td><span class='badge bg-danger'>{$row['donor_blood']}</span></td>
                <td><button class='view-btn'><i class='fa-solid fa-eye'></i> View</button></td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='8' class='text-center text-muted py-4'>No donors registered yet</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Blood Requests -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <span><i class="fa-solid fa-droplet me-2"></i>Blood Requests</span>
      <span class="click-hint"><i class="fa-solid fa-mouse-pointer me-1"></i>Click row to manage status</span>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover table-clickable mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Blood Group</th>
            <th>Units</th>
            <th>Hospital</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM blood_requests ORDER BY request_id DESC LIMIT 10");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              $statusClass = 'bg-secondary';
              if ($row['request_status'] == 'Pending') $statusClass = 'badge-pending';
              else if ($row['request_status'] == 'Approved') $statusClass = 'badge-approved';
              else if ($row['request_status'] == 'Rejected') $statusClass = 'badge-rejected';
              else if ($row['request_status'] == 'Completed') $statusClass = 'badge-completed';
              
              echo "<tr onclick=\"window.location='admin/view_request.php?id={$row['request_id']}'\" style='cursor:pointer;'>
                <td>#{$row['request_id']}</td>
                <td><strong>{$row['recipient_name']}</strong></td>
                <td><span class='badge bg-danger'>{$row['recipient_blood_group']}</span></td>
                <td>{$row['units_required']}</td>
                <td>{$row['hospital_name']}</td>
                <td><span class='badge {$statusClass}'>{$row['request_status']}</span></td>
                <td>{$row['request_date']}</td>
                <td><button class='view-btn'><i class='fa-solid fa-edit'></i> Manage</button></td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='8' class='text-center text-muted py-4'>No blood requests found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Blood Inventory Quick View -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <span><i class="fa-solid fa-warehouse me-2"></i>Blood Inventory</span>
      <a href="admin/inventory.php" class="card-header-link"><i class="fa-solid fa-cog me-1"></i>Manage Inventory</a>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover mb-0">
        <thead>
          <tr>
            <th>Blood Group</th>
            <th>Units Available</th>
            <th>Status</th>
            <th>Expiry Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM blood_inventory ORDER BY blood_group");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              $rowClass = '';
              $statusBadge = '<span class="badge bg-success">OK</span>';
              
              if ($row['units_available'] <= 2) {
                $rowClass = 'critical-stock';
                $statusBadge = '<span class="badge bg-danger">Critical</span>';
              } elseif ($row['units_available'] <= $row['minimum_threshold']) {
                $rowClass = 'low-stock';
                $statusBadge = '<span class="badge bg-warning text-dark">Low Stock</span>';
              }
              
              $expiryDisplay = $row['expiry_date'] ? date('M j, Y', strtotime($row['expiry_date'])) : 'Not set';
              if ($row['expiry_date'] && strtotime($row['expiry_date']) < time()) {
                $expiryDisplay = '<span class="text-danger"><strong>EXPIRED</strong></span>';
              }
              
              echo "<tr class='{$rowClass}'>
                <td><span class='badge bg-danger' style='font-size: 1rem;'>{$row['blood_group']}</span></td>
                <td><strong>{$row['units_available']}</strong> units</td>
                <td>{$statusBadge}</td>
                <td>{$expiryDisplay}</td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='4' class='text-center text-muted py-4'>No inventory data found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Registered Users -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <span><i class="fa-solid fa-user-group me-2"></i>Registered Users (<?= $userCount ?>)</span>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Registered</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM users ORDER BY user_id DESC LIMIT 10");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              echo "<tr>
                <td>#{$row['user_id']}</td>
                <td><strong>" . htmlspecialchars($row['name']) . "</strong></td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . date('M j, Y', strtotime($row['created_at'])) . "</td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='4' class='text-center text-muted py-4'>No users registered yet</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
