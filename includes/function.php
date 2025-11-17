<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function hasRole($role) {
    return isset($_SESSION['peran']) && $_SESSION['peran'] === $role;
}

// Tambahkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>