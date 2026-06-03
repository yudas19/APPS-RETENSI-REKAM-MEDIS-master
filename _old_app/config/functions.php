<?php

require_once 'database.php';

// Login function
function login($username, $password) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Verifikasi password (plain dulu, nanti hash)
        if ($password == 'admin123') { // sementara, nanti hash
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

// Cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Logout
function logout() {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Get all berkas
function getAllBerkas($status = null) {
    global $conn;
    $query = "SELECT * FROM berkas";
    if ($status) {
        $status = mysqli_real_escape_string($conn, $status);
        $query .= " WHERE status = '$status'";
    }
    $query .= " ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Get single berkas
function getBerkas($id) {
    global $conn;
    $id = (int)$id;
    $query = "SELECT * FROM berkas WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Create berkas
function createBerkas($data) {
    global $conn;
    $no_rm = mysqli_real_escape_string($conn, $data['no_rm']);
    $nama_pasien = mysqli_real_escape_string($conn, $data['nama_pasien']);
    $tgl_lahir = !empty($data['tgl_lahir']) ? "'" . mysqli_real_escape_string($conn, $data['tgl_lahir']) . "'" : "NULL";
    $nama_berkas = mysqli_real_escape_string($conn, $data['nama_berkas'] ?? '');
    $file_pdf = mysqli_real_escape_string($conn, $data['file_pdf'] ?? '');
    $status = mysqli_real_escape_string($conn, $data['status']);
    $tgl_retensi = !empty($data['tgl_retensi']) ? "'" . mysqli_real_escape_string($conn, $data['tgl_retensi']) . "'" : "NULL";
    $keterangan = mysqli_real_escape_string($conn, $data['keterangan'] ?? '');
    $created_by = $_SESSION['user_id'] ?? 1;
    
    $query = "INSERT INTO berkas (no_rm, nama_pasien, tgl_lahir, nama_berkas, file_pdf, status, tgl_retensi, keterangan, created_by) 
              VALUES ('$no_rm', '$nama_pasien', $tgl_lahir, '$nama_berkas', '$file_pdf', '$status', $tgl_retensi, '$keterangan', $created_by)";
    
    return mysqli_query($conn, $query);
}

// Update berkas
function updateBerkas($id, $data) {
    global $conn;
    $id = (int)$id;
    $no_rm = mysqli_real_escape_string($conn, $data['no_rm']);
    $nama_pasien = mysqli_real_escape_string($conn, $data['nama_pasien']);
    $tgl_lahir = !empty($data['tgl_lahir']) ? "'" . mysqli_real_escape_string($conn, $data['tgl_lahir']) . "'" : "NULL";
    $nama_berkas = mysqli_real_escape_string($conn, $data['nama_berkas'] ?? '');
    $file_pdf = mysqli_real_escape_string($conn, $data['file_pdf'] ?? '');
    $status = mysqli_real_escape_string($conn, $data['status']);
    $tgl_retensi = !empty($data['tgl_retensi']) ? "'" . mysqli_real_escape_string($conn, $data['tgl_retensi']) . "'" : "NULL";
    $keterangan = mysqli_real_escape_string($conn, $data['keterangan'] ?? '');
    
    $query = "UPDATE berkas SET 
              no_rm = '$no_rm',
              nama_pasien = '$nama_pasien',
              tgl_lahir = $tgl_lahir,
              nama_berkas = '$nama_berkas',
              file_pdf = '$file_pdf',
              status = '$status',
              tgl_retensi = $tgl_retensi,
              keterangan = '$keterangan'
              WHERE id = $id";
    
    return mysqli_query($conn, $query);
}

// Delete berkas
function deleteBerkas($id) {
    global $conn;
    $id = (int)$id;
    
    // Ambil info file untuk dihapus
    $query = "SELECT file_pdf FROM berkas WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row && $row['file_pdf']) {
        $file_path = '../uploads/berkas/' . $row['file_pdf'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $query = "DELETE FROM berkas WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Upload file
function uploadFile($file) {
    $target_dir = "../uploads/berkas/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . '_' . basename($file['name']);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Cek PDF
    if ($file_type != "pdf") {
        return false;
    }
    
    // Cek size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $file_name;
    }
    
    return false;
}

// Hitung statistik
function getStats() {
    global $conn;
    $stats = [];
    
    $query = "SELECT status, COUNT(*) as total FROM berkas GROUP BY status";
    $result = mysqli_query($conn, $query);
    
    $stats = ['Aktif' => 0, 'Inaktif' => 0, 'Musnah' => 0];
    while ($row = mysqli_fetch_assoc($result)) {
        $stats[$row['status']] = $row['total'];
    }
    
    return $stats;
}
?>