<?php
// admission-system/index.php

include('includes/db.php');
include('includes/auth.php');

$page_title = "Login - Admission Management System";
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);
    
    // Query user
    $sql = "SELECT id, username, password, role FROM users WHERE username = '$username' AND role = '$role'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (verifyPassword($password, $user['password'])) {
            loginUser($user['id'], $user['username'], $user['role']);
            
            // Redirect based on role
            switch($user['role']) {
                case 'Admin':
                    header("Location: dashboard/admin.php");
                    break;
                case 'Support Staff':
                    header("Location: dashboard/support.php");
                    break;
                case 'Counselor':
                    header("Location: dashboard/counselor_dashboard.php");
                    break;
                case 'Cashier':
                    header("Location: dashboard/cashier.php");
                    break;
                case 'Management':
                    header("Location: dashboard/management.php");
                    break;
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admission Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header .icon-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
        }
        
        .login-header h1 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2rem;
        }
        
        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15);
            outline: none;
        }
        
        .role-selection {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 2px solid #ecf0f1;
        }
        
        .form-check {
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .form-check:hover {
            background: white;
        }
        
        .form-check-input {
            cursor: pointer;
            width: 1.25em;
            height: 1.25em;
            margin-top: 0.1em;
            border: 2px solid #667eea;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            cursor: pointer;
            margin-left: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .alert {
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
            border-radius: 8px;
        }
        
        .demo-credentials {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .demo-credentials h6 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .demo-credentials p {
            margin: 5px 0;
            color: #666;
            font-size: 0.85rem;
        }
        
        .demo-credentials code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            color: #667eea;
            font-weight: 600;
        }
        #logo{
            width: 100%;
            height: auto;
            border-radius: 40%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- LOGIN HEADER -->
        <div class="login-header">
            <div class="icon-box">
                <img id=logo  src="sec.jpg">
            </div>
            <h1>Admission Portal</h1>
            <p>College Admission Management System</p>
        </div>

        <!-- ERROR ALERT -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form method="POST" action="">

            <select class="form-select" name="role" required>
    <option value="" disabled selected>-- Select Role --</option>
    <option value="Admin">Admin - Full System Access</option>
    <option value="Support Staff">Support Staff - Register Students</option>
    <option value="Counselor">Counselor - Manage Admissions</option>
    <option value="Cashier">Cashier - Handle Payments</option>
    <option value="Management">Management - View Reports</option>
</select>
            <!-- USERNAME FIELD -->
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <!-- PASSWORD FIELD -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>

           

            <!-- LOGIN BUTTON -->
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

      

    <!-- FORM VALIDATION SCRIPT -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const roleSelect = document.querySelector('select[name="role"]');
            const role = roleSelect.value;
            
            if (!username || !password || !role) {
                e.preventDefault();
                alert('Please fill all fields and select a role!');
                return false;
            }
        });
    </script>
</body>
</html>