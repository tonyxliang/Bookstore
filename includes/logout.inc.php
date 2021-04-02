<?php
    if (isset($_POST['logout-submit'])) {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../homepage.php");
        exit();
    } else {
        header("Location: ../homepage.php?error=badlink");
        exit();
    }
