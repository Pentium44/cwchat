<?php 
///////
// CWChat (Chris' Website Chat) - 2013, 2014
// (C) Chris Dorman, GPL v3 - (C) Microchat devs
// https://github.com/crazycoder13/cwchat
// Version: 0.1.0
///////

include "config.php";

	if (isset($_GET['msg']) && $_GET['msg']!=""){
		$msg = $_GET['msg'];
		
		$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
		$msg  = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : ".";
		$line = "<font color=\"#0000ff\">$nick</font>: $msg<br>\n";
		$old_content = file_get_contents($chat_db);
		
		$lines = count(file($chat_db));
		
		if($lines>20) {
			$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
		}
		
		file_put_contents($chat_db, $old_content.$line);
		echo $line;
		
	/*} else if(isset($_GET['ping'])) {
		$nick = $_GET['nick'];
		file_put_contents($pingfile, "$nick" . "-ping");
	
	} else if (isset($_GET['pong'])) {
		$nick = $_GET['nick'];
		$pings = file_get_contents($pingfile);
		if(stristr($pings, "$nick-ping")) {
			$replace = str_replace("$nick-ping", "", $pings);
			file_put_contents($pingfile, $replace);
		}
	*/		
	
	} else if (isset($_GET['all'])) {
	   $flag = file($chat_db);
	   $content = "";
	   foreach ($flag as $value) {
	   	$content .= $value;
	   }
	   echo $content;

	}
?>	
