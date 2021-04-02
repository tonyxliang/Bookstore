<?php
  if (isset($_POST['admin-addbook-submit'])) {
      require 'dbh.inc.php';
      session_start();

      $isbn = trim($_POST['isbn']);
      $author =trim($_POST['author']);
      $title = trim($_POST['title']);
      $edition = trim($_POST['edition']);
      $publisher = trim($_POST['publisher']);
      $year = trim($_POST['year']);
      $price = trim($_POST['price']);
      $copies = trim($_POST['copies']);
      $quantity = trim($_POST['quantity']);

      //cover pic upload
      $file = $_FILES['fileUpload'];
      $fileName = $_FILES['fileUpload']['name'];
      $fileTmpName = $_FILES['fileUpload']['tmp_name'];
      $fileSize = $_FILES['fileUpload']['size'];
      $fileError = $_FILES['fileUpload']['error'];
      $fileType = $_FILES['fileUpload']['type'];

      $fileExt = explode('.', $fileName);
      $fileActualExt = strtolower(end($fileExt));

      $allowed = array('jpg', 'jpeg', 'png');

      //check for duplicate book
      $sql = "SELECT * FROM book WHERE isbn = ?; ";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../registration.php?error=sqlerror-emailCheck");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $isbn);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if ($row = mysqli_fetch_assoc($result)) {
              //isbn found in DB - duplicate
              header("Location: ../adminpage.php?error=duplicatebook");
              exit();
          }
      }

      //file upload
      if (in_array($fileActualExt, $allowed)) {
          if ($fileError === 0) {
              if ($fileSize < 5000000) {
                  //assign unique filename to uploaded file
                  $fileNameNew = uniqid('', true).".".$fileActualExt;
                  $fileDestination = '../images/'.$fileNameNew;
                  move_uploaded_file($fileTmpName, $fileDestination);
              } else {
                  echo 'Your file is too large';
              }
          } else {
              echo 'There was an error uploading the file';
          }
      } else {
          echo 'You cannot upload files of this type';
      }

      //no duplicates found - insert book info into DB
      $sql = "INSERT INTO book (isbn, authorName, bkTitle, bkEdition, bkPublisherName, bkPublishYear, bkCoverPic, bkPrice, bkCopies, bkQuantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../adminpage.php?error=sqlerror-bookinsert");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "ssssssssss", $isbn, $author, $title, $edition, $publisher, $year, $fileNameNew, $price, $copies, $quantity);
          mysqli_stmt_execute($stmt);
          header("Location: ../adminpage.php?add=success");
          exit();
      }
  } else {
      header("Location: ../adminpage.php?error=badlink");
      exit();
  }
