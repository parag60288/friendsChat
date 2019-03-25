<?php include_once "top.php";

if (isset($_POST["enableUsername"])){
    $username=$_POST["enableUsername"];
    databaseQuery("UPDATE users SET enabled=true WHERE username='".$username."'");
}

if (isset($_POST["disableUsername"])){
    $username=$_POST["disableUsername"];
    databaseQuery("UPDATE users SET enabled=false WHERE username='".$username."'");
}
?>

<div class="container">
    <div id="contentContainer">
        <div id="generateCode">
        	<h3>Generate Code</h3>
            <?php
                if (isset($_GET['registrationCodeRequest'])) {
                    echo '<p>the following link can be used to register 1 person. this link is valid for 24 hours <br> <a href="#">'.getMyHostName().'/register.php?registerCode='.generateRegistrationCode().'</a></p>';
                }
            ?>
            <form action="" method="get"><input type="submit" name="registrationCodeRequest" value="Genrate register URL"></form>
        </div>

        <div id="disableUserForm">
            <form id="disableUser" name="disableUser" action="" method="post"></form>
            <h3>Disable User</h3>
            <select name="disableUsername" id="" form="disableUser">
                <?php
                    printUserList("option", true, true);
                ?>
            </select><input type="submit" name="disableuser" id="" value="disable user" form="disableUser">
        </div>

        <div id="enableUserForm">
            <form id="enableUser" name="enableUser" action="" method="post"></form>
            <h3>Enable User</h3>
            <select name="enableUsername" id="" form="enableUser">
                <?php
                    printUserList("option", false);
                ?>
            </select><input type="submit" name="enableuser" id="" value="enable user" form="enableUser">
        </div>
    </div>
</div>


