<?php
session_start();
include_once "functions.php";

PageProtectionRedirect();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div>
        <nav class="container">
            <p id='userNav'>
            <?php
                if (isset($_SESSION["username"])) {
                    if (isLoggedIn(false)) {
                        echo '<p><b>FriendsChat BETA</b> &nbsp; welcome <span id="username">'.$_SESSION['username'].'</span> <span id="navLinks">';
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
                echo "</span></p>";
            ?>
        </nav>
    </div>
