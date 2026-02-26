<?php
// Common Header Include File
// Include this at the top of every page after config.php

// Get current page name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Determine base path for links (handles admin subfolder)
$current_dir = dirname($_SERVER['PHP_SELF']);
$base_path = '';
if (strpos($current_dir, '/admin') !== false) {
    $base_path = '../';
}
?>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $base_path; ?>index.php">
            <img src="<?php echo $base_path; ?>media/lifeblood-logo.png" class="logoimg" alt="Logo"> Blood Bank Management System
        </a>
        <button class="navbar-toggler" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="nabarbtns" id="navMenu">
            <ul>
                <li><a href="<?php echo $base_path; ?>index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo $base_path; ?>registration.php" class="nav-link <?php echo ($current_page == 'registration.php') ? 'active' : ''; ?>">Donate</a></li>
                <li><a href="<?php echo $base_path; ?>bloodRequest.php" class="nav-link <?php echo ($current_page == 'bloodRequest.php') ? 'active' : ''; ?>">Blood Requests</a></li>
                <li><a href="<?php echo $base_path; ?>request_blood.php" class="nav-link <?php echo ($current_page == 'request_blood.php') ? 'active' : ''; ?>">Request Blood</a></li>
                <li><a href="<?php echo $base_path; ?>adminPanel.php" class="nav-link <?php echo ($current_page == 'adminPanel.php') ? 'active' : ''; ?>">Admin Panel</a></li>
                <?php if (isLoggedIn()): ?>
                    <li>
                        <span class="nav-link" style="color: #28a745;">
                            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                    </li>
                    <li>
                        <a href="<?php echo $base_path; ?>logout.php" class="signInButton" style="background-color: #6c757d !important;">Logout</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo $base_path; ?>signIn.php" class="signInButton">Sign In</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="navbar-divider"></div>

<script>
function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('show');
}
</script>
