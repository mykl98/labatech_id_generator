<?php
if($_SERVER['SERVER_ADDR'] == "127.0.0.1" || $_SERVER['SERVER_ADDR'] == "::1"){
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "labatechidgenerator";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$baseUrl = "http://localhost/labatech-id-generator";
}else{
	$servername = "localhost";
	$username = "u528264240_labatechid";
	$password = "Skooltech_113012";
	$dbname = "u528264240_labatechid";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$baseUrl = "https://labatechidgenerator.raptorapps.xyz";
}

date_default_timezone_set("Asia/Manila");

function sanitize($input){
	global $conn;
	$output = mysqli_real_escape_string($conn, $input);
	return $output;
}

function saveLog($log){
	$logFile = fopen("log.txt", "a") or die("Unable to open file!");
	$timeStamp = date("Y-m-d") . '-' . date("h:i:sa");
	fwrite($logFile, $timeStamp .' Log: '. $log . "\n");
	fclose($logFile);
}

function generateCode($length){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

?>