<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blood_donation";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    }
    .table th {
      background-color: #f8f9fa;
    }
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-approved { background-color: #28a745; }
    .badge-rejected { background-color: #dc3545; }
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
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.html">
      <img src="media/lifeblood-logo.png" class="logoimg" alt="Logo"> Blood Bank Management System
    </a>
    <button class="navbar-toggler" onclick="toggleMenu()">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="nabarbtns" id="navMenu">
      <ul>
        <li><a href="index.html" class="nav-link">Home</a></li>
        <li><a href="registration.html" class="nav-link">Donate</a></li>
        <li><a href="bloodRequest.html" class="nav-link">Blood Requests</a></li>
        <li><a href="adminPanel.php" class="nav-link active">Admin Panel</a></li>
        <li>
          <a href="signIn.html" class="signInButton">Sign In</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="navbar-divider"></div>

<!-- ADMIN DASHBOARD -->
<div class="container my-4">
  <h2 class="text-center mb-4"><i class="fa-solid fa-gauge-high me-2"></i>Admin Dashboard</h2>
  
  <!-- Statistics Row -->
  <div class="stats-row">
    <?php
    // Count donors
    $donorCount = $conn->query("SELECT COUNT(*) as count FROM donor_details")->fetch_assoc()['count'] ?? 0;
    // Count blood requests
    $requestCount = $conn->query("SELECT COUNT(*) as count FROM blood_requests")->fetch_assoc()['count'] ?? 0;
    // Count pending requests
    $pendingCount = $conn->query("SELECT COUNT(*) as count FROM blood_requests WHERE request_status = 'Pending'")->fetch_assoc()['count'] ?? 0;
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
  </div>

  <!-- Donors List -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <i class="fa-solid fa-hand-holding-medical me-2"></i>Registered Donors
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Blood Group</th>
            <th>Address</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM donor_details ORDER BY donor_id DESC");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              echo "<tr>
                <td>{$row['donor_id']}</td>
                <td>{$row['donor_name']}</td>
                <td>{$row['donor_number']}</td>
                <td>{$row['donor_mail']}</td>
                <td>{$row['donor_age']}</td>
                <td>{$row['donor_gender']}</td>
                <td><span class='badge bg-danger'>{$row['donor_blood']}</span></td>
                <td>{$row['donor_address']}</td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='8' class='text-center text-muted'>No donors registered yet</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Blood Requests -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <i class="fa-solid fa-droplet me-2"></i>Blood Requests
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Blood Group</th>
            <th>Units</th>
            <th>Condition</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM blood_requests ORDER BY request_id DESC");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              $statusClass = 'bg-secondary';
              if ($row['request_status'] == 'Pending') $statusClass = 'badge-pending';
              else if ($row['request_status'] == 'Approved') $statusClass = 'badge-approved';
              else if ($row['request_status'] == 'Rejected') $statusClass = 'badge-rejected';
              
              echo "<tr>
                <td>{$row['request_id']}</td>
                <td>{$row['recipient_name']}</td>
                <td><span class='badge bg-danger'>{$row['recipient_blood_group']}</span></td>
                <td>{$row['units_required']}</td>
                <td>{$row['patient_condition']}</td>
                <td><span class='badge {$statusClass}'>{$row['request_status']}</span></td>
                <td>{$row['request_date']}</td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='7' class='text-center text-muted'>No blood requests found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Blood Inventory -->
  <div class="card dashboard-card border-danger">
    <div class="card-header bg-danger text-white">
      <i class="fa-solid fa-warehouse me-2"></i>Blood Inventory
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Blood Group</th>
            <th>Units Available</th>
            <th>Minimum Threshold</th>
            <th>Last Updated</th>
            <th>Expiry Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM blood_inventory ORDER BY blood_group");
          if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              $lowStock = ($row['units_available'] <= $row['minimum_threshold']) ? 'table-warning' : '';
              echo "<tr class='{$lowStock}'>
                <td>{$row['inventory_id']}</td>
                <td><span class='badge bg-danger'>{$row['blood_group']}</span></td>
                <td>{$row['units_available']}</td>
                <td>{$row['minimum_threshold']}</td>
                <td>{$row['last_updated']}</td>
                <td>{$row['expiry_date']}</td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='6' class='text-center text-muted'>No inventory data found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('show');
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
