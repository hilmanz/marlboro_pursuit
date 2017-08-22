<!DOCTYPE html>
<!--[if lt IE 7]> <html dir="ltr" lang="en-US" class="ie6"> <![endif]-->
<!--[if IE 7]>    <html dir="ltr" lang="en-US" class="ie7"> <![endif]-->
<!--[if IE 8]>    <html dir="ltr" lang="en-US" class="ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Dashboard A Exchange</title>
<!--Stylesheets-->
<link rel="stylesheet" href="css/style.css" type="text/css"  media="all" />
<link rel="stylesheet" href="css/superfish.css" type="text/css"  media="all" />
<link rel="stylesheet" href="css/colours/red.css" type="text/css"  media="all" />
<link rel="stylesheet" href="css/jquery-ui-datepicker.css" type="text/css"  media="all" />
<!--Favicon-->
<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
<!--JavaScript-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js'></script>
<script type="text/javascript" src="js/superfish.js"></script>
<script type="text/javascript" src="js/jquery.effect.js"></script>
<script type="text/javascript" src="js/organictabs.jquery.js"></script>
<script type="text/javascript" src="js/customform.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
</head>
<body>
<div id="mainContainer">
	<div id="body" class="wrapper">
        <?php include('header.php'); ?>
		<?php 
        if($_GET['menu']=='user-category'){
            include("home.php");
        }else if($_GET['menu']=='google-analytic'){ 
            include("google-analytic.php");
        }else if($_GET['menu']=='web-activities'){ 
            include("web-activities.php");
        }else if($_GET['menu']=='demographic-data'){ 
            include("demographic-data.php");
        }else{ 
            include("home.php");
        }?>
	</div><!-- END .wrapper -->
    <?php include('footer.php'); ?>
    <?php include('popup_post.php'); ?>
    <div class="turntable"></div>
	<div class="headphone"></div>
</div>	
</body>
</html>