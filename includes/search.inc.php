<?php
  require 'dbh.inc.php';

  if (isset($_POST['search-submit'])) {
      $search = mysqli_real_escape_string($connection, $_POST['search']);
      $type;
      $sql = "SELECT * FROM book WHERE a_title LIKE '%$search%' OR a_isbn LIKE '%$search%' OR a_author LIKE '%$search%'";
      $result = mysqli_query($connection, $sql);
      $queryResult = mysqli_num_rows($result);

      if ($queryResult > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='container'
                      <h1>".$row['a_title']."</h1>
                      <p>ISBN: ".$row['a_isbn']."</p>
                      <p>".$row['a_author']."</p>
                    </div>";
          }
      } else {
          echo '<p> There are no results matching your search.</p>';
      }
  }
