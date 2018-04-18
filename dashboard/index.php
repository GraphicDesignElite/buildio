<?php
session_start();
include("../includes/load-yourls.php");
$act = $_GET['act'];
if($act == "logout") {
	$_SESSION['user'] = "";
	unset($_SESSION);
	unset($_SESSION["user"]);
	$error_msg = "Signed off.";
}

include("../header.php");
include("muhtmlfunctions.php");

if(YOURLS_PRIVATE === false) {
	die(); // NO DIRECT CALLS IF PUBLIC!
}



if(!isLogged()) {
	//yourls_html_head( 'login' );
	//mu_html_menu();
	// Login form
	switch($act) {
		case "login":
			$username = yourls_escape($_POST['username']);
			$password = $_POST['password'];
			if(!empty($username) && !empty($password)) {
				if(isValidUser($username, $password)) {
					$token = getUserTokenByEmail($username);
					$id = getUserIdByToken($token);
					$_SESSION['user'] = array("id" => $id, "user" => $username, "token" => $token);
					yourls_redirect("index.php");
				} else {
					$error_msg = "The username or password is incorrect.";
					require_once 'form.php';
				}
			}
			break;
			
		case "joinform":
			require_once 'formjoin.php';
			break;
			
		case "join":
			$username = yourls_escape($_POST['username']);
			$password = $_POST['password'];
			if(captchaEnabled()) {
				require_once('recaptchalib.php');
				$privatekey = YOURLS_MULTIUSER_CAPTCHA_PRIVATE_KEY;
				$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
				if (!$resp->is_valid) {
					$error_msg = "Captch is incorrect.";
					require_once 'formjoin.php';
					break;
				}
			}
			if(!empty($username) && !empty($password)) {
				if(validEmail($username) === false) {
					$error_msg = "E-mail not recognized!";
					require_once 'formjoin.php';
				} else {
					$table = YOURLS_DB_TABLE_USERS;
					$results = $ydb->get_results("select user_email from `$table` where `user_email` = '$username'");
					if($results) {
						$error_msg = "Please choose other username.";
						require_once 'formjoin.php';
					} else {
						$token = createRandonToken();
						$password = md5($password);
						$ydb->query("insert into `$table` (user_email, user_password, user_token) values ('$username', '$password', '$token')");
						$results = $ydb->get_results("select user_token from `$table` where `user_email` = '$username'");
						if (!empty($results)) {
							$token = $results[0]->user_token;
							$error_msg = "Your account has been successfully created. Please log in to continue.";
							require_once 'form.php';
						} else {
							require_once 'formjoin.php';
						}
					}
				}
			} else {
				$error_msg = "Please fill all fields.";
				require_once 'formjoin.php';
			}
			break;
			
		case "request-password-reset":
			require_once 'request-password-reset.php';
			break;	
			
		case "reset-started":
			$username = yourls_escape($_POST['username']);			
			if(!empty($username)) {
				if(validEmail($username) === false) {
					$error_msg = "Please provide a valid email address.";
					require_once 'request-password-reset.php';
				} else {
					$table = YOURLS_DB_TABLE_USERS;
					$results = $ydb->get_results("select user_email, user_token from `$table` where `user_email` = '$username'");
					if($results) {
						require_once 'send-reset-verification-email.php';
					} else {
						$error_msg = "Username not found. Please try again.";
						require_once 'request-password-reset.php';
					}
				}
			} else {
				$error_msg = "Please provide a valid account.";
				require_once 'request-password-reset.php';
			}
			break;
		
		case "reset-link-verification": // After the email link is clicked we verify everything before presenting user with reset form
			$username = $_GET['vun'];
			$tokenRaw = $_GET['code'];
			$tokenEncrypted = substr($tokenRaw, 0, -10);
			$linkTimestamp = substr($tokenRaw, -10);
						
			if(!empty($username)) {
				if(validEmail($username) === false) {
					$error_msg = "Please provide a valid email address.";
					require_once 'request-password-reset.php';
				} else {
					$table = YOURLS_DB_TABLE_USERS;
					$results = $ydb->get_results("select user_email, user_token from `$table` where `user_email` = '$username'");
					
					if($results) { // The user was found
						foreach( $results as $result ) { 
							$dbtoken = $result->user_token; // Get the users token
						} 
						if(password_verify($dbtoken, $tokenEncrypted)) { // Does the database token match the decrypted token

							if($linkTimestamp > (time() - 60*60*12)){ // SUCCESS PASS
								require_once 'reset-password-form.php';
							}else{
								$error_msg = "Your link is expired. Please try again.";
								echo $linkTimestamp;
								require_once 'request-password-reset.php';
							}
							
						}else{
							$error_msg = "Your link is invalid. Please try again.";
							require_once 'request-password-reset.php';
						} // End if token matches
					} else {
						$error_msg = "Username not found. Please try again.";
						require_once 'request-password-reset.php';
					}// End if username is not in the database
				}
			} else { // if no username was provided to the page
				$error_msg = "Please provide a valid account.";
				require_once 'request-password-reset.php';
			}

			break;
			
			case "reset-confirmed":
				$username = $_GET['vun'];
				$tokenRaw = $_GET['code'];
				$tokenEncrypted = substr($tokenRaw, 0, -10);
				$linkTimestamp = substr($tokenRaw, -10);
			
				$newpassword = $_POST['password'];
				$confirmNewpassword = $_POST['confirmpassword'];

				if(!empty($username)) {
					if(validEmail($username) === false) { // if this isnt a valid email format
						$error_msg = "Please provide a valid email address.";
						require_once 'request-password-reset.php';
					} else {
						$table = YOURLS_DB_TABLE_USERS;
						$results = $ydb->get_results("select user_email, user_token from `$table` where `user_email` = '$username'");
						if($results) { // The user was found
							foreach( $results as $result ) { 
								$dbtoken = $result->user_token; // Get the users token
							} 
							if(password_verify($dbtoken, $tokenEncrypted)) { // Does the database token match the decrypted token
								if($linkTimestamp > (time() - 60*60*12)){ // of the link is less than 12 hours old
									if(($newpassword === $confirmNewpassword) && ($newpassword != '') && ($confirmNewpassword != '')){
										
										$token = createRandonToken();
										$password = md5($newpassword);
										
										$ydb->query("update `$table` SET `user_password` = '$password', `user_token` = '$token' where `user_email` = '$username'");
										$results = $ydb->get_results("select user_token from `$table` where `user_email` = '$username'");
										
										if (!empty($results)) {
											$newtoken = $results[0]->user_token;
											
											if($newtoken === $token){
												require_once 'reset-password-complete.php';
											}else{
												$error_msg = "Your account has not been updated. Please try again.";
												require_once 'reset-password-complete.php';
											}
										} else {
											$error_msg = "Your account has not been updated. Please try again.";
											require_once 'reset-password-complete.php';
										}
										// set password here	
									}
									else{
										$error_msg = "Your password and confirm password must match exactly. Please try again.";
										require_once 'reset-password-form.php';
									}
								}else{
									$error_msg = "Your link is expired. Please try again.";
									echo $linkTimestamp;
									require_once 'request-password-reset.php';
								}

							}else{
								$error_msg = "Your link is invalid. Please try again.";
								require_once 'request-password-reset.php';
							} // End if token matches
						} else {
							$error_msg = "Username not found. Please try again.";
							require_once 'request-password-reset.php';
						}// End if username is not in the database
					}
				} else { // if no username was provided to the page
					$error_msg = "Please provide a valid account.";
					require_once 'request-password-reset.php';
				}
			break;

		default:
			require_once 'form.php';

	}
	
	include("../footer.php");
	die();
} else {
	include("admin.php");
	
}
