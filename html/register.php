<?php include_once "top.php";

if (isset($_POST["email"])){
    $username=$_POST["username"];
    $password=$_POST["password"];
    $email=$_POST["email"];

    if (!setupComplete()) {
        addUser($username, $password, $email, true);
    }
    elseif (checkRegisterCode($_POST['registerCode'])) {
        addUser($username, $password, $email, false, $_POST['registerCode']);
    }
}

$title = 'Complete setup';

if (isset($_GET['registerCode'])) {
    if (!checkRegisterCode($_GET['registerCode'])) {
        header("Location: index.php");
    }
    else{
        $title='Register';
    }
}
else if (setupComplete()){
    header("Location: index.php");
}
?>

<main>
    <form name="addAdmin" id="addAdmin" action="#" method="post"></form>

    <div id="addAdminForm">
        <h2><?php echo $title;?></h2>
        <table>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" id="" form="addAdmin" required></td>
            </tr>
            <tr>
                <td>password</td>
                <td><input type="password" name="password" id="" form="addAdmin" required></td>
            </tr>
            <tr>
                <td>email</td>
                <td><input type="email" name="email" id="" form="addAdmin" required></td>
            </tr>
            <tr hidden>
                <td><input type="text" name="registerCode" form="addAdmin" value="<?php echo $_GET['registerCode'];?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="" id="" form="addAdmin"></td>
            </tr>
        </table>
    </div>
</main>
