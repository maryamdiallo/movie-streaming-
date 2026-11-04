<?php
$pageTitle='Movies'; require __DIR__.'/includes/header.php'; $message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 check_post();
 if (!logged_in()) $message='Log in to save or mark movies watched.';
 else {
  $movieId=filter_input(INPUT_POST,'movie_id',FILTER_VALIDATE_INT);
  $s=$pdo->prepare('SELECT movie_id FROM movies WHERE movie_id=?'); $s->execute([$movieId]);
  if (!$movieId || !$s->fetch()) $message='Invalid movie.';
  elseif (($_POST['action'] ?? '')==='save') { $s=$pdo->prepare('INSERT IGNORE INTO watchlist(user_id,movie_id) VALUES (?,?)'); $s->execute([$_SESSION['user_id'],$movieId]); $message='Added to watchlist.'; }
  elseif (($_POST['action'] ?? '')==='watched') { $s=$pdo->prepare('INSERT INTO watch_history(user_id,movie_id) VALUES (?,?)'); $s->execute([$_SESSION['user_id'],$movieId]); $message='Added to watch history.'; }
 }
}
$search=trim($_GET['search'] ?? ''); $genre=trim($_GET['genre'] ?? '');
$genres=$pdo->query('SELECT DISTINCT genre FROM movies ORDER BY genre')->fetchAll(PDO::FETCH_COLUMN);
$sql='SELECT movie_id,title,genre,release_year,rating FROM movies WHERE title LIKE ?'; $params=['%'.$search.'%'];
if ($genre !== '' && in_array($genre,$genres,true)) { $sql.=' AND genre=?'; $params[]=$genre; }
$sql.=' ORDER BY title'; $s=$pdo->prepare($sql); $s->execute($params); $movies=$s->fetchAll();
?>
<section><h2>Browse Movies</h2><?php if($message): ?><p class="message"><?= e($message) ?></p><?php endif; ?>
<form method="get" class="search-area"><label>Search <input name="search" value="<?= e($search) ?>"></label><label>Genre <select name="genre"><option value="">All genres</option><?php foreach($genres as $g): ?><option value="<?= e($g) ?>" <?= $g===$genre?'selected':'' ?>><?= e($g) ?></option><?php endforeach; ?></select></label><button>Search</button></form><p id="liveSearchStatus" aria-live="polite"></p>
<div class="movie-list" id="movieList"><?php foreach($movies as $movie): ?><div class="movie-card" data-title="<?= e($movie['title']) ?>" data-genre="<?= e($movie['genre']) ?>"><h3><?= e($movie['title']) ?></h3><p><?= e($movie['genre']) ?> | <?= (int)$movie['release_year'] ?> | <?= e($movie['rating']) ?>/10</p>
<form method="post"><input type="hidden" name="token" value="<?= e(token()) ?>"><input type="hidden" name="movie_id" value="<?= (int)$movie['movie_id'] ?>"><button name="action" value="save">Save to Watchlist</button> <button name="action" value="watched">Mark Watched</button></form></div><?php endforeach; ?></div><?php if(!$movies): ?><p>No movies found.</p><?php endif; ?></section>
<?php require __DIR__.'/includes/footer.php'; ?>
