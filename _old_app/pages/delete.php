<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    deleteBerkas($id);
}

header('Location: index.php?page=dashboard&success=Data berhasil dihapus!');
exit;
?>