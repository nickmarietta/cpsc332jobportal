<?php
include 'db.php';

$role = $_POST['role'];
$username = $_POST['username'];
$password = $_POST['password']; // can hash this later on if desired
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];


$stmt = $conn->prepare("INSERT INTO account (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();
$account_id = $stmt->insert_id;

if ($role === 'applicant') {
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $stmt2 = $conn->prepare("INSERT INTO applicant (account_id, first_name, last_name, phone_number, email) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("issss", $account_id, $first_name, $last_name, $phone, $email);
    $stmt2->execute();
    echo "Applicant registered!";
} elseif ($role === 'employer') {
    $company = $_POST['company_name'];
    $stmt2 = $conn->prepare("INSERT INTO employer (account_id, company_name, first_name, last_name) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("isss", $account_id, $company, $first_name, $last_name);
    $stmt2->execute();
    echo "Employer registered!";
} else {
    echo "Invalid role.";
}

$conn->close();
?>