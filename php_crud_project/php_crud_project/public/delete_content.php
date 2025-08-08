<?php
require 'config.php';
if(!isset($_SESSION['user_id'])){ header('Location: index.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare('DELETE FROM content WHERE id = ?')->execute([$id]);
header('Location: about.php');
exit;
