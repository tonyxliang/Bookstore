<?php
  if (isset($_POST['editprofile-password-submit'])) {
      require 'dbh.inc.php';
      session_start();

      //for updating password
      $currentPassword = trim($_POST['currentPassword']);
      $newPassword = trim($_POST['newPassword']);
      $confirmNewPassword = trim($_POST['confirmNewPassword']);
      //session user info
      $userID = $_SESSION['userID'];

      //select user from db
      $sql = "SELECT * FROM users WHERE userID=?";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../editprofile.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userID);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_store_result($stmt);
          $result = mysqli_stmt_get_result($stmt);
          //if user found, begin pwd check
          if ($row = mysqli_fetch_assoc($result)) {
              $pwdCheck = password_verify($currentPassword, $row['password']);
              if ($pwdCheck == false) {
                  header("Location: ../editprofile.php?error=wrongpass");
                  exit();
              //password verified, begin update
              } elseif ($pwdCheck == true) {
                  if ($newPassword !== $confirmNewPassword) {
                      header("Location: ../editprofile.php?error=passmismatch");
                  } else {
                      $sql = "UPDATE users SET password = ? WHERE userID = ?";
                      $stmt = mysqli_stmt_init($connection);
                      if (!mysqli_stmt_prepare($stmt, $sql)) {
                          header("Location: ../editprofile.php?error=sqlerror-pwd");
                          exit();
                      } else {
                          $hashedPwd = password_hash($newPassword, PASSWORD_DEFAULT);
                          mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $userID);
                          mysqli_stmt_execute($stmt);
                          header("Location: ../editprofile.php?edit=success");
                          exit();
                      }
                  }
              }
          } else {
              header("Location: ../editprofile.php?error=sql-user");
              exit();
          }
      }
      mysqli_stmt_close($stmt);
      mysqli_close($connection);
  } else {
      header("Location: ../editprofile.php?error=badlink");
      exit();
  }
