<?php

include_once "common.php";

/** DEVICE TRACKING **/
	include_once "deviceTrack.php";
	/** END **/
	/** MOBILE REDIRECTION **/
		include_once  $ENGINE_PATH."Utility/Mobile_Detect.php";
		$detect = new Mobile_Detect();
		
		if($detect->mobileGrade()=="C"){
			header("Location: {$CONFIG['WAP_DOMAIN']}");
		}
		
		if ($detect->isMobile()) {
			header("Location: {$CONFIG['MOBILE_SITE']}");
		}
	/** END **/
include_once $APP_PATH.APPLICATION."/App.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName(APPLICATION);
$logger->setDirectory($CONFIG['LOG_DIR']);
$app = new App();
$app->main();

print $app;
die();
?>
