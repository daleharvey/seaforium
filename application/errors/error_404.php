<html>
<head>
<title>404 Page Not Found</title>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			12px;
color:				#000;
}

#theimg {
	background-image:url('/img/errorpage_icon.gif');
	position:fixed;
	top:50%;
	left:50%;
	width:400px;
	height:100px;
	overflow:hidden;
	margin-top: -50px;
	margin-left: -200px;
	z-index:80;
}

#content {
	position:fixed;
	top:50%;
	left:50%;
	width:400px;
	height:100px;
	overflow:hidden;
	margin-top: -40px;
	margin-left: -100px;
	z-index:90;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#990000;
}
</style>
</head>
<body>
	<div id="theimg"></div>
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		<p>(<a href="/">Return to homepage</a>)</p>
	</div>
</body>
</html>