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
var radius = 250;
var jsonQuery = <?php echo $json; ?>;
var merchants = jsonQuery.merchants;

var angle = 0;
var angularVelocity = 0;

function startRotate() {
	if (angularVelocity <= 0) {
		angularVelocity = (Math.random() + 0.75) * 0.3;
		rotate();
	}
}

function rotate() {
	angle += angularVelocity;
	if (angle > Math.PI * 2) angle -= Math.PI * 2;
	angularVelocity -= 0.003;
	draw();
	if (angularVelocity > 0) {
		setTimeout("rotate()", 50);
	}
	else {
		// spin finished, add item to history
		var item = merchants[Math.floor((-angle + Math.PI * 7 / 2) * merchants.length / Math.PI / 2) % merchants.length].summary.name;
		var div = document.createElement("div");
		var anchor = document.createElement("a");
		anchor.href = "javascript:alert('You just ordered " + item + "!')";
		anchor.appendChild(document.createTextNode(item));
		div.appendChild(anchor);
		document.getElementById("history").appendChild(div);
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
	for (var i = 0; i < merchants.length; i++) {
		var lineAngle = angle + i * 2 * Math.PI / merchants.length;
		ctx.moveTo(canvas.width / 2, canvas.height / 2);
		ctx.lineTo(canvas.width / 2 + radius * Math.cos(lineAngle), canvas.height / 2 + radius * Math.sin(lineAngle));
	}
	// apply strokes
	ctx.stroke();
	// text
	ctx.font = "16px serif";
	for (var i = 0; i < merchants.length; i++) {
		ctx.save();
		ctx.translate(canvas.width / 2, canvas.height / 2);
		ctx.rotate(angle + (i + 0.5) * 2 * Math.PI / merchants.length);
		ctx.fillText(merchants[i].summary.name, 50, 10);
		ctx.restore();
	}
}
</script>


</head>
<body onload="draw()">
<h1>Wheel of Delivery</h1>
<div>
<button onclick="startRotate()" class="btn btn-default">Spin the Wheel!</button>
</div>

<canvas id="spinner" width="600" height="600">
</canvas>

<div id="history">Your spin history:</div>

<table id="history_table" class="table-striped table">
	<tr>
		<th>Name</th>
		<th>Phone #</th>
		<th>Address</th>
		<th>Distance</th>
		<th>Website</th>
		<th>Ratings(# of Raters)</th>
		<th>Delivery!</th>
	</tr>
	<tr>
		<td>Stickers</td>
		<td><a href="callto://+1.805.404.7388">1.805.404.7388</a></td>
		<td><a target="_blank"
			   href="https://www.google.com/maps/dir/3064 Divernon Ave Simi Valley, CA">
			   3064 Divernon Ave Simi Valley, CA</a></td>
		<td>6.5 miles</td>
		<td><a target="_blank"
			   href="https://www.google.com">www.google.com</a></td>
		<td>3.7 stars out of 5</td>
		<td><a target="_blank"
			   href="https://www.delivery.com">link to delivery</a></td>
	</tr>
	
</table>


</body>
</html>
