<?php include_once "top.php";
if (isset($_POST["username"]) && isset($_POST["password"])){
    if (login($_POST["username"], $_POST["password"])){
        header("Location: chat.php");
    };
}
?>

<div class="container">
    <div id="contentContainer">
        <form name="login" id="login" action="#" method="post"></form>
        <div id="loginform">
            <h2>Login</h2>
            <table>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" id="username" form="login" required></td>
                </tr>
                <tr>
                    <td>password</td>
                    <td><input type="password" name="password" id="password" form="login" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="" id="" form="login"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
