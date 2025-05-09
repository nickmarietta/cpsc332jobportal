<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Job Portal</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Welcome to the Job Portal</h1>

  <?php if (isset($_SESSION['account_id'])): ?>
    <p>You are logged in as <?php echo htmlspecialchars($_SESSION['role']); ?>.</p>
    <a href="<?php echo $_SESSION['role'] === 'applicant' ? 'applicant/dashboard.php' : 'employer/dashboard.php'; ?>">Go to Dashboard</a>
    <a href="auth/logout.php">Logout</a>
  <?php else: ?>
    <a href="auth/login.php">Login</a> | <a href="auth/register.php">Register</a>
  <?php endif; ?>
</body>
</html>