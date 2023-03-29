<style>

.chatinputrow{margin-bottom:10px;}
.chatinp{line-height:28px;padding:2px 5px;font-size:16px;height:30px;}
textarea.chatinp{vertical-align:middle;}

.gschatmain{z-index:1000;position:fixed;bottom:30px;right:10px;}
.gschatcontent{width:100%;max-width:420px;background:#ffffff;border:solid 1px #dedede;box-shadow:-2px 2px 10px #999999;paddinga:10px;border-radius:5px;}
.gschatbox{overflow:auto;}

.chatentry{margin-bottom:15px;}
.chatentry_0{margin-left:2%;margin-right:15%;}
.chatentry_1{margin-left:20%;margin-right:2%;}

.chatfrom{font-size:13px;color:#666666;}



.chatbubble{color:#ffffff;border-radius:12px;}
.chatbubble.dir_0,.chatbubble.dir_1{padding:10px;}
.chatbubble.dir_0{background:#9F9F9F;}
.chatbubble.dir_1{background:#2EAE7A;}

.chatbadge{position:absolute;top:-30px;right:10px;background:#004400;color:#ffffff;border-radius:40px;padding:10px 20px;cursor:pointer;}

.chatframe{text-align:right;padding:10px;cursor:pointer;font-weight:bold;border-bottom:solid 1px #999999;padding-bottom:5px;}
.chatframe_close{font-weight:bold;color:#ab0200;font-size:18px;}

#gschat_hasagent{position:absolute;top:10px;left:10px;padding:3px 0;margin-bottom:5px;}

#gschat_chatform button{padding:5px 8px;}
.chatforminner{padding:5px;}

.chatbadge{display:none;}

/*
.chatstatus_0 .chathasagent_0 .chatbadge{display:none;}
.chatstatus_0 .chathasagent_1 .chatbadge{display:block;}
*/

.chatstatus_0 .chathasagent_1 .chatviewstate_1 .chatbadge{display:none;}

/*
.chatstatus_1 .chatviewstate_ .chatbadge{display:block;}
.chatstatus_1 .chatviewstate_1 .chatbadge{display:none;}
.chatstatus_1 .chathasagent_1 .chatviewstate_0 .chatbadge{display:block;}
*/

.chatstatus_0 .gschatcontent{display:none;}
.chatstatus_1 .gschatcontent{display:block;}
.chatviewstate_ .gschatcontent{display:none;}
.chatviewstate_0 .gschatcontent{display:none;}
.chatviewstate_1 .gschatcontent{display:block;}


@media screen and (max-width:420px){
	.chatinp{display:block;width:86%;margin-bottom:5px;}	
}

@media (prefers-color-scheme:dark) {
	
	.gschatcontent{box-shadow:none;background:#2D3239;border:solid 1px #294B70;color:#cccccc;}
}
</style>
<!-- start gschat snippet -->
<div id="gschat" class="gschatmain">
<div id="gschat_chatstatus" class="chatstatus_0">
<div id="gschat_chathasagent" class="chathasagent_0">
<div id="gschat_chatviewstate" class="chatviewstate_">
	<div id="gschat_chatbadge" class="chatbadge" onclick="gschat_setviewstate(1);">
		Chat
	</div>
	<div class="gschatcontent">
		<div id="gschat_hasagent" style="visibility:hidden;">...</div>
		<div class="chatframe" onclick="gschat_setviewstate(0);">
			<!-- a class="chatframe_open" onclick="gschat_setviewstate(1);">Live Chat</a -->
			<a class="chatframe_close" onclick_="gschat_setviewstate(0);">&times;</a>
		</div>
		<div id="gschat_chatinfo">
			<div class="chatinputrow">
				<div class="chatformlabel">First Name:</div>
				<input class="chatinp" id="gschat_chatfname">
			</div>
			<div class="chatinputrow">
				<div class="chatformlabel">Last Name:</div>
				<input class="chatinp" id="gschat_chatlname">
			</div>
			<div class="chatinputrow" >
				<button onclick="gschat_start();" id="gschat_startbutton">Start Chat</button>
			</div>
			
		</div><!-- chatinfo -->
		
		<div id="gschat_chatbox" class="gschatbox" style="display:none;"></div>
		<div id="gschat_gschatreconnect" style="padding:5px 0;display:none;">
			<button onclick="gschat_init(document.chatcontainer,document.portalparams,document.gsid,document.gsauth);">Reconnect</button>
		</div>
		<form id="gschat_chatform" style="display:none;" method="GET" onsubmit="gschat_send();return false;">
		<div class="chatforminner">
			<textarea onkeyup="gschat_inpkeydown(this);" id="gschat_chatbox_input" placeholder="chat here..." class="chatinp"></textarea>
			<button id="gschat_sendbutton">Send</button>			
						&nbsp;
			<button onclick="gschat_end();return false;">End</button>
		</div>
		</form>
	</div>
	<div id="gschat_chattransport" style="color:#dedede;display:none;"></div>
</div><!-- chatviewstate -->
</div><!-- chathasagent -->
</div><!-- chatstatus -->
</div>
<!-- end gschat snippet; js block to be copied separately -->