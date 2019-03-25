<?php include_once "top.php";?>

<script type="text/javascript" src="/js/chat.js"></script>
<div class="container">
	<div id="contentContainer">
		<button id="oldButton" onclick="loadNewMessenges(false, false);">load older messages</button>
	    <div id="chatScroller">
		    <table id="chatTable">

		    </table>
	    </div>

	    <div id="chatbox">
	        <textarea id="input"></textarea>
	        <button onclick="sendMessage()">send</button>
	    </div>
    </div>
</div>

