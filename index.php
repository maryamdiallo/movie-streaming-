<?php $pageTitle='Home'; require __DIR__.'/includes/header.php';
$movieCount=(int)$pdo->query('SELECT COUNT(*) FROM movies')->fetchColumn();
$plan='None'; $saved=0; $watched=0;
if (logged_in()) {
 $s=$pdo->prepare('SELECT s.plan_name FROM user_subscriptions us JOIN subscriptions s ON us.subscription_id=s.subscription_id WHERE us.user_id=? ORDER BY us.start_date DESC,us.user_subscription_id DESC LIMIT 1'); $s->execute([$_SESSION['user_id']]); $plan=$s->fetchColumn() ?: 'None';
 $s=$pdo->prepare('SELECT COUNT(*) FROM watchlist WHERE user_id=?'); $s->execute([$_SESSION['user_id']]); $saved=(int)$s->fetchColumn();
 $s=$pdo->prepare('SELECT COUNT(*) FROM watch_history WHERE user_id=?'); $s->execute([$_SESSION['user_id']]); $watched=(int)$s->fetchColumn();
}
?>
<section class="hero"><h2>Movies, shows, and entertainment</h2><p>Browse <?= $movieCount ?> movies, save favorites, and explore subscriptions.</p><a class="main-link" href="movies.php">Explore Movies</a></section>
<section><h2>My Dashboard</h2><div class="dashboard-card">
<?php if(logged_in()): ?><p>Welcome, <?= e($_SESSION['first_name']) ?>!</p><p><strong>Plan:</strong> <?= e($plan) ?></p><p><strong>Watchlist:</strong> <?= $saved ?></p><p><strong>Watched:</strong> <?= $watched ?></p>
<?php else: ?><p><a href="account.php">Log in</a> to view your dashboard.</p><?php endif; ?>
</div></section><?php require __DIR__.'/includes/footer.php'; ?>
