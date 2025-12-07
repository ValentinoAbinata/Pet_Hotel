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

// --- FITUR NOTIFIKASI ---

// Fungsi kirim notifikasi (Sesuaikan dengan tabel database kamu)
function send_notification($user_id, $judul, $pesan, $link = '#')
{
    global $mysqli;
    $user_id = (int) $user_id;
    $judul = $mysqli->real_escape_string($judul);
    $pesan = $mysqli->real_escape_string($pesan);
    $link = $mysqli->real_escape_string($link);

    // Default status_baca = 0 (Belum dibaca)
    $sql = "INSERT INTO notifikasi (user_id, judul, pesan, link_url, status_baca, created_at) 
            VALUES ($user_id, '$judul', '$pesan', '$link', 0, NOW())";
    
    return $mysqli->query($sql);
}

// Hitung notifikasi yang belum dibaca (status_baca = 0)
function count_unread_notif($user_id)
{
    global $mysqli;
    $user_id = (int)$user_id;
    $res = $mysqli->query("SELECT COUNT(*) as total FROM notifikasi WHERE user_id = $user_id AND status_baca = 0");
    if ($res) {
        return $res->fetch_assoc()['total'];
    }
    return 0;
}

// Ambil daftar notifikasi terbaru
function get_my_notif($user_id, $limit = 5)
{
    global $mysqli;
    $user_id = (int)$user_id;
    return $mysqli->query("SELECT * FROM notifikasi WHERE user_id = $user_id ORDER BY created_at DESC LIMIT $limit");
}

// Tandai notifikasi sudah dibaca (Opsional, dipanggil saat user klik)
function mark_notif_read($notif_id) {
    global $mysqli;
    $nid = (int)$notif_id;
    $mysqli->query("UPDATE notifikasi SET status_baca = 1 WHERE notifikasi_id = $nid");
}