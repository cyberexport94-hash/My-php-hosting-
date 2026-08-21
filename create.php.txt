<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?status=error&msg=' . urlencode('Invalid request.'));
    exit;
}

$filename = trim($_POST['filename'] ?? '');
$type     = $_POST['type'] ?? '';
$code     = $_POST['code'] ?? '';

if ($filename === '' || $code === '') {
    header('Location: index.php?status=error&msg=' . urlencode('Filename aur code dono zaroori hain.'));
    exit;
}

if (!array_key_exists($type, CODE_TYPES)) {
    header('Location: index.php?status=error&msg=' . urlencode('Invalid code type.'));
    exit;
}

if (strlen($code) > MAX_CODE_SIZE) {
    header('Location: index.php?status=error&msg=' . urlencode('Code size limit (' . human_size(MAX_CODE_SIZE) . ') se zyada hai.'));
    exit;
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Filename ko safe banao (extension khud user ke .type ke hisaab se lagega)
$baseName = pathinfo($filename, PATHINFO_FILENAME);
$safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', $baseName);
if ($safeBase === '') {
    $safeBase = 'page';
}
$safeName = $safeBase . '_' . time() . mt_rand(100, 999) . '.' . $type;

$destination = UPLOAD_DIR . $safeName;

if (file_put_contents($destination, $code) !== false) {
    header('Location: index.php?status=success&count=1');
} else {
    header('Location: index.php?status=error&msg=' . urlencode('File save nahi ho saki.'));
}
exit;
