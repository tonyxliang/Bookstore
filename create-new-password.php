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






    <div class="loginContainer">

        <h1> Reset your password </h1>
        <?php
          $selector = trim($_GET['selector']);
          $validator = trim($_GET['validator']);

          if (empty($selector) || empty($validator)) {
              echo "<p>Your request could not be validated.</p>";
              exit();
          } else {
              if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
                  ?>
                      <form action="includes/reset-password.inc.php" method="post">
                          <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                          <input type="hidden" name="validator" value="<?php echo $validator; ?>">

                          <!--<p> TESTING inside hidden </p>
                          <p> The selector is <?php //echo $selector;?></p>
                          <p> The validator is <?php //echo $validator;?></p> -->
                          <label> Password: </label>
                          <input type="password" name ="password" placeholder="Enter a new password" required> <br>
                          <label> Confirm Password: </label>
                          <input type="password" name ="confirmPassword" placeholder="Confirm the new password" required> <br>
                          <button type="submit" name="reset-password-submit"> Reset my password </button>
                        </form>

                        <?php
              }
          }
                    ?>

    </div>
    <div class="loginContainer">
      <?php
      if (isset($_GET['newPwd'])) {
          if ($_GET['newPwd'] == "empty") {
              echo '<p style="color:red">Please fill all fields.</p>';
          }
          if ($_GET['newPwd'] == "mismatch") {
              echo '<p style="color:red">Please enter matching passwords.</p>';
          }
      }
       ?>
    </div>
  <br>
  <br>
</body>

</html>
