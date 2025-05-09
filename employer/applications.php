<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'employer') {
  header('Location: ../auth/login.php');
  exit;
}

$account_id = $_SESSION['account_id'];

$employer_q = $conn->prepare("SELECT employer_id FROM employer WHERE account_id = ?");
$employer_q->bind_param("i", $account_id);
$employer_q->execute();
$employer_id = $employer_q->get_result()->fetch_assoc()['employer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['status'])) {
  $application_id = $_POST['application_id'];
  $status = $_POST['status'];

  $update = $conn->prepare("UPDATE application SET status = ? WHERE application_id = ?");
  $update->bind_param("si", $status, $application_id);
  $update->execute();
}

// get the current applications (could have imported from view_applicants)
$query = $conn->prepare("
  SELECT a.application_id, a.status, j.title, ap.first_name, ap.last_name
  FROM application a
  JOIN jobs j ON a.job_id = j.job_id
  JOIN applicant ap ON a.applicant_id = ap.applicant_id
  WHERE j.employer_id = ?
");
$query->bind_param("i", $employer_id);
$query->execute();
$results = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Applications</title>
  <link rel="stylesheet" href="../css/applications.css">
</head>
<body>
<h2>Manage Applications</h2>

<table border="1">
  <tr>
    <th>Applicant</th>
    <th>Job Title</th>
    <th>Status</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $results->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
      <td>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="application_id" value="<?= $row['application_id'] ?>">
          <select name="status">
            <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Accepted" <?= $row['status'] === 'Accepted' ? 'selected' : '' ?>>Accept</option>
            <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : '' ?>>Reject</option>
          </select>
          <button type="submit">Update</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
