<?php
session_start();

if (isset($_POST['addtocart-submit'])) {
    if ($_SESSION['userStatus'] == 0) {
        header("Location: ../homepage.php?user=activationrequired");
        exit();
    }
    require "dbh.inc.php";

    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $bookTitle = trim($_POST['bookTitle']);

    if (array_push($_SESSION['cart'], $bookTitle)) {
        header("Location: ../checkout.php?cart=itemadded");
        exit();
    }
} else {
    header("Location: ../checkout.php?checkout=badlink");
    exit();
}
