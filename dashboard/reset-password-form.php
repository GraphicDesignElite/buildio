<?php 
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$confirmedURL = '/dashboard/index.php?act=reset-confirmed';

$generatedURL = $protocol . $host . $confirmedURL . '&code=' . $tokenRaw . '&vun=' . $username;
?>



<div id="login">
	<form method="post" action="<?php echo $generatedURL; ?>">
	<?php // reset any QUERY parameters ?>
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			
			<div class="center-box main-form">
			<h1>Reset Your Password</h1>
			<h2><i class="fa fa-lock" aria-hidden="true"></i> Please provide a new password.</h2>
				<?php
					if(!empty($error_msg)) {
						echo '<p class="error">'.$error_msg.'</p>';
						unset($_SESSION['error_msg']);
					}
					?>
					<div class="form-group">
						<label for="username">New Password</label><br />
						<input type="text" id="password" name="password" size="30" class="text form-control" required>
					</div>
					<div class="form-group">
						<label for="username">Confirm Password</label><br />
						<input type="text" id="confirmpassword" name="confirmpassword" size="30" class="text form-control" required>
					</div>
					<div class="form-group">
						<p style="text-align: center;">
							<input type="submit" id="submit" name="submit" value="Reset Password" class="btn btn-primary red" />
						</p>
					</div>
					
					
			</div>
		</div>
	</div>
		
	</form>

</div>
