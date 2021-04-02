<?php
    require 'includes/dbh.inc.php';
    session_start();

    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';

    //$userEmail = trim($_POST['email']);

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
    $mail->Subject = 'Your Order Confirmation';

    $userID = $_SESSION['userID'];
 ?>

<!DOCTYPE html>
<html lang="en-us">

<head>
  <meta charset="UTF-8">
  <title>Deliverable 4</title>
  <link rel="stylesheet" href="stylesheet2.css">
  <script src="pages.js"></script>

</head>

<body>
  <br>
  <div class="sideBar">
    <div>
      <a class="btn nav-link" href="search.php" role="button">
        <svg class="bi bi-search" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
          <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
        </svg>
      </a>
    </div>
  </div>

  <div class="sideBar">
    <div>
      <a class="btn nav-link" href="checkout.php" role="button">
        <svg class="bi bi-cart" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
        </svg>
      </a>
    </div>
  </div>
  <br>
  <br>



  <div class="dropdown">
    <a href="homepage.php"> <button class="dropbtn">Home</button></a>
  </div>
  <!--
    <div class="dropdown">
      <button class="dropbtn">Promotional Deals</button>
    </div>
    <div class="dropdown">
      <button class="dropbtn">About Us</button>
    </div>
  -->
  <br>
  <br>
  <div class="banner">
    <img class="pic" src="images/banner.png">
  </div>
  <br>
  <br>


    <?php
      $cartContent = $_SESSION['cart'];
      $in = str_repeat('?,', count($cartContent) - 1) . '?'; // placeholders
      $sql = "SELECT * FROM book WHERE bkTitle IN ($in); ";
      $stmt = $connection->prepare($sql); // prepare
      $types = str_repeat('s', count($cartContent)); //types
      $stmt->bind_param($types, ...$cartContent); // bind array at once
      $stmt->execute();
      $result = $stmt->get_result(); // get the mysqli result


     ?>



    <div class="loginContainer">
        <h1> Order Summary </h1>
        <fieldset class="first_fieldset">
          <table>
            <tr> <?php
              $totalprice = 0;
              while ($row = mysqli_fetch_assoc($result)) {
                  ?>
              <th>Title</th>
              <th>Price</th>
              <th></th>

            </tr>
            <tr>
              <td>
                <a><?php echo $row['bkTitle']; ?></a>
              </td>
              <td>$<?php echo $row['bkPrice']; ?></td>
              <td>
              </td>
              <td>
              </td>
            </tr>
            <?php $totalprice += $row['bkPrice'];
              }
              ?>
          <tr>
            <td></td>

          </tr>
          </table>
          <p> <b>Total Price: $<?php echo $totalprice; ?> </b></p>
        </fieldset>



    </div>
    <div class="loginContainer">
      <?php
      //select user from db for email send
      $sql = "SELECT * FROM users WHERE userID=?";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../checkout.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userID);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_store_result($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
      }

      $email = $row['email'];
      $phone = $row['phone'];
      $firstName = $row['firstname'];
      $lastName = $row['lastname'];

      //select user from db
      $sql = "SELECT * FROM address WHERE userID=?";
      $stmt = mysqli_stmt_init($connection);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../checkout.php?error=sqlerror");
          exit();
      } else {
          mysqli_stmt_bind_param($stmt, "s", $userID);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_store_result($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
      }



      $address = $row['address'];
      $address2 = $row['address2'];
      $city = $row['city'];
      $state = $row['state'];
      $zipcode = $row['zipcode'];
      $country = $row['country'];

      ?>
      <h2> Shipping Address </h2>
      <p> First Name: <?php echo $firstName; ?> </p>
      <p> Last Name: <?php echo $lastName; ?> </p>
      <p> Street Address: <?php echo $address; ?> </p>
      <p> Building/Apt/Suite: <?php echo $address2; ?> </p>
      <p> City: <?php echo $city; ?> </p>
      <p> State: <?php echo $state; ?> </p>
      <p> Postal Code: <?php $zipcode; ?> </p>
      <p> Phone Number: <?php echo $phone; ?> </p>
      <p> Country: <?php echo $country; ?> </p>
    </div>
    <?php


    ?>
    <?php
    //PHPMailer continuted...
    //Email message to user with activation instructions
    $message = '<p> Thank you for your order! <br></p>';
    /*
    $message .= '<p> If you did not request this, ignore this message.<br></p>';
    $message .= '<p> Here is your account activation link: <br></p>';
    $message .= '<a href="' . $url . '">' . $url . '</a>';
    */
    $mail->Body = $message;
    $mail->AddAddress($email);

    if (!$mail->Send()) {
        $msg = "Mailer Error: " . $mail->ErrorInfo;
        header("Location: ../homepage.php?mailError=$msg");
        exit();
    }
    ?>
  <br>
  <br>
</body>

</html>
<?php
  $_SESSION['cart'] = array();
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


 ?>
