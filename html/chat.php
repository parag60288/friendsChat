<?php include_once "top.php";?>

<script src="<?php echo getMyHostName()?>/js/chat.js"></script>
<main>
	<button id="oldButton" onclick="loadNewMessenges(false, false);">load older messages</button>
    <div id="chatScroller">
	    <table id="chatTable">

	    </table>
    </div>

    <div id="chatbox">
        <textarea id="input"></textarea>
        <button onclick="sendMessage()">send</button>
    </div>
</main>

