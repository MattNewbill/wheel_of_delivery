<!doctype html>
<html>
<head>
<?php include('getMerchants.php'); ?>
<title>Wheel of Delivery</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/styles.css">

<script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>

<script type="text/javascript">
var radius = 250;
var jsonQuery = <?php echo $json; ?>;
var merchants = jsonQuery.merchants.slice(0, 15);
var queuedMerchants = jsonQuery.merchants.slice(15);

var angle = 0;
var angularVelocity = 0;

function startRotate() {
	if (angularVelocity <= 0) {
		angularVelocity = (Math.random() + 0.75) * 0.3;
		rotate();
	}
}

function getAddress(location) {
 var street = location.street;
 var city = location.city;
 var state = location.state;
 var zip = location.zip;
 return street + ", " + city + ", " + state + " " + zip;

}

function getAddressHtml(location) {
 var street = location.street;
 var city = location.city;
 var state = location.state;
 var zip = location.zip;
 return street + " <br>" + city + ", " + state + " " + zip;

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
		var merchantIndex = Math.floor((-angle + Math.PI * 7 / 2) * merchants.length / Math.PI / 2) % merchants.length;
		var merchant = merchants[merchantIndex];
		$('#history_table tr:last').after('<tr>'
														+ '<td><a target="_blank" href="' + merchant.summary.url.complete + '">' + merchant.summary.name + '</a></td>'
														// I think delivery.com only serves US, so ok to assume phone international code is 1
														+ '<td><a target="_blank" href="callto://+1.' + merchant.summary.phone.split("-").join(".") + '">' + merchant.summary.phone + '</a> </td>'
														+ '<td><a target="_blank" href="https://www.google.com/maps?q=' + getAddress(merchant.location) + '">' + getAddressHtml(merchant.location) + '</a></td>'
														+ '<td>' + merchant.location.distance.toFixed(2) + ' miles</td>'
														+ '<td>' + merchant.summary.overall_rating + '% (' + merchant.summary.num_ratings + ' ratings)</td>'
														+ '<td><a target="_blank" href="' + merchant.summary.url.complete + '">link to delivery</a></td></tr>');
		// if there are queued merchants, replace that merchant with new one
		if (queuedMerchants.length > 0) {
			merchants[merchantIndex] = queuedMerchants.shift();
		}
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
	ctx.font = "13px serif";
	for (var i = 0; i < merchants.length; i++) {
		ctx.save();
		ctx.translate(canvas.width / 2, canvas.height / 2);
		ctx.rotate(angle + (i + 0.5) * 2 * Math.PI / merchants.length);
		ctx.fillText(merchants[i].summary.name, 50, 6.5);
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

<table id="history_table" class="table-striped table">
	<tr>
		<th>Name</th>
		<th>Phone</th>
		<th>Address</th>
		<th>Distance</th>
		<th>Rating</th>
		<th>Delivery!</th>
	</tr>
</table>


</body>
</html>
