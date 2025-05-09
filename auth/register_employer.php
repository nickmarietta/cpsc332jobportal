<?php
session_start();
require '../includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $role         = 'employer';
  $username     = $_POST['username'] ?? '';
  $password     = $_POST['password'] ?? '';
  $confirmpass  = $_POST['confirmpass'] ?? '';
  $first_name   = $_POST['first_name'] ?? '';
  $last_name    = $_POST['last_name'] ?? '';
  $email        = $_POST['email'] ?? '';
  $phone_number = $_POST['phone_number'] ?? '';
  $address      = $_POST['address'] ?? '';
  $company_name = $_POST['company_name'] ?? '';

  if (!$username || !$password || !$confirmpass || !$first_name || !$last_name || !$company_name) {
    $error = "Please fill out all required fields.";
  } elseif ($password !== $confirmpass) {
    $error = "Passwords do not match.";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO account (role, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $role, $username, $hashed_password);

    if ($stmt->execute()) {
      $account_id = $stmt->insert_id;

      $insert = $conn->prepare(
        "INSERT INTO employer (company_name, first_name, last_name, email, phone_number, account_id, street_name) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
      );
      $insert->bind_param("sssssis", $company_name, $first_name, $last_name, $email, $phone_number, $account_id, $address);

      if ($insert->execute()) {
        $_SESSION['account_id'] = $account_id;
        $_SESSION['role'] = $role;

        header("Location: ../employer/dashboard.php");
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

<!-- was using the incorrect action here, reference the correct file -->
<form action="register_employer.php" method="POST">
    <div class="signupform">
        <a href="../index.php"><img src="../images/logo.png" ></a>
        
        <h1> Sign Up</h1>
        <input type="text" class="input-box" id="fname" name="first_name" placeholder="First Name" required><br><br>

        <input type="text" class="input-box" id="lname" name="last_name" placeholder="Last Name" required><br><br>

        <input type="text" class="input-box" id="user" name="username" placeholder="Username" required><br><br>

        <input type="password" class="input-box" id="password" name="password" placeholder="Password" required><br><br>

        <input type="password" class="input-box" id="confirmpass" name="confirmpass" placeholder="Confirm Password" required><br><br>

        <input type="text" class="input-box" id="phone_number" name="phone_number" placeholder="Phone Number"><br><br>

        <input type="text" class="input-box" id="company" name="company_name" placeholder="Company Name"><br><br>

        <input type="text" class="input-box" id="address" name="address" placeholder="Address"><br><br>

        <input type="text" class="input-box" id="email" name="email" placeholder="Email"><br><br>

        <input type="submit" class="signupbtn" value="Create Account">
        <p>Already have an account? <a href="./login.php">Click here</a></p>
    </div>
</form>
</body>
</html>
