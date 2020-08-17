<?php

///////
// CWChat (Chris' Website Chat) - 2013, 2014
// (C) Chris Dorman, GPL v3 - (C) Microchat devs
// https://github.com/crazycoder13/cwchat
// Version: 0.1.1a
///////

session_start();
include "config.php";

function loginForm(){
?>
	<br>
	<div class="login">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?do=registerform">Register</a>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?do=login" method="post">
			Username: <input style="padding: 2px;" class="text" type="text" name="username"><br>
			<input style="padding: 2px;" class="text" type="submit" name="submitBtn" value="Login">
		</form>
	</div>
<?php
}

//Logout
if (isset($_GET['do']) && $_GET['do']=="logout") {
	$_SESSION['cwchat-user'] = null;
}

if (isset($_GET['do']) && $_GET['do']=="login" && isset($_POST['submitBtn'])){
	$name = isset($_POST['username']) ? htmlentities(stripslashes($_POST['username'])) : "Unnamed";
	$_SESSION['cwchat-user'] = $name;
}
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
   <title>CWChat</title>
   <link href="style.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript">
    <!--
		var httpObject = null;
		var link = "";
		var timerID = 0;
		var nickName = "<?php echo $_SESSION['cwchat-user']; ?>";

		// Get the HTTP Object
		function getHTTPObject() {
			if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
			else if (window.XMLHttpRequest) return new XMLHttpRequest();
			else {
				alert("Your browser does not support AJAX.");
				return null;
			}
		}   

		// Change the value of the outputText field
		function setHtml() {
			if(ajaxVar.readyState == 4){
				var response = ajaxVar.responseText;
				var msgBox = document.getElementById("msgs");
				msgBox.innerHTML += response;
				msgBox.scrollTop = msgBox.scrollHeight;
			}
		}

		// Change the value of the outputText field
		function setAll() {
			if(ajaxVar.readyState == 4){
				var response = ajaxVar.responseText;
				var msgBox = document.getElementById("msgs");
				msgBox.innerHTML = response;
				msgBox.scrollTop = msgBox.scrollHeight;
			}
		}

		// Implement business logic    
		function serverWrite() {    
			ajaxVar = getHTTPObject();
			if (ajaxVar != null) {
				link = "server.php?nick="+nickName+"&msg="+document.getElementById('msg').value; 
				ajaxVar.open("GET", link , true);
				ajaxVar.onreadystatechange = setHtml;
				ajaxVar.send(null);
			}
		}
      
		function getInput() {
			// Send the server function the input
			var userInput = document.getElementById('msg');
			serverWrite(userInput.value);
			
			// Clean out the input values
			var msgBar = document.getElementById("msg");
			msgBar.value = "";
            msgBar.focus();
		}

		// Implement business logic    
		function serverReload() {    
			ajaxVar = getHTTPObject();
			//var randomnumber=Math.floor(Math.random()*10000);
			if (ajaxVar != null) {
				link = "server.php?all=1";
				ajaxVar.open("GET", link , true);
				ajaxVar.onreadystatechange = setAll;
				ajaxVar.send(null);
			}
		}
	
		function UpdateTimer() {
			serverReload();   
			setTimeout(UpdateTimer, 1000);
		}
    
		function keypressed(e) {
			if(e.keyCode=='13'){
				getInput();
			}
		}
		
		function doLogin() {
			ajaxVar = getHTTPObject();
			if(ajaxVar != null) {
				link = "server.php?do=login&nick="+nickName;
				ajaxVar.open("GET", link, true);
				ajaxVar.onreadystatechange = setHtml;
				ajaxVar.send(null);
			}
		}
		
		function doLogout() {
			ajaxVar = getHTTPObject();
			if(ajaxVar != null) {
				link = "server.php?do=logout&nick="+nickName;
				ajaxVar.open("GET", link, true);
				ajaxVar.onreadystatechange = setHtml;
				ajaxVar.send(null);
			}
		}
		
		window.onbeforeunload = function (e) {
			doLogout();
		};
      
		function wrapBBCode(tag) {
			var msgInput = document.getElementById('msg');
			var content = msgInput.value;
			var selectedContent = content.substring(msgInput.selectionStart, msgInput.selectionEnd);
			var beforeContent = content.substring(0, msgInput.selectionStart);
			var afterContent = content.substring(msgInput.selectionEnd, content.length);
			msgInput.value = beforeContent + '[' + tag + ']' + selectedContent + '[/' + tag + ']' + afterContent;
		}
    //-->
    </script>   
</head>
<body onload="UpdateTimer();">
     <div class="title"><?php echo $title; ?></div>
		<div class="desc"><?php echo $desc; ?></div>
<?php 
if (!isset($_SESSION['cwchat-user'])){ 
    loginForm();
} else { 
    ?>
	<div class="logout"><a onclick="doLogout();" href="index.php?do=logout">Logout</a></div>
		<div id="msgs">
		<?php 
			echo "<div class=\"text\">";
			$get = file_get_contents($chat_server . "?all=1");
			echo $get;
			echo "</div>";
		?>
		</div>
		<div id="msgbox" onkeyup="keypressed(event);">
			<table>
				<tr>
					<td>
						<textarea name="msg" style="width: 406px;" id="msg"></textarea>
					</td>
					<td>
						<button onclick="javascript:wrapBBCode('i');"><img alt="Italic" src="img/italic.png"></button>
						<button onclick="javascript:wrapBBCode('u');"><img alt="Underline" src="img/underline.png"></button> 
						<button onclick="javascript:wrapBBCode('b');"><img alt="Bold" src="img/bold.png"></button> 
						<button onclick="javascript:wrapBBCode('url');"><img alt="URL" src="img/link.png"></button><br>
						<button style="width: 172px;" onclick="getInput();">Send</button>
					</td>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
			doLogin();
		</script>
<?php            
    }

?>
    </div>
</body>   
</html>
