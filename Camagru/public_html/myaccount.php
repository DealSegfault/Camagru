<?php
   include "include/auth.php";
   include "include/users.php";
   include "include/mail.php";
   session_start();
   if ($_SESSION['logged_in'] == "")
   {
    $error = "Veuillez vous connecter pour acceder a votre compte";
    header("Location:../index.php?error=".$error);
   }
   $_SESSION['error'] = "";
   $action = htmlspecialchars($_POST['submit']);
   if ($action == "modifyuser")
      $_SESSION['useraction'] = "edituser";
   if ($action == "modifyemail")
      $_SESSION['useraction'] = "editmail";
   if ($action == "modifypass")
      $_SESSION['useraction'] = "editpass";
   if ($action == "delete")
      $_SESSION['useraction'] = "delete";
   if ($action == "no")
      $_SESSION['useraction'] = "";
  $user = $_SESSION['logged_in'];
  $password = $_POST['oldpass'];
  	if ($action == "yes")
  	{
 	  if (deleteAccount($user, $password) > 0)
	  {   
	  	$_SESSION['useraction'] = "";
      	$_SESSION['logged_in'] = "";
     	$error = "Votre compte a ete supprime";
      	header("Location:index.php?error=".$error);
  	  }
  	  else
      	$_SESSION['error'] = "Mot de passe invalide";
  	}
  switch ($action) {
    case 'newpassv'://xi9FliIMxJ
      if ($_POST['newpass'] <> $_POST['cpass'])
      {
        $_SESSION['error'] = "Les password sont differents";
        break;
      }
      if (modifPassword($user, $password, $_POST['newpass']) > 0)
      {
        $_SESSION['error'] = "Succes votre mot de passe a ete changer";
        $_SESSION['useraction'] = "";
        break;
      }
      else
        $_SESSION['error'] = "Mot de passe invalide";
      break;
    case 'newemailv':
      if ($_POST['newmail'] <> $_POST['cmail'])
      {
        $_SESSION['error'] = "Les mails sont differents";
        break;
      }
      if (modifMail($user, $password, $_POST['newmail']) > 0)
      {
        $token = createNewToken($user, $_POST['newmail'], $password);
        sendNewEmail($_POST['newmail'], $token);
        $_SESSION['useraction'] = "";
        $_SESSION['error'] = "Un lien vous a ete envoye pour confirmer votre mail";
        break;
      }
      else
        $_SESSION['error'] = "Mot de passe invalide";
      break;
    case 'newuserv':
      if (modifUser($_POST['newuser'], $user, $password) > 0)
      {
        $_SESSION['error'] = "Succes votre username a ete changer";
        $_SESSION['useraction'] = "";
        $_SESSION['logged_in'] = htmlspecialchars($_POST['newuser']);
        break;
      }
      else
        $_SESSION['error'] = "Une erreur est survenue";
      break;
   }


?>

<html>
   <head>
      <title>Camagru</title>
      <link rel="stylesheet" type="text/css" href="css/index.css">
      <link rel="stylesheet" type="text/css" href="css/slideshow.css">
   </head>
   <header>
         <?php include "header.php"; ?>
   </header>

   <body>
      <div id="myaccount">
        <h1>My Account : <?php echo htmlspecialchars($_SESSION['error']); ?></h1>
        <div class="edituser">
          <?php 
            if ($_SESSION['useraction'] == "editpass")
              echo '
              <form method="post" action="myaccount.php">
                <input type="password" name="oldpass" placeholder="Current Password">
                <input type="password" name="newpass" placeholder="New password"> 
                <input type="password" name="cpass" placeholder="Confirm password">
                <button name="submit" value="newpassv">Valider</button>
                <button name="submit" value="no">Annuler</button>
              </form>';
            if ($_SESSION['useraction'] == "editmail")
              echo '
              <form method="post" action="myaccount.php">
                <input type="password" name="oldpass" placeholder="Current Password">
                <input type="email" name="newmail" placeholder="New Email"> 
                <input type="email" name="cmail" placeholder="Confirm Email">
                <button name="submit" value="newemailv">Valider</button>
                <button name="submit" value="no">Annuler</button>
              </form>';
            if ($_SESSION['useraction'] == "edituser")
              echo '
              <form method="post" action="myaccount.php">
                <input type="password" name="oldpass" placeholder="Current Password">
                <input type="text" name="newuser", placeholder="New Username"> 
                <button name="submit" value="newuserv">Valider</button>
                <button name="submit" value="no">Annuler</button>
              </form>';
            if ($_SESSION['useraction'] == "delete")
              echo 'Etes vous sur, vous allez nous manquer ?</br>
              <form method="post" action="myaccount.php">
                <input type="password" name="oldpass" placeholder="Current Password">
                <button name="submit" value="yes">Valider</button>
                <button name="submit" value="no">Annuler</button>
              </form>';
            if ($_SESSION['useraction'] == "")
              currentUser($_SESSION['logged_in']);
          ?>
        </div>
      </div>
      <div class="">
        <h1>My pictures:</h1>
          <?php
              include "slideshow.php";
          ?>
      </div>
   </body>
   <footer> Contact me at nocontact@nowhere.x</footer>
</html>