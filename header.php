<?php
session_start();
require_once __DIR__ . '/../db.php';
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function token() { if (empty($_SESSION['token'])) $_SESSION['token'] = bin2hex(random_bytes(16)); return $_SESSION['token']; }
function check_post() { if (!hash_equals(token(), $_POST['token'] ?? '')) { http_response_code(403); exit('Invalid form. Please refresh.'); } }
function logged_in() { return isset($_SESSION['user_id']); }
$page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($pageTitle ?? 'StreamZone') ?> | StreamZone</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1><a class="brand" href="index.php">Stream<span>Zone</span></a></h1><nav aria-label="Main navigation">
<?php foreach (['index.php'=>'Home','movies.php'=>'Movies','plans.php'=>'Plans','watchlist.php'=>'Watchlist','history.php'=>'History','account.php'=>'Account'] as $file=>$name): ?>
<a href="<?= e($file) ?>" <?= $page === $file ? 'aria-current="page"' : '' ?>><?= e($name) ?></a>
<?php endforeach; ?></nav></header><main>
