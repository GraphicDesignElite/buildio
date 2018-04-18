<?php
function mu_html_loginForm($error_msg = '') {
?>
<div id="login">
	<form method="post" action="?act=login">
	<?php // reset any QUERY parameters ?>
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			
			<div class="center-box main-form">
			<h1>Log In</h1>
			<h2><i class="fa fa-lock" aria-hidden="true"></i> Sign in to start sharing.</h2>
				<?php
					if(!empty($error_msg)) {
						echo '<p class="error">'.$error_msg.'</p>';
						unset($_SESSION['error_msg']);
					}
				?>
				<div class="form-group">
					<label for="username">Username</label><br />
					<input type="text" id="username" name="username" size="30" class="text form-control" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label><br /> <input type="password"
						id="password" name="password" size="30" class="text form-control" required />
				</div>
				<div class="form-group">
					<p style="text-align: center;">
						<input type="submit" id="submit" name="submit" value="Login" class="btn btn-primary red" />
					</p>
				</div>
				<div class="centered">
					<strong>Need an Account?</strong> <a href="/dashboard/index.php?act=joinform">Sign Up!</a><br/>
					<small><a href="/dashboard/index.php?act=request-password-reset">Forgot Password?</a></small>
				</div>
			</div>
		</div>
	</div>
		
	</form>

</div>
<?php
}


function mu_html_reset_password($error_msg = '') {
?>
<div id="login">
	<form method="post" action="?act=reset-started">
	<?php // reset any QUERY parameters ?>
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			
			<div class="center-box main-form">
			<h1>Reset Your Password</h1>
				<?php
					if(!empty($error_msg)) {
						echo '<p class="error">'.$error_msg.'</p>';
						unset($_SESSION['error_msg']);
					}
				?>
				<div class="form-group">
					<label for="username">Username</label><br />
					<input type="text" id="username" name="username" size="30" class="text form-control" required>
				</div>
				
				<div class="form-group">
					<p style="text-align: center;">
						<input type="submit" id="submit" name="submit" value="Next" class="btn btn-primary red" />
					</p>
				</div>
				
			</div>
		</div>
	</div>
		
	</form>

</div>
<?php
}

function mu_html_reset_verification($error_msg = '') {
?>
<div id="login">
	<form method="post" action="?act=reset-verification">
	<?php // reset any QUERY parameters ?>
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			
			<div class="center-box main-form">
			<h1>Verify Your Account</h1>
				<?php
					if(!empty($error_msg)) {
						echo '<p class="error">'.$error_msg.'</p>';
						unset($_SESSION['error_msg']);
					}
				?>
				<p>We have sent a link to reset your password to <?php echo $username; ?>.</p>
				
			</div>
		</div>
	</div>
		
	</form>

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
	
	<div class="container">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			<div class="center-box main-form">
				<h1>Sign up</h1>
				<h2><i class="fa fa-lock" aria-hidden="true"></i> Get your free account.</h2>
				<?php
				if(!empty($error_msg)) {
					echo '<p class="error">'.$error_msg.'</p>';
					unset($_SESSION['error_msg']);
				}
				?>
				<div class="form-group">
					<label for="username">Username (e-mail)</label><br />
					<input type="text" id="username" name="username" size="30" class="text form-control" />
				</div>
				<div class="form-group">
					<label for="password">Password</label><br />
					<input type="password" id="password" name="password" size="30" class="text form-control" />
				</div>
				
				
				<?php
				if(captchaEnabled()) {
					echo '<div class="form-group">';	
						require_once 'recaptchalib.php';
						$publickey = YOURLS_MULTIUSER_CAPTCHA_PUBLIC_KEY;
						echo recaptcha_get_html($publickey);
					echo '</div>';
				}
				?>
				<div class="form-group">
				<p style="text-align: center;">
					<input type="submit" id="submit" name="submit" value="Get a Free Account" class="btn btn-primary red" />
				</p>
				</div>
				<div class="centered">
					<strong>Already Have an Account?</strong> <a href="/dashboard/">Sign In</a></p>
					<small>By creating an account, you agree to our <a href="https://www.domain.build/terms" target="_blank">terms of use</a>.</small>
				</div>
			</div>
		</div>
	</div>
	</form>
	
</div>
<?php
}


function display_share_box( $longurl, $shorturl, $title = '', $text='', $shortlink_title = '', $share_title = '', $hidden = false ) {
	if ( $shortlink_title == '' )
		$shortlink_title = '<h4>' . yourls__( 'Your Shortlink' ) . '</h4>';
	if ( $share_title == '' )
		$share_title = '<h4>' . yourls__( 'Quick Share' ) . '</h4>';
	
	// Allow plugins to short-circuit the whole function
	$pre = yourls_apply_filter( 'shunt_share_box', false );
	if ( false !== $pre )
		return $pre;
		
	$text   = ( $text ? '"'.$text.'" ' : '' );
	$title  = ( $title ? "$title " : '' );
	$share  = yourls_esc_textarea( $title.$text.$shorturl );
	$count  = 140 - strlen( $share );
	$hidden = ( $hidden ? 'style="display:none;"' : '' );
	
	// Allow plugins to filter all data
	$data = compact( 'longurl', 'shorturl', 'title', 'text', 'shortlink_title', 'share_title', 'share', 'count', 'hidden' );
	$data = yourls_apply_filter( 'share_box_data', $data );
	extract( $data );
	
	$_share = rawurlencode( $share );
	$_url   = rawurlencode( $shorturl );
	?>
	
	<div id="shareboxes" <?php echo $hidden; ?>>

		<?php yourls_do_action( 'shareboxes_before', $longurl, $shorturl, $title, $text ); ?>

		<div class="col-md-5 social-box">
			<div class="social-box-content">
				<h3>Shortlink</h3>
				<div class="input-with-copy">
					<input name="shortlink" id="copylink" onclick="this.select();" onload="this.select();" value="<?php echo yourls_esc_url( $shorturl ); ?>" class="form-control input-lg">
					<button data-clipboard-target="#copylink" class="copy-button button btn btn-secondary btn-lg" title="Copy to clipboard"><img src="/public/images/clippy.svg"></button>
					<div class="copy-message success" id="copy-success">Copied to clipboard</div>
					<div class="copy-message error" id="copy-error">
						<span class="os macos">Press ⌘+C to copy</span>
						<span class="os pc">Press Ctrl+C to copy</span>
						<span class="os mobile">Tap copy</span>
						<span class="os other">Failed to copy</span>
					</div>
				</div>
				<p>
					<BR/>
					<small><?php yourls_e( 'Long link' ); ?>: <a id="origlink" href="<?php echo yourls_esc_url( $longurl ); ?>"><?php echo yourls_esc_url( $longurl ); ?></a></small>
					<?php if( yourls_do_log_redirect() ) { ?>
						<br/><small><?php yourls_e( 'Stats' ); ?>: <a id="statlink" href="<?php echo yourls_esc_url( $shorturl ); ?>+"><?php echo yourls_esc_url( $shorturl ); ?>+</a></small>
						<input type="hidden" id="titlelink" value="<?php echo yourls_esc_attr( $title ); ?>" class="form-control text" />
					<?php } ?>
				</p>
			</div>
		</div>

		<?php yourls_do_action( 'shareboxes_middle', $longurl, $shorturl, $title, $text ); ?>

		<div class="col-md-6 col-md-offset-1 social-box">
			<div class="social-box-content">
				<?php echo $share_title; ?>
				<div id="tweet" class="form-group">
					
					<textarea id="tweet_body" class="form-control"><?php echo $share; ?></textarea>
					<span id="charcount" class="hide-if-no-js"><?php echo $count; ?></span>
				</div>
				<p id="share_links">
					<?php yourls_e( 'Share with' ); ?> 
					<a id="share_tw" href="http://twitter.com/home?status=<?php echo $_share; ?>" title="<?php yourls_e( 'Tweet this!' ); ?>" onclick="share('tw');return false">Twitter</a>
					<a id="share_fb" href="http://www.facebook.com/share.php?u=<?php echo $_url; ?>" title="<?php yourls_e( 'Share on Facebook' ); ?>" onclick="share('fb');return false;">Facebook</a>
					<?php
						yourls_do_action( 'share_links', $longurl, $shorturl, $title, $text );
						// Note: on the main admin page, there are no parameters passed to the sharebox when it's drawn.
					?>
				</p>
			</div>
		</div>
		
		<?php yourls_do_action( 'shareboxes_after', $longurl, $shorturl, $title, $text ); ?>
	
	</div>
	
	<?php
}


function mu_html_menu() {
	echo "
		<script type=\"text/javascript\">
		//<![CDATA[
			var ajaxurl = '" . "/dashboard/admin-ajax.php"  . "';
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

function mu_html_tfooter( $params = array() ) {
    // Manually extract all parameters from the array. We prefer doing it this way, over using extract(),
    // to make things clearer and more explicit about what var is used.
    $search       = $params['search'];
    $search_text  = $params['search_text'];
    $search_in    = $params['search_in'];
    $sort_by      = $params['sort_by'];
    $sort_order   = $params['sort_order'];
    $page         = $params['page'];
    $perpage      = $params['perpage'];
    $click_filter = $params['click_filter'];
    $click_limit  = $params['click_limit'];
    $total_pages  = $params['total_pages'];
    $date_filter  = $params['date_filter'];
    $date_first   = $params['date_first'];
    $date_second  = $params['date_second'];

	?>
	
				<div id="filters">
					<form action="" method="get">
						<div class="container">
							<div class="well">
								<div class="row">
									<div class="form-inline search-panel-row clearfix">
										<div class="col-sm-12">
											<h3>Search Links</h3>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<?php
													// First search control: text to search
													$_input = '<input type="text" name="search" class="text form-control" size="12" value="' . yourls_esc_attr( $search_text ) . '" />';
													$_options = array(
														'all'     => yourls__( 'All fields' ),
														'keyword' => yourls__( 'Short URL' ),
														'url'     => yourls__( 'URL' ),
														'title'   => yourls__( 'Title' ),
														'ip'      => yourls__( 'IP' ),
													);							
													$_select = create_html_select( 'search_in', $_options, $search_in );
													/* //translators: "Search for <input field with text to search> in <select dropdown with URL, title...>" */
													yourls_se( 'Search for %1$s in %2$s', $_input , $_select );

												?>

											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<?php
													// Second search control: order by
													$_options = array(
														'keyword'      => yourls__( 'Short URL' ),
														'url'          => yourls__( 'URL' ),
														'timestamp'    => yourls__( 'Date' ),
														'ip'           => yourls__( 'IP' ),
														'clicks'       => yourls__( 'Clicks' ),
													);
													$_select = create_html_select( 'sort_by', $_options, $sort_by );
													$sort_order = isset( $sort_order ) ? $sort_order : 'desc' ;
													$_options = array(
														'asc'  => yourls__( 'Ascending' ),
														'desc' => yourls__( 'Descending' ),
													);
													$_select2 = create_html_select( 'sort_order', $_options, $sort_order );
													/* //translators: "Order by <criteria dropdown (date, clicks...)> in <order dropdown (Descending or Ascending)>" */
													yourls_se( 'Order %1$s %2$s', $_select , $_select2 );
												?>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-inline search-panel-row clearfix">
										<div class="col-sm-6">
											<div class="form-group">
												<?php
													// Fourth search control: Show links with more than XX clicks
													$_options = array(
														'more' => yourls__( 'more' ),
														'less' => yourls__( 'less' ),
													);
													$_select = create_html_select( 'click_filter', $_options, $click_filter );
													$_input  = '<input type="text" name="click_limit" class="text form-control" size="4" value="' . $click_limit . '" /> ';
													/* //translators: "Show links with <more/less> than <text field> clicks" */
													yourls_se( 'Show links with %1$s than %2$s clicks', $_select, $_input );
												?>

											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<?php
													// Fifth search control: Show links created before/after/between ...
													$_options = array(
														'before'  => yourls__('before'),
														'after'   => yourls__('after'),
														'between' => yourls__('between'),
													);
													$_select = create_html_select( 'date_filter', $_options, $date_filter );
													$_input  = '<input type="text" name="date_first" id="date_first" class="text form-control" size="12" value="' . $date_first . '" />';
													$_and    = '<span id="date_and"' . ( $date_filter === 'between' ? ' style="display:inline"' : '' ) . '> &amp; </span>';
													$_input2 = '<input type="text" name="date_second" id="date_second" class="text form-control" size="12" value="' . $date_second . '"' . ( $date_filter === 'between' ? ' style="display:inline"' : '' ) . '/>';
													/* //translators: "Show links created <before/after/between> <date input> <"and" if applicable> <date input if applicable>" */
													yourls_se( 'Created %1$s %2$s %3$s %4$s', $_select, $_input, $_and, $_input2 );

												?>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="form-inline search-panel-row clearfix">
										<div class="col-sm-6">
											<div class="form-group">
												<?php
													// Third search control: Show XX rows
													/* //translators: "Show <text field> rows" */
													yourls_se( 'Display %s rows',  '<input type="text" name="perpage" class="text form-control" size="2" value="' . $perpage . '" />' );
												?>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<input type="submit" id="submit-sort" value="<?php yourls_e('Search'); ?>" class="btn btn-primary" />
										&nbsp;
										<input type="button" id="submit-clear-filter" value="<?php yourls_e('Clear'); ?>" class="btn btn-secondary" onclick="window.parent.location.href = 'index.php'" />
									</div>
								</div>
							
							</div>
						</div>
					</form>
				</div>

				<?php
				// Remove empty keys from the $params array so it doesn't clutter the pagination links
				$params = array_filter( $params, 'yourls_return_if_not_empty_string' ); // remove empty keys

				if( isset( $search_text ) ) {
					$params['search'] = $search_text;
					unset( $params['search_text'] );
				}
				?>
			
		<?php yourls_do_action( 'html_tfooter' ); ?>
	<?php
}
function create_html_select( $name, $options, $selected = '', $display = false ) {
	$html = "<select name='$name' id='$name' size='1' class='form-control'>\n";
	foreach( $options as $value => $text ) {
		$html .= "<option value='$value' ";
		$html .= $selected == $value ? ' selected="selected"' : '';
		$html .= ">$text</option>\n";
	}
	$html .= "</select>\n";
	$html  = yourls_apply_filter( 'html_select', $html, $name, $options, $selected, $display );
	if( $display )
		echo $html;
	return $html;
}
/**
 * Return an "Edit" row for the main table
 *
 * @param string $keyword Keyword to edit
 * @return string HTML of the edit row
 */
function mu_table_edit_row( $keyword ) {
	$keyword = yourls_sanitize_string( $keyword );
	$id = yourls_string2htmlid( $keyword ); // used as HTML #id
	$url = yourls_get_keyword_longurl( $keyword );
	$title = htmlspecialchars( yourls_get_keyword_title( $keyword ) );
	$safe_url = yourls_esc_attr( rawurldecode( $url ) );
	$safe_title = yourls_esc_attr( $title );
    
    // Make strings sprintf() safe: '%' -> '%%'
    $safe_url = str_replace( '%', '%%', $safe_url );
    $safe_title = str_replace( '%', '%%', $safe_title );

	$www = yourls_link();
    
	$nonce = yourls_create_nonce( 'edit-save_'.$id );
	
	if( $url ) {
		$return = <<<RETURN
<tr id="edit-$id" class="edit-row">
	<td colspan="5" class="edit-row">
		<div class="form-group">
			<label>%s</label>:
			<input type="text" id="edit-url-$id" name="edit-url-$id" value="$safe_url" class="form-control text" size="70" />
		</div>
		<div class="form-group">
			<label>%s</label>:
			$www<input type="text" id="edit-keyword-$id" name="edit-keyword-$id" value="$keyword" class="text form-control" size="10" />
		</div>
		<div class="form-group">
			<label>%s</label>: 
			<input type="text" id="edit-title-$id" name="edit-title-$id" value="$safe_title" class="text form-control" size="60" />
		</div>
	</td>
	<td colspan="1">
		<input type="button" id="edit-submit-$id" name="edit-submit-$id" value="%s" title="%s" class="btn btn-primary red" onclick="edit_link_save('$id');" />&nbsp;
		<input type="button" id="edit-close-$id" name="edit-close-$id" value="%s" title="%s" class="btn btn-primary red" onclick="edit_link_hide('$id');" />
		<input type="hidden" id="old_keyword_$id" value="$keyword"/><input type="hidden" id="nonce_$id" value="$nonce"/>
	</td>
</tr>
RETURN;
		$return = sprintf( $return, yourls__( 'Long URL' ), yourls__( 'Short URL' ), yourls__( 'Title' ), yourls__( 'Save' ), yourls__( 'Save new values' ), yourls__( 'Cancel' ), yourls__( 'Cancel editing' ) );
	} else {
		$return = '<tr class="edit-row notfound"><td colspan="6" class="edit-row notfound">' . yourls__( 'Error, URL not found' ) . '</td></tr>';
	}
	
	$return = yourls_apply_filter( 'table_edit_row', $return, $keyword, $url, $title );

	return $return;
}


?>
