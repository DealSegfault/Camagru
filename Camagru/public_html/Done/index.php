<?php 
	session_start();
?>
<head>
	<meta charset="utf-8">
</head>
<body>
	<center>
		<video id=video width="200" height="200"></video>
		<button id="snap" name="pic">Snap</button>
		<button id="save" name="spic">Save</button>
		<canvas id="canvas" width="200" height="150"></canvas>
		<img id="volvo" type="checkbox" name="volvo" src="Res/volvo.png" width="200" height="25"/>
	</center>

	<script type="text/javascript">
		
		var video = document.getElementById('video');
		var canvas = document.getElementById('canvas');
		var context = canvas.getContext('2d');


		document.getElementById("volvo").addEventListener("click", function() {
			var volvo = new Image();
			volvo.src = "Res/volvo.png";
			context.drawImage(video, 0, 0, 200, 200);
			context.drawImage(volvo, 20, 150, 150, 20);
		});

		document.getElementById("snap").addEventListener("click", function() {
			context.drawImage(video, 0, 0, 200, 200);
		});

		document.getElementById("save").addEventListener("click", function() {
			var post = new XMLHttpRequest();
			post.open("POST", "save.php");
			post.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			post.setRequestHeader("Access-Control-Allow-Origin", "http://cama-gru.pe.hu");
			//req.setRequestHeader("Content-Type","multipart/form-data")
			//var formData = new FormData();
			//formData.append("thesnap", canvas.toDataURL());
			//console.log(formData);
			//post.send(formData);
			post.send("user=mkll&snap=" + canvas.toDataURL());
			//console.log(post.responseText);

			console.log("Saved");
		});
		if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    	// Not adding `{ audio: true }` since we only want video now
    		navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        	video.src = window.URL.createObjectURL(stream);
        	video.play();
   			});
		}
	</script>
</body>