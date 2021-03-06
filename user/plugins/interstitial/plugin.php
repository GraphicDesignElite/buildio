<?php
/*
Plugin Name: Interstitial Plugin for YOURLs
Plugin URI: http://on.luxury
Description: Add an interstitial before url redirection with code injection
Version: 1.0
Author: A2
Author URI: http://AnthonySAdams.com
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Global variables - default state is "redirect with no interstitial"
global $inter_inter;
$inter_inter['do'] = false;
$inter_inter['keyword'] = '';

// When a redirection to a shorturl is about to happen, register variables to state that an interstitial should be displayed
yourls_add_action( 'redirect_shorturl', 'inter_inter_add' );
function inter_inter_add( $args ) {
    global $inter_inter;
    $inter_inter['do'] = true;
    $inter_inter['keyword'] = $args[1];
}

// On redirection, check if this is an interstitial and draw it if needed
yourls_add_action( 'pre_redirect', 'inter_inter_do' );
function inter_inter_do( $args ) {
    global $inter_inter;
    
    // Does this redirection need an interstitial? IF no, exit here and resume normal flow of operations
    if( !$inter_inter['do'] )
        return;

    // Array to hold all variables needed in the interstitial template
    $vars = array();
    
    // Get URL and page title
    $vars['url'] = $args[0];
    $vars['pagetitle'] = yourls_get_keyword_title( $inter_inter['keyword'] );

    // Plugin URL (no URL is hardcoded)
    $vars['pluginurl'] = YOURLS_PLUGINURL . '/'.yourls_plugin_basename( dirname(__FILE__) );
    
    // gtm content
    $vars['gtm'] = inter_get_gtm();
    if( empty( $vars['gtm'] ) or !$vars['gtm'] ) {
        $vars['gtm'] = '<p>Your gtm body goes here (to be configured in the plugin admin page)</p>';
    }
    
    // Make sure browsers don't cache the page
    if( !headers_sent() ) {
        header( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
    }

    // All set. Draw the interstitial page
    $template = file_get_contents( dirname( __FILE__ ) . '/template.html' );
    // Replace all %stuff% in the template with variable $stuff
    $template = preg_replace_callback( '/%([^%]+)?%/', function( $match ) use( $vars ) { return $vars[ $match[1] ]; }, $template );
    echo $template;
    
    // Don't forget to die, to interrupt the flow of normal events (ie redirecting to long URL)
    die();
}

// Register our plugin admin page
yourls_add_action( 'plugins_loaded', 'inter_inter_add_page' );
function inter_inter_add_page() {
    yourls_register_plugin_page( 'inter_inter', 'Interstitial Code', 'inter_inter_do_page' );
    // parameters: page slug, page title, and function that will display the page itself
}

// Display the plugin admin page
function inter_inter_do_page() {

    // Check if a form was submitted
    if( isset( $_POST['inter_content'] ) ) {
        // Check nonce
        yourls_verify_nonce( 'inter_inter' );
        
        // Process form
        inter_inter_update_option();
    }

    // Get value from database
    $inter_content = inter_get_gtm( true );
    
    // Create nonce
    $nonce = yourls_create_nonce( 'inter_inter' );

    echo <<<HTML
        <h2Interstitial Administration Page</h2>
        <form method="post">
        <input type="hidden" name="nonce" value="$nonce" />
        <p><label for="inter_content">Enter here your GTM Body Tag content. Can be any HTML and/or Javascript</label></p>
        <p><textarea id="inter_content" name="inter_content" rows="5" cols="80">$inter_content</textarea></p>
        <p><input type="submit" value="Update value" /></p>
        </form>

HTML;
}

// Get gtm content. Set optional paramater $escape to true if you need to escape the HTML (eg in an input field)
function inter_get_gtm( $escape = false ) {
    $gtm = yourls_get_option( 'inter_inter_inter_content' );
    if( $escape ) {
        $gtm = yourls_esc_html( $gtm );
    }
    return $gtm;
}

// Update option in database if needed
function inter_inter_update_option() {
    $in = $_POST['inter_content'];
    if( $in ) {
        // Update value in database
        yourls_update_option( 'inter_inter_inter_content', $in );
    }
}
