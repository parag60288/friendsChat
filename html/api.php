<?php

include_once "functions.php";
session_start();

if(isset($_GET["function"]) && isset($_SESSION["username"]) && isset($_SESSION["key"])){
    $function = $_GET["function"];
    $username = $_SESSION["username"];
    $password = $_SESSION["key"];
    if (isLoggedIn()){
        switch ($function){
            case "getMessenges":
                getMessenges();
                break;
            case "sendMessage":
                sendMessage($username);
                break;
            default:
                break;
        }
    }
    else{
        echo "login incorrect";
    }
}
else{
    echo "not all required fields are send";
}

function getMessenges()
{
    $query="";
    if (isset($_GET["latestMessageNR"])) {
        $latestMessageNR = $_GET["latestMessageNR"];
        $query = "SELECT * FROM chat where messageNR > " . $latestMessageNR;
    } else if (isset($_GET["oldestMessageNR"])) {
        $oldestMessageNR = $_GET["oldestMessageNR"];
        $query = "SELECT * FROM chat where messageNR > " . ($oldestMessageNR - 10) . " AND messageNR<" . $oldestMessageNR." ORDER BY LENGTH(messageNR) desc, messageNR desc";
    }
    else{
        $messageCount=databaseRows("SELECT * FROM chat");
        $query = "SELECT * FROM chat where messageNR > " . ($messageCount - 10);
    }

    $messenges = databaseQuery($query);

    $apiMessageCount=count($messenges);

    echo '{"newMessenges":[';
    for ($i=0; $i<$apiMessageCount;$i++){
        echo '{"messageNR": "' . $messenges[$i][0] . '", "username": "' . getusername($messenges[$i][1]) . '", "datetime": "' . $messenges[$i][2] . '", "message": "' . $messenges[$i][3] . '"}';
        if ($i+1<$apiMessageCount){
            echo ",";
        }
    }
    echo '], "count":'.$apiMessageCount.'}';
}

function sendMessage($username){
    if (isset($_GET["message"])){
        $message=htmlspecialchars($_GET["message"]);
        $message = str_replace("'", "\'", $message);
        $messageCount=databaseRows("SELECT * FROM chat");
        $userID=databaseQuery("SELECT * FROM users WHERE username='".$username."'")[0][0];
        databaseQuery("INSERT INTO chat VALUES(".$messageCount.", ".$userID.", SYSDATE(), '".$message."')");
    }
}