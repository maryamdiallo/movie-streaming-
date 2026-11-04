<?php
// Watch history: list what the signed-in user has watched (JOIN watch_history + movies) and allow clearing it.
$pageTitle = 'History';
require __DIR__ . '/includes/header.php';

$message = '';

// --- Clear all history for the current user only ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_post();

    if (logged_in()) {
        $stmt = $pdo->prepare('DELETE FROM watch_history WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $message = 'History cleared.';
    }
}
?>
<section>
    <h2>Watch History</h2>
    <?php if (!logged_in()): ?>
        <p><a href="account.php">Log in</a> to view history.</p>
    <?php else: ?>
        <?php if ($message): ?><p class="message"><?= e($message) ?></p><?php endif; ?>
        <?php
        // JOIN: connect each history row to its movie title, newest first
        $stmt = $pdo->prepare(
            'SELECT m.title, h.watched_at
             FROM watch_history h
             JOIN movies m ON h.movie_id = m.movie_id
             WHERE h.user_id = ?
             ORDER BY h.watched_at DESC, h.history_id DESC'
        );
        $stmt->execute([$_SESSION['user_id']]);
        $rows = $stmt->fetchAll();
        ?>
        <?php foreach ($rows as $r): ?>
            <p><?= e($r['title']) ?> — <?= e($r['watched_at']) ?></p>
        <?php endforeach; ?>
        <?php if (!$rows): ?><p>No viewing history yet.</p><?php endif; ?>

        <form method="post" data-confirm="Clear your entire watch history?">
            <input type="hidden" name="token" value="<?= e(token()) ?>">
            <button>Clear History</button>
        </form>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
