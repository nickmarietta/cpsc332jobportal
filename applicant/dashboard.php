<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'applicant') {
  header('Location: ../auth/login.php');
  exit;
}

$account_id = $_SESSION['account_id'];
$stmt = $conn->prepare("SELECT applicant_id, first_name FROM applicant WHERE account_id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$applicant = $stmt->get_result()->fetch_assoc();

if (!$applicant) {
  die("Applicant not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Applicant Dashboard</title>
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($applicant['first_name']); ?>!</h1>

  <nav>
    <a href="browse_jobs.php">Browse Jobs</a> 
    <a href="applications.php">My Applications</a> 
    <a href="../auth/logout.php">Logout</a>
  </nav>
</body>
</html>