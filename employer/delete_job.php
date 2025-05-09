<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'employer') {
  header('Location: ../auth/login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
  $job_id = intval($_POST['job_id']);

  // delete all existing sessions in which the job_id is tied to it
  $deleteApps = $conn->prepare("DELETE FROM application WHERE job_id = ?");
  $deleteApps->bind_param("i", $job_id);
  $deleteApps->execute();

  // delete the job
  $deleteJob = $conn->prepare("DELETE FROM jobs WHERE job_id = ?");
  $deleteJob->bind_param("i", $job_id);
  $deleteJob->execute();
}

header("Location: manage_jobs.php");
exit;
?>
