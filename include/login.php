<?php
	include "auth.php";
	include "mail.php";
	session_start();

	if ($_POST['submit'] == "reset")
	{
		$pass = resetPassword($_POST['mail']);
    	switch ($pass) {
    		case strlen($pass) > 6:
    			sendReset(htmlspecialchars($_POST['mail']), $pass);
    			$error = "Un email contenant votre nouveau mot de passe a ete envoye";
				header("Location:../index.php?error=".$error);
				break;
    		case 2:
    			$error = "Ce compte n'existe pas";
				header("Location:../index.php?error=".$error);
				break;
    	}
	}
	if ($_POST['submit'] == "login")
	{
		$res = connectUser($_POST['user'], $_POST['pass']);
		$_SESSION['logged_in'] = "";
		switch ($res) 
		{
			case 1:
			{
				$_SESSION['logged_in'] = $_POST['user'];
				header("Location:../index.php");
				break;
			} 
			case 2:
			{
				$error = "Missing fields";
				header("Location:../index.php?error=".$error);
				break;
			}
			case 3:
			{
				$error = "Combinaisons incorrectes";
				header("Location:../index.php?error=".$error);
				break;
			}
			case 4:
			{
				$error = "Account non actif";
				header("Location:../index.php?error=".$error);
				break;
			}
			case 5:
			{
				$error = "Account banni";
				header("Location:../index.php?error=".$error);
				break;
			}
		}
	}
	if ($_POST['submit'] == "register")
	{
		$res = createAccount($_POST['user'], $_POST['email'], $_POST['pass'], $_POST['cpass']);
		$_SESSION['logged_in'] = "";
		switch ($res) 
		{
			case 1:
			{
				$_SESSION['rereq'] == "";
				$error = "Un email vous a ete envoye veuillez valider votre compte";
				header("Location:../index.php?error=".$error);
				break;
			} 
			case 2:
			{	
				$error = "Missing fields";
				header("Location:../index.php?error=".$error);
				break;
			}
			case 3:
			{
				$error = "Password differents";
				header("Location:../index.php?error=".$error);
				break;
			}
			case 4:
			{
				$error = "Nom d'utilisateur ou email deja utilise";
				header("Location:../index.php?error=".$error);
				break;
			}
			case 5:
			{
				$error = "Une erreur est survenue";
				header("Location:../index.php?error=".$error);
				break;
			}
		}
	}
	if ($_GET['token'] <> "" && $_GET['email'] <> "")
	{
		$res = confirmAccount($_GET['token'], $_GET['email']);
		switch ($res)
		{
			case '1':
			{
				$_SESSION['logged_in'] = $_GET['email'];
				$error = "Votre compte a ete confirme avec succes";
				header("Location:../index.php?error=".$error);
				break;
			}
			case '2':
			{
				$_SESSION['logged_in'] = "";
				$error = "Une erreur est survenue, ce lien est invalide";
				header("Location:../index.php?error=".$error);
				break;
			}
		}
	}
	if ($_POST['submit'] == "resend")
	{
		$token = getToken($_POST['mail']);
		$_SESSION['logged_in'] = "";
		$_SESSION['rereq'] == "";
		if ($token == '1')
		{
			$error = "Compte deja valide";
			header("Location:../index.php?error=".$error);
		}
		elseif ($token == '2')
		{
			$error = "Ce compte n'existe pas";
			header("Location:../index.php?error=".$error);
		}
		elseif (strlen($token) > 9) 
		{
			$error = "Un email vous a ete envoye veuillez valider votre compte";
			sendConfirm($_POST['mail'], $token);
			header("Location:../index.php?error=".$error);
		}
	} 
?>