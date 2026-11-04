<?php
$pageTitle='Plans'; require __DIR__.'/includes/header.php'; $message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 check_post();
 if (!logged_in()) $message='Please log in before choosing a plan.';
 else {
  $id=filter_input(INPUT_POST,'subscription_id',FILTER_VALIDATE_INT);
  $s=$pdo->prepare('SELECT subscription_id FROM subscriptions WHERE subscription_id=?'); $s->execute([$id]);
  if (!$id || !$s->fetch()) $message='Invalid plan.';
  else { $s=$pdo->prepare('INSERT INTO user_subscriptions(user_id,subscription_id,start_date) VALUES (?,?,CURDATE())'); $s->execute([$_SESSION['user_id'],$id]); $message='Your plan has been saved.'; }
 }
}
$plans=$pdo->query('SELECT * FROM subscriptions ORDER BY price')->fetchAll();
?>
<section><h2>Subscription Plans</h2><p>Demonstration only. No real payments or streaming.</p><?php if($message): ?><p class="message"><?= e($message) ?></p><?php endif; ?><div class="plan-list">
<?php foreach($plans as $plan): ?><div class="plan"><h3><?= e($plan['plan_name']) ?></h3><p class="price">$<?= number_format((float)$plan['price'],2) ?> / month</p><form method="post"><input type="hidden" name="token" value="<?= e(token()) ?>"><input type="hidden" name="subscription_id" value="<?= (int)$plan['subscription_id'] ?>"><button>Choose Plan</button></form></div><?php endforeach; ?></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
