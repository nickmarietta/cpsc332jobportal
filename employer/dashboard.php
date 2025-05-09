<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'employer') {
  header('Location: ../auth/login.php');
  exit;
}

// grab the employer_id from the foreign key
$account_id = $_SESSION['account_id'];

$employerQuery = $conn->prepare("SELECT employer_id, company_name, first_name FROM employer WHERE account_id = ?");
$employerQuery->bind_param("i", $account_id);
$employerQuery->execute();
$employerResult = $employerQuery->get_result();
$employer = $employerResult->fetch_assoc();

if (!$employer) {
  echo "Employer profile not found.";
  exit;
}

$employer_id = $employer['employer_id'];
$company_name = $employer['company_name'];
$first_name = $employer['first_name'];

$jobQuery = $conn->prepare("SELECT job_id, title, location, date_posted FROM jobs WHERE employer_id = ? ORDER BY date_posted DESC");
$jobQuery->bind_param("i", $employer_id);
$jobQuery->execute();
$jobResult = $jobQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Employer Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
  <p>Company: <?php echo htmlspecialchars($company_name ?: 'N/A'); ?></p>

  <nav>
    <a href="post_job.php">Post New Job</a> |
    <a href="manage_jobs.php">Manage My Listings</a> |
    <a href="../auth/logout.php">Logout</a>
  </nav>

  <h2>Your Job Listings</h2>

  <?php if ($jobResult->num_rows > 0): ?>
    <ul>
      <?php while ($job = $jobResult->fetch_assoc()): ?>
        <li>
          <strong><?php echo htmlspecialchars($job['title']); ?></strong> –
          <?php echo htmlspecialchars($job['location']); ?> –
          Posted on <?php echo htmlspecialchars($job['date_posted']); ?>
          [<a href="applications.php?">View Applicants</a>]
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>You haven’t posted any jobs yet.</p>
  <?php endif; ?>

</body>
</html>
