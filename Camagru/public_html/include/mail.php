<?php
 	function sendConfirm($mail, $token)
 	{
	    ini_set( 'display_errors', 1 );
	 
	    error_reporting( E_ALL );
	 
	    $from = "no-reply@cama-gru.pe.hu";
	 
	    $to = $mail;
	 
	    $subject = "Activez votre compte Camagru";

	    $message = file_get_contents("http://www.cama-gru.pe.hu/include/mail/trump.php");
		$message = str_replace("MYURLTOKEN", "http://www.cama-gru.pe.hu/include/login.php?token=".$token."&email=".htmlspecialchars($mail), $message);
	    $headers  = 'MIME-Version: 1.0' . "\r\n";
    	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    	$headers .= "From:" . $from;
	 
	 	if ($to <> "")
		    mail($to,$subject,$message, $headers);
	 
	    return (1);
	}

	function sendReset($mail, $newpass)
	{
		ini_set( 'display_errors', 1 );
	    error_reporting( E_ALL );

	    $from = "no-reply@cama-gru.pe.hu";
	    $to = $mail;
	    $subject = "Reset Password";

	    $message = file_get_contents("http://www.cama-gru.pe.hu/include/mail/reset.php");
		$message = str_replace("MYURLTOKEN", "http://www.cama-gru.pe.hu/", $message);
		$message = str_replace("NEWPASSWORD", $newpass, $message);
	    $headers  = 'MIME-Version: 1.0' . "\r\n";
    	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    	$headers .= "From:" . $from;
	 	if ($to <> "")
		    mail($to,$subject,$message, $headers);
	 
	    return (1);
	}

	function sendNewEmail($mail, $token)
	{
	    ini_set( 'display_errors', 1 );
	 
	    error_reporting( E_ALL );
	 
	    $from = "no-reply@cama-gru.pe.hu";
	 
	    $to = $mail;
	 
	    $subject = "Bannisement aux frontieres";

	    $message = file_get_contents("http://www.cama-gru.pe.hu/include/mail/cm.php");
		$message = str_replace("MYURLTOKEN", "http://www.cama-gru.pe.hu/include/login.php?token=".$token."&email=".htmlspecialchars($mail), $message);
	    $headers  = 'MIME-Version: 1.0' . "\r\n";
    	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    	$headers .= "From:" . $from;
	 
	 	if ($to <> "" && $message <> "")
		    mail($to,$subject,$message, $headers);
	 
	    return (1);
	}
?>