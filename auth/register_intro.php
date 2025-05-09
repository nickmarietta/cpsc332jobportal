<!DOCTYPE html>
<html>
<head>
  <title>Choose Role</title>
  <link rel="stylesheet" href="../css/createaccount.css">
</head>
<body>
  <div class="signupform">
    <form id="roleForm">
      <p>Are you a job seeker or an employer?</p>

      <input type="radio" id="job_seeker" name="role" value="applicant" required>
      <label for="job_seeker">Job Seeker</label><br><br>

      <input type="radio" id="employer" name="role" value="employer" required>
      <label for="employer">Employer</label><br><br>

      <input type="submit" class="signupbtn" value="Continue">
    </form>

    <p>Already have an account? <a href="./login.php">Click here</a></p>
  </div>


<!-- logic to get the roleForm and then guides it to the right page -->
  <script>
    document.getElementById("roleForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const selected = document.querySelector('input[name="role"]:checked').value;
      if (selected === "applicant") {
        window.location.href = "register_applicant.php";
      } else {
        window.location.href = "register_employer.php";
      }
    });
  </script>
</body>
</html>