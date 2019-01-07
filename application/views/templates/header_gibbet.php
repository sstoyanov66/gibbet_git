<!--  this is php code to require https secure protocol      -->
<?php
if ($_SERVER['HTTPS'] != "on") {
    $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="description" content="Бесеничка" />
<meta name="robots" content="index,follow">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Latest compiled and minified CSS-->
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>assets/css_gibb/jquery-ui.multidatespicker.css">
<!-- my project CSS -->
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>assets/css_gibb/gibbet.css">
<!-- my project CSS -->

<title>Бесеничка</title>
<link rel="shortcut icon" type="image/png"
	href="<?php echo base_url();?>assets/img_gibb/08.png">

<!-- Custom Fonts -->
<!--  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">-->
<!-- jQuery libraries -->

<script src="<?php echo base_url();?>assets/js_gibb/jquery-3.2.1.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery-ui/jquery-ui.js"></script>
<!-- datepicker library is here-->
<script
	src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- bootstrap -->


</head>

<body>

	<div class="header" style="">
		<h4>
			<strong>Да поиграем на "Бесеничка"</strong>
		</h4>
	</div>