<!DOCTYPE html>
<html lang="en-us">

<?php
require "includes/dbh.inc.php";
session_start();
?>

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

    <div class="loginContainer">
<?php
try {
    $selector = trim($_GET['selector']);
    $validator = trim($_GET['validator']);
    $currentDate = date("U");
} catch (Exception $e) {
    echo "<p>Your request could not be validated.</p>";
    exit();
}


if (empty($selector) || empty($validator)) {
    echo "<p>Your request could not be validated.</p>";
    exit();
} else {
    //echo '<h1> Your account has been activated! </h1>';
    if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
        //$sql = "SELECT * FROM useractivate WHERE userActivateSelector = ? AND userActivateExpires >= ?; ";
        $sql = "SELECT * FROM useractivate WHERE userActivateSelector = ?; ";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: account-activate.php?error=sqlerror-findToken");
            exit();
        } else {
            //mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
            mysqli_stmt_bind_param($stmt, "s", $selector);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            if (!$row = mysqli_fetch_assoc($result)) {
                echo 'fetch_assoc failed';
                header("Location: account-activate.php?error=invalidRequest1&selector=" . $selector . "&validator=" . $validator);
                exit();
            } else {
                $tokenBin = hex2bin($validator);
                $tokenCheck = password_verify($tokenBin, $row['userActivateToken']);
                if ($tokenCheck == false) {
                    header("Location: account-activate.php?error=invalidRequest2");
                    exit();
                } elseif ($tokenCheck == true) {
                    $tokenEmail = $row['userActivateEmail'];

                    $sql = "SELECT * FROM users WHERE email = ?; ";
                    $stmt = mysqli_stmt_init($connection);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: account-activate.php?error=sqlerror-findEmail");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);
                        if (!$row = mysqli_fetch_assoc($result)) {
                            header("Location: account-activate.php?error=invalidRequest3&selector=" . $selector . "&validator=" . $validator);
                            exit();
                        } else {
                            $sql = "UPDATE users SET userStatus = '1' WHERE email = ?; ";
                            $stmt = mysqli_stmt_init($connection);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: account-activate.php?error=invalidRequest4&selector=" . $selector . "&validator=" . $validator);
                                exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);

                                echo "<p>STATUS UPDATED.</p>";

                                $sql = "DELETE FROM useractivate WHERE userActivateEmail = ?; ";
                                $stmt = mysqli_stmt_init($connection);
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    header("Location: account-activate.php?error=sqlerror-actDel");
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                    mysqli_stmt_execute($stmt);
                                    //header("Location: account-activate.php?activate=success&selector=" . $selector . "&validator=" . $validator);
                                    header("Location: homepage.php?activate=success");
                                    echo '<h1> Your account has been activated! </h1>';
                                    //exit();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>

    </div>
  <br>
  <br>
</body>

</html>
