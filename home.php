<?php

//home.php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
}

if (isset($_SESSION["user_name"], $_SESSION["user_id"])) {
    $user_name = $_SESSION["user_name"];
    $user_id = $_SESSION["user_id"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://code.jquery.com/jquery.js"></script>
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>

    <div class="container">
        <div class="homme__page">
            Welcome its your webpage <?php echo $user_name; ?>
            <div class="home__user">
                <div class="home__avatar"></div>
                <div class="home__name"><?php echo $user_name; ?></div>
            </div>
            <div class="home__out"><a href="logout.php" class="btn btn-default">Logout</a></div>
        </div>
        <div class="home__info"></div>
    </body>

</html>