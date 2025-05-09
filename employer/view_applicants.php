<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'employer') {
  header('Location: ../auth/login.php');
  exit;
}

$job_id = $_GET['id'] ?? null;

if (!$job_id) {
  echo "Job ID not provided.";
  exit;
}

$stmt = $conn->prepare("SELECT a.first_name, a.last_name, a.email, app.status
                        FROM application app
                        JOIN applicant a ON app.applicant_id = a.applicant_id
                        WHERE app.job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head><title>View Applicants</title></head>
<body>
<h1>Applicants for Job #<?php echo htmlspecialchars($job_id); ?></h1>
<table border="1">
<tr><th>Name</th><th>Email</th><th>Status</th></tr>
<?php while ($row = $results->fetch_assoc()): ?>
  <tr>
    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
    <td><?php echo htmlspecialchars($row['email']); ?></td>
    <td><?php echo htmlspecialchars($row['status']); ?></td>
  </tr>
<?php endwhile; ?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
