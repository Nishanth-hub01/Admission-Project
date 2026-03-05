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
                    header("Location: dashboard/counselor.php");
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
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .role-selection {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-check {
            margin-bottom: 10px;
        }
        .form-check-input {
            cursor: pointer;
        }
        .form-check-label {
            cursor: pointer;
            margin-left: 5px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px;
            font-weight: 500;
            border-radius: 5px;
            width: 100%;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-graduation-cap"></i></h1>
            <h1>Admission Portal</h1>
            <p>Management System</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label class="form-label">Select Role</label>
                <div class="role-selection">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="admin" value="Admin" required>
                        <label class="form-check-label" for="admin">
                            <i class="fas fa-user-shield"></i> Admin
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="support" value="Support Staff">
                        <label class="form-check-label" for="support">
                            <i class="fas fa-headset"></i> Support Staff
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="counselor" value="Counselor">
                        <label class="form-check-label" for="counselor">
                            <i class="fas fa-user-tie"></i> Counselor
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="cashier" value="Cashier">
                        <label class="form-check-label" for="cashier">
                            <i class="fas fa-cash-register"></i> Cashier
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="management" value="Management">
                        <label class="form-check-label" for="management">
                            <i class="fas fa-chart-line"></i> Management
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-login">Login</button>
        </form>

        <hr>
        <div style="text-align: center; font-size: 12px; color: #999;">
            <p>Demo Credentials:<br>
            Username: <strong>admin</strong><br>
            Password: <strong>password123</strong></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>