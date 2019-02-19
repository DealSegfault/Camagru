<?php
	include "include/auth.php";

	session_start();

	$id = htmlspecialchars($_GET['id']);
	if ($id == "")
		$id = htmlspecialchars($_POST['id']);
	$action = htmlspecialchars($_POST['submit']);
	if ($_POST["comment"] == "Send" && $_SESSION["logged_in"] != "")
	{
		saveComment($_SESSION["logged_in"], $_POST["txt"], $_POST["id"]);
		header("Location: img.php?id=".$_POST['id']);
	}
	if ($_GET["deletecom"] == '1' && $_SESSION["logged_in"])
	{
		deleteComment($_GET["id_com_del"], $_SESSION["logged_in"]);
		header("Location: img.php?id=".$_GET["id"]);
	}
	if ($_POST["deletepic"] == "Delete" && $_SESSION["logged_in"])
	{
		if (isOwner($id, $_SESSION["logged_in"]))
		{
			deletePic($id);
			header("Location: index.php");
		}
	}
	if (isset($_POST["likebut"]) && $_SESSION["logged_in"])
	{
		$res = addLike($_POST["id"], $_SESSION["logged_in"]);
		if ($res == 2)
		{
			removeLike($_POST["id"], $_SESSION["logged_in"]);
		}
		header("Location: img.php?id=".$_POST["id"]);
	}
	if ($id == "" || $_SESSION['logged_in'] == "")
			header("Location: index.php");
	$request = request("SELECT data FROM users LEFT JOIN photos ON users.id = photos.id_user WHERE id_p='".$id."'");
	if (!$request)
		die("File not found");
	$data = $request->fetch();
	if ($request->rowCount() == 0)
		header("Location: index.php");
	$img = "Save/".$data[0].".jpeg";

	$request = request("SELECT * FROM comment WHERE id_pic='".$id."'");
	$comment = $request->fetchAll();

	$like_request = countLike($id);
	// echo $like_request;

	$k = 0;
	$com = "";
?>

<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/slideshow.css">
	<meta charset="utf-8">
	<link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<header>
	<?php include "header.php"; ?>
</header>
	<div class="container">
		<img id="mypic" src="<?php echo $img;?>"></br>
		<form action="img.php" id="form_like" method="post">
			<input style="margin: 0 auto; display: inline;" type="image" name="likepicture" width="20" height="20" src="Res/like.png" onclick="this.form.submit();">
			<label for="txt"><?php echo $like_request; ?></label></br>			
			<input type="hidden" name="likebut" value="1"/>
			<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"></input>
		</form>
		<div class="container2">
				<form action="img.php" id="form_co" method="POST">
					<p>
						<label for="txt">Your message</label></br>
						<textarea name="txt" style="width: 100%" placeholder="Laisser votre message..." cols="120" rows="10" form="form_co"></textarea>
						<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"></input>
						<input class="form_comment_button" type="submit" name="comment" value="Send">
						<?php
							if (isOwner($id, $_SESSION["logged_in"]) == 1)
							{
								echo '
								<form action="img.php" method="post">
									<input style="margin: 0 auto; display: inline;" type="submit" name="deletepic" value="Delete">
								</form>
								';
							}
						?>
					</p>
				</form>
		</div>
		<div id="show_comment">
			<?php
				foreach ($comment as $key => $value) {
		            if ($value["comment"] != $com) {
		                $k++;
		                $user = getUser($value['id_user']);
		                $com = $value['comment'];
		                $time = $value['timestamp'];
		                $cross = "";
		                if ($_SESSION["logged_in"] == $user)
		                	$cross = '
		                	<form action="img.php" mehod="GET">
		                		<input type="hidden" name="id_com_del" value="'.$value['id_com'].'"/>
		                		<input type="hidden" name="id" value="'.htmlspecialchars($_GET['id']).'"></input>
								<input type="hidden" name="deletecom" value="1"/>
		                		<input type="image" name="osef" width="20" height="20" src="Res/cross.png" onclick="this.form.submit();"/>
		                	</form>';
		                if ($user != "")
		                {
		                    echo'
		                    <span>'.$user.'</span> 
							<span class="right">le '.date('l jS \of F Y h:i:s A',$time).$cross.'</span>
							<p class="show_comment_txt">'.$com.'</p>
							<br>';
		                }
		            }
	        	}
        	?>
		</div>
	</div>
	</div>
</body>
<footer> Contact me at nocontact@nowhere.x</footer>
</html>