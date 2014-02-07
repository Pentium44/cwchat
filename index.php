<?php

///////
// CWChat (Chris' Website Chat) - 2013, 2014
// (C) Chris Dorman, GPL v3 - (C) Microchat devs
// https://github.com/crazycoder13/cwchat
// Version: 0.1.0
///////

session_start();

include "config.php";

function createForm(){
?>
	<br>
	<div class="login">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      Please eneter a nickname to login!<br>
      Your name: <input style="padding: 2px;" class="text" type="text" name="name">
		<br><input style="padding: 2px;" class="text" type="submit" name="submitBtn" value="Login">
      </form>
    </div>
<?php
}

//Logout
if (isset($_GET['do']) && $_GET['do']=="logout"){
	$name = $_SESSION['nickname'];
    $old_content = file_get_contents($chat_db);
    $line = "<font color=\"#ff0000\">$chat_bot</font>: $name left the chat<br>\n";
    file_put_contents($chat_db, $old_content.$line);
	unset($_SESSION['nickname']);
}

// Process login info
if (isset($_POST['submitBtn'])){
    $name    = isset($_POST['name']) ? $_POST['name'] : "Unnamed";
    $_SESSION['nickname'] = $name;
    $nickname = $_SESSION['nickname'];
    $old_content = file_get_contents($chat_db);
    $line = "<font color=\"#ff0000\">$chat_bot</font>: $nickname entered the chat<br>\n";
    file_put_contents($chat_db, $old_content.$line);
}

$nickname = isset($_SESSION['nickname']) ? $_SESSION['nickname'] : "Hidden";   
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
      var nickName = "<?php echo $nickname; ?>";

      // Get the HTTP Object
      function getHTTPObject(){
			if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
			else if (window.XMLHttpRequest) return new XMLHttpRequest();
			else {
				alert("Your browser does not support AJAX.");
				return null;
			}
      }   

      // Change the value of the outputText field
      function setHtml(){
			if(ajaxVar.readyState == 4){
				var response = ajaxVar.responseText;
				var msgBox = document.getElementById("msgs");
				msgBox.innerHTML += response;
				msgBox.scrollTop = msgBox.scrollHeight;
			}
      }

      // Change the value of the outputText field
      function setAll(){
			if(ajaxVar.readyState == 4){
				var response = ajaxVar.responseText;
				var msgBox = document.getElementById("msgs");
				msgBox.innerHTML = response;
				msgBox.scrollTop = msgBox.scrollHeight;
			}
      }

      // Implement business logic    
      function serverWrite(){    
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
      function serverReload(){    
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
    
	  /*function UpdatePingTimer() {
         doPong();   
         timerID = setTimeout("UpdatePingTimer()", 2000);
      }*/
    
      function keypressed(e){
         if(e.keyCode=='13'){
            getInput();
         }
      }
    //-->
    </script>   
</head>
<body onload="UpdateTimer();">
     <div class="title"><?php echo $title; ?></div>
		<div class="desc"><?php echo $desc; ?></div>
<?php 

if (!isset($_SESSION['nickname']) ){ 
    createForm();
} else  { 
      $name    = isset($_POST['name']) ? $_POST['name'] : "Unnamed";
      $_SESSION['nickname'] = $name;
    ?>
	<center>
		<div class="logout"><a href="index.php?do=logout">Logout</a></div>
		<table id="ctable">
		<tr><td id="msgstd">
			<div id="msgs">
			<?php 
				echo "<div class=\"text\">";
				$data = file_get_contents($chat_db);
				echo $data;
				echo "</div>";
			?>
			</div>
		</td></tr>
		<tr><td id="msgboxtd">
			<div id="msgbox" onkeyup="keypressed(event);">
			Your message: <input type="text" name="msg" size="30" id="msg" />
			<button onclick="getInput();">Send</button>
			</div>   
		</td></tr>
		</table>
	</center>
<?php            
    }

?>
    </div>
</body>   
