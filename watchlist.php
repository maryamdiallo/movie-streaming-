<?php
$pageTitle='Watchlist'; require __DIR__.'/includes/header.php'; $message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 check_post();
 if(logged_in()) { $id=filter_input(INPUT_POST,'watchlist_id',FILTER_VALIDATE_INT); if($id) { $s=$pdo->prepare('DELETE FROM watchlist WHERE watchlist_id=? AND user_id=?'); $s->execute([$id,$_SESSION['user_id']]); $message='Removed from watchlist.'; } }
}
?>
<section><h2>My Watchlist</h2><?php if(!logged_in()): ?><p><a href="account.php">Log in</a> to see your watchlist.</p><?php else: ?>
<?php if($message): ?><p class="message"><?= e($message) ?></p><?php endif; ?>
<?php $s=$pdo->prepare('SELECT w.watchlist_id,m.title,m.genre,m.rating FROM watchlist w JOIN movies m ON w.movie_id=m.movie_id WHERE w.user_id=? ORDER BY m.title'); $s->execute([$_SESSION['user_id']]); $rows=$s->fetchAll(); ?>
<?php foreach($rows as $r): ?><div class="movie-card"><h3><?= e($r['title']) ?></h3><p><?= e($r['genre']) ?> | <?= e($r['rating']) ?>/10</p><form method="post" data-confirm="Remove this movie from your watchlist?"><input type="hidden" name="token" value="<?= e(token()) ?>"><input type="hidden" name="watchlist_id" value="<?= (int)$r['watchlist_id'] ?>"><button>Remove</button></form></div><?php endforeach; ?><?php if(!$rows): ?><p>No saved movies yet.</p><?php endif; ?><?php endif; ?></section>
<?php require __DIR__.'/includes/footer.php'; ?>
