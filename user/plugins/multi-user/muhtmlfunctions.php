<?php
function mu_html_loginForm($error_msg = '') {
	?>
<div id="login">
	<form method="post" action="?act=login">
	<?php // reset any QUERY parameters ?>
	<?php
	if(!empty($error_msg)) {
		echo '<p class="error">'.$error_msg.'</p>';
		unset($_SESSION['error_msg']);
	}
	?>
		<div class="form-group">
			<label for="username">Username</label><br />
			<input type="text" id="username" name="username" size="30" class="text form-control input-lg" />
		</div>
		<div class="form-group">
			<label for="password">Password</label><br /> <input type="password"
				id="password" name="password" size="30" class="text form-control input-lg" />
		</div>
		<div class="form-group">
			<p style="text-align: center;">
				<input type="submit" id="submit" name="submit" value="Login"
					class="btn btn-primary red btn-lg" />
			</p>
		</div>
	</form>
	<p><strong>Need an Account?</strong> <a href="<?php echo muAdminUrl('index.php?act=joinform'); ?>">Sign Up!</a></p>
	<script type="text/javascript">$('#username').focus();</script>
</div>
	<?php
}

function mu_html_signupForm($error_msg = '') {
	?>
<script type="text/javascript">
		 var RecaptchaOptions = {
		    theme : '<?php echo YOURLS_MULTIUSER_CAPTCHA_THEME ?>'
		 };
</script>
<div id="login">
	<form method="post" action="?act=join">
	<?php // reset any QUERY parameters ?>
	<?php
	if(!empty($error_msg)) {
		echo '<p class="error">'.$error_msg.'</p>';
		unset($_SESSION['error_msg']);
	}
	?>
		<p>
			<label for="username">Username (e-mail)</label><br /> <input
				type="text" id="username" name="username" size="30" class="text" />
		</p>
		<p>
			<label for="password">Password</label><br /> <input type="password"
				id="password" name="password" size="30" class="text" />
		</p>
		<p>
		<?php
		if(captchaEnabled()) {
			require_once 'recaptchalib.php';
			$publickey = YOURLS_MULTIUSER_CAPTCHA_PUBLIC_KEY;
			echo recaptcha_get_html($publickey);
		}
		?>
		</p>



		<input type="submit" id="submit" name="submit" value="Join!"
			class="button" />
		</p>








	</form>
	<script type="text/javascript">$('#username').focus();</script>
</div>
		<?php
}

function mu_html_menu() {
	echo "
		<script type=\"text/javascript\">
		//<![CDATA[
			var ajaxurl = '" . muAdminUrl("admin-ajax.php") . "';
		//]]>
		</script>";
	?>

<ul id="admin_menu">
	<?php if(isLogged()) { ?>
	
	<li><a href="<?php echo muAdminUrl('index.php?act=logout'); ?>">Logout</a>
	</li>
	<?php
	} else {
		?>
	<li><a href="<?php echo muAdminUrl('index.php'); ?>">Log in</a></li>
	<li><a href="<?php echo muAdminUrl('index.php?act=joinform'); ?>">Sign Up</a></li>
			<?php
	}
	?>
</ul>
	<?php
}
?>
