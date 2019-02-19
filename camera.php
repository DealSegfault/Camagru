<?php
include "include/auth.php";
session_start();

if ($_POST['submit'])
{
	if (htmlspecialchars($_POST['submit']) == "logout")
	{
		session_destroy();
		header("Location:index.php");
	}
	if (htmlspecialchars($_POST['submit']) == "regme")
		$_SESSION['rereq'] = "register";
	if (htmlspecialchars($_POST['submit']) == "logme")
		$_SESSION['rereq'] = "";
}
?>

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

<!DOCTYPE html>
<html>
<body>
    <video id="camera" autoplay></video>

	<script> 
    var camera = document.getElementById('camera');
    navigator.mediaDevices.getUserMedia({
      video: true
    }).then(function (stream) {
      var videoTracks = stream.getVideoTracks();
      camera.srcObject = stream;
      camera.addEventListener('click', function () {
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        var videoWidth = camera.videoWidth,
            videoHeight = camera.videoHeight;
        canvas.width = videoWidth;
        canvas.height = videoHeight;
        context.drawImage(camera, 0, 0, videoWidth, videoHeight);
      });
    }).catch(function (error) {
      console.error(error);
    });
  </script>
</body>
</html>