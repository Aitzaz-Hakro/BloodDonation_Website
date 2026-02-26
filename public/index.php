<?php
require_once 'config.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blood Bank Management System</title>
    <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .bigtext {
            animation: FadeIn 0.5s ease-in;
        }
        @keyframes FadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0px); }
        }
        .hero-section {
            display: flex;
            flex-wrap: wrap;
            min-height: 80vh;
            background-color: #f8f9fa;
        }
        .hero-text {
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }
        .hero-image {
            flex: 1;
            min-width: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .hero-image img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- HERO SECTION -->
<div class="hero-section">
    <div class="hero-text">
        <div class="bigtext">
            <span>Donate Blood, <br> Save Lives</span>
        </div>
        <div class="btnn mt-4">
            <a href="registration.php" class="btn btn-danger btn-lg" style="background-color: rgb(228, 19, 19); color: white !important; font-weight: 600;">Get Started</a>
        </div>
    </div>
    <div class="hero-image">
        <img src="media/blood-donation-illustration.png" alt="Blood Donation" onerror="this.style.display='none'">
    </div>
</div>

<!-- ABOUT US SECTION -->
<div class="about-us-section text-center py-5">
    <h1 style="font-weight: bold;">About Us</h1>
    <p class="mt-3" style="font-weight: 500; text-align: center; max-width: 800px; margin: 0 auto; font-size: 1.2rem; padding: 0 1rem;">
        Welcome to the Blood Bank Management System. Our mission is to connect donors with those in need, ensuring a seamless and efficient process for blood donation. We strive to save lives by promoting the importance of blood donation and making it accessible to everyone. Join us in making a difference today.
    </p>
</div>

<!-- STATS SECTION -->
<?php
// Get stats from database
$totalDonors = 0;
$totalRequests = 0;

$donorCountQuery = "SELECT COUNT(*) as count FROM donor_details";
$donorResult = $conn->query($donorCountQuery);
if ($donorResult && $row = $donorResult->fetch_assoc()) {
    $totalDonors = $row['count'];
}

$requestCountQuery = "SELECT COUNT(*) as count FROM blood_requests";
$requestResult = $conn->query($requestCountQuery);
if ($requestResult && $row = $requestResult->fetch_assoc()) {
    $totalRequests = $row['count'];
}
?>
<section class="stats-section">
    <div class="stat-card">
        <div class="circle"><?php echo $totalDonors > 200 ? $totalDonors . '+' : '200+'; ?></div>
        <p>Total Donors</p>
    </div>
    <div class="stat-card">
        <div class="circle">90</div>
        <p>Active Donors</p>
    </div>
    <div class="stat-card">
        <div class="circle"><?php echo $totalRequests > 0 ? $totalRequests : '800'; ?></div>
        <p>Blood Requests</p>
    </div>
    <div class="stat-card">
        <div class="circle">36</div>
        <p>Cities Covered</p>
    </div>
</section>

<!-- WHY DONATE SECTION -->
<div class="about-us-section text-center" style="margin-top: 90px; margin-bottom: 90px;">
    <h1 style="font-weight: bold;">Why Donate Blood?</h1>
    <div class="row justify-content-center mt-5" style="padding: 0 1rem;">
        <div class="col-md-3 mb-4">
            <div class="card text-center h-100">
                <i class="fa-solid fa-heartbeat mt-3" style="font-size: 4.5rem; color: rgb(228, 19, 19);"></i>
                <div class="card-body">
                    <h4 class="card-title">Save Lives</h4>
                    <p class="card-text">Your blood donation can save lives and bring hope to those in need.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center h-100">
                <i class="fa-solid fa-stethoscope mt-3" style="font-size: 4.5rem; color: rgb(228, 19, 19);"></i>
                <div class="card-body">
                    <h4 class="card-title">Free Checkup</h4>
                    <p class="card-text">Get a free health checkup when you donate blood. Your health matters to us.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center h-100">
                <i class="fa-solid fa-users mt-3" style="color: rgb(228, 19, 19); font-size: 4.5rem;"></i>
                <div class="card-body">
                    <h4 class="card-title">Community Support</h4>
                    <p class="card-text">Join a community of donors and make a collective impact in saving lives.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTACT SECTION -->
<div class="about-us-section text-center mt-5 mb-5 pt-5" style="margin-left: 60px; margin-right: 60px; border-top: 2px solid crimson;">
    <h1 style="font-weight: bold;">Contact Us</h1>
    <p class="mt-3" style="font-weight: 500; text-align: center; max-width: 800px; margin: 0 auto; font-size: 1.2rem;">
        Location: Doctor Lane, Saddar, Hyderabad, Pakistan<br>
        Phone: <span style="border-right: 2px solid black; border-left: 2px solid black; padding: 0 10px;">+92 304 3005127</span>
        <span style="padding-left: 10px;">+92 304 3005127</span><br>
        Email: <a href="mailto:aitzazhakro123@gmail.com">aitzazhakro123@gmail.com</a>
        <a href="mailto:hammadshah18@gmail.com">hammadshah18@gmail.com</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
