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
// print_r($_SESSION);
?>
<!DOCTYPE html>


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
<body>
	<div class="container">
		
		<h1 style="text-align: center">Take a Snap:</h1>
		<div class="photos">
				<video style="display: inline-block;" autoplay id=camera width="400" height="300"></video>
				<canvas style="display: inline-block;" id="can" width="400" height="300" onclick="can();"></canvas></br>
				<div class="snapsave">
					<button id="snap" name="pic">Snap</button>
					<button id="save" name="spic">Save</button>
					<input type="file" accept="image/*;" id="upl">
					<input type="radio" name="filtre" id="volvo" value="volvo" />
						<label><img src="Res/volvo.png" alt="Volvo" width="50" height="20" /></label>
					<input type="radio" name="filtre" id="bitcoin" value="btc"/>
						<label><img src="Res/btc.png" alt="Bitcoin" width="25" height="25" /></label>
					<input type="radio" name="filtre" id="ethereum" value="eth"/>
						<label><img src="Res/eth.png" alt="Ethreum" width="25" height="25" /></label>
				</div>
		</div>
		<div class="">
        <h1>Pictures:</h1>
          <?php
              include "slideshowAll.php";
          ?>
		</div>
		
		
	</div>
		<script>
		var camera = document.getElementById('camera');
    	navigator.mediaDevices.getUserMedia({
    	  video: true
    	}).then(function (stream) {
    	//   var videoTracks = stream.getVideoTracks();
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

		var fileinput = document.getElementById('upl'); // input file
		var video = document.getElementById('camera');
		var canvas = document.getElementById('can');
		var context = canvas.getContext('2d');
		var cfile = document.getElementById('file');
		var filtre = new Image();

		canvas.onclick = function can(event) {
			var volvo = document.getElementById("volvo");
			var btc = document.getElementById("bitcoin");
			var eth = document.getElementById("ethereum");
			var source = "";
			if (volvo.checked == true)
				source = "Res/volvo.png";
			if (btc.checked == true)
				source = "Res/btc.png";
			if (eth.checked == true)
				source = "Res/eth.png";
			if (source != "")
			{
				filtre.src = source;
				context.drawImage(filtre, event.pageX - canvas.offsetLeft - 35, event.pageY - canvas.offsetTop - 170, 50, 50);
			}
		}

		fileinput.addEventListener("change", function() {
			var reader = new FileReader();
			reader.addEventListener("loadend", function(arg) {
			var src_image = new Image();
			src_image.onload = function() {
				canvas.height = 300//src_image.height;
				canvas.width = 400//src_image.width;
				context.drawImage(src_image, 0, 0, 400, 300);
				var imageData = canvas.toDataURL("image/png"); 
			}
			src_image.src = this.result;
			});
			reader.readAsDataURL(this.files[0]);
		});

		document.getElementById("snap").addEventListener("click", function() {
			var canvas = window.canvas = document.querySelector('canvas');
			canvas.getContext('2d').drawImage(video, 0, 0, 400, 300);
		});

		document.getElementById("save").addEventListener("click", function() {
				var post = new XMLHttpRequest();
				post.open("POST", "save.php");
				post.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				post.setRequestHeader("Access-Control-Allow-Origin", "http://localhost:8080");

		 		post.send("user=" + "<?php echo $_SESSION['logged_in'];?>" + "&snap=" + canvas.toDataURL());
				post.onreadystatechange = function(event) {
					if (this.readyState == XMLHttpRequest.DONE) {
						console.log(post.responseText);
						console.log("Saved");
						var response = post.responseText;
						// alert("Saved");
						location.reload();
					}
				}
		});

	</script>
</body>
<footer> Contact me at nocontact@nowhere.x</footer>
</html>