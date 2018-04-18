<!DOCTYPE html>
<?php
// Start YOURLS engine
require_once( dirname(__FILE__) . '/includes/load-yourls.php' );

// Ask for Infinity Squared settings
if( @include dirname(__FILE__) . '/public/config.php' ) {
	include( dirname(__FILE__) . '/public/config.php' );
} else {
	include( dirname(__FILE__) . '/public/config-sample.php' );
}

class ISQ { public static $general = array(), $links = array(), $social = array(), $recaptcha = array(); }
class LUX_SETTINGS { public static $general = array(), $links = array(), $social = array(), $recaptcha = array(), $enableLogin = false;  }

// Default dependencies
$dependencies = array(); 

$currentPage = $_SERVER['PHP_SELF'];
switch ( $currentPage ) {
	case '/index.php':
		$pageClass = 'home';
		break;
			
	default:
		$pageClass = 'standard';
		break;
}

// Load translations
yourls_load_custom_textdomain( 'isq_translation', 'public/languages' );
?>

<html><head>
		<title><?php echo LUX_SETTINGS::$general['page_title']; ?></title> <!-- Site title defined in theme settings -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="fas"="fas1057" />
		
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-NVF39CV');</script>
		<!-- End Google Tag Manager -->
		
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
		
		<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/css/tablesorter.css?v=1.7.3" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/css/share.css?v=1.7.3" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/cal.css?v=1.7.3" type="text/css" media="screen" />
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/custom.css" /><!-- Custom CSS -->
		
		<?php 
			$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			if(strpos($url,'admin') !== false){
				?>
				<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/css/styleOR.css" />
				<?php if ( $tabs ) { ?>
					<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/infos.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
					<script src="<?php yourls_site_url(); ?>/js/infos.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
				<?php } ?>
				<?php if ( $tablesorter ) { ?>
					<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/tablesorter.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
					<script src="<?php yourls_site_url(); ?>/js/jquery.tablesorter.min.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
				<?php } ?>
				<?php if ( $insert ) { ?>
					<script src="<?php yourls_site_url(); ?>/js/insert.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
				<?php } ?>
				<?php if ( $share ) { ?>
					<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/share.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
					<script src="<?php yourls_site_url(); ?>/js/share.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
					<script src="<?php yourls_site_url(); ?>/js/clipboard.min.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
				<?php } ?>
				<?php if ( $cal ) { ?>
					<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/cal.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
					<?php yourls_l10n_calendar_strings(); ?>
					<script src="<?php yourls_site_url(); ?>/js/jquery.cal.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
				<?php } ?>
				<?php if ( $charts ) { ?>
						<script type="text/javascript" src="https://www.google.com/jsapi"></script>
						<script type="text/javascript">
								 google.load('visualization', '1.0', {'packages':['corechart', 'geochart']});
						</script>
				<?php } ?>
				<script type="text/javascript">
				//<![CDATA[
					var ajaxurl  = '<?php echo yourls_admin_url( 'admin-ajax.php' ); ?>';
				//]]>
				</script>
		<?php } ?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load('visualization', '1.0', {'packages':['corechart', 'geochart']});
		</script>
		<link rel="apple-touch-icon" sizes="180x180" href="public/images/app-icons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="public/images/app-icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="public/images/app-icons/favicon-16x16.png">
		<link rel="manifest" href="public/images/app-icons/manifest.json">
		<link rel="mask-icon" href="public/images/app-icons/safari-pinned-tab.svg" color="#000000">
		<meta name="theme-color" content="#ffffff">
	</head>
	
<body class="load <?php echo $pageClass; ?>">
	<nav class="push-sidebar hidden" id="sidebar-left">
		<div class="mobile-menu-logo">
			<a href="/"><img src="/public/images/ibuild.logo.png" alt="iBuild"></a>
		</div>

		<ul id="menu-mobile-menu" class="nav nav-pills nav-stacked side-navigation mobile">
			<?php 
				$auth = yourls_is_valid_user(); 
				$uri = $_SERVER['REQUEST_URI'];
				$isAdminArea = strpos($uri, 'admin');
						
			?>
			<?php if($auth == 1 && $isAdminArea) : ?>
			<li>
				<a href="/admin/">Admin</a>
			</li>
			<?php endif; ?>
		
			<li>
				<a href="/" class="menu-link main-menu-link">Create</a>
			</li>
			<?php if(!isLogged()){ ?>
				<li><a class="menu-link main-menu-link" href="/dashboard/?act=joinform">Sign Up</a></li>
				<li><a class="menu-link main-menu-link" href="/dashboard/">Log In</a></li>
			<?php } else { ?>
					<li><a class="menu-link main-menu-link" href="/dashboard/">Dashboard</a></li>
					<li><a class="menu-link main-menu-link" href="/dashboard/index.php?act=logout">Log Out</a></li>
			<?php } ?>
		</ul>
		

	</nav>
	<section class="canvas" id="canvas">
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVF39CV"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<div id="loader">
			<div class="loader-display">
				<img src="/public/images/pageloader.gif" width="100" height="100"/>
				<br/></br/>Loading...
			</div>
		</div>
		<div class="wrap">
			<header>
				<div class="container">
				  <nav class="navbar navbar-default">
					<div class="container-fluid">
					  <div class="navbar-header">
						<?php if(LUX_SETTINGS::$general['enablelogin'] == true){ ?>
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							  <span class="sr-only">Toggle navigation</span>
							  <span class="icon-bar"></span>
							  <span class="icon-bar"></span>
							  <span class="icon-bar"></span>
							</button>
						<?php } ?>
						<div class="pull-menu-button">
							<button type="button" id="nav-icon"
									class="btn btn-default pull-left" 
									data-toggle="push" 
									data-target="#sidebar-left" 
									data-distance="320"
									data-antiScroll="false">
									  <span></span>
									  <span></span>
									  <span></span>
									  <span></span>
							</button>
						</div>
						<a class="navbar-brand" href="/"><img src="<?php yourls_site_url(); ?>/public/images/ibuild.logo.png" alt="iBuild Logo" width="110px;"/></a>
					  </div>
					  <div id="navbar" class="navbar-collapse collapse"> 
					  	<?php if(LUX_SETTINGS::$general['enablelogin'] == true){ ?>
						<ul class="nav navbar-nav navbar-right">
							<?php if($auth == 1 && $isAdminArea) : ?>
								<li>
									<a href="/admin/">Admin</a>
								</li>
							<?php endif; ?>
							<li><a href="/">Create</a></li>
							<?php if(!isLogged()){ ?>
							<li><a href="/dashboard/?act=joinform">Sign Up</a></li>
							<li><a href="/dashboard/">Log In</a></li>
							
							<?php } else { ?>
								<li><a href="/dashboard/">Dashboard</a></li>
								<li><a href="/dashboard/index.php?act=logout">Log Out</a></li>
							<?php } ?>
						<?php } ?>	
						</ul>
					  </div><!--/.nav-collapse -->
					</div><!--/.container-fluid -->
				  </nav>
				</div>
			</header>