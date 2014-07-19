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
			Password: <input style="padding: 2px;" class="text" type="password" name="password"><br>
			<input style="padding: 2px;" class="text" type="submit" name="submitBtn" value="Login">
		</form>
	</div>
<?php
}

function registerForm(){
?>
	<br>
	<div class="login">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?do=register" method="post">
			Username: <input style="padding: 2px;" class="text" type="text" name="username"><br>
			Password: <input style="padding: 2px;" class="text" type="password" name="password"><br>
			Password Again: <input style="padding: 2px;" class="text" type="password" name="password-again"><br>
			<input style="padding: 2px;" class="text" type="submit" name="submitBtn" value="Login">
		</form>
	</div>
<?php
}

//Logout
if (isset($_GET['do']) && $_GET['do']=="logout") {
	$_SESSION['cwchat-user'] = null;
	$_SESSION['cwchat-pass'] = null;
}

if (isset($_GET['do']) && $_GET['do']=="register" && isset($_POST['submitBtn'])) {
	if($_POST['username']!="" && $_POST['password']!="" && $_POST['password-again']!="") {
		if($_POST['password']==$_POST['password-again']) {
			if(!preg_match('/[^a-z0-9]/i', $_POST['username'])) {
				if(!file_exists("users/" . $_POST['username'] . ".php")) {
					$colors = array("0000ff", "9900cc", "0080ff", "008000", "ededed");
					file_put_contents("users/" . $_POST['username'] . ".php", "<?php\n \$user_password = \"" . sha1(md5($_POST['password'])) . "\";\n \$user_color = \"" . $colors[array_rand($colors)] . "\"; \n?>");
					header("Location: index.php");
				} else {
					header("Location: index.php?notify=6");
				}
			} else {
				header("Location: index.php?notify=5");
			}
		} else {
			header("Location: index.php?notify=4");
		}
	} else {
		header("Location: index.php?notify=3");
	}
} else if (isset($_GET['do']) && $_GET['do']=="login" && isset($_POST['submitBtn'])){
    $username = $_POST['username'];
    if(file_exists("users/$username.php")) {
		include_once("users/$username.php");
		if($user_password==sha1(md5($_POST['password']))) {
			$pass = $user_password;
			$user = $username;
			$color = $user_color;
			$_SESSION['cwchat-user'] = $user;
			$_SESSION['cwchat-pass'] = $pass;
			$_SESSION['cwchat-color'] = $color;
		} else {
			header("Location: index.php?notify=2");
		}
		$name = isset($_POST['username']) ? $_POST['username'] : "Unnamed";
		$_SESSION['cwchat-user'] = $name;
	} else {
		header("Location: index.php?notify=1");
	}
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
		var userColor = "<?php echo $_SESSION['cwchat-color'];; ?>";

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
				link = "server.php?nick="+nickName+"&msg="+document.getElementById('msg').value+"&color="+userColor; 
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
				link = "server.php?do=login&nick="+nickName+"&color="+userColor;
				ajaxVar.open("GET", link, true);
				ajaxVar.onreadystatechange = setHtml;
				ajaxVar.send(null);
			}
		}
		
		function doLogout() {
			ajaxVar = getHTTPObject();
			if(ajaxVar != null) {
				link = "server.php?do=logout&nick="+nickName+"&color="+userColor;
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
if (isset($_GET['do']) && $_GET['do']=="registerform") {
	registerForm();
} else if(isset($_GET['notify'])) {
	if($_GET['notify']=="1") { echo "Error: User not found"; }
	else if($_GET['notify']=="2") { echo "Error: Incorrect password provided"; }
	else if($_GET['notify']=="3") { echo "Error: Please fill out all the text boxes"; }
	else if($_GET['notify']=="4") { echo "Error: The provided passwords did not match"; }
	else if($_GET['notify']=="5") { echo "Error: Special characters cannot be used in your username"; }
	else if($_GET['notify']=="6") { echo "Error: This username is already in use"; }
} else if (!isset($_SESSION['cwchat-user']) || !isset($_SESSION['cwchat-pass'])){ 
    loginForm();
} else { 
      $name = isset($_SESSION['cwchat-user']) ? $_SESSION['cwchat-user'] : "Unnamed";
      $_SESSION['cwchat-user'] = $name;
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
