<?php
/*
Plugin Name: Redirect with GET
Plugin URI: https://ge1.me/yourls-redirect-with-get
Description: Redirect with all GET parameters.
Version: 2.0
Author: fnkr
Author URI: https://www.fnkr.net
License: Creative Commons Attribution 3.0 Unported: https://creativecommons.org/licenses/by/3.0/
*/

yourls_add_action( 'redirect_shorturl', 'fnkr_redirect_with_get' );

function fnkr_redirect_with_get($url) {
    $parsed_url = parse_url($url[0]);

    parse_str($_SERVER['QUERY_STRING'], $query);
    parse_str($parsed_url['query'], $url_query);

    $a = array_merge($query, $url_query);
    $parsed_url['query'] = http_build_query($a);

    $new_url = $parsed_url['scheme'].'://'.$parsed_url['host'];
    if (isset($parsed_url['port']) && $parsed_url['port'] != '')
       $new_url = $new_url.':'.$parsed_url['port'];
    $new_url = $new_url.$parsed_url['path'];


    if (isset($parsed_url['query']) && $parsed_url['query'] != '')
        $new_url = $new_url.'?'.$parsed_url['query'];

    global $url;
    $url = $new_url;
}
