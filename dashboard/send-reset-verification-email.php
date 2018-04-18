<?php

// Generates and mails a link to recover the users account

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$recoveryURL = '/dashboard/index.php?act=reset-link-verification';
$currentTimestamp = time();

foreach( $results as $result ) {
	$token = $result->user_token;
}
$encryptToken = password_hash($token, PASSWORD_DEFAULT);
$encryptUsername = password_hash($username, PASSWORD_DEFAULT);

$generatedURL = $protocol . $host . $recoveryURL . '&code=' . $encryptToken . $currentTimestamp . '&vun=' . $username;

// Include and initialize phpmailer class
require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

// SMTP configuration
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'luxurytld@gmail.com';
$mail->Password = 'DoTLuxUry-Mail';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('luxurytld@gmail.com', 'iBuild');
$mail->addReplyTo('luxurytld@gmail.com', 'iBuild');

// Add a recipient
$mail->addAddress($username);
// Email subject
$mail->Subject = 'iBuild Password Reset';

// Set email format to HTML
$mail->isHTML(true);

// Email body content
$mailContent = "
	<h1>Reset Your Password</h1>
	<h4>iBuild Account</h4>
    <p>Someone has requested a password reset for your iBuild account: " . $username . " To get started, please click the following link:</p>
	<p><a href='" . $generatedURL . "'>" . $generatedURL . "</a></p>
	<p><strong>Your link is only valid for 12 hours.</strong> If you did not reset your password, you may ignore this email, and your password will not change.</p>
	<p>The iBuild Team</p>
	";
$mail->Body = $mailContent;

// Send email
if(!$mail->send()){
   $error_msg .= 'Message could not be sent. ';
   $error_msg .= 'Mailer Error: ' . $mail->ErrorInfo;
}



?>



<div id="login">
	<form method="post" action="?act=reset-verification">
	<?php // reset any QUERY parameters ?>
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="text-align: center;">
			
			<div class="center-box main-form">
			<h1>Verify Your Account</h1>
				<?php
					if(!empty($error_msg)) {
						echo '<p class="error">'.$error_msg.'</p>';
						unset($_SESSION['error_msg']);
					}else{
				?>
					<p>We have sent a link to reset your password to <?php echo $username; ?>. Please allow up to 10 minutes for delivery.</p>	
					<a class="btn btn-primary red" href="/">Return to Home</a>
				<?php
					}
				?>
				
				
			</div>
		</div>
	</div>
		
	</form>

</div>