<?php
	include "include/auth.php";

	session_start();

	$snap = $_POST["snap"];
	$user = htmlspecialchars($_POST["user"]);
	
	//Check the request
	if ($_SESSION['logged_in'] == "" || $_SESSION['logged_in'] <> $user || $snap == "")
		header('Location: index.php');
	
	//ConvertDATA
	$imgData = str_replace(' ','+',$snap);
	$imgData =  substr($imgData, strpos($imgData,",") + 1);
	$imgData = base64_decode($imgData);
	$data = hash("md5", $imgData);

	//Check empty pic
	if ($data != "8280d31dfc2c12f5cd37ba846e6b5429" && $data != "f70ee2457104e891d635470ce2eeaad9")
	{
		//Compatibility Browser
		header("Access-Control-Allow-Origin: http://cama-gru.pe.hu");

		//Get ID
		$UserID = getID($user);
		//Save Picture
		echo $UserID;
		if ($UserID != 0)
		{
			request("INSERT INTO photos VALUES (NULL, '".$UserID."', '".$data."', 'none', ".time().")");
			file_put_contents("Save/".$data.".jpeg", $imgData);
			echo $data;
		}
	}
	else
		echo "Nope";
?>