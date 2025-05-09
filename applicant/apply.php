<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'applicant') {
  header('Location: ../auth/login.php');
  exit;
}

$job_id = $_GET['job_id'] ?? null;
if (!$job_id) {
  die("Job ID not provided.");
}

$account_id = $_SESSION['account_id'];
$applicant_id = $conn->query("SELECT applicant_id FROM applicant WHERE account_id = $account_id")->fetch_assoc()['applicant_id'];

// check if user has already applied
$check = $conn->prepare("SELECT * FROM application WHERE applicant_id = ? AND job_id = ?");
$check->bind_param("ii", $applicant_id, $job_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
  $alreadyApplied = true;
} else {
  $apply = $conn->prepare("INSERT INTO application (applicant_id, job_id, status) VALUES (?, ?, 'Pending')");
  $apply->bind_param("ii", $applicant_id, $job_id);
  $apply->execute();
  $alreadyApplied = false;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Application Status</title>
  <link rel="stylesheet" href="../css/browse_jobs.css">
</head>
<body>

  <div class="confirmation-box">
    <?php if ($alreadyApplied): ?>
      <h1>You have already applied to this job.</h1>
    <?php else: ?>
      <h1>Application submitted successfully!</h1>
    <?php endif; ?>
    <a href="browse_jobs.php">Back to Job Listings</a>
  </div>

</body>
</html>
