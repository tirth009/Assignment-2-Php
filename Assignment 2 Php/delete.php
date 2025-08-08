<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    exit('Item not found.');
}

if ($item['user_id'] != current_user_id()) {
    exit('Unauthorized.');
}

// Delete DB row
$stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
$stmt->execute([$id]);

// Remove uploaded image if exists
if (!empty($item['image']) && file_exists(__DIR__ . '/uploads/' . $item['image'])) {
    @unlink(__DIR__ . '/uploads/' . $item['image']);
}

header('Location: index.php');
exit;
