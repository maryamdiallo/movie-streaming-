<?php
$pageTitle='History'; require __DIR__.'/includes/header.php'; $message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 check_post(); if(logged_in()) { $s=$pdo->prepare('DELETE FROM watch_history WHERE user_id=?'); $s->execute([$_SESSION['user_id']]); $message='History cleared.'; }
}
?>
<section><h2>Watch History</h2><?php if(!logged_in()): ?><p><a href="account.php">Log in</a> to view history.</p><?php else: ?>
<?php if($message): ?><p class="message"><?= e($message) ?></p><?php endif; ?>
<?php $s=$pdo->prepare('SELECT m.title,h.watched_at FROM watch_history h JOIN movies m ON h.movie_id=m.movie_id WHERE h.user_id=? ORDER BY h.watched_at DESC,h.history_id DESC'); $s->execute([$_SESSION['user_id']]); $rows=$s->fetchAll(); ?>
<?php foreach($rows as $r): ?><p><?= e($r['title']) ?> — <?= e($r['watched_at']) ?></p><?php endforeach; ?><?php if(!$rows): ?><p>No viewing history yet.</p><?php endif; ?>
<form method="post" data-confirm="Clear your entire watch history?"><input type="hidden" name="token" value="<?= e(token()) ?>"><button>Clear History</button></form><?php endif; ?></section>
<?php require __DIR__.'/includes/footer.php'; ?>
