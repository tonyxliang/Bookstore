<?php

  define('serverName', 'localhost:3555');
  define('dbUsername', 'root');
  define('dbPassword', 'root');
  define('dbName', 'bookstore');

  $connection = mysqli_connect(serverName, dbUsername, dbPassword, dbName);

  if (!$connection) {
      die("Connection failed: ".mysqli_connect_error());
  } else {
      //  echo "Connected successfully";
  }
