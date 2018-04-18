<div id="login">
	<form method="post" action="?act=reset-verification">
	<?php // reset any QUERY parameters ?>
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			
			<div class="center-box main-form">

				<?php
					if(!empty($error_msg)) {
						echo '<h1>Your Password Was Not Reset</h1>';
						echo '<p class="error">'.$error_msg.'</p>';
						unset($_SESSION['error_msg']);
					}else{
				?>
					<h1>Success!</h1>
					<h2>Your Password Has Been Reset</h2>
					<p>Please return to the log in page to sign into your account.</p>
					<a class="btn btn-primary red" href="/dashboard/">Login</a>
				<?php
					}
				?>
				
				
			</div>
		</div>
	</div>
		
	</form>

</div>
