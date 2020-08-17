<?php 
///////
// CWChat (Chris' Website Chat) - 2013, 2014
// (C) Chris Dorman, GPL v3 - (C) Microchat devs
// https://github.com/crazycoder13/cwchat
// Version: 0.1.1a
///////

include_once("config.php");
//include("bbcode.php");

if (isset($_GET['msg']) && $_GET['msg']!=""){
		
	$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
	$msg  = htmlentities(stripcslashes($_GET['msg']));
	$line = "PRIVMSG $channel :[WEB]$nick: $msg\n";
	$brline = "$nick: $msg\n";
	$old_content = file_get_contents($irc_input);
		
	$lines = count(file($irc_input));
	
	if($lines>$server_msgcount) {
		$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
	}

	file_put_contents($irc_input, $old_content.$line);

	$old_br_content = file_get_contents($server_db);
	$lines2 = count(file($server_db));

        if($lines2>$server_msgcount) {
                $old_br_content = implode("\n", array_slice(explode("\n", $old_br_content), 1));
        }

        file_put_contents($server_db, $old_br_content.$brline);
	//echo $brline;
	
} else if (isset($_GET['all'])) {
	//$content = file_get_contents($server_db);
	// This is faster
	$flag = file($server_db);
	$content = "";
	foreach ($flag as $value) {
		$content .= $value;
	}
	echo nl2br(htmlentities(stripslashes($content)));
	
} else if (isset($_GET['do'])) {
	if($_GET['do']=="login") {
		$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
		$line = "PRIVMSG $channel :[WEB]$nick joined $channel\n";
		$old_content = file_get_contents($irc_input);
			
		$lines = count(file($irc_input));
		
		if($lines>$server_msgcount) {
			$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
		}
		
		file_put_contents($irc_input, $old_content.$line);
	} else if($_GET['do']=="logout") {
		$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
		$line = "PRIVMSG $channel :[WEB]$nick left $channel\n";
		$old_content = file_get_contents($irc_input);
			
		$lines = count(file($irc_input));
		
		if($lines>$server_msgcount) {
			$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
		}
		
		file_put_contents($irc_input, $old_content.$line);
	}
} /*else if(isset($_GET['ping'])) {
	$username = $_GET['nick'];
	
} else if(isset($_GET['pong'])) {
	
}*/
?>	
