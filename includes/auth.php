<?php
// admission-system/includes/auth.php

session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../index.php");
        exit();
    }
}

// Check user role
function userRole() {
    return $_SESSION['user_role'] ?? null;
}

// Require specific role
function requireRole($roles) {
    requireLogin();
    
    if (!is_array($roles)) {
        $roles = array($roles);
    }
    
    if (!in_array(userRole(), $roles)) {
        header("Location: ../index.php?error=Unauthorized Access");
        exit();
    }
}

// Login user
function loginUser($userId, $username, $role) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['user_role'] = $role;
    $_SESSION['login_time'] = time();
}

// Logout user
function logoutUser() {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>