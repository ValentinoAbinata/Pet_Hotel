<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!empty($_SESSION['user'])) {
    $uid = (int) ($_SESSION['user']['user_id'] ?? 0);
    if ($uid > 0) {
        $res = $mysqli->query("SELECT user_id,nama_lengkap,email,no_telepon,alamat,peran,created_at FROM users WHERE user_id = $uid");
        if ($res && $res->num_rows) {
            $_SESSION['user'] = $res->fetch_assoc();
        } else {
            unset($_SESSION['user']);
        }
    } else {
        unset($_SESSION['user']);
    }
}
