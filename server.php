<?php 
///////
// CWChat (Chris' Website Chat) - 2013, 2014
// (C) Chris Dorman, GPL v3 - (C) Microchat devs
// https://github.com/crazycoder13/cwchat
// Version: 0.1.1a
///////

include_once("config.php");
include("bbcode.php");

if (isset($_GET['msg']) && $_GET['msg']!=""){
		
	$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
	$color = $_GET['color'];
	$msg  = bbcode_format(htmlentities(stripcslashes($_GET['msg'])));
	$line = "<font color=\"#$color\">$nick</font>: $msg<br>\n";
	$old_content = file_get_contents($server_db);
		
	$lines = count(file($server_db));
		
	if($lines>$server_msgcount) {
		$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
	}
		
	file_put_contents($server_db, $old_content.$line);
	echo $line;	
	
} else if (isset($_GET['all'])) {
	//$content = file_get_contents($server_db);
	// This is faster
	$flag = file($server_db);
	$content = "";
	foreach ($flag as $value) {
		$content .= $value;
	}
	echo $content;
	
} else if (isset($_GET['do'])) {
	if($_GET['do']=="login") {
		$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
		$color = $_GET['color'];
		$line = "<font color=\"#ff0000\">$server_bot</font>: <span style='color:#$color;'>$nick</span> joined $title<br>\n";
		$old_content = file_get_contents($server_db);
			
		$lines = count(file($server_db));
		
		if($lines>$server_msgcount) {
			$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
		}
		
		/*file_put_contents("db/ping.txt","$nick ".time()."\n");*/
		
		file_put_contents($server_db, $old_content.$line);
		echo $line;
	
	} else if($_GET['do']=="logout") {
		$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
		$color = $_GET['color'];
		$line = "<font color=\"#ff0000\">$server_bot</font>: <span style='color:#$color;'>$nick</span> left $title<br>\n";
		$old_content = file_get_contents($server_db);
			
		$lines = count(file($server_db));
		
		if($lines>$server_msgcount) {
			$old_content = implode("\n", array_slice(explode("\n", $old_content), 1));
		}
		
		file_put_contents($server_db, $old_content.$line);
		echo $line;
	}
} /*else if(isset($_GET['ping'])) {
	$username = $_GET['nick'];
	
} else if(isset($_GET['pong'])) {
	
}*/
?>	
