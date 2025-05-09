<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'employer') {
  header('Location: ../auth/login.php');
  exit;
}

$account_id = $_SESSION['account_id'];
$employer = $conn->prepare("SELECT employer_id FROM employer WHERE account_id = ?");
$employer->bind_param("i", $account_id);
$employer->execute();
$employer_id = $employer->get_result()->fetch_assoc()['employer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $location = $_POST['location'];
  $salary = $_POST['salary'];
  $description = $_POST['description'];
  $job_type = $_POST['job_type'];

  $stmt = $conn->prepare("INSERT INTO jobs (employer_id, title, location, salary, description, job_type, date_posted)
                          VALUES (?, ?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param("issdss", $employer_id, $title, $location, $salary, $description, $job_type);
  $stmt->execute();

  header("Location: manage_jobs.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Post Job</title></head>
<body>
<h1>Post a New Job</h1>
<form method="POST">
  <label>Title: <input type="text" name="title" required></label><br>
  <label>Location: <input type="text" name="location" required></label><br>
  <label>Salary: <input type="number" step="0.01" name="salary" required></label><br>
  <label>Description: <textarea name="description" required></textarea></label><br>
  <label>Type:
    <select name="job_type" required>
      <option value="full-time">Full-time</option>
      <option value="part-time">Part-time</option>
      <option value="intern">Intern</option>
    </select>
  </label><br>
  <button type="submit">Post Job</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
