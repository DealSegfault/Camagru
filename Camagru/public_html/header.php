
<h1 style="text-align: center;" href="index.php">Camagru</h1>
<center><a href="."><span>Home</span></a></center>
<div class="mybuttons">
<?php
if ($_SESSION['logged_in'] <> "")
{
	?><div class="welcome">Bonjour <?php echo $_SESSION['logged_in'];?></div>
	<form  action="." method="post"><button name="submit" value="logout">Logout</button></form>
	<?php
	if (isAdmin($_SESSION['logged_in']) == 1)
	{?>   
		<form  action="admin.php" method="post"><button name="submit" value="admin">PanelAdmin</button></form>
		<?php
	}
	echo  '<form  action="myaccount.php" method="post"><button name="submit" value="myaccount">MyAccount</button></form>';
}
elseif ($_SESSION['rereq'] == "")
{?>
	Login: <?php echo htmlspecialchars($_GET['error']).'</br>';?>
	<?php if (htmlspecialchars($_GET['error']) == "Account non actif")
	{
		echo '<form method="post" action="include/login.php">
		<input type="email" name="mail" placeholder="Email of registration">
		<button name="submit" value="resend">Resend link</button>
		</form>';
	}?>
	<?php if (htmlspecialchars($_GET['error']) == "Combinaisons incorrectes")
	{
		echo '<form method="post" action="include/login.php">
		<input type="email" name="mail" placeholder="Your Email">
		<button name="submit" value="reset">Reset</button>
		</form>';
	}?>
	
	</br>
	<form method="post" action="include/login.php">
	<input type="text" name="user", placeholder="Username/Email">
	<input type="password" name="pass", placeholder="Password">
	<button name="submit", value="login">Login</button>
	</form>
	<form  action="." method="post"><button name="submit" value="regme">Register</button></form>
	<?php 
}
elseif ($_SESSION['rereq'] == "register")
{?>
	Register: <?php echo htmlspecialchars($_GET['error']).'</br>';?></br>
	
	<form method="post" action="include/login.php">
	<input type="text" name="user", placeholder="Username">
	<input type="email" name="email", placeholder="Email"></br>
	<input type="password" name="pass", placeholder="Password">
	<input type="password" name="cpass", placeholder="Confirm password">
	<button name="submit", value="register">Register</button>
	</form>
	<form  action="index.php" method="post"><button name="submit" value="logme">Login</button></form>
	
	<?php 
} ?>
</div>