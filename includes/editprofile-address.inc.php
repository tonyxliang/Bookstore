<?php
  if (isset($_POST['editprofile-address-submit'])) {
      require 'dbh.inc.php';
      session_start();

      //updating personal info
      $firstName = trim($_POST['firstName']);
      $lastName = trim($_POST['lastName']);
      $address = trim($_POST['address']);
      $address2 = trim($_POST['address2']);
      $city = trim($_POST['city']);
      $state = trim($_POST['state']);
      $zipcode = trim($_POST['zipcode']);
      $phone = trim($_POST['phone']);
      $country = trim($_POST['country']);
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
          //user found, begin
          if ($row = mysqli_fetch_assoc($result)) {
              //update address record with fields from address form
              $sql = "UPDATE address
                            SET address=?,
                                address2=?,
                                city=?,
                                state=?,
                                zipcode=?,
                                country=?
                            WHERE
                                userID = (SELECT userID FROM users WHERE userID=?);";
              $stmt = mysqli_stmt_init($connection);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header("Location: ../editprofile.php?error=address-sqlerror");
                  exit();
              } else {
                  mysqli_stmt_bind_param($stmt, "sssssss", $address, $address2, $city, $state, $zipcode, $country, $userID);
                  mysqli_stmt_execute($stmt);
              }
              //update user name records with fields from address form
              $sql = "UPDATE users
                            SET firstname=?,
                                lastname=?,
                                phone=?
                            WHERE
                                userID = (SELECT userID FROM users WHERE userID=?);";
              $stmt = mysqli_stmt_init($connection);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header("Location: ../editprofile.php?error=users-sqlerror");
                  exit();
              } else {
                  mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $phone, $userID);
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
