<?php


use PHPMailer\PHPMailer\PHPMailer;



$connect = new PDO("mysql:host=localhost; dbname=testing", "root", "");

$message = '';
$error_user_name = '';
$error_user_email = '';
$error_user_password = '';
$user_name = '';
$user_email = '';
$user_password = '';


if (isset($_POST["register"])) {
    if (empty($_POST["user_name"])) {
        $error_user_name = "<label class='text-danger'>Enter Name</label>";
    } else {
        $user_name = trim($_POST["user_name"]);
        $user_name = htmlentities($user_name);
    }

    if (empty($_POST["user_email"])) {
        $error_user_email = '<label class="text-danger">Enter Email Address</label>';
    } else {
        $user_email = trim($_POST["user_email"]);
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $error_user_email = '<label class="text-danger">Enter Valid Email Address</label>';
        }
    }

    if (empty($_POST["user_password"])) {
        $error_user_password = '<label class="text-danger">Enter Password</label>';
    } else {
        $user_password = trim($_POST["user_password"]);
        $user_password = password_hash($user_password, PASSWORD_DEFAULT);
    }

    if ($error_user_name == '' && $error_user_email == '' && $error_user_password == '') {
        $user_activation_code = md5(rand());

        $user_otp = rand(100000, 999999);

        $data = array(
            ':user_name'  => $user_name,
            ':user_email'  => $user_email,
            ':user_password' => $user_password,
            ':user_activation_code' => $user_activation_code,
            ':user_email_status' => 'not verified',
            ':user_otp'   => $user_otp
        );

        $query = "INSERT INTO register_user(user_name, user_email, user_password, user_activation_code, user_email_status, user_otp) SELECT * FROM (SELECT :user_name, :user_email, :user_password, :user_activation_code, :user_email_status, :user_otp) AS tmp WHERE NOT EXISTS (SELECT user_email FROM register_user WHERE user_email = :user_email) LIMIT 1";

        $statement = $connect->prepare($query);

        $statement->execute($data);

        if ($connect->lastInsertId() == 0) {
            $message = '<label class="text-danger">Email Already Register</label>';
        } else {
            require("PHPMailer/PHPMailer.php");
            require("PHPMailer/SMTP.php");
            require("PHPMailer/Exception.php");
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'imanbekovphp12@gmail.com';
            $mail->Password = 'amangeldi12';
            $mail->SMTPSecure = "tls";  // tls or ssl
            $mail->smtpConnect([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            $mail->From = 'imanbekovphp12@gmail.com';
            $mail->FromName = 'AMAN-WEB';
            $mail->AddAddress($user_email);
            $mail->WordWrap = 50;
            $mail->IsHTML(true);
            $mail->Subject = 'Verification code for Verify Your Email Address';

            $message_body = '
                <p>For verify your email address, enter this verification code when prompted: <b>' . $user_otp . '</b></p>
            ';
            $mail->Body = $message_body;

            if ($mail->Send()) {
                echo '<script>alert("Please Check Your Email for Verification Code")</script>';

                header('location:email_verify.php?code=' . $user_activation_code);
            } else {
                $message = $mail->ErrorInfo;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://code.jquery.com/jquery.js"></script>
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <header class="header">
        <div class="header__title">Registration</div>
        <div class="header__input">
            <form method="post" class="inputs">
                <div class="form-group">
                    <input type="text" name="user_name" placeholder="Enter Your Name" />
                    <?php echo $error_user_name; ?>
                </div>
                <div class="form-group">
                    <input type="text" name="user_email" placeholder="Enter Your Email" />
                    <?php echo $error_user_email; ?>
                </div>
                <div class="form-group">
                    <input type="password" name="user_password" placeholder="Enter Your Password" />
                    <?php echo $error_user_password; ?>
                </div>
                <div class="buttons">
                    <input type="submit" name="register" value="Register" class="button" />&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;
                    <div  class="login"><a href="login.php">Login</a></div>
                </div>
            </form>
        </div>
    </header>
</body>

</html>