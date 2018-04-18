<?php
if (!defined('YOURLS_MULTIUSER_PROTECTED')) {
	define('YOURLS_MULTIUSER_PROTECTED',true);
}

if(!defined('YOURLS_DB_TABLE_USERS')) {
	define('YOURLS_DB_TABLE_USERS', YOURLS_DB_PREFIX."users");
}

if(!defined('YOURLS_DB_TABLE_URL_TO_USER')) {
	define('YOURLS_DB_TABLE_URL_TO_USER', YOURLS_DB_PREFIX.'url_to_user');
}

if(!defined('YOURLS_MULTIUSER_CAPTCHA')) {
	define('YOURLS_MULTIUSER_CAPTCHA', false);
}

if(!defined('YOURLS_MULTIUSER_CAPTCHA_PUBLIC_KEY')) {
	define('YOURLS_MULTIUSER_CAPTCHA', false);
}
if(!defined('YOURLS_MULTIUSER_CAPTCHA_PRIVATE_KEY')) {
	define('YOURLS_MULTIUSER_CAPTCHA', false);
}
if(!defined('YOURLS_MULTIUSER_CAPTCHA_THEME')) { 
	define('YOURLS_MULTIUSER_CAPTCHA_THEME', 'white');
}
if(!defined('YOURLS_MULTIUSER_ANONYMOUS')) { 
	define('YOURLS_MULTIUSER_ANONYMOUS', true);
}

function captchaEnabled() {
	if(defined('YOURLS_MULTIUSER_CAPTCHA') && (YOURLS_MULTIUSER_CAPTCHA == true))
		return true;
	return false;
}


//http://www.linuxjournal.com/article/9585?page=0,3
/**
 Validate an email address.
 Provide email address (raw input)
 Returns true if the email address has the email
 address format and the domain exists.
 */
function validEmail($email)
{
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex)
	{
		$isValid = false;
	}
	else
	{
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64)
		{
			// local part length exceeded
			$isValid = false;
		}
		else if ($domainLen < 1 || $domainLen > 255)
		{
			// domain part length exceeded
			$isValid = false;
		}
		else if ($local[0] == '.' || $local[$localLen-1] == '.')
		{
			// local part starts or ends with '.'
			$isValid = false;
		}
		else if (preg_match('/\\.\\./', $local))
		{
			// local part has two consecutive dots
			$isValid = false;
		}
		else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		{
			// character not valid in domain part
			$isValid = false;
		}
		else if (preg_match('/\\.\\./', $domain))
		{
			// domain part has two consecutive dots
			$isValid = false;
		}
		else if
		(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
		str_replace("\\\\","",$local)))
		{
			// character not valid in local part unless
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/',
			str_replace("\\\\","",$local)))
			{
				$isValid = false;
			}
		}
		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
		{
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}

// GENERATE A RANDON TOKEN!
function createRandonToken() {
	global $ydb;
	$ui = uniqid(uniqid("a", true), true);
	$table = YOURLS_DB_TABLE_USERS;
	$results = $ydb->get_results("select user_email from `$table` where `user_token` = '$ui'");
	if(!empty($results)) {
		return createRandonToken();
	} else {
		return str_replace(".", "", $ui);
	}
}
function isValidUser($user, $pass) {
	global $ydb;
	$table = YOURLS_DB_TABLE_USERS;
	$pass = md5($pass);
	$results = $ydb->get_results("select user_token from `$table` where `user_email` = '$user' AND user_password = '$pass'");
	if(!empty($results)) {
		return true;
	}
	return false;
}
function getUserTokenByEmail($user) {
	global $ydb;
	$table = YOURLS_DB_TABLE_USERS;
	$results = $ydb->get_results("select user_token from `$table` where `user_email` = '$user'");
	if (!empty($results)) {
		return $results[0]->user_token;
	}
	return false;
}
function getUserIdByToken($token) {
	global $ydb;
	$table = YOURLS_DB_TABLE_USERS;
	$results = $ydb->get_results("select user_id from `$table` where `user_token` = '$token'");
	if (!empty($results)) {
		return $results[0]->user_id;
	}
	return false;
}

function verifyUrlOwner($keyword, $userId) {
	global $ydb;
	$table = YOURLS_DB_TABLE_URL_TO_USER;
	$results = $ydb->get_results("select url_keyword from `$table` where `url_keyword` = '$keyword' AND users_user_id = '$userId'");
	if (isset($results)) {
		return true;
	}
	return false;
}

// Add a link row
function mu_table_add_row( $keyword, $url, $title = '', $ip, $clicks, $timestamp ) {
	$keyword  = yourls_sanitize_string( $keyword );
	$display_keyword = htmlentities( $keyword );

	$url = yourls_sanitize_url( $url );
	$display_url = htmlentities( yourls_trim_long_string( $url ) );
	$title_url = htmlspecialchars( $url );

	$title = yourls_sanitize_title( $title ) ;
	$display_title   = yourls_trim_long_string( $title );
	$title = htmlspecialchars( $title );

	$id      = yourls_string2htmlid( $keyword ); // used as HTML #id
	$date    = date( 'M d, Y H:i', $timestamp+( YOURLS_HOURS_OFFSET * 3600) );
	$clicks  = number_format($clicks, 0, '', '');

	$shorturl = YOURLS_SITE.'/'.$keyword;
	$statlink = $shorturl.'+';
	if( yourls_is_ssl() )
	$statlink = str_replace( 'http://', 'https://', $statlink );

	if( $title ) {
		$display_link = "<a href=\"$url\" title=\"$title\">$display_title</a><br/><small><a href=\"$url\" title=\"$title_url\">$display_url</a></small>";
	} else {
		$display_link = "<a href=\"$url\" title=\"$title_url\">$display_url</a>";
	}

	$delete_link = yourls_nonce_url( 'delete-link_'.$id,
	yourls_add_query_arg( array( 'id' => $id, 'action' => 'delete', 'keyword' => $keyword ), muAdminUrl( 'admin-ajax.php' ) )
	);

	$edit_link = yourls_nonce_url( 'edit-link_'.$id,
	yourls_add_query_arg( array( 'id' => $id, 'action' => 'edit', 'keyword' => $keyword ), muAdminUrl( 'admin-ajax.php' ) )
	);



	$actions = <<<ACTION
<a href="$statlink" id="statlink-$id" title="Stats" class="btn btn-primary red"><i class="fa fa-bar-chart" aria-hidden="true"></i> Stats</a><span class="button-wrapper"><a href="" id="share-button-$id" name="share-button" title="Share" class="btn button_share" onclick="toggle_share('$id');return false;"><i class="fa fa-share-alt" aria-hidden="true"></i></a><a href="$edit_link" id="edit-button-$id" name="edit-button" title="Edit" class="btn button_edit" onclick="edit_link_display('$id');return false;"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="$delete_link" id="delete-button-$id" name="delete-button" title="Delete" class="btn  button_delete" onclick="remove_link('$id');return false;"><i class="fa fa-trash" aria-hidden="true"></i></a></span>
ACTION;
	$actions = yourls_apply_filter( 'action_links', $actions, $keyword, $url, $ip, $clicks, $timestamp );

	$row = <<<ROW
<tr id="id-$id"><td id="keyword-$id" class="keyword"><a href="$shorturl">$display_keyword</a></td><td id="url-$id" class="url">$display_link</td><td id="timestamp-$id" class="timestamp">$date</td><td id="ip-$id" class="ip">$ip</td><td id="clicks-$id" class="clicks">$clicks</td><td class="actions" id="actions-$id">$actions<input type="hidden" id="keyword_$id" value="$keyword"/></td></tr>
ROW;
	$row = yourls_apply_filter( 'table_add_row', $row, $keyword, $url, $title, $ip, $clicks, $timestamp );

	return $row;
}

function muAdminUrl($page = '') {
	$admin = YOURLS_SITE . '/dashboard/' . $page;
	return yourls_apply_filter( 'admin_url', $admin, $page );
}

function isLogged() { 
	if(!empty($_SESSION['user']) && isset($_SESSION['user'])) { 
		return true;
	}
	return false;
}

function encryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );
}

function decryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}

?>
