<!DOCTYPE html>
<html lang="en-us">

<?php
session_start();

if (isset($_SESSION['userID']) && $_SESSION['userType'] == 'admin') {
    header("Location: adminpage.php");
    exit();
}
if (!isset($_SESSION['userID'])) {
    header("Location: homepage.php");
    exit();
}
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
    <form action="includes/logout.inc.php" method="post">
      <?php
      if (isset($_SESSION['userID'])) {
          echo '<button type="submit" name="logout-submit"> Logout </button>';
      //echo "<p> You are logged in </p>";
      } else {
          //echo "<p> You are logged out </p>";
      }
       ?>
    </form>
  </div>
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
      <h1 class="homePageText">Your Profile</h1>
      <div class="loginContainer">
        <a href="editprofile.php"> <button>Edit Profile</button></a>
      </div>
      <div class="loginContainer">
        <a href="orderhistory.php"> <button>Order History</button></a>
      </div>
  </div>
  <br>
  <br>
</body>

</html>
