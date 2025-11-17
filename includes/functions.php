<?php
function esc($s, $mysqli = null)
{
    if ($mysqli) {
        return htmlspecialchars($mysqli->real_escape_string($s));
    }
    return htmlspecialchars($s);
}

function flash($msg)
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $_SESSION['flash'] = $msg;
}

function get_flash()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    if (!empty($_SESSION['flash'])) {
        $m = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $m;
    }
    return null;
}

function is_logged_in()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    return !empty($_SESSION['user']);
}

function current_user()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    return $_SESSION['user'] ?? null;
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: /Pet_Hotel/pages/login.php');
        exit;
    }
}

function require_role($roles = [])
{
    require_login();
    $u = current_user();
    if (!in_array($u['peran'], (array) $roles)) {
        http_response_code(403);
        echo "Akses ditolak.";
        exit;
    }
}
