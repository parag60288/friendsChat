let SecureConnection=false;
var messageIntervalMS = 1000;

let protocol='http';
if (SecureConnection) {
    protocol = 'https';
}
let mylocation=protocol+'://'+window.location.hostname+"/api.php";

var oldestMessage = -1;
var newestMessage = -1;

var normalHeight;

loadNewMessenges(true, false);

function loadNewMessenges(firstmessage, newMessage) {
    resizeWindow();
    if (firstmessage) {
        fetch(mylocation+"?function=getMessenges")
            .then(function(response) {
                return response.json();
            })
            .then(function(jsonResponse) {
                addMessages(jsonResponse, true, true);
            });
        sendNotification();
        setTimeout(function(){loadNewMessenges(false, true)}, messageIntervalMS);
    }
    else if (newMessage){
        fetch(mylocation+"?function=getMessenges&latestMessageNR="+newestMessage)
            .then(function(response) {
                return response.json();
            })
            .then(function(jsonResponse) {
                addMessages(jsonResponse, true);
            });
        setTimeout(function(){loadNewMessenges(false, true)}, messageIntervalMS);
    }
    else{
        fetch(mylocation+"?function=getMessenges&oldestMessageNR="+oldestMessage)
            .then(function(response) {
                return response.json();
            })
            .then(function(jsonResponse) {
                addMessages(jsonResponse, false);
            });
    }
}

function sendMessage(){
    let message = document.getElementById("input").value;
    document.getElementById("input").value="";

    if(message.length==0){
        return false;
    }
    fetch(mylocation+"?function=sendMessage&message="+message);
}

function addMessages(jsonResponse, isNew, chatInit=false){

    var scrollBottom = false;
    if (document.getElementById("chatScroller").scrollTop===document.getElementById("chatScroller").scrollTopMax){
        scrollBottom = true;
    }

    for (let i = 0; i<jsonResponse.newMessenges.length; i++){

        let row = "<tr><td class='username'>"+ jsonResponse.newMessenges[i].username + "</td> <td class='datetime'>"+jsonResponse.newMessenges[i].datetime+"</td> <td class='message'>" + jsonResponse.newMessenges[i].message + "</td>";
        if (isNew) {
            document.getElementById("chatTable").innerHTML += row;
        }
        else{
            document.getElementById("chatTable").innerHTML = row + document.getElementById("chatTable").innerHTML;
        }

        let messageNR = parseInt(jsonResponse.newMessenges[i].messageNR);

        if(chatInit){
            if (i==0) {
                oldestMessage=messageNR;
            }
            newestMessage=messageNR;
        }
        else if (messageNR<oldestMessage){
            oldestMessage=messageNR;
        }
        else if (messageNR>newestMessage){
            newestMessage=messageNR;
            if (jsonResponse.newMessenges[i].username!=document.getElementById("username").innerText){
                sendNotification(jsonResponse.newMessenges[i].username + ": " + jsonResponse.newMessenges[i].message)
            };
        }
    }

    if (scrollBottom) {
        document.getElementById("chatScroller").scrollTop = document.getElementById("chatScroller").scrollTopMax;
    }

    if (oldestMessage>0 && document.getElementById("chatScroller").scrollTop===0){
        document.getElementById("oldButton").hidden=false;
        document.getElementById("chatScroller").style.height = normalHeight+'vh';
    }
    else{
        document.getElementById("oldButton").hidden=true;
        document.getElementById("chatScroller").style.height = (normalHeight+4)+'vh';
    }
}

function sendNotification(message='') {
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    }

    else if (Notification.permission === "granted") {
        if (message!='') {
            var notification = new Notification(message);
        }
    }

    else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(function (permission) {
            if (permission === "granted") {
                var notification = new Notification('You will receive your chat notifications here :)');
            }
        });
    }
}

function resizeWindow(){
    if (window.innerWidth>1024 && window.innerWidth>window.innerHeight){
        normalHeight = 65;
    }
    else{
        normalHeight = 71;
    }
}