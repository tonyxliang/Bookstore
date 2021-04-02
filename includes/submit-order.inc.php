<?php
  if (isset($_POST['submit-order'])) {
      require 'dbh.inc.php';
      session_start();

      $userID = $_SESSION['userID'];

      $ccNumber = trim($_POST['ccNumber']);
      $fullName = trim($_POST['ccFullName']);
      $expireDate = trim($_POST['expireDate']);
      $cvc = trim($_POST['cvcNum']);

      $firstName = trim($_POST['firstName']);
      $lastName = trim($_POST['lastName']);
      $address = trim($_POST['address']);
      $address2 = trim($_POST['address2']);
      $city = trim($_POST['city']);
      $state = trim($_POST['state']);
      $zipcode = trim($_POST['zipcode']);
      $phone = trim($_POST['phone']);
      $country = trim($_POST['country']);


      //select user from db
      $sql = "SELECT * FROM paymentinfo WHERE userID=?";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../checkout.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userID);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_store_result($stmt);
          $result = mysqli_stmt_get_result($stmt);
          //if user found, begin pwd check
          if ($row = mysqli_fetch_assoc($result)) {
              $ccCheck = password_verify($ccNumber, $row['cardNumber']);
              if ($ccCheck == false) {
                  header("Location: ../checkout.php?error=wrongcc");
                  exit();
              } elseif ($ccCheck == true) {
                  $cvcCheck = password_verify($cvc, $row['securityCode']);
                  if ($cvcCheck == false) {
                      header("Location: ../checkout.php?error=wrongcvc");
                      exit();
                  } elseif ($cvcCheck == true) {
                      if ($fullName != $row['cardFullName']) {
                          header("Location: ../checkout.php?error=wrongccname");
                          exit();
                      }
                      /*
                      if ($expireDate != $row['expiryDate']) {
                          header("Location: ../checkout.php?error=wrongexpdate");
                          exit();
                      } */
                      header("Location: ../ordersummary.php");
                      exit();
                  }
              }
              //cc verified
          }
      }
  } else {
      header("Location: ../checkout.php?error=badlink");
      exit();
  }
