<?php
include_once 'config/database.php';

function registerUser($data) {
    global $conn;
    
    $nama_lengkap = sanitize($data['nama_lengkap']);
    $email = sanitize($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $no_telepon = sanitize($data['no_telepon']);
    $alamat = sanitize($data['alamat']);
    $peran = sanitize($data['peran']);
    
    // Cek apakah email sudah ada
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        return false; // Email sudah terdaftar
    }
    
    $query = "INSERT INTO users (nama_lengkap, email, password_hash, no_telepon, alamat, peran) 
              VALUES ('$nama_lengkap', '$email', '$password', '$no_telepon', '$alamat', '$peran')";
    
    return mysqli_query($conn, $query);
}

function loginUser($email, $password) {
    global $conn;
    
    $email = sanitize($email);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['peran'] = $user['peran'];
            return true;
        }
    }
    return false;
}
?>