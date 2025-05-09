<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'applicant') {
  header('Location: ../auth/login.php');
  exit;
}

$account_id = $_SESSION['account_id'];
$applicant_id = $conn->query("SELECT applicant_id FROM applicant WHERE account_id = $account_id")->fetch_assoc()['applicant_id'];

$applications = $conn->query("SELECT j.title, j.location, a.status, e.company_name
                              FROM application a
                              JOIN jobs j ON a.job_id = j.job_id
                              JOIN employer e ON j.employer_id = e.employer_id
                              WHERE a.applicant_id = $applicant_id");
?>

<!DOCTYPE html>
<html>
<head><title>My Applications</title></head>
<body>
<h1>My Applications</h1>
<table border="1">
<tr><th>Job Title</th><th>Company</th><th>Location</th><th>Status</th></tr>
<?php while ($app = $applications->fetch_assoc()): ?>
<tr>
  <td><?php echo htmlspecialchars($app['title']  ?? ''); ?></td>
  <td><?php echo htmlspecialchars($app['company_name']  ?? ''); ?></td>
  <td><?php echo htmlspecialchars($app['location']  ?? ''); ?></td>
  <td><?php echo htmlspecialchars($app['status']  ?? ''); ?></td>
</tr>
<?php endwhile; ?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
