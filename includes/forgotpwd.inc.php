<?php
  if (isset($_POST['forgotpwd-submit'])) {
      require 'dbh.inc.php';
      require '../phpmailer/Exception.php';
      require '../phpmailer/PHPMailer.php';
      require '../phpmailer/SMTP.php';

      $userEmail = trim($_POST['email']);

      //PHPMailer setup
      $mail = new PHPMailer\PHPMailer\PHPMailer();
      $mail->isSMTP();
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'ssl';
      $mail->Host = 'ssl://smtp.gmail.com';
      $mail->Port = '465';
      $mail->isHTML(true);
      $mail->Username = 'secrets.email';
      $mail->Password = 'secrets.emailpw';
      $mail->SetFrom('no-reply@sktstore.com');
      $mail->Subject = 'Reset Your Password';

      //tokens - authentication
      $selector = bin2hex(random_bytes(8));
      $token = random_bytes(32);
      //url for user
      $url = "localhost/bookstore/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

      $expires = date("U") + 3600;

      //delete any previous pending pwd reset requests
      $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../forgottenpassword.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userEmail);
          mysqli_stmt_execute($stmt);
      }
      //insert reset request into DB for later authentication via link
      $sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?); ";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../forgottenpassword.php?error=sqlerror");
          exit();
      } else {
          //token should be encrypted
          $hashedToken = password_hash($token, PASSWORD_DEFAULT);
          mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
          mysqli_stmt_execute($stmt);
      }

      //PHPMailer continuted...
      //Email message to user with pwd reset instructions
      $message = '<p> We received a request to reset your password. <br></p>';
      $message .= '<p> If you did not request this, ignore this message.<br></p>';
      $message .= '<p> Here is your password reset link: <br></p>';
      $message .= '<a href="' . $url . '">' . $url . '</a>';
      $mail->Body = $message;
      $mail->AddAddress($userEmail);

      //check for email in DB
      $sql = "SELECT * FROM users WHERE email=?; ";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../forgottenpassword.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userEmail);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if ($row = mysqli_fetch_assoc($result)) {
              //email found in DB
              if ($row['email'] == $userEmail) {
                  //if send mail function doesn't work, error msg in URL
                  if (!$mail->Send()) {
                      $msg = "Mailer Error: " . $mail->ErrorInfo;
                      header("Location: ../forgottenpassword.php?mailError=$msg");
                      exit();
                  } else {
                      header("Location: ../forgottenpassword.php?sentmail=success");
                      exit();
                  }
                  /*
                  if (mail($to, $subject, $message)) {
                      header("Location: ../forgottenpassword.php?sentmail=success");
                  } else {
                      header("Location: ../forgottenpassword.php?sentmail=failed");
                  }
                  exit();
                  */
              } else {
                  header("Location: ../forgottenpassword.php?error=notfound");
                  exit();
              }
          }
          header("Location: ../forgottenpassword.php?error=notfound");
          exit();
      }
      mysqli_stmt_close($stmt);
      mysqli_close($connection);
  } else {
      header("Location: ../forgottenpassword.php?forgotpwd=badlink");
      exit();
  }
