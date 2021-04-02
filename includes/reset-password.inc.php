<?php
    if (isset($_POST['reset-password-submit'])) {
        $selector = trim($_POST['selector']);
        $validator = trim($_POST['validator']);

        //CHECK SELECTOR - VALIDATOR VALUES
        //header("Location: ../homepage.php?CHECK=SELECTOR-VALIDATOR&selector=" . $selector . "&validator=" . $validator);
        //exit();

        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if (empty($password) || empty($confirmPassword)) {
            //header("Location: ../homepage.php?newPassword=empty");
            header("Location: ../create-new-password.php?selector=" . $selector . "&validator=" . $validator . "&newPwd=empty");
            exit();
        } elseif ($password != $confirmPassword) {
            //header("Location: ../create-new-password.php?newPassword=mismatch");
            header("Location: ../create-new-password.php?selector=" . $selector . "&validator=" . $validator . "&newPwd=mismatch");
            exit();
        }

        $currentDate = date("U");
        //CHECK TIME TEST
        //header("Location: ../homepage.php?CHECKTIME=" . $currentDate);
        //exit();

        require "dbh.inc.php";

        $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector = ? AND pwdResetExpires >= ?";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../homepage.php?error=sqlerror-resetPwd1");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            if (!$row = mysqli_fetch_assoc($result)) {
                //header("Location: ../homepage.php?error=invalidRequest1&=" . $row['pwdResetSelector']);
                header("Location: ../homepage.php?error=invalidRequest1&selector=" . $selector . "&validator=" . $validator);
                exit();
            } else {
                $tokenBin = hex2bin($validator);
                $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);
                if ($tokenCheck == false) {
                    header("Location: ../homepage.php?error=invalidRequest2");
                    exit();
                } elseif ($tokenCheck == true) {
                    $tokenEmail = $row['pwdResetEmail'];

                    $sql = "SELECT * FROM users WHERE email = ? ;";
                    $stmt = mysqli_stmt_init($connection);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../homepage.php?error=sqlerror-resetPwd2");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);
                        if (!$row = mysqli_fetch_assoc($result)) {
                            header("Location: ../homepage.php?error=invalidRequest3");
                            exit();
                        } else {
                            $sql = "UPDATE users SET password = ? WHERE email = ?";
                            $stmt = mysqli_stmt_init($connection);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: ../homepage.php?error=sqlerror-resetPwd3");
                                exit();
                            } else {
                                $newHashedPwd = password_hash($password, PASSWORD_DEFAULT);
                                mysqli_stmt_bind_param($stmt, "ss", $newHashedPwd, $tokenEmail);
                                mysqli_stmt_execute($stmt);

                                $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";
                                $stmt = mysqli_stmt_init($connection);
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    header("Location: ../forgottenpassword.php?error=sqlerror");
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                    mysqli_stmt_execute($stmt);
                                    header("Location: ../homepage.php?newPwd=success");
                                    exit();
                                }
                            }
                        }
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
    } else {
        header("Location: ../homepage.php?resetPwd=badlink");
        exit();
    }
