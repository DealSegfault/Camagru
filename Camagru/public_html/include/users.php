<?php
	function ban($user)
	{
		$req = request("SELECT `ban` FROM `users` WHERE `mail`='".$user."'");
		$result = $req->fetch();
		$update = $result[0] == 0 ? 1 : 0;
		$req = request("UPDATE `users` SET `ban`=".$update." WHERE `mail`='".$user."'");
	}

	function delete($user)
	{
		$req = request("DELETE FROM `users` WHERE `mail`='".$user."'");
	}

	function getMail($user)
	{
		$req = request("SELECT `mail` FROM `users` WHERE `user`='".$user."' OR mail='".$user."'");
		$res = $req->fetch();
		return ($res[0]);
	}

	function listUsers()
	{
		$req = request("SELECT * FROM `users` WHERE 1");
		echo '<div style="overflow-x:auto;"><table>';
		for ($i=0; $i < $req->rowCount() ; $i++) {

			$tmp = $req->fetch();
			$tmp[1] = ($tmp[1] == 1 ? 'Banni' : 'Legit');
			$tmp[2] = ($tmp[2] == 1 ? 'Admin' : 'User');
			$tmp[6] = ($tmp[6] == 1 ? 'Active' : 'Not Active');
			echo 
			'<tr>
			<td>'.$tmp[2].'</td>
			<td>'.$tmp[3].'</td>
			<td>'.$tmp[4].'</td>
			<td>'.$tmp[6].'</td>
			<td>'.$tmp[1].'</td>
			<td><form method="post" action="admin.php">
				<input type="hidden" name="user" value='.$tmp[4].'>
				<button name="submit" value="ban">BAN</button>
				<button name="submit" value="delete">Delete</button>
				<button name="submit" value="reset">Reset Password</button>
			</form></td>
			</tr>';
		} 
		echo "</table></div>";
	}

	function currentUser($user)
	{
		$req = request("SELECT * FROM users WHERE user='".$user."' OR mail='".$user."'");
		$res = $req->fetch();
		echo '
		<table>
			<tr>
				<td>Username: '.$res[3].'</td>
				<td>Email adress: '.$res[4].'</td>
				<td>
					<form method="post" action="myaccount.php">
					<input type="hidden" name="user" value="'.$res[4].'">
					<button name="submit" value="delete">Delete Account</button>
					<button name="submit" value="modifypass">Edit Password</button>
					<button name="submit" value="modifyemail">Edit Mail</button>
					<button name="submit" value="modifyuser">Edit Username</button>
					</form></td>
			</tr>
		</table>';
	}
?>