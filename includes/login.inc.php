<?php
  if (isset($_POST['login-submit'])) {
      require "dbh.inc.php";

      $username = trim($_POST['username']);
      $password = trim($_POST['password']);

      if (empty($username) || empty(password)) {
          header("Location: ../login.php?error=emptyfields");
          exit();
      } else {
          $sql = "SELECT * FROM users WHERE username=? OR email=?; ";
          $stmt = mysqli_stmt_init($connection);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
              header("Location: ../login.php?error=sqlerror");
              exit();
          } else {
              mysqli_stmt_bind_param($stmt, "ss", $username, $username);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              //user found - begin
              if ($row = mysqli_fetch_assoc($result)) {
                  $pwdCheck = password_verify($password, $row['password']);
                  if ($pwdCheck == false) {
                      header("Location: ../login.php?error=wrongpass");
                      exit();
                  } elseif ($pwdCheck == true) {
                      session_start();
                      $_SESSION['username'] = $row['username'];
                      $_SESSION['userID'] = $row['userID'];
                      $_SESSION['userStatus'] = $row['userStatus'];

                      if ($row['userType'] == 'admin') {
                          $_SESSION['userType'] = $row['userType'];
                          header("Location: ../homepage.php?login=success&admin=true");
                          exit();
                      } else {
                          $_SESSION['userType'] = $row['userType'];
                          header("Location: ../homepage.php?login=success");
                          //header("Location: ../homepage.php");
                          exit();
                      }
                  } else {
                      header("Location: ../login.php?error=wrongcredentials");
                      exit();
                  }
              } else {
                  header("Location: ../login.php?error=nouser");
                  exit();
              }
          }
      }
      mysqli_stmt_close($stmt);
      mysqli_close($connection);
  } else {
      header("Location: ../login.php?login=badlink");
      exit();
  }
