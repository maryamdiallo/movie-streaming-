<?php
$pageTitle='Account'; require __DIR__.'/includes/header.php';
$error=''; $success='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 check_post(); $action=$_POST['action'] ?? '';
 if ($action==='logout') { $_SESSION=[]; session_regenerate_id(true); header('Location: account.php'); exit; }
 if ($action==='register') {
  $name=trim($_POST['first_name'] ?? ''); $last=trim($_POST['last_name'] ?? ''); $email=trim($_POST['email'] ?? ''); $pass=$_POST['password'] ?? '';
  if (!$name || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pass)<8) $error='Enter your first name, a valid email and a password of at least 8 characters.';
  else {
   try { $s=$pdo->prepare('INSERT INTO users (first_name,last_name,email,password) VALUES (?,?,?,?)'); $s->execute([$name,$last,$email,password_hash($pass,PASSWORD_DEFAULT)]); $success='Account created. You can now log in.'; }
   catch (PDOException $ex) { if ($ex->getCode()==='23000') $error='Email already registered.'; else { error_log($ex->getMessage()); $error='Could not register.'; } }
  }
 }
 if ($action==='login') {
  $s=$pdo->prepare('SELECT user_id,first_name,password FROM users WHERE email=?'); $s->execute([trim($_POST['email'] ?? '')]); $user=$s->fetch();
  if ($user && password_verify($_POST['password'] ?? '',$user['password'])) { session_regenerate_id(true); $_SESSION['user_id']=(int)$user['user_id']; $_SESSION['first_name']=$user['first_name']; header('Location: index.php'); exit; }
  else $error='Invalid email or password.';
 }
}
?>
<section><h2>My Account</h2><?php if($error): ?><p class="message"><?= e($error) ?></p><?php endif; ?><?php if($success): ?><p><?= e($success) ?></p><?php endif; ?>
<?php if(logged_in()): ?><p>Signed in as <?= e($_SESSION['first_name']) ?>.</p><form method="post"><input type="hidden" name="token" value="<?= e(token()) ?>"><button name="action" value="logout">Log out</button></form>
<?php else: ?><div class="account-layout"><div class="form-card"><h3>Login</h3><form method="post"><input type="hidden" name="token" value="<?= e(token()) ?>"><label>Email <input type="email" name="email" required></label><label>Password <input type="password" name="password" required></label><button name="action" value="login">Login</button></form></div>
<div class="form-card"><h3>Create an Account</h3><form method="post" id="registerForm"><input type="hidden" name="token" value="<?= e(token()) ?>"><label>First name <input name="first_name" required></label><label>Last name <input name="last_name"></label><label>Email <input type="email" name="email" required></label><label>Password (8+ characters) <input type="password" name="password" minlength="8" required></label><button name="action" value="register">Register</button></form></div></div><?php endif; ?></section>
<?php require __DIR__.'/includes/footer.php'; ?>
