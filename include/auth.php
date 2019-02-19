<?php
	
	function request($request) // doteb@next2cloud.info
	{
		try {
			$pdo = new PDO('mysql:host=localhost;dbname=Camagru', "root",  "password");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$req = $pdo->prepare($request);
			$req->execute();
			return ($req);
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
			header("Location:index.php");
		}
		$pdo = new PDO('mysql:host=localhost;dbname=camgru', "root",  "password");

	}
	
	function isAdmin($user)
	{		
		$req = request("SELECT `admin` FROM `users` WHERE `user`='".$user."'");
		if ($req->rowCount() > 0)
		{
			$result = $req->fetch();
			if ($result[0] == "1")
				return (1);
			else
				return (0);
		}
		else
			return (0);
	}

	function isOwner($id, $user)
	{
		$id = htmlspecialchars($id);
		$user = htmlspecialchars($user);

		$id_r = request("SELECT id_user FROM photos WHERE id_p='".$id."'");
		$id_user = request("SELECT id FROM users WHERE user='".$user."' OR mail='".$user."'");

		$id_r = $id_r->fetch();
		$id_user = $id_user->fetch();
		if ($id_user[0] == $id_r[0])
			return (1);
		else
			return (0);
	}

	function changeRole($user, $role)
	{
		$user = htmlspecialchars($user);
		$role = htmlspecialchars($role);
		$req = request("UPDATE `user` SET `admin`='".$role."' WHERE `user`='".$user."'");
	}

	function createAccount($user, $mail, $password, $cpass)
	{
		if (!$user || !$mail || !$password || !$cpass)
			return (2);
		$user = htmlspecialchars($user);
		$mail = htmlspecialchars($mail);
		$password = hash("sha256", htmlspecialchars($password));
		$cpass = hash("sha256", htmlspecialchars($cpass));
		$token = hash("sha256", $user.$mail.$password.rand());
		if ($cpass <> $password)
			return (3);
		$check = checkPassword($password);
		if (strlen($check) > 5)
		{
			?>
				<script language="javascript">
				window.alert($check);				
				</script>
			<?php
			return (5);	
		}
		$req = request("SELECT 'active' FROM `users` WHERE `mail`='".$mail."' OR `user`='".$user."'");
		if ($req->rowCount() > 0)
			return (4);
		$req = request("INSERT INTO `users` VALUES (NULL, 0, 0,'".$user."','".$mail."','".$password."', '".$token."')");
		if ($req->rowCount() > 0)
		{
			sendConfirm($mail, $token);
			return (1);
		}
		return (5);
	}

	function connectUser($user, $password)
	{
		if (!$user || !$password)
			return (2);
		$user = htmlspecialchars($user);
		$password = hash("sha256", htmlspecialchars($password));
		$req = request("SELECT `active`, `ban` FROM `users` WHERE `user`='".$user."' AND `passwd`='".$password."' OR `mail`='".$user."' AND `passwd`='".$password."'");
		if ($req->rowCount() > 0)
		{

			$result = $req->fetch();
			if ($result[1] == "1")
				return (5);
			if ($result[0] == "1")
				return (1);
			else
				return (4);
		}
		return (3);
	}

	function checkPassword($pwd) {
		$errors = [];
		if (strlen($pwd) < 8) {
			$errors[] = "Password too short!";
		}
	
		if (!preg_match("#[0-9]+#", $pwd)) {
			$errors[] = "Password must include at least one number!";
		}
	
		if (!preg_match("#[a-zA-Z]+#", $pwd)) {
			$errors[] = "Password must include at least one letter!";
		}     
		return ($errors);
	}

	function modifPassword($user, $password, $newpass)
	{
		if (($res = connectUser($user, $password)) == 1)
		{
			$check = checkPassword($password);
			if (strlen($check) > 5)
			{
				?>
					<script language="javascript">
					window.alert($check);				
					</script>
				<?php
				return (0);	
			}
			$req = request("UPDATE `users` SET `passwd`='".hash("sha256", $newpass)."' WHERE `user`='".$user."' AND `passwd`='".hash("sha256", $password)."'");
			return (1);
		}
		return (0);
	}

	function modifUser($nuser, $user, $password)
	{
		if (($res = connectUser($user, $password)) == 1)
		{
			$req = request("UPDATE `users` SET `user`='".htmlspecialchars($nuser)."' WHERE `user`='".$user."' AND `passwd`='".hash("sha256", $password)."'");
			return (1);
		}
		return (0);
	}

	function modifMail($user, $password, $nmail)
	{
		if (($res = connectUser($user, $password)) == 1)
		{
			$req = request("UPDATE `users` SET `mail`='".htmlspecialchars($nmail)."' WHERE `user`='".$user."' AND `passwd`='".hash("sha256", $password)."'");
			return (1);
		}
		return (0);
	}
	
	function deleteAccount($user, $password)
	{
		if (connectUser($user, $password) == 1)
		{
			$password = hash("sha256", $password);
			$req = request("DELETE FROM `users` WHERE `user`='".$user."' AND `passwd`='".$password."'");
			return (1);
		}
		return (0);

	}

	function deletePic($id)
	{
		$id = htmlspecialchars($id);

		//Delete file
		$del_file = request("SELECT data FROM photos WHERE id_p='".$id."'");
		$del_file = $del_file->fetch();
		$del_file = $del_file['data'];
		unlink("Save/".$del_file.".jpeg");

		// Delete comment and picture in DB
		$del_com = request("DELETE FROM comment WHERE id_pic='".$id."'");
		$del_pic = request("DELETE FROM photos WHERE id_p='".$id."'");
		
	}
	function confirmAccount($hash, $mail)
	{
		$hash = htmlspecialchars($hash);
		$mail = htmlspecialchars($mail);
		$req = request("UPDATE `users` SET `active`='1' WHERE `active`='".$hash."' AND `mail`='".$mail."'");
		if ($req->rowCount() > 0)
		{	
			return (1);
		}
		return (2);
	}

	function createNewToken($user, $mail, $password)
	{
		$token = hash("sha256", $user.$password.$mail.rand());
		$req = request("UPDATE `users` SET `active`='".$token."' WHERE `mail`='".htmlspecialchars($mail)."' AND `passwd`='".hash("sha256", $password)."'");
		if ($req->rowCount() > 0)
			return ($token);
		else
			return (0);
	}
	
	function getToken($mail)
	{
		$mail = htmlspecialchars($mail);
		$req = request("SELECT `active` FROM `users` WHERE `mail`='".$mail."'");
		if ($req->rowCount() > 0)
		{
			$res = $req->fetch();
			if ($res <> '1')
				return ($res[0]);
			else
				return (1);
		}
		else
			return (2);
	}

	function resetPassword($user)
	{
		$user = htmlspecialchars($user);
		$newpass = chr(rand(97, 122));
		for ($i=0 ; $i < 9 ; $i++ ) { 
			$tmp = chr(rand(48, 122));
			while (ctype_alnum($tmp) == FALSE) {
				$tmp = chr(rand(48, 122));
			}
			$newpass .= $tmp;
		}
		$hnewpass = hash("sha256", $newpass);
		$req = request("UPDATE `users` SET `passwd`='".$hnewpass."' WHERE `mail`='".$user."'");
		if ($req->rowCount() > 0)
			return ($newpass);
		else
			return (2);
	}
	
	function getUser($id)
	{
		$request = request("SELECT user FROM users WHERE id='".$id."'");
		$user = $request->fetch();
		$user = $user['user'];
		return $user;
	}

	function getID($user)
	{
		//Get id of the user
		$request = request("SELECT id FROM users WHERE user='".$user."' OR mail='".$user."'");
		$user = $request->fetch();
		$user = $user['id'];
		return $user;
	}
	
	function saveComment($user, $comment, $id_pic)
	{
		$user = htmlspecialchars($user);
		$comment = htmlspecialchars($comment);
		$id_pic = htmlspecialchars($id_pic);

		if ($user == "" || $comment == "" || $id_pic == "")
			return (0);
		
		$user = getID($user);
		// Save the comment 
		$request = request("INSERT INTO comment VALUES (NULL, '".$comment."', '".$id_pic."', '".$user."', '".time()."')");
	}

	function addLike($id_p, $user)
	{
		$id_p = htmlspecialchars($id_p);
		$user = htmlspecialchars($user);
		$user = getID($user);

		$req = request("SELECT 'id_user' FROM `like_p` WHERE `id_p`='".$id_p."'");
		if ($req->rowCount() > 0)
			return (2);
		$request = request("INSERT INTO like_p VALUE ('".$id_p."', '".$user."')");
		return (1);
	}

	function removeLike($id_p, $user)
	{
		$id_p = htmlspecialchars($id_p);
		$user = htmlspecialchars($user);
		$user = getID($user);
		$request = request("DELETE FROM like_p WHERE id_p='".$id_p."' AND id_user='".$user."'");
	}

	function countLike($id_p)
	{
		$id_p = htmlspecialchars($id_p);
		$request = request("SELECT * FROM like_p WHERE id_p='".$id_p."'");
		$result = $request->rowCount();
		if ($result <= 0)
			return 0;
		return ($result);
	}
	
	function deleteComment($id_com, $user)
	{
		$id_com = htmlspecialchars($id_com);
		$user = htmlspecialchars($user);
		$user = getID($user);

		$request = request("DELETE FROM comment WHERE id_com='".$id_com."' AND id_user='".$user."'");
	}
?>