<?php
    session_start();
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
      <!-- Account -->
      <a class="btn nav-link" href="login.php" role="button">
        <svg class="bi bi-person-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z" />
          <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
          <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
        </svg>
      </a>
    </div>
  </div>
  <div class="sideBar">
    <!-- Search -->
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
  <div class="banner">
    <img class="pic" src="images/banner.png">
  </div>
  <h1 class="homePageText">SNT Bookstore Homepage</h1>
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
  <br>


  <?php
    if (isset($_GET['newPwd'])) {
        if ($_GET['newPwd'] == "success") {
            echo '<h2 style="text-align:center;"> Your password was updated! </h2>';
            echo '<p style="text-align:center;"> Please login with your new password. </p>';
        }
    }
    if (isset($_GET['activate'])) {
        if ($_GET['activate'] == "success") {
            echo '<h2 style="text-align:center;"> Your account was activated! </h2>';
        }
    }
    if (isset($_GET['user'])) {
        if ($_GET['user'] == "activationrequired") {
            echo '<h2 style="text-align:center;"> You need to activate your account. Check your email! </h2>';
        }
    }
    
   ?>

  <br>
  <br>
  <!--
  <div class="dropdown">
    <button class="dropbtn">Search:</button>
  </div>
  <div>
    <form style="margin-right: 5%; margin-left: 15%;" action="" method="post">
      <input type="search" id="myInput" name="search" width="100%">
      <button type="submit" name="search-submit">Go</button>
    </form>
  </div>
-->

  <?php /*
    require 'includes/dbh.inc.php';

    if (isset($_POST['search-submit'])) {
        $search = mysqli_real_escape_string($connection, $_POST['search']);
        $sql = "SELECT * FROM book WHERE bkTitle LIKE '%$search%' OR isbn LIKE '%$search%' OR authorName LIKE '%$search%'";
        $result = mysqli_query($connection, $sql);
        $queryResult = mysqli_num_rows($result);

        if ($queryResult > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $img = $row['bkCoverPic']; */?>
                <!--
                <div class="container">
                  <img src="images/<?php// echo $img ?>" height="500" width="300"/>
                  <p><b>Title: </b><?php //echo $row['bkTitle']?></p>
                  <p><b>ISBN: </b><?php //echo $row['isbn']?></p>
                  <p><b>Author: </b><?php //echo $row['authorName']?></p>
                </div> -->

  <?php /*
            }
        } else {
            echo '<p> There are no results matching your search.</p>';
        }
    } */
   ?>

  <div class="loginContainer">
    <div style="margin-left:10%;padding:1px 16px;height:500px;">
      <h2>Featured Products</h2>

    <?php
        require 'includes/dbh.inc.php';
        $sql = "SELECT * FROM book LIMIT 10";
        $result = mysqli_query($connection, $sql);
        $queryResult = mysqli_num_rows($result);

        if ($queryResult > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $img = $row['bkCoverPic']; ?>
                <div class="container">
                  <form action="includes/addtocart.inc.php" method="post">
                    <img src="images/<?php echo $img; ?>" height="375" width="200"/>
                    <p><b>Title: </b><?php echo $row['bkTitle']; ?></p>
                    <p><b>ISBN: </b><?php echo $row['isbn']; ?></p>
                    <p><b>Author: </b><?php echo $row['authorName']; ?></p>
                    <input type="hidden" name="bookTitle" value="<?php echo $row['bkTitle']; ?>"/>
                    <button type="submit" name="addtocart-submit"> Add to Cart </button>
                  </form>
                </div>
  <?php
            }
        } else {
            echo '<p> There are no results matching your search.</p>';
        }

  ?>
</div>
  </div>
  <br>
  <br>
</body>

</html>
