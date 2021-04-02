<?php
//check if this script page was accessed legitimately
if (isset($_POST['registration-submit'])) {
    require "dbh.inc.php";
    require '../phpmailer/Exception.php';
    require '../phpmailer/PHPMailer.php';
    require '../phpmailer/SMTP.php';

    //get text from fields
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $address2 = trim($_POST['address2']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zipcode = trim($_POST['zipcode']);
    $phone = trim($_POST['phone']);
    $country = trim($_POST['country']);

    //empty fields check
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($firstName) || empty($lastName) || empty($email) || empty($address) || empty($city) || empty($state) || empty($zipcode) || empty($phone) || empty($country)) {
        //    echo ($username $password $confirmPassword $name $email $address $city $state $zipcode $phone $country);
        echo "<h2>" . username . "</h2>";
        header("Location: ../registration.php?error=emptyfields&username=" . $username . "&email=" . $email . "&firstname=" . $firstName . "&lastname=" . $lastName . "&address=" . $address . "&city=" . $city . "&state=" . $state . "&zipcode=" . $zipcode . "&phone=" . $phone . "&country=" . $country);
        exit();
    //invalid email format
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../registration.php?error=invalidemail&username=" . $username);
        exit();
    //check for matching passwords
    } elseif ($password !== $confirmPassword) {
        header("Location: ../registration.php?error=passwordCheck&username=" . $username . "&email=" . $email);
        exit();
    //password length check
    } elseif (strlen($password) < 5) {
        header("Location: ../registration.php?error=passwordLength&username=" . $username);
        exit();
    } else {
        //field checks passed - begin
        //now check if username is duplicate
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../registration.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            //if > 0, duplicate username found
            if ($resultCheck > 0) {
                header("Location: ../registration.php?error=usertaken&email=" . $email);
                exit();
            }
        }
        //check for duplicate email
        $sql = "SELECT * FROM users WHERE email = ?; ";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../registration.php?error=sqlerror-emailCheck");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                //email found in DB - duplicate
                header("Location: ../registration.php?error=emailtaken");
                exit();
            }
        }

        //no duplicates found - insert user info into DB
        $sql = "INSERT INTO users (username, password, firstname, lastname, email, phone) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../registration.php?error=userinfo-sqlerror");
            exit();
        } else {
            //password hashing
            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

            mysqli_stmt_bind_param($stmt, "ssssss", $username, $hashedPwd, $firstName, $lastName, $email, $phone);
            mysqli_stmt_execute($stmt);
        }
        //insert into address table in DB with userID foreign key
        $sql = "INSERT INTO address (userID) SELECT userID FROM users WHERE username=?; ";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../registration.php?error=userIDinsert-sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
        }
        //update address record with fields from registration form
        $sql = "UPDATE address
                        SET address=?,
                            address2=?,
                            city=?,
                            state=?,
                            zipcode=?,
                            country=?
                        WHERE
                            userID = (SELECT userID FROM users WHERE username=?);";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../registration.php?error=address-sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "sssssss", $address, $address2, $city, $state, $zipcode, $country, $username);
            mysqli_stmt_execute($stmt);

            //user account info and user address has been inserted
            //Send user activation email
            //PHPMailer setup
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '587';
            $mail->isHTML(true);
            $mail->Username = 'secrets.email';
            $mail->Password = 'secrets.emailpw';
            $mail->SetFrom('no-reply@sktstore.com');
            $mail->Subject = 'Activate Your Account';

            //tokens - authentication
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);
            //url for user
            $url = "localhost/bookstore/account-activate.php?selector=" . $selector . "&validator=" . bin2hex($token);
            //token expiration
            $expires = date("U") + 3600;
            //insert into activation table in db
            $sql = "INSERT INTO useractivate (userActivateEmail, userActivateSelector, userActivateToken, userActivateExpires) VALUES (?, ?, ?, ?); ";
            $stmt = mysqli_stmt_init($connection);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../registration.php?mailError=activatemailsqlerror");
                exit();
            } else {
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hashedToken, $expires);
                mysqli_stmt_execute($stmt);
            }

            //PHPMailer continuted...
            //Email message to user with activation instructions
            $message = '<p> A new account was created with this email. <br></p>';
            $message .= '<p> If you did not request this, ignore this message.<br></p>';
            $message .= '<p> Here is your account activation link: <br></p>';
            $message .= '<a href="' . $url . '">' . $url . '</a>';
            $mail->Body = $message;
            $mail->AddAddress($email);

            if (!$mail->Send()) {
                $msg = "Mailer Error: " . $mail->ErrorInfo;
                header("Location: ../registration.php?mailError=$msg");
                exit();
            } else {
                //all successful, redirect to submission message
                header("Location: /bookstore/submission.html");
                exit();
            }
        }
    }
    //close db connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    //this includes file should not be accessed directly - redirect to registration
    header("Location: ../registration.php?registration=badlink");
    exit();
}
