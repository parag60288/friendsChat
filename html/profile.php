<?php include_once "top.php";

if (isset($_POST["username"]) || isset($_POST["password"]) || isset($_POST["email"])) {
    if (isset($_POST["username"])){
        updateProfileProperty('username', $_POST["username"]);
    }
    else if (isset($_POST["password"])){
        updateProfileProperty('password', $_POST["password"]);
    }
    else if (isset($_POST["email"])){
    	updateProfileProperty('email', $_POST["email"]);
    }
}
?>

<main>
    <div id="changeUsernameForm">
        <form id="changeUsername" name="changeUsername" action="" method="post"></form>
        <h3>Change Username</h3>
        <tr>
            <td>when changing username, old messanges are updated on display when you reload the page</td>
        </tr>
        <table>
            <tr>
                <td><input type="text" name="username" form="changeUsername" required></td>
                <td><input type="submit" name="changepass" id="" value="change username" form="changeUsername"></td>
            </tr>
        </table>
    </div>

    <div id="changePasswordForm">
        <form id="changePassword" name="changePassword" action="" method="post"></form>
        <h3>Change password</h3>
        <table>
            <tr>
                <td><input type="password" name="password" form="changePassword" required></td>
                <td><input type="submit" name="changepass" id="" value="change password" form="changePassword"></td>
            </tr>
        </table>
    </div>

    <div id="changeEmailForm">
        <form id="changeEmail" name="changeEmail" action="" method="post"></form>
        <h3>Change email</h3>
        <table>
            <tr>
                <td><input type="email" name="email" form="changeEmail" required></td>
                <td><input type="submit" name="changepass" id="" value="change email" form="changeEmail"></td>
            </tr>
        </table>
    </div>
</main>


