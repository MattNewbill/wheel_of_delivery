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
var items = [<?php echo getMerchantsNames($merchants); ?>];

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
	angularVelocity -= 0.002;
	draw();
	if (angularVelocity > 0) {
		setTimeout("rotate()", 33);
	}
	else {
		// spin finished, add item to history
		var item = items[Math.floor((-angle + Math.PI * 7 / 2) * items.length / Math.PI / 2) % items.length];
		var div = document.createElement("div");
		var anchor = document.createElement("a");
		anchor.href = "javascript:alert('You just ordered " + item + "!')";
		anchor.appendChild(document.createTextNode(item));
		div.appendChild(anchor);
		document.getElementById("history").appendChild(div);
		
		$.ajax({
			url: "getMerchantData.php",
			type: "POST",
			data: item,
			dataType: 'json',
			success: function(data) {
			if(data != null){
					alert('success');
					$('#history_table tr:last').after('<tr>'
															+ '<td>Name</td>'
															+ '<td>Address</td>'
															+ '<td>Phone#</td>'
															+ '<td>Dist</td>'
															+ '<td>Name</td>'
															+ '<td>Name</td>'
															+ '<td>Name</td>' +
														'</tr>');
			}
			else
				alert('failed');
			}
		});
		
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
	ctx.font = "16px serif";
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
		<td>1.805.404.7388</td>
		<td>3064 Divernon Ave Simi Valley, CA</td>
		<td>6.5 miles</td>
		<td>www.stickers.com</td>
		<td>3.7 stars out of 5</td>
		<td>link to delivery</td>
	</tr>
	
</table>


</body>
</html>
