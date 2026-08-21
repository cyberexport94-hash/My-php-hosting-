<?php
require_once __DIR__ . '/config.php';

$file = $_GET['file'] ?? '';
$key  = $_GET['key'] ?? '';

$file = basename($file); // path traversal se bachne ke liye
$path = UPLOAD_DIR . $file;

if ($key !== ADMIN_KEY) {
    header('Location: index.php?status=error&msg=' . urlencode('Galat admin key.'));
    exit;
}

if ($file !== '' && file_exists($path)) {
    unlink($path);
    header('Location: index.php?status=success&msg=' . urlencode('File delete ho gayi.'));
} else {
    header('Location: index.php?status=error&msg=' . urlencode('File nahi mili.'));
}
exit;
