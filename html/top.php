<?php
session_start();
include_once "functions.php";

PageProtectionRedirect();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <nav>
        <p id='userNav'>
        <?php
            if (isset($_SESSION["username"])) {
                if (isLoggedIn(false)) {
                    echo 'welcome <span id="username">'.$_SESSION['username'].'</span> &nbsp; &nbsp; &nbsp;';
                }
                else{
                    logout();
                }
            }
            
            if (isLoggedIn(false)){
                echo '<a href="info.php">About</a> &nbsp; <a href="chat.php">Chat</a> &nbsp; <a href="profile.php">Profile</a> &nbsp;';
                if (isAdmin()){
                    echo '<a href="admin.php">Admin</a>';
                }
                echo '&nbsp; <a href="index.php?logout=true">logout</a>';
            }
            else{
                echo '<a href="index.php">Login</a> &nbsp; <a href="info.php">About</a> &nbsp;';
            }
        ?>

        </p>&nbsp; <b>FriendsChat BETA</b> &nbsp; all viewed times are Amsterdam time</p>
    </nav>
