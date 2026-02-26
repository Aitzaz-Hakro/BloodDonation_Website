<?php
require_once 'config.php';

$error = '';
$success = '';
$isSignUp = isset($_GET['signup']) ? true : false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $action = $_POST['action'] ?? 'signin';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill all fields.';
    } else {
        if ($action === 'signup') {
            // Sign Up - Register new user
            $name = sanitize($_POST['name'] ?? '');
            
            if (empty($name)) {
                $error = 'Please provide your name.';
            } else {
                // Check if email already exists
                $checkSql = "SELECT user_id FROM users WHERE email = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bind_param("s", $email);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                
                if ($checkResult->num_rows > 0) {
                    $error = 'Email already registered. Please sign in.';
                    $isSignUp = false;
                } else {
                    // Hash password and insert user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $insertSql = "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
                    $insertStmt = $conn->prepare($insertSql);
                    $insertStmt->bind_param("sss", $name, $email, $hashedPassword);
                    
                    if ($insertStmt->execute()) {
                        $success = 'Account created successfully! You can now sign in.';
                        $isSignUp = false;
                    } else {
                        $error = 'Registration failed. Please try again.';
                    }
                    $insertStmt->close();
                }
                $checkStmt->close();
            }
        } else {
            // Sign In - Check credentials
            $sql = "SELECT user_id, name, email, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['password'])) {
                    // Login successful
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    redirect('index.php');
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'Invalid email or password.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isSignUp ? 'Sign Up' : 'Sign In'; ?> - Blood Bank</title>
    <link rel="icon" type="image/svg" href="media/blood-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .boddy {
            min-height: calc(100vh - 100px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .auth-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #dc3545;
        }
        .auth-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        .auth-container input:focus {
            outline: none;
            border-color: #dc3545;
        }
        .auth-container button {
            width: 100%;
            padding: 12px;
            background-color: #dc3545;
            border: none;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .auth-container button:hover {
            background-color: #b02a37;
        }
        .toggle {
            text-align: center;
            margin-top: 1rem;
        }
        .toggle a {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
        }
        .toggle a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        #nameField {
            display: <?php echo $isSignUp ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="boddy">
    <div class="auth-container">
        <h2 id="formTitle"><?php echo $isSignUp ? 'Sign Up' : 'Sign In'; ?></h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="signIn.php">
            <input type="hidden" name="action" id="actionField" value="<?php echo $isSignUp ? 'signup' : 'signin'; ?>">
            
            <div id="nameField">
                <input type="text" name="name" placeholder="Full Name" <?php echo $isSignUp ? 'required' : ''; ?>>
            </div>
            
            <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" id="submitBtn"><?php echo $isSignUp ? 'Sign Up' : 'Sign In'; ?></button>
            
            <div class="toggle">
                <span id="toggleText"><?php echo $isSignUp ? 'Already have an account?' : "Don't have an account?"; ?></span>
                <a href="#" onclick="toggleForm(); return false;" id="toggleLink"><?php echo $isSignUp ? 'Sign In' : 'Sign Up'; ?></a>
            </div>
        </form>
    </div>
</div>

<script>
let isSignUp = <?php echo $isSignUp ? 'true' : 'false'; ?>;

function toggleForm() {
    isSignUp = !isSignUp;
    
    document.getElementById('formTitle').textContent = isSignUp ? 'Sign Up' : 'Sign In';
    document.getElementById('toggleText').textContent = isSignUp ? 'Already have an account?' : "Don't have an account?";
    document.getElementById('toggleLink').textContent = isSignUp ? 'Sign In' : 'Sign Up';
    document.getElementById('submitBtn').textContent = isSignUp ? 'Sign Up' : 'Sign In';
    document.getElementById('actionField').value = isSignUp ? 'signup' : 'signin';
    
    const nameField = document.getElementById('nameField');
    const nameInput = nameField.querySelector('input');
    
    if (isSignUp) {
        nameField.style.display = 'block';
        nameInput.required = true;
    } else {
        nameField.style.display = 'none';
        nameInput.required = false;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
