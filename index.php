<?php include('header.php');

$url     = isset( $_REQUEST['url'] ) ? yourls_sanitize_url( $_REQUEST['url'] ) : '' ;
$keyword = isset( $_REQUEST['keyword'] ) ? yourls_sanitize_keyword( $_REQUEST['keyword'] ) : '' ;
$title   = isset( $_REQUEST['title'] ) ? yourls_sanitize_title( $_REQUEST['title'] ) : '' ;


?>
<form method="post" action="result.php" class="newurl" id="newurl" novalidate>
	<?php if(isLogged()){ ?>
	<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1">
			
				<div class="center-box main-form">
					<div class="sign-up">
						<div class="row">
							<div class="col-sm-12">
								<h1>iBuild.io</h1>
								<h2>The URL Shortener That Puts YOUR Content On .Build</h2>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<input type="text" id="url" name="url" value="<?php echo($url); ?>" autofocus class="form-control input-lg url-entry" placeholder="Ex: http://mysite">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<p><label for="keyword" class="primary"><?php yourls_e( 'Custom keyword', 'isq_translation'); ?></label></p>
								<input type="text" id="keyword" name="keyword" autocorrect="off" autocapitalize="none" value="<?php echo($keyword); ?>" class="form-control input-lg">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<p><label for="title" class="primary"><?php yourls_e( 'Custom title', 'isq_translation'); ?></label></p>
								<input type="text" id="title" name="title" value="<?php echo($title); ?>" class="form-control input-lg">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group centered">
								<input type="submit" class="btn btn-primary red" value="<?php yourls_e( 'Create Shortlink', 'isq_translation'); ?>">
							</div>
							<div class="errortxt"></div>
						</div>		
					</div>
				</div>
				
					
					
					<?php
						if ( function_exists( 'yourls_is_valid_user' ) && yourls_is_valid_user() == 1 ) {
							echo '<input type="hidden" name="antispam_method" value="user_login" class="hidden">';
						} else if ( !empty(LUX_SETTINGS::$recaptcha['sitekey']) && !empty(LUX_SETTINGS::$recaptcha['secret']) ) {
							$dependencies[] = 'reCAPTCHA';
							echo '<input type="hidden" name="antispam_method" value="recaptcha" class="hidden">';
						?>
							<div class="form-item recaptcha-container">
								<p><label class="primary" title=""><?php yourls_e( 'Verification', 'isq_translation'); ?></label></p>
								<div class="g-recaptcha" data-theme="light" data-sitekey="<?php echo LUX_SETTINGS::$recaptcha['sitekey']; ?>"></div>
							</div>
						<?php
						} else {
							echo '<input type="hidden" name="antispam_method" value="basic" class="hidden">';
							echo '<input type="hidden" name="basic_antispam" class="hidden">';

						}
					?>
				</div>
			
		</div>
	</div>
	<?php  } else { ?> <!-- not logged -->
	<div class="home-hero">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 centered">
					<h1>.build your online identity</h1>
					<br/>
					<img src="/public/images/ibuild.logo.png" alt="iBuild" class="luxury-logo"/>
				</div>
				<div class="col-sm-6 col-sm-offset-3 centered">
					<h2 class="text-box-wrapper">Custom Shortened links on the .build domain.</h2>
				</div>
				<div class="col-sm-8 col-sm-offset-2">
					<div class="input-group input-group-lg">
						<input type="text" id="url" name="url" value="<?php echo($url); ?>" autofocus class="form-control input-lg url-entry" placeholder="Ex: http://mysite">
						<div class="input-group-btn">
						  <button class="btn btn-primary red" id="submit-inline" type="submit">
							Create Shortlink
						  </button>
						</div>
					</div>
				</div>
				<?php
						if ( function_exists( 'yourls_is_valid_user' ) && yourls_is_valid_user() == 1 ) {
							echo '<input type="hidden" name="antispam_method" value="user_login" class="hidden">';
						} else if ( !empty(LUX_SETTINGS::$recaptcha['sitekey']) && !empty(LUX_SETTINGS::$recaptcha['secret']) ) {
							$dependencies[] = 'reCAPTCHA';
							echo '<input type="hidden" name="antispam_method" value="recaptcha" class="hidden">';
						?>
							<div class="form-item recaptcha-container">
								<p><label class="primary" title=""><?php yourls_e( 'Verification', 'isq_translation'); ?></label></p>
								<div class="g-recaptcha" data-theme="light" data-sitekey="<?php echo LUX_SETTINGS::$recaptcha['sitekey']; ?>"></div>
							</div>
						<?php
						} else {
							echo '<input type="hidden" name="antispam_method" value="basic" class="hidden">';
							echo '<input type="hidden" name="basic_antispam" class="hidden">';

						}
					?>
			</div>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<div class="errortxt"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 centered">
					<a href="/dashboard/?act=joinform" class="sign-up-cta">Sign Up For Free!</a>
					<p><a class="white-link" href="/dashboard/"> Log In</a></p>
				</div>
			</div>
		</div>	
	</div>	
	
	<?php  } ?>

</form>
<?php include('footer.php'); ?>
