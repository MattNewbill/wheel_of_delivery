<!doctype html>
<html>
<head>
<?php include('getMerchants.php'); ?>
<title>Wheel of Delivery</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/styles.css">

<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>

<script type="text/javascript">
var radius = 200;
var items = ["hello world", "food", "water", "hacks", "zombies", "computers", "awesomeness", "stuff", "phone", "you die"];

var angle = 0;
var angularVelocity = 0;

function startRotate() {
	if (angularVelocity <= 0) {
		angularVelocity = (Math.random() + 0.5) * 0.2;
		rotate();
	}
}

function rotate() {
	angle += angularVelocity;
	if (angle > Math.PI * 2) angle -= Math.PI * 2;
	angularVelocity -= 0.001;
	draw();
	if (angularVelocity > 0) {
		setTimeout("rotate()", 33);
	}
	else {
		alert("You landed on " + items[Math.floor((-angle + Math.PI * 7 / 2) * items.length / Math.PI / 2) % items.length]);
	}
}

function draw() {
	var canvas = document.getElementById("spinner");
	var ctx = canvas.getContext("2d");
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	// pointer
	ctx.fillRect(canvas.width / 2 - 3, canvas.height / 2 - radius - 20, 6, 40);
	// circle
	ctx.beginPath();
	ctx.arc(canvas.width / 2, canvas.height / 2, radius, 0, 2 * Math.PI);
	// lines
	for (var i = 0; i < items.length; i++) {
		var lineAngle = angle + i * 2 * Math.PI / items.length;
		ctx.moveTo(canvas.width / 2, canvas.height / 2);
		ctx.lineTo(canvas.width / 2 + radius * Math.cos(lineAngle), canvas.height / 2 + radius * Math.sin(lineAngle));
	}
	// apply strokes
	ctx.stroke();
	// text
	ctx.font = "20px serif";
	for (var i = 0; i < items.length; i++) {
		ctx.save();
		ctx.translate(canvas.width / 2, canvas.height / 2);
		ctx.rotate(angle + (i + 0.5) * 2 * Math.PI / items.length);
		ctx.fillText(items[i], 50, 10);
		ctx.restore();
	}
}
</script>


</head>
<body onload="draw()">
<h1>Spin the Wheel</h1>
<div>
Enter Preferences<br>
<form>
Zip Code <input type="text">
</form>

<button onclick="startRotate()" class="btn btn-default">Click to Start</button>
</div>

<canvas id="spinner" width="500" height="500">
</canvas>

</body>
</html>