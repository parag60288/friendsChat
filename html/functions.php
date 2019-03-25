<?php
$GLOBALS["databaseLocation"]="localhost";
$GLOBALS["databaseUsername"]="root";
$GLOBALS["databasePassword"]="";
$GLOBALS["databasename"]="chat";
$GLOBALS["secureConnection"]=false;

/**********************
 * DATABASE FUNCTIONS *
 **********************/

function setupComplete(){
    if (databaseRows('SHOW TABLES FROM '.$GLOBALS["databasename"], true)>0) {
        if (databaseRows("SELECT * FROM users")>0){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        databaseQuery( 'CREATE DATABASE IF NOT EXISTS '.$GLOBALS["databasename"], true);
        databaseQuery( 'SET FOREIGN_KEY_CHECKS = 0;
                        DROP TABLE IF EXISTS `users`;
                        DROP TABLE IF EXISTS `chat`;
                        DROP TABLE IF EXISTS `registercodes`;
                        SET FOREIGN_KEY_CHECKS = 1;

                        CREATE TABLE `users` (
                            `userID` SMALLINT NOT NULL,
                            `username` VARCHAR(128) NOT NULL,
                            `password` CHAR(60) NOT NULL,
                            `email` VARCHAR(255),
                            `isAdmin` BOOLEAN NOT NULL,
                            `enabled` BOOLEAN NOT NULL,
                            PRIMARY KEY (`userID`)
                        );

                        CREATE TABLE `chat` (
                            `messageNR` BIGINT NOT NULL,
                            `userID` SMALLINT NOT NULL,
                            `dateTime` DATETIME NOT NULL,
                            `message` VARCHAR(2048) NOT NULL,
                            PRIMARY KEY (`messageNR`, `userID`),
                            UNIQUE (`messageNR`)
                        );

                        CREATE TABLE `registercodes` (
                            `code` CHAR(128) NOT NULL,
                            `active` BOOLEAN NOT NULL,
                            `validUntill` DATETIME NOT NULL,
                            PRIMARY KEY (`code`)
                        );

                        ALTER TABLE `chat` ADD FOREIGN KEY (`userID`) REFERENCES `users`(`userID`);');
        return false;
    }
}

function databaseRows($query, $noDatabase=false){
    $conn;
    if ($noDatabase) {
        $conn=mysqli_connect($GLOBALS["databaseLocation"], $GLOBALS["databaseUsername"], $GLOBALS["databasePassword"]);
    }
    else{
        $conn=mysqli_connect($GLOBALS["databaseLocation"], $GLOBALS["databaseUsername"], $GLOBALS["databasePassword"], $GLOBALS["databasename"]);
    }

    if ($conn) {
        $queryResult = mysqli_query($conn, $query);
        if (strtoupper(substr($query, 0, 6))=="SELECT" || strtoupper(substr($query, 0, 4))=="SHOW"){
            if ($queryResult==false) {
                return 0;
            }
            else{
                return mysqli_num_rows($queryResult);
            }
        }
    }
    else{
        return 0;
    }
}

function databaseQuery($query, $noDatabase=false){
    $conn;
    if ($noDatabase) {
        $conn=mysqli_connect($GLOBALS["databaseLocation"], $GLOBALS["databaseUsername"], $GLOBALS["databasePassword"]);
    }
    else{
        $conn=mysqli_connect($GLOBALS["databaseLocation"], $GLOBALS["databaseUsername"], $GLOBALS["databasePassword"], $GLOBALS["databasename"]);
    }

    if ($conn) {
        if (substr_count($query, "\n")) {
            mysqli_multi_query($conn, $query);
        }
        else{
            $queryResult = mysqli_query($conn, $query);
            echo mysqli_error($conn);
            if (strtoupper(substr($query, 0, 6))=="SELECT" || strtoupper(substr($query, 0, 4))=="SHOW"){
                return mysqli_fetch_all($queryResult);
            }
        }
    }
}

/******************
 * USER FUNCTIONS *
 ******************/

function addUser($username, $password, $email, $isAdmin, $code=""){
    $isAdminStr='false';
    if ($isAdmin) {
        $isAdminStr='true';
    }

    $userCount = databaseRows("SELECT * FROM users")+1;
    $username=htmlspecialchars($username);
    $password=password_hash(htmlspecialchars($password), PASSWORD_BCRYPT);
    $email=htmlspecialchars($email);

    databaseQuery("INSERT INTO users VALUES (".$userCount.", '".$username."', '".$password."', '".$email."', ".$isAdminStr.", true)");
    if ($code!="") {
        databaseQuery("UPDATE registercodes set active=false where code='".$code."'");
    }

}

function getusername($userID){
    return databaseQuery("SELECT * FROM users WHERE userID=".$userID)[0][1];
}

function login($username, $password){
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    $data = databaseQuery("SELECT * FROM users WHERE username ='".$username."' AND enabled=true");
    if (count($data)>0) {
        if (password_verify($password, $data[0][2])){
            $_SESSION["username"]=$username;
            $_SESSION["key"]=$data[0][2];
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function logout(){
    session_destroy();
    session_abort();
}

function isLoggedIn($backToIndex=false){
    if (isset($_SESSION["username"]) && isset($_SESSION["key"])) {
        if (databaseRows("SELECT * FROM users WHERE username ='" . $_SESSION["username"] . "' AND password='" . $_SESSION["key"] . "' AND enabled=true") == 1) {
            return true;
        } else {
            if ($backToIndex) {
                header("Location: index.php");
            }
            return false;
        }
    }
    else{
        if ($backToIndex) {
            header("Location: index.php");
        }
        return false;
    }
}

function isAdmin($backToIndex=false){
    if (isset($_SESSION["username"]) && isset($_SESSION["key"])) {
        if (databaseRows("SELECT * FROM users WHERE username ='" . $_SESSION["username"] . "' and password='" . $_SESSION["key"] . "' and isAdmin=true AND enabled=true") == 1) {
            return true;
        } else {
            if ($backToIndex) {
                header("Location: index.php");
            }
            return false;
        }
    }
    else{
        if ($backToIndex) {
            header("Location: index.php");
        }
        return false;
    }
}

function getUserID(){
    $password = $_SESSION['key'];
    $temp = databaseQuery('SELECT * FROM users WHERE password="'.$password.'"');
    $userID = $temp[0][0];
    return $userID;
}

function printUserList($tag, $enabled, $hideadmin=false){
    if ($enabled==true){
        $enabled="true";
    }
    else{
        $enabled="false";
    }

    $query = "SELECT * FROM users WHERE enabled=".$enabled;
    if ($hideadmin){
        $query = "SELECT * FROM users WHERE isAdmin=false AND enabled=".$enabled;
    }
    $userlist = databaseQuery($query);
    foreach ($userlist as $user){
        echo "<".$tag.">".$user[1]."</".$tag.">";
    }
}

function updateProfileProperty($property, $value){
    if ($property == 'password') {
        $value = password_hash(htmlspecialchars($value), PASSWORD_BCRYPT);
    }

    $userID = getUserID();
    databaseQuery('UPDATE users set '.$property.'="'.$value.'" where userID='.$userID);

    if ($property=='username') {
        $_SESSION['username']=$value;
        header('Location: profile.php');
    }
    else if($property=='password'){
        $_SESSION['key']=$value;
    }
}

/**************************
 * REGISTRATION FUNCTIONS *
 **************************/

function generateRegistrationCode(){
    $code = bin2hex(random_bytes(64));
    $daysValid = 1;
    databaseQuery('INSERT INTO registercodes VALUES("'.$code.'", true, DATE_ADD(NOW(), INTERVAL 1 DAY))');
    return $code;
}

function checkRegisterCode($code){
    if (databaseRows('SELECT * FROM registercodes WHERE code="'.$code.'" AND active=true AND validUntill > NOW()')==1) {
        return true;
    }
    else{
        return false;
    }
}

/*******************
 * OTHER FUNCTIONS *
********************/

function PageProtectionRedirect(){
    $filename = basename($_SERVER['PHP_SELF']);
    if(isset($_GET["logout"])){
        logout();
    }

    if(!setupComplete() && $filename!="register.php"){
        header("Location: register.php");
    }
    else if($filename=="admin.php"){
        isAdmin(true);
    }
    else if($filename=="index.php" || $filename=="register.php"){
        if (isLoggedIn(false)) {
            header("Location: chat.php");
        }
    }
    else if ($filename!="index.php" && $filename!="register.php" && $filename!="info.php"){
        isLoggedIn(true);
    }
}

function getMyHostName(){
    $protocol='http://';
    if ($GLOBALS["secureConnection"]){
        $protocol = 'https://';
    }
    return $protocol.$_SERVER['SERVER_NAME'];
}
