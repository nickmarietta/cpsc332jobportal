<?php
session_start();
require '../includes/db.php';

// --- Sorting logic ---
$orderBy = "date_posted DESC"; // Default

if (isset($_GET['sort'])) {
  switch ($_GET['sort']) {
    case 'date_asc':
      $orderBy = "date_posted ASC";
      break;
    case 'salary_desc':
      $orderBy = "salary DESC";
      break;
    case 'salary_asc':
      $orderBy = "salary ASC";
      break;
    case 'title_asc':
      $orderBy = "title ASC";
      break;
    case 'title_desc':
      $orderBy = "title DESC";
      break;
    default:
      $orderBy = "date_posted DESC";
  }
}

// --- get sorted jobs ---
$jobsQuery = $conn->prepare("
  SELECT jobs.*, employer.company_name
  FROM jobs
  JOIN employer ON jobs.employer_id = employer.employer_id
  ORDER BY $orderBy
");
$jobsQuery->execute();
$jobs = $jobsQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Browse Jobs</title>
  <link rel="stylesheet" href="../css/browse_jobs.css">
</head>
<body>

<h1>Available Jobs</h1>

<!-- this is where the sorting logic is
    also had to change to the ? so that we did not get a null defined value error for the radio button
-->
<form method="GET" action="browse_jobs.php">
  <label for="sort">Sort by:</label>
  <select name="sort" id="sort" onchange="this.form.submit()">
    <option value="">-- Select --</option>
    <option value="date_desc" <?= ($_GET['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
    <option value="date_asc" <?= ($_GET['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
    <option value="salary_desc" <?= ($_GET['sort'] ?? '') === 'salary_desc' ? 'selected' : '' ?>>Highest Salary</option>
    <option value="salary_asc" <?= ($_GET['sort'] ?? '') === 'salary_asc' ? 'selected' : '' ?>>Lowest Salary</option>
    <option value="title_asc" <?= ($_GET['sort'] ?? '') === 'title_asc' ? 'selected' : '' ?>>Title A–Z</option>
    <option value="title_desc" <?= ($_GET['sort'] ?? '') === 'title_desc' ? 'selected' : '' ?>>Title Z–A</option>
  </select>
</form>

<!-- here are the actual listings -->
<?php if ($jobs->num_rows > 0): ?>
  <ul class="job-list">
    <?php while ($job = $jobs->fetch_assoc()): ?>
      <li class="job-card">
        <h2><?= htmlspecialchars($job['title']) ?></h2>
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p class="salary">$<?= number_format($job['salary'], 2) ?></p>
        <p><strong>Company:</strong> <?= htmlspecialchars($job['company_name'] ?? 'N/A') ?></p>
        <p><strong>Posted:</strong> <?= htmlspecialchars($job['date_posted']) ?></p>
        <a class="apply-btn" href="apply.php?job_id=<?= $job['job_id'] ?>">Apply</a>
      </li>
    <?php endwhile; ?>
  </ul>
<?php else: ?>
  <p>No jobs available.</p>
<?php endif; ?>

  <a href="dashboard.php" class="back-to-dashboard">Back to Dashboard</a>

</body>
</html>
