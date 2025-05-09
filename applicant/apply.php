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

// check if user has already applied to job
$check = $conn->prepare("SELECT * FROM application WHERE applicant_id = ? AND job_id = ?");
$check->bind_param("ii", $applicant_id, $job_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
  echo "You have already applied to this job.<br><a href='browse_jobs.php'>Back to Job List</a>";
  exit;
}

// logic to actually apply
$apply = $conn->prepare("INSERT INTO application (applicant_id, job_id, status) VALUES (?, ?, 'Pending')");
$apply->bind_param("ii", $applicant_id, $job_id);
$apply->execute();

echo "Application submitted successfully.<br><a href='browse_jobs.php'>Back to Job List</a>";
?>
