<?php

LUX_SETTINGS::$general = array(
	'company_name' => 'iBuild',
	'page_title' => 'iBuild.io | Link Shortening Service',
	'qr' => TRUE, // Do you want to display a QR code?
	'customstyle' => TRUE, // Do you want to enable the custom stylesheet, found in public/custom.css?
	'enablelogin' => TRUE,
	'enablestats' => TRUE
);


// SOCIAL SHARING
LUX_SETTINGS::$social = array(
	'twitter' => TRUE,
	'appdotnet' => TRUE,
	'facebook' => TRUE,
	'linkedin' => TRUE,
	'tumblr' => TRUE,
	'googleplus' => TRUE,
	'vk' => TRUE,
);

// reCAPTCHA API KEYS
LUX_SETTINGS::$recaptcha = array(
	//'sitekey' => '6LccqCAUAAAAAGPdrpoL6Exb0SCA1v--aYmIGndS',
	//'secret' => '6LccqCAUAAAAACnEnO1W2H0HqlCPFtnz21ydo7VJ'
);



ISQ::$general = array(
	'name' => 'iBuild', 
	'qr' => TRUE, //  QR code?
	'customstyle' => TRUE 
);


// SOCIAL SHARING
ISQ::$social = array(
	'twitter' => TRUE,
	'appdotnet' => TRUE,
	'facebook' => TRUE,
	'linkedin' => TRUE,
	'tumblr' => TRUE,
	'googleplus' => TRUE,
	'vk' => TRUE,
);

// reCAPTCHA API KEYS
// Get yourls from https://www.google.com/recaptcha/admin
// If you don't want to use reCAPTCHA, that's cool. Leave this empty, and basic
// antispam protection will be provided.
ISQ::$recaptcha = array(
	'sitekey' => '6LccqCAUAAAAAGPdrpoL6Exb0SCA1v--aYmIGndS',
	'secret' => '6LccqCAUAAAAACnEnO1W2H0HqlCPFtnz21ydo7VJ'
);


?>
