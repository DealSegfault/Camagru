<?php
	include "include/auth.php";
	include "include/users.php";
  include "include/mail.php";
	session_start();
	if (isAdmin($_SESSION['logged_in']) == 0)
		header("Location:../index.php");
  if ($_POST['submit'] == "ban")
    ban($_POST['user']);
  if ($_POST['submit'] == "delete")
    delete($_POST['user']);
  if ($_POST['submit'] == "reset")
  {
    $pass = resetPassword($_POST['user']);
    $mail = getMail($_POST['user']);
    sendReset($mail, $pass);
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
      <div class="container">
        <h2>Users</h2>
        <?php listUsers();?>

      </div>
   </body>
   <footer>	Contact me at nocontact@nowhere.x</footer>
</html>