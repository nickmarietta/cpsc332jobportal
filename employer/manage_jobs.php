<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'employer') {
  header('Location: ../auth/login.php');
  exit;
}

$account_id = $_SESSION['account_id'];
$employer_id = $conn->query("SELECT employer_id FROM employer WHERE account_id = $account_id")->fetch_assoc()['employer_id'];
$jobs = $conn->query("SELECT * FROM jobs WHERE employer_id = $employer_id ORDER BY date_posted DESC");
?>

<!DOCTYPE html>
<html>
<head><title>View Jobs Posted</title></head>
<link rel="stylesheet" href="../css/manage_jobs.css">
<body>
<h1>View Jobs Posted</h1>
<table border="1">
<tr><th>Title</th><th>Location</th><th>Date Posted</th></tr>
<?php while ($job = $jobs->fetch_assoc()): ?>
  <tr>
  <td><?php echo htmlspecialchars($job['title']); ?></td>
  <td><?php echo htmlspecialchars($job['location']); ?></td>
  <td><?php echo htmlspecialchars($job['date_posted']); ?></td>
  <td>
    <form method="POST" action="delete_job.php" onsubmit="return confirm('Are you sure you want to delete this job?');">
      <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
      <button type="submit" class="delete-btn">Delete</button>
    </form>
  </td>
</tr>
<?php endwhile; ?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
