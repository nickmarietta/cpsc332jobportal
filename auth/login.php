<?php
session_start();
require '../includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM account WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['account_id'] = $user['account_id'];
    $_SESSION['role'] = $user['role'];
    
    if ($user['role'] === 'applicant') {
      header("Location: ../applicant/dashboard.php");
    } else {
      header("Location: ../employer/dashboard.php");
    }
    exit;
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="login.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Login</title>
</head>
<body>
  <header>
    <div id="navigation" class="obj-width">
      <a href="index.php"><img class="logo" src="images/logo.png" alt=""></a>
      <ul id="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Job List</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <i id="menubar" class="bx bx-menu"></i>
    </div>
  </header>

  <section class="main">
    <div class="mainbox obj-width">
      <div class="h-left">
        <h2>Log in to your account</h2>
        <?php if ($error): ?>
          <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php" class="login-sect">
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
        </form>
        <a href="register.php">Sign up</a>
      </div>
    </div>
  </section>
</body>
</html>


