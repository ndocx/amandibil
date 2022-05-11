<?php

session_start();

if (isset($_SESSION["user_id"])) {
    header("location:home.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://code.jquery.com/jquery.js"></script>
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
   
    <div class="container">
        <?php
        if (isset($_GET["register"])) {
            if ($_GET["register"] == 'success') {
                echo '<script> alert(Email Successfully verified, Registration Process Completed...)</script>';
            }
        }
        ?>
            <div >&nbsp;</div>
            <div class="login__page">
                    <div class="login__title">Login</div>
                    <div class="login__form">
                        <form method="POST" id="login_form" class="login__inputs">
                            <div class="login__input" id="email_area">
                                <input type="text" name="user_email" id="user_email" placeholder="Enter Email Address"/>
                                <div><span id="user_email_error" class="text-danger"></span></div>
                            </div>
                            <div class="login__input" id="password_area" style="display:none;">
                                <input type="password" name="user_password" id="user_password" placeholder="Enter password"/>
                                <div><span id="user_password_error" class="text-danger"></span></div>
                            </div>
                            <div class="login__input" id="otp_area" style="display:none;">
                                <input type="text" name="user_otp" id="user_otp" class="form-control" placeholder="Enter verify code" />
                                <div><span id="user_password_error" class="text-danger"></span></div>
                            </div>
                            <div class="login__input">
                                <input type="hidden" name="action" id="action" value="email" />
                                <input type="submit" name="next" id="next" class="btn btn-primary" value="Next" />
                            </div>
                        </form>
                    </div>
            </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#login_form').on('submit', function(event) {
            event.preventDefault();
            var action = $('#action').val();
            $.ajax({
                url: "login_verify.php",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#next').attr('disabled', 'disabled');
                },
                success: function(data) {
                    $('#next').attr('disabled', false);
                    if (action == 'email') {
                        if (data.error != '') {
                            $('#user_email_error').text(data.error);
                        } else {
                            $('#user_email_error').text('');
                            $('#email_area').css('display', 'none');
                            $('#password_area').css('display', 'block');
                        }
                    } else if (action == 'password') {
                        if (data.error != '') {
                            $('#user_password_error').text(data.error);
                        } else {
                            $('#user_password_error').text('');
                            $('#password_area').css('display', 'none');
                            $('#otp_area').css('display', 'block');
                        }
                    } else {
                        if (data.error != '') {
                            $('#user_otp_error').text(data.error);
                        } else {
                            window.location.replace("home.php");
                        }
                    }

                    $('#action').val(data.next_action);
                }
            })
        });
    });
</script>