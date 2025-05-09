<?php
session_start();
require '../includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $role         = $_POST['role'] ?? '';
  $username     = $_POST['username'] ?? '';
  $password     = $_POST['password'] ?? '';
  $confirmpass  = $_POST['confirmpass'] ?? '';
  $first_name   = $_POST['first_name'] ?? '';
  $last_name    = $_POST['last_name'] ?? '';
  $email        = $_POST['email'] ?? '';
  $phone_number = $_POST['phone_number'] ?? '';
  $address      = $_POST['address'] ?? '';

  if (!$role || !$username || !$password || !$confirmpass || !$first_name || !$last_name) {
    $error = "Please fill out all required fields.";
  } elseif ($password !== $confirmpass) {
    $error = "Passwords do not match.";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO account (role, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $role, $username, $hashed_password);

    if ($stmt->execute()) {
      $account_id = $stmt->insert_id;

      if ($role === 'applicant') {
        $insert = $conn->prepare(
          "INSERT INTO applicant (first_name, last_name, email, phone_number, account_id, street_name) 
           VALUES (?, ?, ?, ?, ?, ?)"
        );
        $insert->bind_param("ssssis", $first_name, $last_name, $email, $phone_number, $account_id, $address);
      } else {
        $insert = $conn->prepare(
          "INSERT INTO employer (first_name, last_name, email, phone_number, account_id, street_name) 
           VALUES (?, ?, ?, ?, ?, ?)"
        );
        $insert->bind_param("ssssis", $first_name, $last_name, $email, $phone_number, $account_id, $address);
      }

      if ($insert->execute()) {
        $_SESSION['account_id'] = $account_id;
        $_SESSION['role'] = $role;

        header("Location: " . ($role === 'applicant' ? "/applicant/dashboard.php" : "/employer/dashboard.php"));
        exit;
      } else {
        $error = "Error inserting profile: " . $insert->error;
      }
    } else {
      $error = "Error creating account: " . $stmt->error;
    }
  }
}   
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../css/createaccount.css">
</head>
<body>
<h1>
  <a href="index.php">
    <img src="../images/2936630.png" alt="portal icon" width="251" height="251">
  </a>
</h1>

<h2>
  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form action="register.php" method="POST">
    <label for="fname">First name:</label><br>
    <input type="text" id="fname" name="first_name" required><br><br>

    <label for="lname">Last name:</label><br>
    <input type="text" id="lname" name="last_name" required><br><br>

    <label for="username">Username:</label><br>
    <input type="text" id="user" name="username" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirmpass">Confirm Password:</label><br>
    <input type="password" id="confirmpass" name="confirmpass" required><br><br>

    <label for="phone_number">Phone Number:</label><br>
    <input type="text" id="phone_number" name="phone_number"><br><br>

    <label for="address">Address:</label><br>
    <input type="text" id="address" name="address"><br><br>

    <label for="email">Email:</label><br>
    <input type="text" id="email" name="email"><br><br>

    <p>Are you a job seeker or an employer?</p>
    <input type="radio" id="Job Seeker" name="role" value="applicant" required>
    <label for="Job Seeker">Job Seeker</label><br><br>

    <input type="radio" id="Employer" name="role" value="employer" required>
    <label for="Employer">Employer</label><br><br>

    <input type="submit" value="Create Account">
  </form>
</h2>
</body>
</html>
