<?php
// Database connection
<<<<<<< HEAD
$host = "localhost"; // or "127.0.0.1"
$user = "root"; // your DB username
$password = ""; // your DB password
$dbname = "blood_donation"; // your DB name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
=======
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blood_donation";

$conn = new mysqli($host, $user, $password, $dbname);
>>>>>>> b2a896997a298b341f1f3e3e62835f6d954a9725
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!doctype html>
<html lang="en">
<<<<<<< HEAD
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  </head>
  <body>

    <!-- NAVBAR HEADER -->
    <nav class="navbar">
      <div class="container-fluid">
        <a class="navbar-brand" style="color: black !important; padding-left: 40px;" href="#">
          <img src="media/lifeblood-logo.png" class="logoimg" alt=""> Blood Bank Managment System</a>
        <div class="nabarbtns text-light">
          <ul style="padding-inline: 40px; margin-bottom: 10px;">
            <li><a href="index.html" style="text-decoration: none; color:black; font-weight: 500; font-size: 1.2rem;">Home</a></li>
            <li><a href="registration.html" style="text-decoration: none; color:black;font-weight: 500; font-size: 1.2rem;">Donate</a></li>
            <li><a href="patientregistration.html" style="text-decoration: none; color:black; font-weight: 500; font-size: 1.2rem;">Patient</a></li>
            <li><a href="patientregistration.html" style="text-decoration: none; color:black; font-weight: 500; font-size: 1.2rem;">Blood Requests</a></li>
            <li id="signIn">
              <button style="background-color: rgb(228, 19, 19); font-weight: 600;" type="button" class="signInButton btn btn-outline-danger">
                <a href="signIn.html" style="text-decoration: none; color:white; font-weight: 500; font-size: 1.2rem;">Sign In</a>
              </button>
            </li>
            <li id="Profile" style="display: none;">
              <div style="width: 40px; height: 40px; display: flex; align-items: end; justify-content: center; border: 4px solid rgb(228, 19, 19); border-radius: 50%; font-size: 1.7rem; overflow: hidden;">
                <i class="fa-solid fa-user" style="color: red;"></i>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    

    <!-- ADMIN PANEL -->
    <div class="container my-5">
      <h2 class="text-center mb-4">Admin Dashboard</h2>

      <!-- Blood Requests -->
      <div class="card mb-4 shadow border-danger">
        <div class="card-header bg-danger text-white">
          <i class="fa-solid fa-droplet me-2"></i>Blood Requests
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr><th>#</th><th>Patient Name</th><th>Blood Group</th><th>Units</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php
              $res = $conn->query("SELECT * FROM blood_requests");
              while($row = $res->fetch_assoc()) {
                  echo "<tr>
                    <td>{$row'request_id'}</td>
                    <td>{$row'recipient_name'}</td>
                    <td>{$row'recipient_blood_group'}</td>
                    <td>{$row'units_required'}</td>
                    <td>{$row'patient_condition'}</td>
                    <td>{$row'request_status'}</td>
                     <td>{$row'request_date'}</td
                  </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Donors -->
      <div class="card mb-4 shadow border-danger">
        <div class="card-header bg-danger text-white">
          <i class="fa-solid fa-hand-holding-medical me-2"></i>Donors List
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr><th>#</th><th>Name</th><th>Blood Group</th><th>Last Donation</th></tr>
            </thead>
            <tbody>
              <?php
              $res = $conn->query("SELECT * FROM donor_details");
              while($row = $res->fetch_assoc()) {
                  echo "<tr>
                  <td>{$row'donor_name'}</td>
                  <td>{$row'donor_id'}</td>
                    <td>{$row'blood_number'}</td>
                    <td>{$row'donor_mail'}</td>
                     <td>{$row'donor_blood'}</td>
                  </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

   
    <!-- Scripts -->
    <script>
      const signInButton = document.getElementById('signIn');
      const profileButton = document.getElementById('Profile');
      signInButton.addEventListener('click', function () {
        signInButton.style.display = 'none';
        profileButton.style.display = 'block';
      });
    </script>
    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  </body>
=======
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel</title>
  <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <style>
      * {
      box-sizing: border-box;
    }
    .boddy{
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  
    .container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
    h2 {
      text-align: center;
      margin-bottom: 1rem;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 1rem;
      border: 1px solid #ddd;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background-color: #0056b3;
    }
    .toggle {
      text-align: center;
      margin-top: 1rem;
    }
    .error {
      color: red;
      margin-bottom: 1rem;
      text-align: center;
    }
    .success {
      color: green;
      margin-bottom: 1rem;
      text-align: center;
    }
   </style>
</head>
<body>

<!-- NAVBAR HEADER -->

<nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: black !important; padding-left: 40px;" href="#">
                <img src="media/lifeblood-logo.png" class="logoimg" alt=""> Blood Bank Managment System</a>
            <div class="nabarbtns  text-light">
                <ul style="padding-inline: 40px; margin-bottom: 10px; ">
                    <li ><a href="index.html" style="text-decoration: none; color:black; font-weight: 500; font-size: 1.2rem;">Home</a></li>
                    <li ><a href="registration.html" style="text-decoration: none; color:black;font-weight: 500; font-size: 1.2rem;">Donate</a></li>
                    <li ><a href="patientregistration.html" style="text-decoration: none; color:black; font-weight: 500; font-size: 1.2rem;">Patient</a></li>
                    <li ><a href="patientregistration.html" style="text-decoration: none; color:black; font-weight: 500; font-size: 1.2rem;">Blood Requests</a></li>
          
                    <li id="signIn">
                        <button style="background-color: rgb(228, 19, 19); font-weight: 600;" type="button" class="signInButton btn btn-outline-danger" ><a href="signIn.html" style="text-decoration: none; color:white; font-weight: 500; font-size: 1.2rem;">Sign In</a></button>
                    </li>
                    <li id="Profile" style="display: none;">
                      <div  style="width: 40px; height: 40px;  display: flex;
                      align-items: end;
                      justify-content: center; border: 4px solid rgb(228, 19, 19); border-radius: 50%; font-size: 1.7rem; overflow: hidden;">
                        <i class="fa-solid fa-user" style="color: red;"></i>
                      </div>
                    </li>

                </ul>
            </div>
        </div>
      </nav>
      <div class="navbar" style="margin-left: 60px; margin-right: 60px; border-top: 2px solid rgb(228, 19, 19);"></div>

      
      <div class="container">

      <h2 id="formTitle">Sign In</h2>
<form action="" method="post">
  <div id="message"></div>
  <input type="text" name="username" id="username" placeholder="Username" required />
  <input type="password" name="password" id="password" placeholder="Password" required />
  <button type="submit">Submit</button>
  <div class="toggle">
    <span id="toggleText">Only Admin have Access to Dashboard.</span>
  </div>
</form>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $password = $conn->real_escape_string($_POST['password']);

  $query = "SELECT * FROM admin_info WHERE admin_username = '$username' AND admin_password = '$password'";
  $result = $conn->query($query);


  echo "<script>
    document.getElementById('formTitle').style.display = 'block';
    if ($result->num_rows > 0) {
      document.getElementById('formTitle').style.display = 'none';
      document.getElementById('Boddy').style.display = 'block';
    } else {
      document.getElementById('formTitle').style.display = 'block';
      document.getElementById('Boddy').style.display = 'none';
    }
  </script>";
}
else {
  echo "<div class='error'>Invalid username or password. Please try again.</div>";
}
?>

    
  </div>

<!-- ADMIN PANEL -->
 <div id="Boddy" style="display: none;" class="boddy">
   
  
<div class="container my-5">
  <h2 class="text-center mb-4">Admin Dashboard</h2>

  <!-- Blood Requests -->
  <div class="card mb-4 shadow border-danger">
    <div class="card-header bg-danger text-white">
      <i class="fa-solid fa-droplet me-2"></i>Blood Requests
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr><th>#</th><th>Patient Name</th><th>Blood Group</th><th>Units</th><th>Status</th><th>Date</th></tr>
        </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM blood_request");
          while ($row = $res->fetch_assoc()) {
          ?>
            <tr>
              <td><?= $row['request_id']; ?></td>
              <td><?= $row['recipient_name']; ?></td>
              <td><?= $row['recipient_blood_group']; ?></td>
              <td><?= $row['units_required']; ?></td>
              <td><?= $row['request_status']; ?></td>
              <td><?= $row['request_date']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Donor Details -->
  <div class="card mb-4 shadow border-danger">
    <div class="card-header bg-danger text-white">
      <i class="fa-solid fa-hand-holding-medical me-2"></i>Donors List
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Number</th>
          <th>Blood Group</th>
        </tr>
        
      </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM donor_details");
          while ($row = $res->fetch_assoc()) {
            echo "<tr>
              <td>{$row['donor_id']}</td>
              <td>{$row['donor_name']}</td>
            
              <td>{$row['donor_number']}</td>
              <td>{$row['donor_blood']}</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Donor Details -->
  <div class="card mb-4 shadow border-danger">
    <div class="card-header bg-danger text-white">
      <i class="fa-solid fa-hand-holding-medical me-2"></i>Blood Invetory
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          
        <tr>
          <th>Inventory ID</th>
          <th>Blood Group</th>
          <th>Units Available</th>
          <th>Minimum Threshold</th>
          <th>Last Updated</th>
          <th>Expiry Date</th>
        </tr>
        
      </thead>
        <tbody>
          <?php
          $res = $conn->query("SELECT * FROM blood_inventory");
          while ($row = $res->fetch_assoc()) {
            echo "<tr>
              <td>{$row['inventory_id']}</td>
              <td>{$row['blood_group']}</td>
              <td>{$row['units_available']}</td>
              <td>{$row['minimum_threshold']}</td>
              <td>{$row['last_updated']}</td>
              <td>{$row['expiry_date']}</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>


</div>
<!-- Scripts -->
<script>
  const signInButton = document.getElementById('FormTitle');
  const profileButton = document.getElementById('Boddy');
 
  // const profileButton = document.getElementById('Profile');
  signInButton.addEventListener('click', function () {
    signInButton.style.display = 'none';
    profileButton.style.display = 'block';
  });
</script>
<script src="app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
>>>>>>> b2a896997a298b341f1f3e3e62835f6d954a9725
</html>
