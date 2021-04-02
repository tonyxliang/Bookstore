<?php
  if (isset($_POST['editprofile-card-submit'])) {
      require 'dbh.inc.php';
      session_start();

      //adding new card
      $cardName = trim($_POST['cardName']);
      $ccNumber = trim($_POST['ccNumber']);
      $expire = trim($_POST['expire']);
      $cvc = trim($_POST['cvc']);
      //session user info
      $userID = $_SESSION['userID'];

      //select user from db
      $sql = "SELECT userID FROM users WHERE userID=?";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../editprofile.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userID);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_store_result($stmt);
          $result = mysqli_stmt_get_result($stmt);
          //user found, begin
          if ($row = mysqli_fetch_assoc($result)) {
              //insert into paymentinfo table in DB with userID foreign key
              $sql = "INSERT INTO paymentinfo (userID) SELECT userID FROM users WHERE userID=?; ";
              $stmt = mysqli_stmt_init($connection);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header("Location: ../editprofile.php?error=userIDinsert-sqlerror");
                  exit();
              } else {
                  mysqli_stmt_bind_param($stmt, "s", $userID);
                  mysqli_stmt_execute($stmt);
              }
              //update address record with fields from address form
              $sql = "UPDATE paymentinfo
                            SET cardFullName=?,
                                cardNumber=?,
                                expiryDate=?,
                                securityCode=?
                            WHERE
                                userID = (SELECT userID FROM users WHERE userID=?);";
              $stmt = mysqli_stmt_init($connection);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header("Location: ../editprofile.php?error=pymtinfupdate");
                  exit();
              } else {
                  $hashedCC = password_hash($ccNumber, PASSWORD_DEFAULT);
                  $hashedCVC = password_hash($cvc, PASSWORD_DEFAULT);
                  mysqli_stmt_bind_param($stmt, "sssss", $cardName, $hashedCC, $expire, $hashedCVC, $userID);
                  mysqli_stmt_execute($stmt);
                  header("Location: ../editprofile.php?edit=success");
                  exit();
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
