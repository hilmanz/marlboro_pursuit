<?php
session_start();
session_destroy();
include_once "common.php";

$db = new SQLData();
$msg = "";
if(isset($_POST['noHP'])!=null){
	$db->open(1);
		$sql="SELECT * FROM axis_registered_number
				WHERE number = {$_POST['noHP']}";

		$rs = $db->fetch($sql);
	$db->close();
	if(!$rs){
		$msg = "Please insert your active AXIS number.";
	}else{
		if($rs['n_status'] == 0){
			$_SESSION['number'] = $_POST['noHP'];
			sendRedirect('online_registration.php');
		}else{
			$msg = "Your AXIS number is already registered.";
		}
	}
	$db->close();
}
?>
<html>
<head>
</head>
<body>
	<div style="padding:20px;float:right;padding-right:600px">	
	<div style="padding:20px;">
		<p><h2>Welcome to AXISWORLD.</h2></p> 
		<p><strong>According to Government Regulation, it is a mandatory for the new prepaid customers to register their numbers.</strong></p>
		<p><strong>Please kindly register yourself by filling your AXIS number in the box below. Thank you.</strong></p>
		<p><strong>example. 08388000838</strong></p>
		<div class="input">
			<form action="#" method="post" id="formLogin">
				<div style="display:none;">
					<input value="POST" name="_method" type="hidden"></div>
					<input onblur="if (this.value == '') {this.value = 'Your AXIS No.';}" onfocus="if (this.value == 'Your AXIS No.') {this.value = '';}" value="Your AXIS No." class="inputnomoraxis" name="noHP" type="text"/>
					<input class="reg" value="Registration" type="submit">
				<span><?php echo $msg; ?></span>
				<div class="clear"></div>
			</form>
		</div>
		<p class="line"></p>
	</div>
</div>
<div style="clear:both" ></div>
</body>
</html>