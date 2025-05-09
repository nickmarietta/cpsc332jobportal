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
<head><title>Manage Jobs</title></head>
<body>
<h1>Manage Your Jobs</h1>
<table border="1">
<tr><th>Title</th><th>Location</th><th>Date Posted</th></tr>
<?php while ($job = $jobs->fetch_assoc()): ?>
  <tr>
    <td><?php echo htmlspecialchars($job['title']); ?></td>
    <td><?php echo htmlspecialchars($job['location']); ?></td>
    <td><?php echo htmlspecialchars($job['date_posted']); ?></td>
  </tr>
<?php endwhile; ?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
