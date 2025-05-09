<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'applicant') {
  header('Location: ../auth/login.php');
  exit;
}

$jobs = $conn->query("SELECT j.job_id, j.title, j.location, j.job_type, e.company_name
                      FROM jobs j
                      JOIN employer e ON j.employer_id = e.employer_id
                      ORDER BY j.date_posted DESC");
?>

<!DOCTYPE html>
<html>
<head><title>Browse Jobs</title></head>
<body>
<h1>Available Jobs</h1>
<table border="1">
<tr><th>Title</th><th>Company</th><th>Location</th><th>Type</th><th></th></tr>
<?php while ($job = $jobs->fetch_assoc()): ?>
<tr>
  <td><?php echo htmlspecialchars($job['title'] ?? ''); ?></td>
  <td><?php echo htmlspecialchars($job['company_name'] ?? ''); ?></td>
  <td><?php echo htmlspecialchars($job['location'] ?? ''); ?></td>
  <td><?php echo htmlspecialchars($job['job_type'] ?? ''); ?></td>
  <td><a href="apply.php?id=<?php echo $job['job_id']; ?>">Apply</a></td>
</tr>
<?php endwhile; ?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
