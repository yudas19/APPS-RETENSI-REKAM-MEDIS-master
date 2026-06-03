<?php
session_start();
require_once __DIR__ . '/config/functions.php';

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Cek akses ke halaman yang butuh login
$public_pages = ['login', 'logout']; // halaman yang tidak perlu login

if (!in_array($page, $public_pages) && !isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

switch ($page) {
    case 'login':
        include 'pages/login.php';
        break;
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'form':
        include 'pages/form.php';
        break;
    case 'detail':
        include 'pages/detail.php';
        break;
    case 'delete':
        include 'pages/delete.php';
        break;
    case 'logout':
        logout();
        break;
    default:
        include 'pages/login.php';
        break;
}
?>